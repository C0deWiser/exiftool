<?php

namespace Tests;

use Codewiser\Exiftool\Contracts\Plain;

class IptcPlusTest extends TestCase
{
    protected string $template = __DIR__.'/IPTC-Empty.jpg';
    protected string $filename = __DIR__.'/IptcPlusTest.jpg';

    protected function setUp(): void
    {
        parent::setUp();

        if (file_exists($this->filename)) {
            unlink($this->filename);
        }

        copy($this->template, $this->filename);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }

        parent::tearDown();
    }

    /**
     * We guarantee proper mapping of Plus values.
     */
    public function test()
    {
        $attributes = [
            'dataMining',
            'minorModelAgeDisclosure',
            'modelReleaseStatus',
            'propertyReleaseStatus',
        ];
        $printConv = [true, false];

        foreach ($printConv as $mode) {
            $this->exiftool->printConv($mode);

            foreach ($attributes as $jsonName) {

                $attr = $this->exiftool->specification()->topLevel()->getAttributeByJsonName($jsonName);
                $values = $attr->enum();

                foreach ($values as $key => $value) {
                    $iptc = $this->exiftool->newMetadata();
                    $iptc->fromJson([$jsonName => $mode ? $key : $value]);
                    $this->exiftool->clear($this->filename);
                    $this->exiftool->write($this->filename, $iptc);
                    $embedded = $this->exiftool->read($this->filename);
                    $this->assertEquals($iptc->jsonSerialize(), $embedded->jsonSerialize());
                }
            }
        }
    }

    public function testFaker()
    {
        $attributes = [
            'dataMining',
            'minorModelAgeDisclosure',
            'modelReleaseStatus',
            'propertyReleaseStatus',
        ];

        $printConv = [true, false];

        foreach ($printConv as $mode) {
            $this->exiftool->printConv($mode);
            $iptc = $this->exiftool->newMetadata()->fake();

            foreach ($attributes as $jsonName) {
                $spec = $this->exiftool->specification()->topLevel()->getAttributeByJsonName($jsonName);
                $values = $spec->enum();

                /** @var Plain $attribute */
                $attribute = $iptc->$jsonName;
                $this->assertTrue(in_array($attribute->toString(), $values));
            }
        }
    }
}