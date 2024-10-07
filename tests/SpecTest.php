<?php

namespace Tests;

use Codewiser\Exiftool\Spec\Specification;

class SpecTest extends TestCase
{
    public Specification $spec;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spec = Specification::fetch(__DIR__.'/../iptc-pmd-techreference_2023.2.json');
    }

    public function test()
    {
        $topAttr = $this->spec->topLevel()->getAttributeByEtName('AltTextAccessibility-en');
        $this->assertTrue(in_array('AltTextAccessibility', $topAttr->etNames()));

        $structAttr = $this->spec->topLevel()
            ->getAttributeByEtName('ArtworkOrObject')
            ->struct()
            ->getAttributeByEtName('AOContentDescription-en');
        $this->assertEquals('AOContentDescription', $structAttr->etName());
    }
}
