<?php

namespace Codewiser\Exiftool;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Attributes\DateTimeAttribute;
use Codewiser\Exiftool\Attributes\PlainAttribute;
use Codewiser\Exiftool\Attributes\StructureAttribute;
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

        // Init first, then fill
        $this->openapi['components']['schemas']['IPTC'] = [];

        $attributes = $this->specification->topLevel()->getAttributes();

        usort($attributes, function (TopLevelAttributeSpec $a, TopLevelAttributeSpec $b) {
            if ($a->sortOrder() == $b->sortOrder()) {
                return 0;
            }

            return $a->sortOrder() < $b->sortOrder() ? -1 : 1;
        });

        $this->openapi['components']['schemas']['IPTC'] = $this->makeTop(
            $attributes
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
        $externalLink = "https://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata";

        $top = [
            'description'  => "IPTC Photo Metadadata Standard\n\n[$externalLink]($externalLink)",
            'externalDocs' => [
                'description' => 'IPTC',
                'url'         => $externalLink
            ],
            'type'         => 'object',
            'properties'   => []
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
        $externalLink = "https://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata{$attr->specIdx()}";

        $topic = $this->topic($attr);

        $api = [
            'description'  => "{$attr->name()}".
                ($topic ? "\n\n`$topic`" : '').
                ($attr->helpText() ? "\n\n".$attr->helpText() : '').
                ($attr->userNotes() ? "\n\n".$attr->userNotes() : '').
                "\n\n".
                "[$externalLink]($externalLink)",
            'externalDocs' => [
                'description' => $attr->name(),
                'url'         => $externalLink
            ],
        ];

        if ($topic) {
            $api['tags'] = [$topic];
        }

        if (str_contains($attr->name(), '(legacy)')) {
            $api['deprecated'] = true;
        }

        if (! $attr->isRequired()) {
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
        } elseif ($attr->dataFormat() == 'url' || $attr->dataFormat() == 'uri') {
            $item['format'] = $attr->dataFormat();
        }
        if ($attr->dataFormat() == 'date-time') {
            $item['format'] = 'date-time';
        }

        return $item;
    }

    protected function makeExample(AttributeSpec $attr): mixed
    {
        $generator = match ($attr->dataFormat()) {
            'AltLang'   => new AltLangAttribute(),
            'date-time' => new DateTimeAttribute(),
            default     => new PlainAttribute()
        };

        if ($attr->dataType() == 'any') {
            $example = null;
        } elseif ($attr->isSingle()) {
            $example = $generator->fake($attr);
        } else {
            $example = [
                $generator->fake($attr),
                (clone $generator)->fake($attr)
            ];
        }

        return $example;
    }

    protected function makePlain(AttributeSpec $attr): array
    {
        $api = $this->makeDefault($attr);

        if ($attr->dataType() == 'any') {
            $api['type'] = 'any';
        } elseif ($attr->isSingle()) {
            $api = array_merge($api, $this->makeSingular($attr));
        } else {
            $api['type'] = 'array';
            $api['items'] = $this->makeSingular($attr);
        }
        $example = $this->makeExample($attr);
        if ($example !== null) {
            $api['example'] = $example;
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
            $api['example'] = $this->makeExample($attr);
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

        $this->openapi['components']['schemas'][$struct->dataFormat()] = $el;

        $api = $this->makeDefault($struct);

        if ($struct->isSingle()) {
            $api['$ref'] = '#/components/schemas/'.$struct->dataFormat();
        } else {
            $api['type'] = 'array';
            $api['items'] = ['$ref' => '#/components/schemas/'.$struct->dataFormat()];
        }

        return $api;
    }

    protected function topic(AttributeSpec $attr): ?string
    {
        $topic = $attr instanceof TopLevelAttributeSpec ? $attr->topic() : null;

        return match ($topic) {
            'admin'     => 'Administrative Details',
            'gimgcont'  => 'General Image Content',
            'imgreg'    => 'Image Region',
            'licensing' => 'Licensing Use',
            'location'  => 'Location',
            'othings'   => 'Other Things Shown',
            'person'    => 'Persons Shown',
            'rights'    => 'Rights Information',
            default     => $topic
        };
    }

    public function save(string $filename): bool|int
    {
        return file_put_contents($filename, json_encode($this->make(), JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
    }
}