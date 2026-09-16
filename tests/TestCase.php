<?php

namespace Tests;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Exiftool;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Exiftool $exiftool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exiftool = new Exiftool();

        AltLangAttribute::useLocale('en');
    }
}