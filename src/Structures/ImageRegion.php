<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|AltLang $name Name.
 * @property null|Multiple|Entity[] $rCtype Content Type.
 * @property null|Plain $rId Identifier.
 * @property null|Multiple|Entity[] $rRole Role.
 * @property null|RegionBoundary $regionBoundary Region Boundary.
 */
interface ImageRegion extends Structure
{

}
