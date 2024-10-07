<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\DateTime;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;

/**
 * @property null|Multiple|CvTerm[] $aboutCvTerms
 * @property null|Plain $additionalModelInfo
 * @property null|AltLang $altTextAccessibility
 * @property null|Multiple|ArtworkOrObject[] $artworkOrObjects
 *
 * @property null|Plain $captionWriter
 * @property null|Plain $cityName
 * @property null|Multiple|EntityWRole[] $contributors
 * @property null|AltLang $copyrightNotice
 * @property null|Multiple|CopyrightOwner[] $copyrightOwners
 * @property null|Plain $countryCode
 * @property null|Plain $countryName
 * @property null|CreatorContactInfo $creatorContactInfo
 * @property null|Multiple|Plain[] $creatorNames
 * @property null|Plain $creditLine
 *
 * @property null|Plain $dataMining
 * @property null|DateTime $dateCreated
 * @property null|AltLang $description
 * @property null|Plain $digitalImageGuid
 * @property null|Plain $digitalSourceType
 *
 * @property null|Multiple|Plain[] $eventId
 *
 * @property null|Multiple|EmbdEncRightsExpr[] $embdEncRightsExprs
 * @property null|AltLang $eventName
 * @property null|AltLang $extDescrAccessibility
 *
 * @property null|Multiple|CvTerm[] $genres
 *
 * @property null|Plain $headline
 *
 * @property null|Multiple|ImageCreator[] $imageCreators
 * @property null|Plain $imageRating
 * @property null|Multiple|ImageRegion[] $imageRegion
 * @property null|Plain $imageSupplierImageId
 * @property null|Plain $instructions
 * @property null|Plain $intellectualGenre
 *
 * @property null|Plain $jobid
 * @property null|Plain $jobtitle
 *
 * @property null|Multiple|Plain[] $keywords
 *
 * @property null|Multiple|Licensor[] $licensors
 * @property null|Multiple|LinkedEncRightsExpr[] $linkedEncRightsExprs
 * @property null|Location $locationCreated
 * @property null|Multiple|Location[] $locationsShown
 *
 * @property null|Plain $maxAvailHeight
 * @property null|Plain $maxAvailWidth
 * @property null|Plain $minorModelAgeDisclosure
 * @property null|Multiple|Plain[] $modelAges
 * @property null|Multiple|Plain[] $modelReleaseDocuments
 * @property null|Plain $modelReleaseStatus
 *
 * @property null|Multiple|Plain[] $organisationInImageCodes
 * @property null|Multiple|Plain[] $organisationInImageNames
 * @property null|AltLang $otherConstraints
 *
 * @property null|Multiple|Plain[] $personInImageNames
 * @property null|Multiple|PersonWDetails[] $personsShown
 * @property null|Multiple|ProductWGtin[] $productsShown
 * @property null|Multiple|Plain[] $propertyReleaseDocuments
 * @property null|Plain $propertyReleaseStatus
 * @property null|Plain $provinceState
 *
 * @property null|Multiple|RegistryEntry[] $registryEntries
 * @property null|Multiple|Plain[] $sceneCodes
 * @property null|Plain $source
 * @property null|Multiple|Plain[] $subjectCodes
 * @property null|Plain $sublocationName
 * @property null|Multiple|ImageSupplier[] $suppliers
 *
 * @property null|AltLang $title
 *
 * @property null|AltLang $usageTerms
 *
 * @property null|Plain $webstatementRights
 */
interface TopLevel
{
    //
}
