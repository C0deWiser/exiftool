<?php

namespace Codewiser\Exiftool\Spec;

use Codewiser\Exiftool\Spec\Concerns\AttributeBag;
use Codewiser\Exiftool\Traits\HasAltLang;

/**
 * IPTC Specification for top level elements.
 */
class TopLevel implements AttributeBag
{
    use HasAltLang;

    public function listTopNoPrefix(): array
    {
        return Specification::get()['et_topnoprefix'];
    }

    public function listTopWithPrefix(): array
    {
        return Specification::get()['et_topwithprefix'];
    }

    public function getAttributesEtNames(): array
    {
        return array_keys($this->listTopNoPrefix());
    }

    /**
     * @return array<TopLevelAttributeSpec>
     */
    public function getAttributes(): array
    {
        return array_map(
            fn($etName) => $this->getAttributeByJsonName($etName),
            $this->getAttributesJsonNames()
        );
    }

    public function getAttributeByEtName(string $etName): ?TopLevelAttributeSpec
    {
        return ($jsonName = $this->getAttributeJsonName($etName))
            ? $this->getAttributeByJsonName($jsonName)
            : null;
    }

    public function getAttributesJsonNames(): array
    {
        return array_keys(Specification::get()['ipmd_top']);
    }

    public function getAttributeByJsonName(string $jsonName): ?TopLevelAttributeSpec
    {
        $spec = Specification::get()['ipmd_top'][$jsonName] ?? null;

        if (!$spec) {
            return null;
        }

        return new TopLevelAttributeSpec(
            $spec,
            $jsonName,
            $this->getEtNames($jsonName),
            $this->getEtNamesWithPrefix($jsonName),
        );
    }

    public function getAttributeJsonName(string $etName): ?string
    {
        $etName = $this->pureEtName($this->getAttributesEtNames(), $etName);

        return $etName ? $this->listTopNoPrefix()[$etName]['ipmdid'] : null;
    }

    public function getEtNames(string $jsonName): array
    {
        $map = array_filter($this->listTopNoPrefix(), fn($map) => ($map['ipmdid'] ?? null) == $jsonName);

        return array_keys($map);
    }

    public function getEtNamesWithPrefix(string $jsonName): array
    {
        $map = array_filter($this->listTopWithPrefix(), fn($map) => ($map['ipmdid'] ?? null) == $jsonName);

        return array_map(fn($name) => str_replace('_', ':', $name), array_keys($map));
    }
}
