<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\DateTimeAttribute;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Spec\Specification;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Tests\TestCase;

class AttributeDateTimeTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = $this->exiftool->specification();
    }

    public function testFromExiftool()
    {
        $attr = new DateTimeAttribute();
        $spec = $this->spec->topLevel()->getAttributeByJsonName('dateCreated');

        $attr->fromExiftool([
            'TimeCreated' => '19:21:30.49',
            'DateCreated' => '2023:09:20 19:21:30.49'
        ]);

        $this->assertEquals('2023-09-20T19:21:30+00:00', $attr->jsonSerialize());
        $this->assertEquals('2023-09-20T19:21:30+00:00', (string) $attr);
        $this->assertInstanceOf(DateTimeInterface::class, $attr->toDateTime());
        $this->assertEquals([
            'IPTC:DateCreated'          => '2023-09-20',
            'IPTC:TimeCreated'          => '19:21:30UTC',
            'XMP-photoshop:DateCreated' => '2023-09-20T19:21:30+00:00',
        ], $attr->toExiftool($spec));
    }

    public function testFromJson()
    {
        $attr = new DateTimeAttribute();
        $now = new DateTime();

        // Formatted string
        $attr->fromJson(['dateCreated' => $now->format('c')]);
        $this->assertEquals($now->format('c'), $attr->jsonSerialize());
        $this->assertEquals($now->getTimestamp(), $attr->toDateTime()->getTimestamp());

        // DateTimeInterface instance
        $attr->fromJson(['dateCreated' => $now]);
        $this->assertEquals($now->format('c'), $attr->jsonSerialize());
        $this->assertEquals($now->format('c'), (string) $attr);
    }

    public function testFromJsonTimestamp()
    {
        $attr = new DateTimeAttribute();

        $attr->fromJson(['dateCreated' => 1700000000]);

        $this->assertEquals('2023-11-14T22:13:20+00:00', $attr->jsonSerialize());
    }

    public function testFromJsonKeepsGivenTimezone()
    {
        $attr = new DateTimeAttribute();

        $attr->fromJson(['dateCreated' => new DateTimeImmutable('2023-09-20 19:21:30', new DateTimeZone('UTC'))]);

        $this->assertInstanceOf(DateTimeImmutable::class, $attr->toDateTime());
        $this->assertEquals('UTC', $attr->toDateTime()->getTimezone()->getName());
        $this->assertEquals('2023-09-20T19:21:30+00:00', $attr->jsonSerialize());
    }

    public function testFalsyValueIsRejected()
    {
        $attr = new DateTimeAttribute();

        $this->expectException(MistypeException::class);
        $attr->fromJson(['dateCreated' => null]);
    }

    public function testFake()
    {
        $attr = new DateTimeAttribute();
        $attr->fake($this->spec->topLevel()->getAttributeByJsonName('dateCreated'));

        $this->assertInstanceOf(DateTimeInterface::class, $attr->toDateTime());
    }
}