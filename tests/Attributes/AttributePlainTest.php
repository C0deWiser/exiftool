<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\ArrayAttribute;
use Codewiser\Exiftool\Attributes\PlainAttribute;
use Codewiser\Exiftool\Exceptions\MistypeException;
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

    public function testFromExiftool()
    {
        $attr = new PlainAttribute();
        $spec = $this->spec->topLevel()->getAttributeByJsonName('countryName');

        $attr->fromExiftool([
            'Country'                     => 'value',
            'Country-PrimaryLocationName' => 'value'
        ]);

        $this->assertEquals('value', $attr->jsonSerialize());
        $this->assertEquals('value', $attr->toString());
        $this->assertEquals('value', (string) $attr);
        $this->assertEquals([
            'IPTC:Country-PrimaryLocationName' => 'value',
            'XMP-photoshop:Country'            => 'value',
        ], $attr->toExiftool($spec));
    }

    public function testFromExiftoolTakesFirstValue()
    {
        $attr = new PlainAttribute();

        $attr->fromExiftool([
            'Country'                     => 'first',
            'Country-PrimaryLocationName' => 'second'
        ]);

        $this->assertEquals('first', $attr->jsonSerialize());
    }

    public function testFromJson()
    {
        $attr = new PlainAttribute();
        $spec = $this->spec->topLevel()->getAttributeByJsonName('countryName');

        $attr->fromJson(['countryName' => ' new name ']);

        // jsonSerialize trims whitespace
        $this->assertEquals('new name', $attr->jsonSerialize());
        // toExiftool stores the original value
        $this->assertEquals([
            'IPTC:Country-PrimaryLocationName' => ' new name ',
            'XMP-photoshop:Country'            => ' new name ',
        ], $attr->toExiftool($spec));
    }

    public function testZeroIsStoredForNumberField()
    {
        $spec = $this->spec->topLevel()->getAttributeByJsonName('maxAvailHeight');
        $attr = new PlainAttribute($spec);

        $attr->fromJson(['maxAvailHeight' => 0]);

        $this->assertEquals('0', $attr->jsonSerialize());
        $this->assertEquals([
            'XMP-iptcExt:MaxAvailHeight' => '0',
        ], $attr->toExiftool($spec));
    }

    public function testEnumAllowsInternalValues()
    {
        $this->exiftool->useMachineValues();

        $spec = $this->spec->struct('Location')->getAttributeByJsonName('gpsAltitudeRef');
        $attr = new PlainAttribute($spec);

        $attr->fromJson(['gpsAltitudeRef' => 0]);
        $this->assertEquals('0', $attr->jsonSerialize());

        $attr->fromJson(['gpsAltitudeRef' => 1]);
        $this->assertEquals('1', $attr->jsonSerialize());
    }

    public function testEnumAllowsHumanReadableValues()
    {
        $this->exiftool->useHumanValues();

        $spec = $this->spec->struct('Location')->getAttributeByJsonName('gpsAltitudeRef');
        $attr = new PlainAttribute($spec);

        $attr->fromJson(['gpsAltitudeRef' => 'Above Sea Level']);
        $this->assertEquals('Above Sea Level', $attr->jsonSerialize());

        $attr->fromJson(['gpsAltitudeRef' => 'Below Sea Level']);
        $this->assertEquals('Below Sea Level', $attr->jsonSerialize());
    }

    public function testEnumRejectsUnknownValues()
    {
        $spec = $this->spec->struct('Location')->getAttributeByJsonName('gpsAltitudeRef');
        $attr = new PlainAttribute($spec);

        foreach (['unknown', 2, '2'] as $value) {
            try {
                $attr->fromJson(['gpsAltitudeRef' => $value]);
                $this->fail('Value is not rejected: '.var_export($value, true));
            } catch (MistypeException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function testFalsyValuesAreRejected()
    {
        $attr = new PlainAttribute();

        foreach ([null, '', false, 0, 0.0, '0'] as $value) {
            try {
                $attr->fromJson(['countryName' => $value]);
                $this->fail('Value is not rejected: '.var_export($value, true));
            } catch (MistypeException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function testMultiFromExiftool()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute(), true);
        $spec = $this->spec->topLevel()->getAttributeByJsonName('creatorNames');

        $attr->fromExiftool([
            'By-line' => ['one', 'two'],
            'Artist'  => ['two', 'three'],
            'Creator' => 'two'
        ]);

        $this->assertEquals(['one', 'two', 'three'], $attr->jsonSerialize());
        $this->assertCount(3, $attr);
        $this->assertEquals([
            'IFD0:Artist'    => ['one', 'two', 'three'],
            'IPTC:By-line'   => ['one', 'two', 'three'],
            'XMP-dc:Creator' => ['one', 'two', 'three'],
        ], $attr->toExiftool($spec));
    }

    public function testMultiFromJson()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => ['one', 'two']]);
        $this->assertEquals(['one', 'two'], $attr->jsonSerialize());

        // Scalars are allowed too
        $attr->fromJson(['creatorNames' => 'one']);
        $this->assertEquals(['one'], $attr->jsonSerialize());
    }

    public function testMultiIteration()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => 'one']);

        $this->assertCount(1, $attr);
        $this->assertTrue(isset($attr[0]));
        $this->assertEquals('one', (string) $attr[0]);

        foreach ($attr as $value) {
            $this->assertEquals('one', (string) $value);
        }
    }

    public function testMultiFiltersFalsy()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => ['one', '', null, 'two']]);

        // Keys are not renumbered after internal unset
        $this->assertCount(2, $attr);
        $this->assertEquals(['one', 'two'], array_values($attr->jsonSerialize()));
    }

    public function testMultiMergeDeduplicates()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute(), true);

        $attr->fromJson(['creatorNames' => ['one', 'one', 'two']]);

        $this->assertEquals(['one', 'two'], $attr->jsonSerialize());
    }
}