<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\ArrayAttribute;
use Codewiser\Exiftool\Attributes\PlainAttribute;
use Codewiser\Exiftool\Spec\Specification;
use Tests\TestCase;

class AttributePlainTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = $this->exiftool->specification();
    }

    public function testSingle()
    {
        $attr = new PlainAttribute();

        $spec = $this->spec->topLevel()->getAttributeByJsonName('countryName');

        $attr->fromExiftool([
            'Country'                     => 'value',
            'Country-PrimaryLocationName' => 'value'
        ]);

        $this->assertEquals('value', $attr->jsonSerialize());
        $this->assertCount(2, $attr->toExiftool($spec));

        $attr->fromJson(['countryName' => 'new name']);
        $this->assertEquals('new name', $attr->jsonSerialize());

        $export = $attr->toExiftool($spec);
    }

    public function testMulti()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute(), true);

        $spec = $this->spec->topLevel()->getAttributeByJsonName('creatorNames');

        $attr->fromExiftool([
            'By-line' => ['one', 'two'],
            'Artist'  => ['two', 'three'],
            'Creator' => 'two'
        ]);

        $this->assertEquals(['one', 'two', 'three'], $attr->jsonSerialize());
        $this->assertCount(3, $attr->toExiftool($spec));
        foreach ($attr->toExiftool($spec) as $values) {
            $this->assertEquals(['one', 'two', 'three'], $values);
        }
        $this->assertCount(3, $attr);

        $attr->fromJson(['creatorNames' => ['one', 'two']]);
        $this->assertEquals(['one', 'two'], $attr->jsonSerialize());

        $attr->fromJson(['creatorNames' => 'one']);
        $this->assertEquals(['one'], $attr->jsonSerialize());

        // Iterable
        foreach ($attr as $value) {
            $this->assertEquals('one', $value);
        }

        $export = $attr->toExiftool($spec);
    }
}
