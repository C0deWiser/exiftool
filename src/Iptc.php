<?php

namespace Codewiser\Exiftool;

use Codewiser\Exiftool\Attributes\AttributeFactory;
use Codewiser\Exiftool\Contracts\Collection;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Codewiser\Exiftool\Spec\TopLevel;
use Codewiser\Exiftool\Structures;
use Codewiser\Exiftool\Traits\HasAttributes;

class Iptc implements Structures\TopLevel, Collection
{
    use HasAttributes;

    public function __construct(public TopLevel $specification)
    {
        //
    }

    /**
     * Fill metadata with fake values.
     */
    public function fake(?AttributeSpec $spec = null): static
    {
        foreach ($this->specification->getAttributes() as $spec) {
            $jsonName = $spec->jsonName();
            $attr = AttributeFactory::for($spec);
            $this->attributes[$jsonName] = $attr->fake($spec);
        }

        return $this;
    }

    /**
     * Get attribute specification by its json-name.
     */
    public function getAttributeSpec(string $name): ?AttributeSpec
    {
        return $this->specification->getAttributeByJsonName($name);
    }

    /**
     * Import rows, exported from exiftool (with -struct -json).
     */
    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static
    {
        $this->et = array_merge($this->et, $values);

        foreach ($this->et as $etName => $value) {
            // Dealing only with familiar names
            if ($spec = $this->specification->getAttributeByEtName($etName)) {
                $jsonName = $spec->jsonName();
                $attr = $this->attributes[$jsonName] ?? AttributeFactory::for($spec);
                $attr->fromExiftool([$etName => $value], $spec);
                $this->attributes[$jsonName] = $attr;
            }
        }

        return $this;
    }

    /**
     * Export rows to import to exiftool.
     */
    public function toExiftool(): array
    {
        $attributes = [];

        foreach ($this->attributes as $jsonName => $attribute) {
            $spec = $this->getAttributeSpec($jsonName);

            $rows = $attribute->toExiftool($spec);

            if ($spec->isMultiple()) {
                if ($spec->struct()) {
                    foreach ($rows as $etName => $values) {
                        $assign = '=';
                        foreach ($values as $value) {
                            $attributes[] = '-'.$etName.$assign.($value);
                            $assign = '+=';
                        }
                    }
                } else {
                    foreach ($rows as $etName => $values) {
                        $attributes[] = '-'.$etName.'='.(implode(Exiftool::$separator, $values));
                    }
                }
            } else {
                foreach ($rows as $etName => $value) {
                    $attributes[] = '-'.$etName.'='.($value);
                }
            }
        }

        return $attributes;
    }
}
