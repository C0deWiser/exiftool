<?php

namespace Codewiser\Exiftool\Contracts;

use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use JsonSerializable;

interface Attribute extends JsonSerializable, Importable, Fakeable
{
    /**
     * Export values to exiftool command-line notation.
     *
     * [XMP:Name => value, IPTC:Name => value]
     */
    public function toExiftool(AttributeSpec $spec): array;
}
