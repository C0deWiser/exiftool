<?php

namespace Tests;

use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Exiftool;

class PrintConvTest extends TestCase
{
    protected string $template = __DIR__.'/IPTC-Empty.jpg';
    protected string $filename = __DIR__.'/IPTC-PrintConv.jpg';

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

    public function testAboveSeaLevelEnum()
    {
        $location = $this->exiftool->newStructure()->location()->fake();

        $this->exiftool->useMachineValues();

        // Value is rejected
        $location->gpsAltitudeRef = 5;
        $this->assertNull($location->gpsAltitudeRef);

        // Value is approved
        $location->gpsAltitudeRef = 0;
        $this->assertEquals('0', $location->gpsAltitudeRef->toString());

        $this->exiftool->useHumanValues();

        // Value is rejected
        $location->gpsAltitudeRef = 'Not a Sea Level';
        $this->assertNull($location->gpsAltitudeRef);

        // Value is approved
        $location->gpsAltitudeRef = 'Above Sea Level';
        $this->assertEquals('Above Sea Level', $location->gpsAltitudeRef->toString());
    }

    public function testAboveSeaLevelPrintConv()
    {
        // Write human-readable values.
        $this->exiftool->useHumanValues();

        $iptc = $this->exiftool->newMetadata();
        $location = $this->exiftool->newStructure()->location()->fake();

        // Above Sea Level
        $location->gpsAltitudeRef = 'Above Sea Level';
        $iptc->locationsShown = [$location];

        $this->assertStringContainsString('GPSAltitudeRef=Above Sea Level', $iptc->toExiftool()[0]);
        $this->exiftool->write($this->filename, $iptc);

        // Read human-readable values.
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('Above Sea Level', $iptc->locationsShown[0]->gpsAltitudeRef->toString());

        // Read internal format
        $this->exiftool->useMachineValues();
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('0', $iptc->locationsShown[0]->gpsAltitudeRef->toString());
    }

    public function testAboveSeaLevel()
    {
        // Write internal values.
        $this->exiftool->useMachineValues();

        $iptc = $this->exiftool->newMetadata();
        $location = $this->exiftool->newStructure()->location()->fake();

        // Above Sea Level
        $location->gpsAltitudeRef = 0;
        $iptc->locationsShown = [$location];

        $this->assertStringContainsString('GPSAltitudeRef=0', $iptc->toExiftool()[0]);
        $this->exiftool->write($this->filename, $iptc);

        // Read internal values.
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('0', $iptc->locationsShown[0]->gpsAltitudeRef->toString());

        // Read human-readable format
        $this->exiftool->useHumanValues();
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('Above Sea Level', $iptc->locationsShown[0]->gpsAltitudeRef->toString());
    }

    public function testBelowSeaLevelPrintConv()
    {
        // Use human-readable values.
        $this->exiftool->useHumanValues();

        $iptc = $this->exiftool->newMetadata();
        $location = $this->exiftool->newStructure()->location()->fake();

        // Above Sea Level
        $location->gpsAltitudeRef = 'Below Sea Level';
        $iptc->locationsShown = [$location];

        $this->assertStringContainsString('GPSAltitudeRef=Below Sea Level', $iptc->toExiftool()[0]);
        $this->exiftool->write($this->filename, $iptc);

        // Read human-readable values.
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('Below Sea Level', $iptc->locationsShown[0]->gpsAltitudeRef);

        // Read internal format
        $this->exiftool->useMachineValues();
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('1', $iptc->locationsShown[0]->gpsAltitudeRef->toString());
    }

    public function testBelowSeaLevel()
    {
        // Use internal values.
        $this->exiftool->useMachineValues();

        $iptc = $this->exiftool->newMetadata();
        $location = $this->exiftool->newStructure()->location()->fake();

        // Above Sea Level
        $location->gpsAltitudeRef = 1;
        $iptc->locationsShown = [$location];

        $this->assertStringContainsString('GPSAltitudeRef=1', $iptc->toExiftool()[0]);
        $this->exiftool->write($this->filename, $iptc);

        // Read internal values.
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('1', $iptc->locationsShown[0]->gpsAltitudeRef->toString());

        // Read human-readable format
        $this->exiftool->useHumanValues();
        $iptc = $this->exiftool->read($this->filename);
        $this->assertEquals('Below Sea Level', $iptc->locationsShown[0]->gpsAltitudeRef);
    }
}