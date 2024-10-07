<?php

namespace Codewiser\Exiftool\Contracts;

use Stringable;

interface Plain extends Attribute, Stringable
{
    /**
     * Get scalar value of attribute.
     */
    public function toString(): string;
}
