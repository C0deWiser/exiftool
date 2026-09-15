<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $gtin GTIN.
 * @property null|Plain $identifiers Identifier.
 * @property null|AltLang $name Name.
 * @property null|AltLang $description Description.
 */
interface ProductWGtin extends Structure
{

}
