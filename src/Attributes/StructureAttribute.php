<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Codewiser\Exiftool\Spec\StructureSpec;
use Codewiser\Exiftool\Traits\HasAttributes;

class StructureAttribute implements Contracts\Structure
{
    use HasAttributes;

    public function __construct(public StructureSpec $structure)
    {
        //
    }

    public function getAttributeSpec(string $name): ?AttributeSpec
    {
        return $this->structure->getAttributeByJsonName($name);
    }

    public function fake(?AttributeSpec $spec = null): static
    {
        $faked = [];

        foreach ($this->structure->getAttributes() as $spec) {
            $jsonName = $spec->jsonName();
            $attr = AttributeFactory::for($spec);
            $faked[$jsonName] = $attr->fake($spec);
        }

        $this->attributes = $faked;

        return $this;
    }

    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static
    {
        $this->et = array_merge($this->et, $values);

        foreach ($this->et as $attributes) {
            foreach ($attributes as $etName => $value) {
                // Spec may not be found in case of wrong structure. Just skip it, we cant fix it.
                if ($spec = $this->structure->getAttributeByEtName($etName)) {
                    $jsonName = $spec->jsonName();
                    $attr = $this->attributes[$jsonName] ?? AttributeFactory::for($spec);
                    $attr->fromExiftool([$etName => $value], $spec);
                    $this->attributes[$jsonName] = $attr;
                }
            }
        }

        return $this;
    }

    public function toExiftool(AttributeSpec $spec): array
    {
        $data = [];

        foreach ($spec->etNamesWithPrefix() as $etName) {
            $values = [];
            foreach ($this->attributes as $jsonName => $attribute) {
                if ($jsonName == '$anypmdproperty') {
                    continue;
                }

                $attributeSpec = $this->structure->getAttributeByJsonName($jsonName);

                foreach ($attribute->toExiftool($attributeSpec) as $attrEtName => $value) {
                    if (is_string($value)) {
                        if (!$attributeSpec->struct()) {
                            // Do not escape nested structs
                            $value = str_replace(['|', ','], ['||', '|,'], $value);
                        }
                    } elseif (is_array($value)) {
                        // ExifTool treats ProductInImage.ProductId as a plain value.
                        // We are forced to write just one identifier.
                        if ($this->structure->name === 'ProductWGtin' && $jsonName === 'identifiers') {
                            $value = (string) reset($value);
                        } else {
                            $value = '['.implode(',', $value).']';
                        }
                    }
                    $values[] = $attrEtName.'='.$value;
                }
            }
            if ($values) {
                $data[$etName] = '{'.implode(',', $values).'}';
            }
        }

        return $data;
    }

    public function fromJson(?array $values): static
    {
        if (!$values) {
            throw new MistypeException();
        }

        $attributes = [];

        $values = array_filter($values, fn($v) => !is_null($v));

        foreach ($values as $jsonName => $value) {
            $spec = $this->structure->getAttributeByJsonName($jsonName);
            if (!$spec) {
                continue;
            }
            $attr = AttributeFactory::for($spec);

            try {
                if ($attr instanceof Contracts\Structure) {
                    $attributes[$jsonName] = $attr->fromJson($value);
                } else {
                    $attributes[$jsonName] = $attr->fromJson([$jsonName => $value]);
                }
            } catch (MistypeException) {
                //
            }
        }

        $this->attributes = $attributes;

        return $this;
    }
}
