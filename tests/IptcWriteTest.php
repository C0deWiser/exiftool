<?php

namespace Tests;

class IptcWriteTest extends TestCase
{
    protected string $template = __DIR__.'/IPTC-Empty.jpg';
    protected string $filename = __DIR__.'/IptcWriteTest.jpg';

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
        $iptc = $this->exiftool->newMetadata()->fake();

        $proc = $this->exiftool->write($this->filename, $iptc);
        dump($proc->getErrorOutput());

        $embedded = $this->exiftool->read($this->filename);
        $this->assertEquals($iptc->jsonSerialize(), $embedded->jsonSerialize());
    }
}