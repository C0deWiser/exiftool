<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $city
 * @property null|Plain $countryCode
 * @property null|Plain $countryName
 * @property null|Plain $gpsAltitude
 * @property null|Plain $gpsAltitudeRef
 * @property null|Plain $gpsLatitude
 * @property null|Plain $gpsLongitude
 * @property null|Multiple|Plain[] $identifiers
 * @property null|AltLang $name
 * @property null|Plain $provinceState
 * @property null|Plain $sublocation
 * @property null|Plain $worldRegion
 */
interface Location extends Structure
{

}
