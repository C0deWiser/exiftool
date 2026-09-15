<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $rbShape Shape.
 * @property null|Plain $rbUnit Measuring Unit.
 * @property null|Plain $rbX X-Axis Coordinate.
 * @property null|Plain $rbY Y-Axis Coordinate.
 * @property null|Plain $rbW Rectangle Width.
 * @property null|Plain $rbH Rectangle Height.
 * @property null|Plain $rbRx Circle Radius.
 * @property null|Multiple|RegionBoundaryPoint[] $rbVertices Polygon Vertices.
 */
interface RegionBoundary extends Structure
{

}
