<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Exceptions\MistypeException;
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

    protected function tearDown(): void
    {
        AltLangAttribute::collapse(false);

        parent::tearDown();
    }

    protected function descriptionSpec()
    {
        return $this->spec->topLevel()->getAttributeByJsonName('description');
    }

    public function testLangFromEtName()
    {
        $attr = new AltLangAttribute();

        $this->assertNull($attr->langFromEtName('Caption-Abstract'));
        $this->assertEquals('en', $attr->langFromEtName('Caption-Abstract-en'));
        $this->assertEquals('es_ES', $attr->langFromEtName('Caption-Abstract-es_ES'));
        $this->assertEquals('es-ES', $attr->langFromEtName('Caption-Abstract-es-ES'));
    }

    public function testPureEtName()
    {
        $attr = new AltLangAttribute();

        $this->assertEquals(
            'XMP-dc:Description',
            $attr->pureEtName(['XMP-dc:Description', 'XMP-dc:Description-en'], 'XMP-dc:Description')
        );
        $this->assertEquals(
            'Description-en_US',
            $attr->pureEtName(['Description-en_GB', 'Description-en_US'], 'Description-en_US')
        );
        $this->assertNull($attr->pureEtName(['Description-en_GB', 'Description-en_US'], 'Description-en-GB'));
    }

    public function testFromExiftool()
    {
        $attr = new AltLangAttribute();

        $attr->fromExiftool([
            'Caption-Abstract'  => 'Привет',
            'Description'       => 'Привет',
            'Description-en_GB' => 'Hello',
            'Description-en-US' => 'Hello',
            'Description-es'    => 'Hola',
        ]);

        $this->assertEquals(
            ['ru' => 'Привет', 'en_GB' => 'Hello', 'en_US' => 'Hello', 'es' => 'Hola'],
            $attr->jsonSerialize()
        );

        $this->assertEquals([
            'IFD0:ImageDescription'    => 'Привет',
            'IPTC:Caption-Abstract'    => 'Привет',
            'XMP-dc:Description-en-GB' => 'Hello',
            'XMP-dc:Description-en-US' => 'Hello',
            'XMP-dc:Description-es'    => 'Hola',
            'XMP-dc:Description'       => 'Привет',
        ], $attr->toExiftool($this->descriptionSpec()));
    }

    public function testFromJson()
    {
        $attr = new AltLangAttribute();

        $attr->fromJson(['description' => ['ru' => 'Привет', 'en' => 'Hello']]);
        $this->assertEquals(['ru' => 'Привет', 'en' => 'Hello'], $attr->jsonSerialize());
        $this->assertEquals([
            'IFD0:ImageDescription' => 'Привет',
            'IPTC:Caption-Abstract' => 'Привет',
            'XMP-dc:Description-en' => 'Hello',
            'XMP-dc:Description'    => 'Привет',
        ], $attr->toExiftool($this->descriptionSpec()));

        // Scalar value is assigned to the current locale
        $attr->fromJson(['description' => 'Привет']);
        $this->assertEquals(['ru' => 'Привет'], $attr->jsonSerialize());

        // Null values are filtered out
        $attr->fromJson(['description' => ['ru' => null, 'en' => 'Hello']]);
        $this->assertEquals(['en' => 'Hello'], $attr->jsonSerialize());
    }

    public function testFromJsonRejectsEmpty()
    {
        $attr = new AltLangAttribute();

        $this->expectException(MistypeException::class);
        $attr->fromJson(['description' => ['ru' => null]]);
    }

    public function testOffsetAccess()
    {
        $attr = new AltLangAttribute();
        $attr->fromJson(['description' => ['en' => 'Hello', 'en_GB' => 'Hello', 'es' => 'Hola']]);

        // Regional locale is accessible by any of its forms
        foreach (['en', 'en-GB', 'en-gb', 'en_GB', 'EN-GB'] as $offset) {
            $this->assertTrue(isset($attr[$offset]), "Not set: $offset");
            $this->assertEquals('Hello', $attr[$offset]);
        }

        $this->assertTrue(isset($attr['es-ES']));
        $this->assertEquals('Hola', $attr['es-ES']);

        // Unknown locales are not set
        $this->assertFalse(isset($attr['ru']));
        $this->assertFalse(isset($attr['de']));

        // Unset removes a single locale
        unset($attr['es']);
        $this->assertFalse(isset($attr['es']));
        $this->assertEquals(['en' => 'Hello', 'en_GB' => 'Hello'], $attr->jsonSerialize());
    }

    public function testToString()
    {
        $attr = new AltLangAttribute();
        $attr->fromJson(['description' => ['ru' => 'Привет', 'en' => 'Hello']]);

        // Current locale wins
        $this->assertEquals('Привет', $attr->toString());
        $this->assertEquals('Привет', (string) $attr);

        // Fallback locale is used when the current one has no value
        AltLangAttribute::useLocale('de');
        $this->assertEquals('Hello', (string) $attr);
        AltLangAttribute::useLocale('ru', 'en');
    }

    public function testCollapsed()
    {
        AltLangAttribute::collapse();

        $attr = new AltLangAttribute();
        $attr->fromJson(['description' => ['en' => 'Hello', 'es' => 'Hola']]);

        $this->assertTrue($attr->isCollapsed());
        // Current locale ('ru') has no value, so the fallback one is used
        $this->assertEquals('Hello', $attr->jsonSerialize());
    }

    public function testFake()
    {
        $attr = new AltLangAttribute();

        $attr->fake($this->descriptionSpec());

        $this->assertEqualsCanonicalizing(['en', 'es'], array_keys($attr->toArray()));
        foreach ($attr->toArray() as $value) {
            $this->assertNotEquals('', $value);
        }
    }
}