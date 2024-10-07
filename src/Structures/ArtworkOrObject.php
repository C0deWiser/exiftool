<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\DateTime;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $circaDateCreated
 * @property null|AltLang $contentDescription
 * @property null|AltLang $contributionDescription
 * @property null|Plain $copyrightNotice
 * @property null|Multiple|Plain[] $creatorIdentifiers
 * @property null|Multiple|Plain[] $creatorNames
 * @property null|Plain $currentCopyrightOwnerIdentifier
 * @property null|Plain $currentCopyrightOwnerName
 * @property null|Plain $currentLicensorIdentifier
 * @property null|Plain $currentLicensorName
 * @property null|DateTime $dateCreated
 * @property null|AltLang $physicalDescription
 * @property null|Plain $source
 * @property null|Plain $sourceInventoryNr
 * @property null|Plain $sourceInventoryUrl
 * @property null|Multiple|Plain[] $stylePeriod
 * @property null|AltLang $title
 */
interface ArtworkOrObject extends Structure
{

}
