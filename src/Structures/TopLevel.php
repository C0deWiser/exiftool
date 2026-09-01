<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Contracts\DateTime;
use Codewiser\Exiftool\Contracts\Multiple;
use Codewiser\Exiftool\Contracts\Plain;

/**
 * @property null|Multiple|CvTerm[] $aboutCvTerms CV-Term About Image.
 * @property null|Plain $additionalModelInfo Additional model info.
 * @property null|Plain $aIPromptInformation AI Prompt Information.
 * @property null|Plain $aIPromptWriterNam AI Prompt Writer Name.
 * @property null|Plain $aISystemUsed AI System Used.
 * @property null|Plain $aISystemVersionUsed AI System Version Used.
 * @property null|AltLang $altTextAccessibility Alt Text (Accessibility).
 * @property null|Multiple|ArtworkOrObject[] $artworkOrObjects Artwork or object in the image.
 *
 * @property null|Plain $captionWriter Caption/Description writer.
 * @property null|Plain $cityName City.
 * @property null|Multiple|EntityWRole[] $contributors Contributor.
 * @property null|AltLang $copyrightNotice Copyright Notice.
 * @property null|Multiple|CopyrightOwner[] $copyrightOwners Copyright owner.
 * @property null|Plain $countryCode ISO Country Code.
 * @property null|Plain $countryName Country.
 * @property null|CreatorContactInfo $creatorContactInfo Creator's Contact info.
 * @property null|Multiple|Plain[] $creatorNames Creator.
 * @property null|Plain $creditLine Credit Line.
 *
 * @property null|Plain $dataMining Data Mining.
 * @property null|DateTime $dateCreated Date Created.
 * @property null|AltLang $description Caption/Description.
 * @property null|Plain $digitalImageGuid Digital Image Identifier.
 * @property null|Plain $digitalSourceType Type of source for this photo.
 *
 * @property null|Multiple|EmbdEncRightsExpr[] $embdEncRightsExprs Embedded Encoded Rights Expression.
 * @property null|Multiple|Plain[] $eventId Event ID.
 * @property null|AltLang $eventName Event Name.
 * @property null|AltLang $extDescrAccessibility Extended Description (Accessibility).
 *
 * @property null|Multiple|CvTerm[] $genres Genre.
 *
 * @property null|Plain $headline Headline.
 *
 * @property null|Multiple|ImageCreator[] $imageCreators Image Creator.
 * @property null|Plain $imageRating Rating.
 * @property null|Multiple|ImageRegion[] $imageRegion Image Region(s).
 * @property null|Plain $imageSupplierImageId Image Supplier Image Id.
 * @property null|Plain $instructions Instructions.
 * @property null|Plain $intellectualGenre Intellectual genre.
 *
 * @property null|Plain $jobid Job Identifier.
 * @property null|Plain $jobtitle Creator's Jobtitle.
 *
 * @property null|Multiple|Plain[] $keywords Keywords.
 *
 * @property null|Multiple|Licensor[] $licensors Licensor.
 * @property null|Multiple|LinkedEncRightsExpr[] $linkedEncRightsExprs Linked Encoded Rights Expression.
 * @property null|Multiple|Location[] $locationCreated Location Created.
 * @property null|Multiple|Location[] $locationsShown Location shown.
 *
 * @property null|Plain $maxAvailHeight Maximum available height.
 * @property null|Plain $maxAvailWidth Maximum available width.
 * @property null|Plain $minorModelAgeDisclosure Minor Model Age Disclosure.
 * @property null|Multiple|Plain[] $modelAges Model age.
 * @property null|Multiple|Plain[] $modelReleaseDocuments Model Release Id.
 * @property null|Plain $modelReleaseStatus Model Release Status.
 *
 * @property null|Multiple|Plain[] $organisationInImageCodes Code of featured Organisation.
 * @property null|Multiple|Plain[] $organisationInImageNames Name of featured Organisation.
 * @property null|AltLang $otherConstraints Constraint.
 *
 * @property null|Multiple|Plain[] $personInImageNames Person shown.
 * @property null|Multiple|PersonWDetails[] $personsShown Person Shown (Details).
 * @property null|Multiple|ProductWGtin[] $productsShown Product Shown.
 * @property null|Multiple|Plain[] $propertyReleaseDocuments Property Release Id.
 * @property null|Plain $propertyReleaseStatus Property Release Status.
 * @property null|Plain $provinceState Province/State.
 *
 * @property null|Multiple|RegistryEntry[] $registryEntries Registry Entry.
 * @property null|Multiple|Plain[] $sceneCodes IPTC Scene Code.
 * @property null|Plain $source Source (Supply Chain).
 * @property null|Multiple|Plain[] $subjectCodes IPTC Subject Code.
 * @property null|Plain $sublocationName Sublocation.
 * @property null|Multiple|ImageSupplier[] $suppliers Image Supplier.
 *
 * @property null|AltLang $title Title.
 *
 * @property null|AltLang $usageTerms Rights Usage Terms.
 *
 * @property null|Plain $webstatementRights Copyright Info URL.
 */
interface TopLevel
{
    //
}
