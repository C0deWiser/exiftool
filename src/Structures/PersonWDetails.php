<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Multiple|Plain[] $identifiers Identifier.
 * @property null|AltLang $name Name.
 * @property null|AltLang $description Description.
 * @property null|Multiple|CvTerm[] $characteristics Characteristics.
 */
interface PersonWDetails extends Structure
{

}
