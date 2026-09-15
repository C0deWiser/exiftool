<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\DateTime;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $circaDateCreated Circa Date Created.
 * @property null|AltLang $contentDescription Content Description.
 * @property null|AltLang $contributionDescription Contribution Description.
 * @property null|Plain $copyrightNotice Copyright Notice.
 * @property null|Multiple|Plain[] $creatorIdentifiers Creator ID.
 * @property null|Multiple|Plain[] $creatorNames Creator.
 * @property null|Plain $currentCopyrightOwnerIdentifier Current Copyright Owner ID.
 * @property null|Plain $currentCopyrightOwnerName Current Copyright Owner Name.
 * @property null|Plain $currentLicensorIdentifier Current Licensor ID.
 * @property null|Plain $currentLicensorName Current Licensor Name.
 * @property null|DateTime $dateCreated Date Created.
 * @property null|AltLang $physicalDescription Physical Description.
 * @property null|Plain $source Source.
 * @property null|Plain $sourceInventoryNr Source inventory number.
 * @property null|Plain $sourceInventoryUrl Source Inventory URL.
 * @property null|Multiple|Plain[] $stylePeriod Style Period.
 * @property null|AltLang $title Title.
 */
interface ArtworkOrObject extends Structure
{

}
