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
        $api = new OpenApi($spec, '2025.1');
        $api->save(__DIR__.'/../openapi/iptc.json');
        // Then run:
        // npx openapi-generate-html -i openapi/iptc.json --output=openapi/index.html --ui=stoplight

        $this->markTestSkipped();
    }
}
