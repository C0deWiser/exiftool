<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\ArrayAttribute;
use Codewiser\Exiftool\Attributes\PlainAttribute;
use Codewiser\Exiftool\Attributes\StructureAttribute;
use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Spec\Specification;
use Tests\TestCase;

class AttributeMultipleTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = $this->exiftool->specification();
    }

    public function testFromExiftoolExplodesSeparatedString()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute(), true);

        $attr->fromExiftool(['PersonInImage' => 'one,two']);

        $this->assertEquals(['one', 'two'], $attr->jsonSerialize());
    }

    public function testFromExiftoolKeepsOnlyFirstEtNameWhenNotMerging()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromExiftool([
            'By-line' => ['one', 'two'],
            'Artist'  => ['ignored'],
        ]);

        $this->assertEquals(['one', 'two'], $attr->jsonSerialize());
    }

    public function testFromExiftoolMergesAndDeduplicates()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute(), true);
        $spec = $this->spec->topLevel()->getAttributeByJsonName('creatorNames');

        $attr->fromExiftool([
            'By-line' => ['one', 'two'],
            'Artist'  => ['two', 'three'],
            'Creator' => 'two',
        ]);

        $this->assertEquals(['one', 'two', 'three'], $attr->jsonSerialize());
        $this->assertCount(3, $attr);
        $this->assertEquals([
            'IFD0:Artist'    => ['one', 'two', 'three'],
            'IPTC:By-line'   => ['one', 'two', 'three'],
            'XMP-dc:Creator' => ['one', 'two', 'three'],
        ], $attr->toExiftool($spec));
    }

    public function testFromJson()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => ['one', 'two']]);

        $this->assertEquals(['one', 'two'], $attr->jsonSerialize());
        $this->assertCount(2, $attr);
        $this->assertCount(2, $attr->toArray());
    }

    public function testFromJsonAllowsScalar()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => 'one']);

        $this->assertEquals(['one'], $attr->jsonSerialize());
    }

    public function testFromJsonSkipsInvalidValues()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => ['one', '', null, 'two']]);

        $this->assertCount(2, $attr);
        $this->assertEquals(['one', 'two'], array_values($attr->jsonSerialize()));
    }

    public function testJsonSerializePreservesSparseKeys()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr->fromJson(['creatorNames' => ['one', '', 'two']]);

        $this->assertEquals([0 => 'one', 2 => 'two'], $attr->jsonSerialize());
    }

    public function testFromJsonKeepsAttributeInstances()
    {
        $one = new PlainAttribute();
        $one->fromJson(['creatorNames' => 'one']);

        $attr = new ArrayAttribute(fn() => new PlainAttribute());
        $attr->fromJson(['creatorNames' => [$one]]);

        $this->assertSame($one, $attr[0]);
    }

    public function testToExiftoolGroupsValuesByEtName()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());
        $spec = $this->spec->topLevel()->getAttributeByJsonName('creatorNames');

        $attr->fromJson(['creatorNames' => ['one', 'two']]);

        $this->assertEquals([
            'IFD0:Artist'    => ['one', 'two'],
            'IPTC:By-line'   => ['one', 'two'],
            'XMP-dc:Creator' => ['one', 'two'],
        ], $attr->toExiftool($spec));
    }

    public function testIterator()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());
        $attr->fromJson(['creatorNames' => ['one', 'two', 'three']]);

        $keys = [];
        $values = [];
        foreach ($attr as $key => $value) {
            $keys[] = $key;
            $values[] = (string) $value;
        }

        $this->assertEquals([0, 1, 2], $keys);
        $this->assertEquals(['one', 'two', 'three'], $values);

        $attr->rewind();
        $this->assertTrue($attr->valid());
        $this->assertEquals(0, $attr->key());
        $this->assertEquals('one', (string) $attr->current());

        $attr->next();
        $this->assertEquals(1, $attr->key());
        $this->assertEquals('two', (string) $attr->current());

        $attr->next();
        $attr->next();
        $this->assertFalse($attr->valid());
    }

    public function testArrayAccess()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());
        $attr->fromJson(['creatorNames' => ['one']]);

        $this->assertTrue(isset($attr[0]));
        $this->assertEquals('one', (string) $attr[0]);

        $attr[1] = 'two';
        $this->assertEquals('two', (string) $attr[1]);
        $this->assertCount(2, $attr);

        unset($attr[0]);
        $this->assertFalse(isset($attr[0]));
        $this->assertCount(1, $attr);
    }

    public function testOffsetSetConvertsScalarValues()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr[0] = 'hello';
        $attr[] = 'world';

        $this->assertEquals(['hello', 'world'], $attr->jsonSerialize());
        $this->assertInstanceOf(PlainAttribute::class, $attr[0]);
    }

    public function testOffsetSetRejectsInvalidValue()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());

        $attr[0] = 'valid';
        $attr[1] = '';

        $this->assertCount(1, $attr);
        $this->assertFalse(isset($attr[1]));
    }

    public function testToArrayReturnsAttributes()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());
        $attr->fromJson(['creatorNames' => ['one', 'two']]);

        foreach ($attr->toArray() as $row) {
            $this->assertInstanceOf(Contracts\Attribute::class, $row);
        }
    }

    public function testWorksWithStructures()
    {
        $attr = new ArrayAttribute(fn() => new StructureAttribute($this->spec->struct('CvTerm')));

        $attr->fromJson(['genres' => [
            ['cvId' => 'https://example.com', 'cvTermId' => 'https://example.com/term'],
            ['cvId' => 'https://example.org', 'cvTermId' => 'https://example.org/term'],
        ]]);

        $this->assertCount(2, $attr);
        $this->assertEquals('https://example.com', (string) $attr[0]->cvId);
        $this->assertEquals('https://example.org', (string) $attr[1]->cvId);
        $this->assertInstanceOf(StructureAttribute::class, $attr->toArray()[0]);
    }

    public function testFake()
    {
        $attr = new ArrayAttribute(fn() => new PlainAttribute());
        $spec = $this->spec->topLevel()->getAttributeByJsonName('creatorNames');

        $attr->fake($spec);

        $this->assertCount(2, $attr);
        foreach ($attr->toArray() as $row) {
            $this->assertInstanceOf(PlainAttribute::class, $row);
            $this->assertNotEquals('', (string) $row);
        }
    }
}