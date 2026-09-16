<?php

namespace Tests\Spec2025;

class IptcInteropTest extends \Tests\Spec2024\IptcInteropTest
{
    protected string $spec = __DIR__.'/../../iptc-pmd-techreference_2025.1.json';
    protected string $reference = __DIR__.'/IPTC-PhotometadataRef-Std2025.1.jpg';
    protected string $work = __DIR__.'/IptcInteropTest-copy.jpg';
}