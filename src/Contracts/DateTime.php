<?php

namespace Codewiser\Exiftool\Contracts;

use DateTimeInterface;
use Stringable;

interface DateTime extends Attribute, Stringable
{
    /**
     * Get DateTime value of the attribute.
     */
    public function toDateTime(): DateTimeInterface;
}
