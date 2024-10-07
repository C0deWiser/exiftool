<?php

namespace Tests;

use DateTime;

class IptcAttributesTest extends TestCase
{
    public function testResetPlain()
    {
        $iptc = $this->exiftool->newMetadata();

        $json = ['cityName' => 'test'];
        $iptc->fromJson($json);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['cityName' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetPlain2()
    {
        $iptc = $this->exiftool->newMetadata();

        $json = ['cityName' => 'test'];
        $iptc->fromJson($json);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->cityName = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetDateTime()
    {
        $iptc = $this->exiftool->newMetadata();

        $json = ['dateCreated' => (new DateTime())->format('c')];
        $iptc->fromJson($json);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['dateCreated' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetDateTime2()
    {
        $iptc = $this->exiftool->newMetadata();

        $json = ['dateCreated' => (new DateTime())->format('c')];
        $iptc->fromJson($json);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->dateCreated = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetAltLang()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['description' => 'test']);
        $this->assertEquals(['description' => ['en' => 'test']], $iptc->jsonSerialize());

        $iptc->fromJson(['description' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetAltLang2()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['description' => 'test']);
        $this->assertEquals(['description' => ['en' => 'test']], $iptc->jsonSerialize());

        $iptc->description = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetAltLang3()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['description' => 'test']);
        $this->assertEquals(['description' => ['en' => 'test']], $iptc->jsonSerialize());

        $iptc->description['en'] = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetArray()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['keywords' => ['one']]);
        $this->assertEquals(['keywords' => ['one']], $iptc->jsonSerialize());

        $iptc->fromJson(['keywords' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetArray2()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['keywords' => ['one']]);
        $this->assertEquals(['keywords' => ['one']], $iptc->jsonSerialize());

        $iptc->fromJson(['keywords' => []]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetArray3()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['keywords' => ['one']]);
        $this->assertEquals(['keywords' => ['one']], $iptc->jsonSerialize());

        $iptc->fromJson(['keywords' => [null]]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetArray4()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson(['keywords' => ['one']]);
        $this->assertEquals(['keywords' => ['one']], $iptc->jsonSerialize());

        $iptc->keywords = [null];
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStruct()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['creatorContactInfo' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStruct2()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['creatorContactInfo' => ['city' => null]]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStruct3()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['creatorContactInfo' => [null]]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStruct4()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->creatorContactInfo->city = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStruct5()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->creatorContactInfo = [];
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStruct6()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['creatorContactInfo' => ['city' => 'London']]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->creatorContactInfo = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['genres' => null]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray2()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['genres' => []]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray3()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['genres' => [null]]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray4()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['genres' => [0 => []]]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray5()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->fromJson(['genres' => [0 => ['cvTermId' => null]]]);
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray6()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->genres[0]->cvTermId = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray7()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->genres[0] = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray8()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->genres[0] = [];
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray9()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->genres[0] = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray10()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->genres = [];
        $this->assertEquals([], $iptc->jsonSerialize());
    }

    public function testResetStructArray11()
    {
        $iptc = $this->exiftool->newMetadata();

        $iptc->fromJson($json = ['genres' => [['cvTermId' => 'test']]]);
        $this->assertEquals($json, $iptc->jsonSerialize());

        $iptc->genres = null;
        $this->assertEquals([], $iptc->jsonSerialize());
    }
}