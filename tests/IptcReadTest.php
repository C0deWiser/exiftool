<?php

namespace Tests;

class IptcReadTest extends TestCase
{
    protected string $filename = __DIR__.'/IPTC-PhotometadataRef-Std2023.2.jpg';

    public function test()
    {
        $iptc = $this->exiftool->read($this->filename);

        $this->assertEquals(
            "The description aka caption (ref2023.2)",
            $iptc->description
        );

        $this->assertEquals(
            [
                0 => "Creator1 (ref2023.2)"
            ],
            $iptc->creatorNames->jsonSerialize()
        );

        $this->assertEquals(
            "Copyright (Notice) 2023.2 IPTC - www.iptc.org  (ref2023.2)",
            $iptc->copyrightNotice
        );

        $this->assertEquals(
            '000:Actuality',
            $iptc->intellectualGenre
        );

        $this->assertEquals(
            "The Title (ref2023.2)",
            $iptc->title
        );

        $this->assertEquals(
            [
                0 => "IPTC:10020231",
                1 => "IPTC:20020231",
                2 => "IPTC:30020231",
                3 => "10020231",
                4 => "20020231",
                5 => "30020231",
            ],
            $iptc->subjectCodes->jsonSerialize()
        );

        $this->assertEquals(
            [
                0 => "Keyword1ref2023.2",
                1 => "Keyword2ref2023.2",
                2 => "Keyword3ref2023.2",
            ],
            $iptc->keywords->jsonSerialize()
        );

        $this->assertEquals(
            "An Instruction (ref2023.2)",
            $iptc->instructions
        );

        $this->assertEquals(
            '2024-03-22T00:23:02+00:00',
            $iptc->dateCreated
        );

        $this->assertEquals(
            "Creator's Job Title  (ref2023.2)",
            $iptc->jobtitle
        );

        $this->assertEquals(
            "Sublocation (Core) (ref2023.2)",
            $iptc->sublocationName
        );

        $this->assertEquals(
            "Province/State(Core)(ref2023.2)",
            $iptc->provinceState
        );

        $this->assertEquals(
            "R23",
            $iptc->countryCode
        );

        $this->assertEquals(
            "Country (Core) (ref2023.2)",
            $iptc->countryName
        );

        $this->assertEquals(
            "Job Id (ref2023.2)",
            $iptc->jobid
        );

        $this->assertEquals(
            "Description Writer (ref2023.2)",
            $iptc->captionWriter
        );

        $this->assertEquals(
            "This is the Alt Text description to support accessibility in 2023.2",
            $iptc->altTextAccessibility
        );

        $this->assertEquals(
            [
                "city"       => "Creator's CI: City (ref2023.2)",
                "country"    => "Creator's CI: Country (ref2023.2)",
                "address"    => "Creator's CI: Address, line 1 (ref2023.2)",
                "postalCode" => "Creator's CI: Postcode (ref2023.2)",
                "region"     => "Creator's CI: State/Province (ref2023.2)",
                "emailwork"  => "Creator's CI: Email@1, Email@2 (ref2023.2)",
                "phonework"  => "Creator's CI: Phone # 1, Phone # 2 (ref2023.2)",
                "weburlwork" => "http://www.Creators.CI/WebAddress/ref2023.2",
            ],
            $iptc->creatorContactInfo->jsonSerialize()
        );

        $this->assertEquals(
            "This is the Extended Description to support accessibility in 2023.2",
            $iptc->extDescrAccessibility
        );

        $this->assertEquals(
            [
                0 => "011232",
                1 => "012232",
            ],
            $iptc->sceneCodes->jsonSerialize()
        );

        $this->assertEquals(
            [
                "cvId"               => "http://example.com/cv/about/ref2023.2",
                "cvTermId"           => "http://example.com/cv/about/ref2023.2/code987",
                "cvTermName"         => [
                    "en" => "CV-Term Name 1 (ref2023.2)"
                ],
                "cvTermRefinedAbout" => "http://example.com/cv/refinements2/ref2023.2/codeX145"
            ],
            $iptc->aboutCvTerms[0]->jsonSerialize()
        );

        $this->assertEquals(
            "Additional Model Info (ref2023.2)",
            $iptc->additionalModelInfo
        );

        $this->assertEquals(
            [
                "circaDateCreated"                => "AO Circa Date: between 1550 and 1600 (ref2023.2)",
                "contentDescription"              => [
                    "en" => "AO Content Description 1 (ref2023.2)"
                ],
                "contributionDescription"         => [
                    "en" => "AO Contribution Description 1 (ref2023.2)"
                ],
                "copyrightNotice"                 => "AO Copyright Notice 1 (ref2023.2)",
                "creatorNames"                    => [
                    0 => "AO Creator Name 1a (ref2023.2)",
                    1 => "AO Creator Name 1b (ref2023.2)"
                ],
                "creatorIdentifiers"              => [
                    0 => "AO Creator Id 1a (ref2023.2)",
                    1 => "AO Creator Id 1b (ref2023.2)"
                ],
                "currentCopyrightOwnerIdentifier" => "AO Current Copyright Owner ID 1 (ref2023.2)",
                "currentCopyrightOwnerName"       => "AO Current Copyright Owner Name 1 (ref2023.2)",
                "currentLicensorIdentifier"       => "AO Current Licensor ID 1 (ref2023.2)",
                "currentLicensorName"             => "AO Current Licensor Name 1 (ref2023.2)",
                "dateCreated"                     => "1924-03-22T00:23:02+00:00",
                "physicalDescription"             => [
                    "en" => "AO Physical Description 1 (ref2023.2)"
                ],
                "source"                          => "AO Source 1 (ref2023.2)",
                "sourceInventoryNr"               => "AO Source Inventory No 1 (ref2023.2)",
                "sourceInventoryUrl"              => "AO Source Inventory URL (ref2023.2)",
                "stylePeriod"                     => [
                    0 => "AO Style Baroque (ref2023.2)",
                    1 => "AO Style Italian Baroque (ref2023.2)"
                ],
                "title"                           => [
                    "en" => "AO Title 1 (ref2023.2)"
                ]
            ],
            $iptc->artworkOrObjects[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "identifiers" => [
                    0 => "Contributor Id 1 (ref2023.2)"
                ],
                "name"        => [
                    "en" => "Contributor Name 1 (ref2023.2)"
                ],
                "role"        => [
                    0 => "https://example.com/contributor-role-cv/ref2023.2-1)"
                ]
            ],
            $iptc->contributors[0]->jsonSerialize()
        );

        $this->assertEquals(
            "http://example.com/imageGUIDs/TestGUID12345/ref2023.2",
            $iptc->digitalImageGuid
        );

        $this->assertEquals(
            "http://cv.iptc.org/newscodes/digitalsourcetype/softwareImage",
            $iptc->digitalSourceType
        );

        $this->assertEquals(
            [
                "encRightsExpr"     => "The Encoded Rights Expression (ERE) (ref2023.2)",
                "rightsExprEncType" => "IANA Media Type of ERE (ref2023.2)",
                "rightsExprLangId"  => "http://example.org/RELids/id4711/ref2023.2"
            ],
            $iptc->embdEncRightsExprs[0]->jsonSerialize()
        );

        $this->assertEquals(
            "Event Name (ref2023.2)",
            $iptc->eventName
        );

        $this->assertEquals(
            [
                0 => "https://example.com/events/ref2023.2-a",
                1 => "https://example.com/events/ref2023.2-b"
            ],
            $iptc->eventId->jsonSerialize()
        );

        $this->assertEquals(
            [
                "cvId"               => "http://example.com/cv/genre/ref2023.2",
                "cvTermId"           => "http://example.com/cv/genre/ref2023.2/code1369",
                "cvTermName"         => [
                    "en" => "Genre CV-Term Name 1 (ref2023.2)"
                ],
                "cvTermRefinedAbout" => "http://example.com/cv/genrerefinements2/ref2023.2/codeY864"
            ],
            $iptc->genres[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "name"           => [
                    "en" => "Listener 1"
                ],
                "rCtype"         => [
                    0 => [
                        "identifiers" => [
                            0 => "https://example.org/rctype/type_ref2023.2-a",
                            1 => "https://example.org/rctype/type_ref2023.2-b"
                        ],
                        "name"        => [
                            "en" => "Region Boundary Content Type Name (ref2023.2)"
                        ]
                    ]
                ],
                "rId"            => "persltr1",
                "rRole"          => [
                    0 => [
                        "identifiers" => [
                            0 => "https://example.org/rrole/role_ref2023.2-a",
                            1 => "https://example.org/rrole/role_ref2023.2-b"
                        ],
                        "name"        => [
                            "en" => "Region Boundary Content Role Name (ref2023.2)"
                        ]
                    ]
                ],
                "regionBoundary" => [
                    "rbH"     => "0.385",
                    "rbShape" => "Rectangle",
                    "rbUnit"  => "Relative",
                    "rbW"     => "0.127",
                    "rbX"     => "0.31",
                    "rbY"     => "0.18"
                ]
            ],
            $iptc->imageRegion[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "linkedRightsExpr"  => "http://example.org/linkedrightsexpression/id986/ref2023.2",
                "rightsExprEncType" => "IANA Media Type of ERE (ref2023.2)",
                "rightsExprLangId"  => "http://example.org/RELids/id4712/ref2023.2"
            ],
            $iptc->linkedEncRightsExprs[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "city"           => "City (Location shown1) (ref2023.2)",
                "countryCode"    => "R23",
                "countryName"    => "CountryName (Location shown1) (ref2023.2)",
                "gpsAltitude"    => "140 m",
                "gpsAltitudeRef" => "0",
                "gpsLatitude"    => "48 deg 8' 49.20\" N",
                "gpsLongitude"   => "17 deg 5' 52.80\" E",
                "identifiers"    => [
                    0 => "Location Id 1a(Location shown1) (ref2023.2)",
                    1 => "Location Id 1b(Location shown1) (ref2023.2)"
                ],
                "name"           => [
                    "en" => "Location Name (Location shown1) (ref2023.2)"
                ],
                "provinceState"  => "Province/State (Location shown1) (ref2023.2)",
                "sublocation"    => "Sublocation (Location shown1) (ref2023.2)",
                "worldRegion"    => "Worldregion (Location shown1) (ref2023.2)"
            ],
            $iptc->locationsShown[0]->jsonSerialize()
        );

        $this->assertEquals(
            "20",
            $iptc->maxAvailHeight
        );

        $this->assertEquals(
            "23",
            $iptc->maxAvailWidth
        );

        $this->assertEquals(
            [
                0 => "25",
                1 => "27",
                2 => "30"
            ],
            $iptc->modelAges->jsonSerialize()
        );

        $this->assertEquals(
            [
                0 => "Organisation Code 1 (ref2023.2)",
                1 => "Organisation Code 2 (ref2023.2)",
                2 => "Organisation Code 3 (ref2023.2)"
            ],
            $iptc->organisationInImageCodes->jsonSerialize()
        );

        $this->assertEquals(
            [
                0 => "Organisation Name 1 (ref2023.2)",
                1 => "Organisation Name 2 (ref2023.2)",
                2 => "Organisation Name 3 (ref2023.2)"
            ],
            $iptc->organisationInImageNames->jsonSerialize()
        );

        $this->assertEquals(
            [
                0 => "Person Shown 1 (ref2023.2)",
                1 => "Person Shown 2 (ref2023.2)"
            ],
            $iptc->personInImageNames->jsonSerialize()
        );

        $this->assertEquals(
            [
                "characteristics" => [
                    0 => [
                        "cvId"               => "http://example.com/cv/test99/ref2023.2",
                        "cvTermId"           => "http://example.com/cv/test99/code987/ref2023.2",
                        "cvTermName"         => [
                            "en" => "Person Characteristic Name 1 (ref2023.2)"
                        ],
                        "cvTermRefinedAbout" => "http://example.com/cv/refinements987/codeY765/ref2023.2"
                    ]
                ],
                "description"     => [
                    "en" => "Person Description 1 (ref2023.2)"
                ],
                "identifiers"     => [
                    0 => "http://wikidata.org/item/Q123456789/ref2023.2",
                    1 => "http://freebase.com/m/987654321/ref2023.2"
                ],
                "name"            => [
                    "en" => "Person Name 1 (ref2023.2)"
                ]
            ],
            $iptc->personsShown[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "description" => [
                    "en" => "Product Description 1 (ref2023.2)"
                ],
                "gtin"        => "123456782023.2",
                "identifiers" => "Product ID 1 (ref2023.2)",
                "name"        => [
                    "en" => "Product Name 1 (ref2023.2)"
                ]
            ],
            $iptc->productsShown[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "role"               => "Registry Entry Role ID 1 (ref2023.2)",
                "assetIdentifier"    => "Registry Image ID 1 (ref2023.2)",
                "registryIdentifier" => "Registry Organisation ID 1 (ref2023.2)"
            ],
            $iptc->registryEntries[0]->jsonSerialize()
        );

        $this->assertEquals(
            "City (Core) (ref2023.2)",
            $iptc->cityName
        );

        $this->assertEquals(
            "Credit Line (ref2023.2)",
            $iptc->creditLine
        );

        $this->assertEquals(
            "The Headline (ref2023.2)",
            $iptc->headline
        );

        $this->assertEquals(
            "Source (ref2023.2)",
            $iptc->source
        );

        $this->assertEquals(
            [
                "copyrightOwnerId"   => "Copyright Owner Id 1 (ref2023.2)",
                "copyrightOwnerName" => "Copyright Owner Name 1 (ref2023.2)"
            ],
            $iptc->copyrightOwners[0]->jsonSerialize()
        );

        $this->assertEquals(
            "Prohibited for Generative AI/ML training",
            $iptc->dataMining
        );

        $this->assertEquals(
            [
                "imageCreatorId"   => "Image Creator Id 1 (ref2023.2)",
                "imageCreatorName" => "Image Creator Name 1 (ref2023.2)"
            ],
            $iptc->imageCreators[0]->jsonSerialize()
        );

        $this->assertEquals(
            [
                "imageSupplierId"   => "Image Supplier Id (ref2023.2)",
                "imageSupplierName" => "Image Supplier Name (ref2023.2)"
            ],
            $iptc->suppliers[0]->jsonSerialize()
        );

        $this->assertEquals(
            "Image Supplier Image ID (ref2023.2)",
            $iptc->imageSupplierImageId
        );

        $this->assertEquals(
            [
                "licensorCity"           => "Licensor City 1 (ref2023.2)",
                "licensorCountryName"    => "Licensor Country 1 (ref2023.2)",
                "licensorEmail"          => "Licensor Email 1 (ref2023.2)",
                "licensorAddressDetail"  => "Licensor Ext Addr 1 (ref2023.2)",
                "licensorId"             => "Licensor ID 1 (ref2023.2)",
                "licensorName"           => "Licensor Name 1 (ref2023.2)",
                "licensorPostalCode"     => "Licensor Postcode 1 (ref2023.2)",
                "licensorStateProvince"  => "Licensor Region 1 (ref2023.2)",
                "licensorAddress"        => "Licensor Street Addr 1 (ref2023.2)",
                "licensorTelephone1"     => "Licensor Phone1 1 (ref2023.2)",
                "licensorTelephone2"     => "Licensor Phone2 1 (ref2023.2)",
                "licensorTelephoneType1" => "Work",
                "licensorTelephoneType2" => "Cell",
                "licensorUrl"            => "https://example.com/LicensorURL_1_ref2023.2"
            ],
            $iptc->licensors[0]->jsonSerialize()
        );

        $this->assertEquals(
            "Age 25 or Over",
            $iptc->minorModelAgeDisclosure
        );

        $this->assertEquals(
            [
                0 => "Model Release ID 1 (ref2023.2)",
                1 => "Model Release ID 2 (ref2023.2)"
            ],
            $iptc->modelReleaseDocuments->jsonSerialize()
        );

        $this->assertEquals(
            "Not Applicable",
            $iptc->modelReleaseStatus
        );

        $this->assertEquals(
            "Data Mining for Generative AI/ML training is definitely prohibited",
            $iptc->otherConstraints
        );

        $this->assertEquals(
            [
                0 => "Property Release ID 1 (ref2023.2)",
                1 => "Property Release ID 2 (ref2023.2)"
            ],
            $iptc->propertyReleaseDocuments->jsonSerialize()
        );

        $this->assertEquals(
            "Not Applicable",
            $iptc->propertyReleaseStatus
        );

        $this->assertEquals(
            "1",
            $iptc->imageRating
        );

        $this->assertEquals(
            "Rights Usage Terms (ref2023.2)",
            $iptc->usageTerms
        );

        $this->assertEquals(
            "https://example.com/WebStatementOfRights/ref2023.2",
            $iptc->webstatementRights
        );
    }
}
