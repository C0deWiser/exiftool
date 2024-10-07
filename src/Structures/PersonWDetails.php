<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Multiple|CvTerm[] $characteristics
 * @property null|AltLang $description
 * @property null|Multiple|Plain[] $identifiers
 * @property null|AltLang $name
 */
interface PersonWDetails extends Structure
{

}
