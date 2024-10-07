<?php

namespace Codewiser\Exiftool\Contracts;

use ArrayAccess;
use Stringable;

interface AltLang extends Attribute, ArrayAccess, Stringable
{
    /**
     * Get AltLang values of attribute.
     *
     * @return array<string>
     */
    public function toArray(): array;

    /**
     * Get value of attribute is current locale.
     */
    public function toString(): string;

    /**
     * Whether attribute returns string when jsonSerialized?
     */
    public function isCollapsed(): bool;
}
