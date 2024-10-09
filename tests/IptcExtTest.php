<?php

namespace Tests;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\IptcExt;

class IptcExtTest extends TestCase
{

    public function testGetValidationRules()
    {
        $ext = new IptcExt($this->exiftool->printConv()->specification());
        AltLangAttribute::$collapsed = true;
        dump($ext->getValidationRules([
            'enum' => true
        ]));

        $this->markTestSkipped();
    }

    public function testDot()
    {
        $ext = new IptcExt($this->exiftool->printConv()->specification());
        AltLangAttribute::$collapsed = true;
        dump($ext->asDotArray());

        $this->markTestSkipped();
    }
}
