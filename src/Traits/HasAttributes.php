<?php

namespace Codewiser\Exiftool\Traits;

use Codewiser\Exiftool\Attributes\AttributeFactory;
use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;

trait HasAttributes
{
    /**
     * @var array<Contracts\Attribute>
     */
    protected array $attributes = [];
    protected array $et = [];

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name): ?Contracts\Attribute
    {
        return $this->attributes[$name] ?? null;
    }

    public function fromJson(array $values): static
    {
        $attributes = [];

        foreach ($values as $jsonName => $value) {
            if ($spec = $this->getAttributeSpec($jsonName)) {
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
        }

        $this->attributes = $attributes;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $serialized = [];

        foreach ($this->attributes as $key => $attribute) {
            if ($key == '$anypmdproperty') {
                continue;
            }
            $value = $attribute->jsonSerialize();

            if (
                $attribute instanceof Contracts\AltLang ||
                $attribute instanceof Contracts\Multiple ||
                $attribute instanceof Contracts\Structure
            ) {
                // May return empty array
                if ($value) {
                    $serialized[$key] = $value;
                }
            } else {
                $serialized[$key] = $value;
            }
        }

        return $serialized;
    }

    public function __get(string $name): ?Contracts\Attribute
    {
        return $this->getAttribute($name);
    }

    public function __set(string $name, $value): void
    {
        try {
            if (!($value instanceof Contracts\Attribute)) {

                $attr = AttributeFactory::for($this->getAttributeSpec($name));

                if ($attr instanceof Contracts\Structure) {
                    $value = $attr->fromJson($value);
                } else {
                    $value = $attr->fromJson([$name => $value]);
                }
            }

            $this->attributes[$name] = $value;
        } catch (MistypeException) {
            unset($this->$name);
        }
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->attributes[$name]);
    }
}
