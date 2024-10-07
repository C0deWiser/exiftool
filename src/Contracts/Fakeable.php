<?php

namespace Codewiser\Exiftool\Contracts;

use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;

interface Fakeable
{
    /**
     * Fill with fake data.
     */
    public function fake(?AttributeSpec $spec = null): static;
}
