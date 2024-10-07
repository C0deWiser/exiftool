<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Spec\Specification;
use Tests\TestCase;

class AttributeAltLangTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = $this->exiftool->specification();

        AltLangAttribute::useLocale('ru', 'en');
    }

    public function testSingle()
    {
        $attr = new AltLangAttribute();

        $this->assertNull($attr->langFromEtName('Caption-Abstract'));
        $this->assertEquals('en', $attr->langFromEtName('Caption-Abstract-en'));
        $this->assertEquals('es_ES', $attr->langFromEtName('Caption-Abstract-es_ES'));
        $this->assertEquals('es-ES', $attr->langFromEtName('Caption-Abstract-es-ES'));

        $spec = $this->spec->topLevel()->getAttributeByJsonName('description');

        $attr->fromExiftool([
            'Caption-Abstract'       => 'Привет',
            'Caption-Abstract-en-GB' => 'Hello',
            'Caption-Abstract-en_US' => 'Hello',
            'Caption-Abstract-es'    => 'Hola',
            'Description'            => 'Привет',
            'Description-en_GB'      => 'Hello',
            'Description-en-US'      => 'Hello',
            'Description-es'         => 'Hola',
        ]);

        $this->assertEquals(
            ['ru' => 'Привет', 'en_GB' => 'Hello', 'en_US' => 'Hello', 'es' => 'Hola'],
            $attr->jsonSerialize()
        );
        $this->assertCount(12, $attr->toExiftool($spec));
        $this->assertTrue(isset($attr['ru']));
        $this->assertTrue(isset($attr['en']));
        $this->assertTrue(isset($attr['en-GB']));
        $this->assertTrue(isset($attr['en_US']));
        $this->assertTrue(isset($attr['es']));

        $this->assertEquals('Привет', $attr['ru']);
        $this->assertEquals('Hello', $attr['en']);
        $this->assertEquals('Hello', $attr['en-GB']);
        $this->assertEquals('Hello', $attr['en-US']);
        $this->assertEquals('Hola', $attr['es-ES']);

        AltLangAttribute::useLocale('ru');
        $this->assertEquals('Привет', (string) $attr);
        AltLangAttribute::useLocale('en');
        $this->assertEquals('Hello', (string) $attr);

        $attr->fromJson(['description' => ['ru' => 'Привет', 'en' => 'Hello']]);
        $this->assertCount(6, $attr->toExiftool($spec));

        $attr->fromJson(['description' => 'Привет']);
        $this->assertCount(3, $attr->toExiftool($spec));
    }
}
