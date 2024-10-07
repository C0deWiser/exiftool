<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $rbH
 * @property null|Plain $rbRx
 * @property null|Plain $rbShape
 * @property null|Plain $rbUnit
 * @property null|Multiple|RegionBoundaryPoint[] $rbVertices
 * @property null|Plain $rbW
 * @property null|Plain $rbX
 * @property null|Plain $rbY
 */
interface RegionBoundary extends Structure
{

}
