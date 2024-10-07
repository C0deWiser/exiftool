<?php

namespace Codewiser\Exiftool\Contracts;

use ArrayAccess;
use Countable;
use Iterator;

interface Multiple extends Attribute, Iterator, ArrayAccess, Countable
{
    /**
     * Gat all attributes.
     *
     * @return array<Attribute>
     */
    public function toArray(): array;
}
