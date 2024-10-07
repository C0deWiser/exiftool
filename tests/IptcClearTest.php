<?php

namespace Tests;

class IptcClearTest extends TestCase
{
    protected string $template = __DIR__.'/IPTC-PhotometadataRef-Std2023.2.jpg';
    protected string $filename = __DIR__.'/IptcClearTest.jpg';

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

    public function test()
    {
        $this->exiftool->clear($this->filename);

        $iptc = $this->exiftool->read($this->filename);

        $this->assertEquals([], $iptc->jsonSerialize());
    }
}