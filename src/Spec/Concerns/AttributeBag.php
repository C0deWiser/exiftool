<?php

namespace Codewiser\Exiftool\Spec\Concerns;

interface AttributeBag
{
    /**
     * @return array<AttributeSpec>
     */
    public function getAttributes(): array;

    public function getAttributeByJsonName(string $jsonName): ?AttributeSpec;

    public function getAttributeByEtName(string $etName): ?AttributeSpec;

    public function getAttributesEtNames(): array;

    public function getAttributesJsonNames(): array;

    public function getAttributeJsonName(string $etName): ?string;
}
