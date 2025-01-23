<?php

namespace Codewiser\Exiftool;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Codewiser\Exiftool\Spec\Specification;
use Codewiser\Exiftool\Spec\TopLevelAttributeSpec;

class OpenApi
{
    public array $openapi = [];

    public function __construct(public Specification $specification, public string $version)
    {
        //
    }

    public function make(): array
    {
        $this->openapi['openapi'] = '3.1.0';
        $this->openapi['info'] = [
            'title'       => 'IPTC Photo Metadadata Standard',
            'description' => $this->specification->releaseComment(),
            'version'     => $this->version,
        ];
        $this->openapi['externalDocs'] = [
            'url'         => $this->specification->externalDocumentation(),
            'description' => 'IPTC Specification'
        ];

        $this->openapi['components'] = [
            'schemas' => []
        ];

        $this->openapi['components']['schemas']['iptc'] = $this->makeTop(
            $this->specification->topLevel()->getAttributes()
        );

        return $this->openapi;
    }

    /**
     * @param  array<TopLevelAttributeSpec>  $attributes
     *
     * @return array
     */
    protected function makeTop(array $attributes): array
    {
        $top = [
            'description' => 'IPTC',
            'externalDocs' =>
                ['url' => "https://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata"],
            'type'        => 'object',
            'properties'  => []
        ];

        foreach ($attributes as $attr) {
            if ($attr->dataFormat() == 'AltLang') {
                $top['properties'][$attr->jsonName()] = $this->makeAltLang($attr);
            } elseif ($attr->dataType() == 'struct') {
                $top['properties'][$attr->jsonName()] = $this->makeStruct($attr);
            } else {
                $top['properties'][$attr->jsonName()] = $this->makePlain($attr);
            }
        }

        return $top;
    }

    protected function makeDefault(AttributeSpec $attr): array
    {
        $api = [
            'description' => $attr->name(),
            'externalDocs' =>
            ['url' => "https://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata{$attr->specIdx()}"],
        ];

        if (str_contains($attr->name(), '(legacy)')) {
            $api['deprecated'] = true;
        }

        if (!$attr->isRequired()) {
            $api['nullable'] = true;
        }

        return $api;
    }

    protected function makeSingular(AttributeSpec $attr): array
    {
        $item = [
            'type' => match ($attr->dataType()) {
                'number' => $attr->dataFormat() ?? 'number',
                default  => 'string'
            }
        ];
        if ($max = $attr->maxBytes()) {
            $item['maxLength'] = $max;
        }
        if ($enum = $attr->enum()) {
            $item['enum'] = $enum;
        } elseif ($attr->dataFormat() == 'url') {
            $item['format'] = 'uri';
        }
        if ($attr->dataFormat() == 'date-time') {
            $item['format'] = 'date-time';
        }

        return $item;
    }

    protected function makePlain(AttributeSpec $attr): array
    {
        $api = $this->makeDefault($attr);

        if ($attr->isSingle()) {
            $api = array_merge($api, $this->makeSingular($attr));
        } else {
            $api['type'] = 'array';
            $api['items'] = $this->makeSingular($attr);
        }

        return $api;
    }

    protected function makeAltLang(AttributeSpec $attr): array
    {
        $api = $this->makeDefault($attr);

        if (AltLangAttribute::$collapsed) {
            $api = array_merge($api, $this->makeSingular($attr));
        } else {
            $api['type'] = 'object';
            $api['additionalProperties'] = $this->makeSingular($attr);
        }

        return $api;
    }

    protected function makeStruct(AttributeSpec $struct): array
    {
        $spec = $this->specification->struct($struct->dataFormat());

        $el = $this->makeDefault($struct) + ['properties' => []];

        foreach ($spec->getAttributes() as $attr) {
            if ($attr->dataFormat() == 'AltLang') {
                $el['properties'][$attr->jsonName()] = $this->makeAltLang($attr);
            } elseif ($attr->dataType() == 'struct') {
                $el['properties'][$attr->jsonName()] = $this->makeStruct($attr);
            } else {
                $el['properties'][$attr->jsonName()] = $this->makePlain($attr);
            }
        }

        $this->openapi['components']['schemas'][$struct->jsonName()] = $el;

        $api = $this->makeDefault($struct);

        if ($struct->isSingle()) {
            $api['$ref'] = '#/components/schemas/'.$struct->jsonName();
        } else {
            $api['type'] = 'array';
            $api['items'] = ['$ref' => '#/components/schemas/'.$struct->jsonName()];
        }

        return $api;
    }

    public function save(string $filename): bool|int
    {
        return file_put_contents($filename, json_encode($this->make(), JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
    }
}