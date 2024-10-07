<?php

namespace Tests\Attributes;

use Codewiser\Exiftool\Attributes\DateTimeAttribute;
use Codewiser\Exiftool\Spec\Specification;
use DateTime;
use Tests\TestCase;

class AttributeDateTimeTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = $this->exiftool->specification();
    }

    public function test()
    {
        $attr = new DateTimeAttribute();

        $spec = $this->spec->topLevel()->getAttributeByJsonName('dateCreated');

        $attr->fromExiftool([
            'TimeCreated' => '19:21:30.49',
            'DateCreated' => '2023:09:20 19:21:30.49'
        ]);

        $this->assertEquals('2023-09-20T19:21:30+00:00', $attr->jsonSerialize());
        $this->assertCount(4, $attr->toExiftool($spec));

        $now = new DateTime();

        $attr->fromJson(['dateCreated' => $now->format('c')]);
        $this->assertEquals($now->format('c'), $attr->jsonSerialize());

        $attr->fromJson(['dateCreated' => $now]);
        $this->assertEquals($now->format('c'), $attr->jsonSerialize());
    }
}
