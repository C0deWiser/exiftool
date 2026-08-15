<?php

namespace Tests;

class IptcWriteTest extends TestCase
{
    protected string $template = __DIR__.'/IPTC-Empty.jpg';
    protected string $filename = __DIR__.'/IptcWriteTest.jpg';
    protected string $spaced = __DIR__.'/Iptc Write Test.jpg';

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([$this->filename, $this->spaced] as $filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }

            copy($this->template, $filename);
        }

    }

    protected function tearDown(): void
    {
        foreach ([$this->filename, $this->spaced] as $filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        parent::tearDown();
    }

    public function test()
    {
        $iptc = $this->exiftool->newMetadata()->fake();
        $iptc->productsShown = null;

        $proc = $this->exiftool->write($this->filename, $iptc);
        dump($proc->getErrorOutput());

        $embedded = $this->exiftool->read($this->filename);

        $this->assertEquals($iptc->jsonSerialize(), $embedded->jsonSerialize());
    }

    public function test_spaced()
    {
        $iptc = $this->exiftool->newMetadata()->fake();
        $iptc->productsShown = null;

        $proc = $this->exiftool->write($this->spaced, $iptc);
        dump($proc->getErrorOutput());

        $embedded = $this->exiftool->read($this->spaced);

        $this->assertEquals($iptc->jsonSerialize(), $embedded->jsonSerialize());
    }
}