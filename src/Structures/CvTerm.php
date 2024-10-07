<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $cvId
 * @property null|Plain $cvTermId
 * @property null|AltLang $cvTermName
 * @property null|Plain $cvTermRefinedAbout
 */
interface CvTerm extends Structure
{

}
