<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|AltLang $description
 * @property null|Plain $gtin
 * @property null|Plain $identifiers
 * @property null|AltLang $name
 */
interface ProductWGtin extends Structure
{

}
