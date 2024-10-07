<?php

namespace Codewiser\Exiftool\Spec;

use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;

/**
 * IPTC Specification for top level attribute.
 */
class TopLevelAttributeSpec extends AttributeSpec
{
    public function __construct(
        protected array $spec,
        protected string $jsonName,
        protected array $etNames,
        protected array $etNamesWithPrefix
    ) {
        //
    }

    /**
     * Get an attribute group.
     */
    public function topic(): string
    {
        return $this->spec['ugtopic'];
    }

    public function etNames(): array
    {
        return $this->etNames;
    }

    public function etNamesWithPrefix(): array
    {
        return $this->etNamesWithPrefix;
    }
}
