<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $licensorId Licensor ID.
 * @property null|Plain $licensorName Licensor Name.
 * @property null|Plain $licensorAddress Licensor Address.
 * @property null|Plain $licensorAddressDetail Licensor Address Detail.
 * @property null|Plain $licensorCity Licensor City.
 * @property null|Plain $licensorStateProvince Licensor State or Province.
 * @property null|Plain $licensorPostalCode Licensor Postal Code.
 * @property null|Plain $licensorCountryName Licensor Country.
 * @property null|Plain $licensorTelephoneType1 Licensor Telephone Type 1.
 * @property null|Plain $licensorTelephone1 Licensor Telephone 1.
 * @property null|Plain $licensorTelephoneType2 Licensor Telephone Type 2.
 * @property null|Plain $licensorTelephone2 Licensor Telephone 2.
 * @property null|Plain $licensorEmail Licensor Email.
 * @property null|Plain $licensorUrl Licensor URL.
 */
interface Licensor extends Structure
{

}
