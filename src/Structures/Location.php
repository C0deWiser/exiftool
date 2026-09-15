<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|AltLang $name Location Name.
 * @property null|Multiple|Plain[] $identifiers Location ID.
 * @property null|Plain $city City.
 * @property null|Plain $sublocation Sublocation.
 * @property null|Plain $countryName Country Name.
 * @property null|Plain $countryCode Country ISO-Code.
 * @property null|Plain $provinceState Province/State.
 * @property null|Plain $gpsAltitude GPS-Altitude.
 * @property null|Plain $gpsAltitudeRef GPS-Altitude Ref.
 * @property null|Plain $gpsLongitude GPS-Longitude.
 * @property null|Plain $gpsLatitude GPS-Latitude.
 * @property null|Plain $worldRegion World Region.
 */
interface Location extends Structure
{

}
