<?php

namespace Codewiser\Exiftool\Spec;

use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;

/**
 * IPTC Specification for structure attribute.
 */
class StructureAttributeSpec extends AttributeSpec
{
    public function __construct(
        protected array $spec,
        protected string $structName,
        protected string $jsonName,
        protected string $etName
    )
    {
        //
    }

    public function etName(): string
    {
        return $this->etName;
    }

    public function etNames(): array
    {
        return [$this->etName()];
    }

    public function etNamesWithPrefix(): array
    {
        return [$this->etName()];
    }
}
