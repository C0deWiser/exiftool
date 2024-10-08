<?php

namespace Codewiser\Exiftool\Spec\Concerns;

interface AttributeBag
{
    /**
     * Get attribute listing.
     *
     * @return array<AttributeSpec>
     */
    public function getAttributes(): array;

    /**
     * Get attribute specification by its json-name.
     */
    public function getAttributeByJsonName(string $jsonName): ?AttributeSpec;

    public function getAttributeByEtName(string $etName): ?AttributeSpec;

    public function getAttributesEtNames(): array;

    public function getAttributesJsonNames(): array;

    public function getAttributeJsonName(string $etName): ?string;
}
