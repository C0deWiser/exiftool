<?php

namespace Codewiser\Exiftool\Contracts;

use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;

interface Importable
{
    /**
     * Import values from exiftool command-line notation.
     *
     * This method may be called many times while importing.
     *
     * [Name => value, Name-Alt => value], [Alt-Name => value]
     *
     */
    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static;

    /**
     * Import values from array with json-named keys.
     */
    public function fromJson(array $values): static;
}
