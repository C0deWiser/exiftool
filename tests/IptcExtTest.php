<?php

namespace Tests;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\IptcExt;

class IptcExtTest extends TestCase
{
    protected function tearDown(): void
    {
        AltLangAttribute::$collapsed = false;

        parent::tearDown();
    }

    public function testGetValidationRules()
    {
        $ext = new IptcExt($this->exiftool->useMachineValues()->specification());
        AltLangAttribute::$collapsed = true;
        dump($ext->getValidationRules([
            'enum' => true
        ]));

        $this->markTestSkipped();
    }

    public function testDot()
    {
        $ext = new IptcExt($this->exiftool->useMachineValues()->specification());
        AltLangAttribute::$collapsed = true;
        dump($ext->asDotArray());

        $this->markTestSkipped();
    }
}
