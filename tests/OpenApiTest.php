<?php

namespace Tests;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\OpenApi;
use Codewiser\Exiftool\Spec\Specification;
use PHPUnit\Framework\TestCase;

class OpenApiTest extends TestCase
{
    public function test()
    {
        AltLangAttribute::collapse();

        $spec = (new Exiftool)->useMachineValues()->specification();
        $api = new OpenApi($spec, '2024.1');
        $spec = $api->make();

        dump($spec);

        $api->save(__DIR__.'/../openapi/iptc.json');

        $this->markTestSkipped();
    }
}
