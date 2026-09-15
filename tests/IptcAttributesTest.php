<?php

namespace Tests;

use Codewiser\Exiftool\Attributes\ArrayAttribute;
use Codewiser\Exiftool\Attributes\PlainAttribute;
use Codewiser\Exiftool\Attributes\StructureAttribute;
use DateTime;

class IptcAttributesTest extends TestCase
{
    public function testResetPlain()
    {
        $iptc = $this->exiftool->newMetadata();
        $json = ['cityName' => 'test'];
        $iptc->fromJson($json);
        $this->assertEquals($json, $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->cityName));

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['cityName' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->cityName));
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->cityName = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->cityName));
        $this->assertEmpty($iptc->toExiftool());
    }

    public function testResetDateTime()
    {
        $iptc = $this->exiftool->newMetadata();
        $json = ['dateCreated' => (new DateTime())->format('c')];
        $iptc->fromJson($json);
        $this->assertEquals($json, $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->dateCreated));

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['dateCreated' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->dateCreated));
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->dateCreated = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->dateCreated));
        $this->assertEmpty($iptc->toExiftool());
    }

    public function testResetAltLang()
    {
        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['description' => 'test']);
        $this->assertEquals(['description' => ['en' => 'test']], $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->description));

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['description' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->description));
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->description = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->description));
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['description' => 'test']);
        $iptc->description['en'] = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        // Attribute is kept, only its value cleared.
        $this->assertTrue(isset($iptc->description));
        // Null values are still exported to be cleared on the file side.
        $this->assertNotEmpty($iptc->toExiftool());
    }

    public function testResetArray()
    {
        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['keywords' => ['one']]);
        $this->assertEquals(['keywords' => ['one']], $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->keywords));

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['keywords' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['keywords' => []]);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['keywords' => [null]]);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->keywords = [null];
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());
    }

    public function testResetArrayPartially()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['keywords' => ['one', 'two']]);
        $this->assertEquals(['keywords' => ['one', 'two']], $iptc->jsonSerialize());

        $iptc->keywords[] = 'three';
        $this->assertEquals(['keywords' => ['one', 'two', 'three']], $iptc->jsonSerialize());

        unset($iptc->keywords[1]);
        $this->assertEquals(['keywords' => [0 => 'one', 2 => 'three']], $iptc->jsonSerialize());
    }

    public function testResetStruct()
    {
        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->creatorContactInfo));

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['creatorContactInfo' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->creatorContactInfo));
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['creatorContactInfo' => ['city' => null]]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['creatorContactInfo' => [null]]);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['creatorContactInfo' => ['city' => 'London']]);
        $iptc->creatorContactInfo->city = null;
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->creatorContactInfo = [];
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->creatorContactInfo));
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->creatorContactInfo = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->creatorContactInfo));
        $this->assertEmpty($iptc->toExiftool());
    }

    public function testResetStructPartially()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson([
            'creatorContactInfo' => ['city' => 'London', 'country' => 'GB']
        ]);
        $this->assertEquals([
            'creatorContactInfo' => ['city' => 'London', 'country' => 'GB']
        ], $iptc->jsonSerialize());

        $iptc->creatorContactInfo->city = null;
        $this->assertEquals([
            'creatorContactInfo' => ['country' => 'GB']
        ], $iptc->jsonSerialize());

        $this->assertFalse(isset($iptc->creatorContactInfo->city));
        $this->assertTrue(isset($iptc->creatorContactInfo->country));
    }

    public function testResetStructArray()
    {
        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->genres));

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => []]);
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => [null]]);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => [0 => []]]);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => [0 => ['cvTermId' => null]]]);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => [['cvTermId' => 'test']]]);
        $iptc->genres[0]->cvTermId = null;
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => [['cvTermId' => 'test']]]);
        $iptc->genres[0] = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->fromJson(['genres' => [['cvTermId' => 'test']]]);
        $iptc->genres[0] = [];
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->genres = [];
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());

        $iptc = $this->exiftool->newMetadata();
        $iptc->genres = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertEmpty($iptc->toExiftool());
    }

    public function testFromExiftoolRoundTrip()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromExiftool(['City' => 'London']);
        $this->assertEquals(['cityName' => 'London'], $iptc->jsonSerialize());
        $this->assertTrue(isset($iptc->cityName));
        $this->assertNotEmpty($iptc->toExiftool());

        $iptc->cityName = null;
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->cityName));
        $this->assertEmpty($iptc->toExiftool());
    }

    public function testResetKeepsOtherAttributes()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->cityName = 'London';
        $iptc->countryName = 'GB';
        $this->assertEquals(['cityName' => 'London', 'countryName' => 'GB'], $iptc->jsonSerialize());

        $iptc->cityName = null;

        $this->assertEquals(['countryName' => 'GB'], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->cityName));
        $this->assertTrue(isset($iptc->countryName));
    }

    public function testResetFakedAttribute()
    {
        $iptc = $this->exiftool->newMetadata()->fake();

        $before = $iptc->jsonSerialize();
        $this->assertNotEmpty($before);

        $this->assertTrue(isset($iptc->cityName));

        $iptc->cityName = null;

        $after = $iptc->jsonSerialize();
        $this->assertCount(count($before) - 1, $after);
        $this->assertArrayNotHasKey('cityName', $after);
        $this->assertFalse(isset($iptc->cityName));
    }

    public function testIssetUnset()
    {
        $iptc = $this->exiftool->newMetadata();

        $this->assertFalse(isset($iptc->cityName));

        $iptc->cityName = 'London';
        $this->assertTrue(isset($iptc->cityName));

        unset($iptc->cityName);
        $this->assertFalse(isset($iptc->cityName));
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testGetAttributesApi()
    {
        $iptc = $this->exiftool->newMetadata();

        $this->assertEquals([], $iptc->getAttributes());
        $this->assertNull($iptc->getAttribute('cityName'));

        $iptc->cityName = 'London';
        $this->assertCount(1, $iptc->getAttributes());
        $this->assertInstanceOf(
            PlainAttribute::class,
            $iptc->getAttribute('cityName')
        );

        $iptc->keywords = ['one', 'two'];
        $this->assertCount(2, $iptc->getAttributes());
        $this->assertInstanceOf(
            ArrayAttribute::class,
            $iptc->getAttribute('keywords')
        );

        $iptc->creatorContactInfo = ['city' => 'London'];
        $this->assertCount(3, $iptc->getAttributes());
        $this->assertInstanceOf(
            StructureAttribute::class,
            $iptc->getAttribute('creatorContactInfo')
        );
    }

    public function testUnknownPropertyIsIgnored()
    {
        $iptc = $this->exiftool->newMetadata();

        $this->assertNull($iptc->unknownProperty);
        $this->assertFalse(isset($iptc->unknownProperty));

        $iptc->fromJson(['unknownProperty' => 'value']);
        $this->assertEquals([], $iptc->jsonSerialize());

        $iptc->unknownProperty = 'value';
        $this->assertEquals([], $iptc->jsonSerialize());
        $this->assertFalse(isset($iptc->unknownProperty));
    }

    public function testFalsyValuesAreNotStored()
    {
        foreach ([null, '', false, 0] as $falsy) {
            $iptc = $this->exiftool->newMetadata();

            $iptc->cityName = $falsy;

            $this->assertEquals([], $iptc->jsonSerialize());
            $this->assertFalse(isset($iptc->cityName));
        }
    }
}