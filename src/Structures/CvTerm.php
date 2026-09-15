<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $cvId CV ID.
 * @property null|Plain $cvTermId Term ID.
 * @property null|AltLang $cvTermName Name.
 * @property null|Plain $cvTermRefinedAbout Refined Aboutness.
 */
interface CvTerm extends Structure
{

}
