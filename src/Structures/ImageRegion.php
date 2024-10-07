<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|AltLang $name
 * @property null|Multiple|Entity[] $rCtype
 * @property null|Plain $rId
 * @property null|Multiple|Entity[] $rRole
 * @property null|RegionBoundary $regionBoundary
 */
interface ImageRegion extends Structure
{

}
