<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $address Address.
 * @property null|Plain $city City.
 * @property null|Plain $country Country.
 * @property null|Plain $emailwork Email(s).
 * @property null|Plain $phonework Phone(s).
 * @property null|Plain $postalCode Postal Code.
 * @property null|Plain $region State/Province.
 * @property null|Plain $weburlwork Web URL(s).
 */
interface CreatorContactInfo extends Structure
{
    //
}
