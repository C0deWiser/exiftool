<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Attributes\ArrayAttribute;
use Codewiser\Exiftool\Attributes\StructureAttribute;
use Codewiser\Exiftool\Spec\Specification;
use Codewiser\Exiftool\Structures\CreatorContactInfo;
use Codewiser\Exiftool\Structures\CvTerm;
use Tests\TestCase;

class AttributeStructureTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = $this->exiftool->specification();

        AltLangAttribute::useLocale('ru', 'en');
    }

    public function testCI()
    {
        $raw = '{"CreatorContactInfo": {
                    "CiAdrCity": "Серпухов",
                    "CiAdrCtry": "Бразилия",
                    "CiAdrExtadr": "339417, Сахалинская область, город Истра, въезд Космонавтов, 48",
                    "CiAdrPcode": "085286",
                    "CiAdrRegion": "",
                    "CiEmailWork": "antonina87@rogova.ru",
                    "CiTelWork": "+7 (922) 760-0483",
                    "CiUrlWork": "https://www.artemev.ru/numquam-quo-omnis-fuga-rerum-voluptas"
                  }}';

        $spec = $this->spec->topLevel()->getAttributeByJsonName('creatorContactInfo');

        /** @var CreatorContactInfo $attr */
        $attr = new StructureAttribute($spec->struct());

        $attr->fromExiftool(json_decode($raw, true));
        $this->assertCount(1, $attr->toExiftool($spec));

        $this->assertEquals('Серпухов', (string) $attr->city);
        $this->assertEquals('Бразилия', (string) $attr->country);

        $attr->fromJson(['city' => 'Ленинград']);
        $this->assertEquals('Ленинград', (string) $attr->city);
        $this->assertFalse(isset($attr->country));

        $attr->country = 'Россия';
        $this->assertEquals('Россия', (string) $attr->country);
    }

    public function testGenres()
    {
        $raw = '{"Genre": [{
                    "CvId": "http://bartoletti.com/",
                    "CvTermId": "http://cv.iptc.org/newscodes/genre-1",
                    "CvTermName": "Информационный работник",
                    "CvTermName-en": "Machinery Maintenance",
                    "CvTermRefinedAbout": ""
                  },{
                    "CvId": "http://hane.com/",
                    "CvTermId": "http://cv.iptc.org/newscodes/genre-3",
                    "CvTermName": "Системный аналитик",
                    "CvTermName-en": "Marking Machine Operator",
                    "CvTermRefinedAbout": ""
                  }]}';

        $spec = $this->spec->topLevel()->getAttributeByJsonName('genres');

        $attr = new ArrayAttribute(fn() => new StructureAttribute($spec->struct()));

        $attr->fromExiftool(json_decode($raw, true));
        $this->assertCount(2, $attr->jsonSerialize());

        /** @var CvTerm $genre */
        $genre = $attr[1];

        $this->assertEquals('http://cv.iptc.org/newscodes/genre-3', (string) $genre->cvTermId);

        $attr->fromJson([
            'genres' => [
                [
                    'cvId'       => 'https://example.com',
                    'cvTermId'   => 'https://example.com/term',
                    'cvTermName' => 'Term name',
                ]
            ]
        ]);
        $this->assertCount(1, $attr->jsonSerialize());
        /** @var CvTerm $genre */
        $genre = $attr[0];
        $this->assertEquals('https://example.com/term', (string) $genre->cvTermId);
    }

    public function testNested()
    {
        $raw = [
            'ImageRegion' => [
                [
                    'RegionBoundary' => [
                        'RbVertices' => [
                            [
                                'RbX' => 1,
                                'RbY' => 1,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $spec = $this->spec->topLevel()->getAttributeByJsonName('imageRegion');
        $attr = new ArrayAttribute(fn() => new StructureAttribute($spec->struct()));
        $attr->fromExiftool($raw);

        $boundary = $attr[0];

        $this->assertEquals([
            'regionBoundary' => [
                'rbVertices' => [['rbX' => 1, 'rbY' => 1]]
            ]
        ], $boundary->jsonSerialize());
    }
}
