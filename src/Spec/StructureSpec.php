<?php

namespace Codewiser\Exiftool\Spec;

use Codewiser\Exiftool\Spec\Concerns\AttributeBag;
use Codewiser\Exiftool\Traits\HasAltLang;

/**
 * IPTC Specification for structure.
 */
class StructureSpec implements AttributeBag
{
    use HasAltLang;

    protected array $spec;

    public function __construct(public string $name)
    {
        $this->spec = Specification::get()['ipmd_struct'][$this->name];
    }

    public function getAttributesEtNames(): array
    {
        return array_map(fn($spec) => $spec['etTag'], $this->spec);
    }

    /**
     * @return array<StructureAttributeSpec>
     */
    public function getAttributes(): array
    {
        return array_map(
            fn($name) => $this->getAttributeByJsonName($name),
            $this->getAttributesJsonNames()
        );
    }

    public function getAttributesJsonNames(): array
    {
        return array_keys($this->spec);
    }

    public function getAttributeByEtName(string $etName): ?StructureAttributeSpec
    {
        return ($jsonName = $this->getAttributeJsonName($etName))
            ? $this->getAttributeByJsonName($jsonName)
            : null;
    }

    public function getAttributeByJsonName(string $jsonName): ?StructureAttributeSpec
    {
        $spec = $this->spec[$jsonName] ?? null;

        if (!$spec) {
            return null;
        }

        return new StructureAttributeSpec(
            $spec,
            $this->name,
            $jsonName,
            $this->getEtName($jsonName)
        );
    }

    public function getAttributeJsonName(string $etName): ?string
    {
        $etName = $this->pureEtName($this->getAttributesEtNames(), $etName);

        return $etName ? Specification::get()['et_instructure'][$etName]['ipmdid'] : null;
    }

    public function getEtName(string $jsonName): string
    {
        return $this->spec[$jsonName]['etTag'];
    }
}
