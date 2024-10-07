<?php

namespace Codewiser\Exiftool\Contracts;

use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use JsonSerializable;

interface Collection extends JsonSerializable, Importable, Fakeable
{
    /**
     * Get all attributes.
     *
     * @return array<Attribute>
     */
    public function getAttributes(): array;

    /**
     * Get attribute by its json-name.
     */
    public function getAttribute(string $name): ?Attribute;

    /**
     * Get attribute specification by its json-name.
     */
    public function getAttributeSpec(string $name): ?AttributeSpec;

    public function __get(string $name): ?Attribute;

    public function __isset(string $name): bool;

    public function __set(string $name, $value): void;

    public function __unset(string $name): void;
}
