<?php

namespace Tests\Spec2024;

class IptcInteropTest extends \Tests\Spec2023\IptcInteropTest
{
    protected string $spec = __DIR__.'/../../iptc-pmd-techreference_2024.1.json';
    protected string $reference = __DIR__.'/IPTC-PhotometadataRef-Std2024.1.jpg';
    protected string $work = __DIR__.'/IptcInteropTest-copy.jpg';
}