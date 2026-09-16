<?php

namespace Tests\Spec2025;

class IptcReadTest extends \Tests\Spec2024\IptcReadTest
{
    protected string $spec = __DIR__.'/../../iptc-pmd-techreference_2025.1.json';
    protected string $filename = __DIR__.'/IPTC-PhotometadataRef-Std2025.1.jpg';

    public function testIptcCoreDescription()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "The description aka caption (ref2025.1)",
            $iptc->description
        );
    }

    public function testIptcCoreCreator()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "Creator1 (ref2025.1)"
            ],
            $iptc->creatorNames->jsonSerialize()
        );
    }

    public function testIptcCoreCopyrightNotice()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Copyright (Notice) 2025.1 IPTC - www.iptc.org  (ref2025.1)",
            $iptc->copyrightNotice
        );
    }

    public function testIptcCoreTitle()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "The Title (ref2025.1)",
            $iptc->title
        );
    }

    public function testIptcCoreSubjectCodes()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "IPTC:10020251",
                1 => "IPTC:20020251",
                2 => "IPTC:30020251",
                3 => "10020251",
                4 => "20020251",
                5 => "30020251",
            ],
            $iptc->subjectCodes->jsonSerialize()
        );
    }

    public function testIptcCoreKeywords()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "Keyword1ref2025.1",
                1 => "Keyword2ref2025.1",
                2 => "Keyword3ref2025.1",
            ],
            $iptc->keywords->jsonSerialize()
        );
    }

    public function testIptcCoreInstructions()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "An Instruction (ref2025.1)",
            $iptc->instructions
        );
    }

    public function testIptcCoreDateCreated()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            '2025-11-03T00:25:01+00:00',
            $iptc->dateCreated
        );
    }

    public function testIptcCoreJobtitle()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Creator's Job Title  (ref2025.1)",
            $iptc->jobtitle
        );
    }

    public function testIptcCoreLocation()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Sublocation (Core) (ref2025.1)",
            $iptc->sublocationName
        );

        $this->assertEquals(
            "Province/State(Core)(ref2025.1)",
            $iptc->provinceState
        );

        $this->assertEquals(
            "R25",
            $iptc->countryCode
        );

        $this->assertEquals(
            "Country (Core) (ref2025.1)",
            $iptc->countryName
        );

        $this->assertEquals(
            "City (Core) (ref2025.1)",
            $iptc->cityName
        );
    }

    public function testIptcCoreJobid()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Job Id (ref2025.1)",
            $iptc->jobid
        );
    }

    public function testIptcCoreCaptionWriter()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Description Writer (ref2025.1)",
            $iptc->captionWriter
        );
    }

    public function testIptcCoreAltTextAccessibility()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "This is the Alt Text description to support accessibility in 2025.1",
            $iptc->altTextAccessibility
        );
    }

    public function testIptcCoreCreatorContactInfo()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                "city"       => "Creator's CI: City (ref2025.1)",
                "country"    => "Creator's CI: Country (ref2025.1)",
                "address"    => "Creator's CI: Address, line 1 (ref2025.1)",
                "postalCode" => "Creator's CI: Postcode (ref2025.1)",
                "region"     => "Creator's CI: State/Province (ref2025.1)",
                "emailwork"  => "Creator's CI: Email@1, Email@2 (ref2025.1)",
                "phonework"  => "Creator's CI: Phone # 1, Phone # 2 (ref2025.1)",
                "weburlwork" => "http://www.Creators.CI/WebAddress/ref2025.1",
            ],
            $iptc->creatorContactInfo->jsonSerialize()
        );
    }

    public function testIptcCoreExtDescriptionAccessibility()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "This is the Extended Description to support accessibility in 2025.1",
            $iptc->extDescrAccessibility
        );
    }

    public function testIptcCoreSceneCodes()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "011252",
                1 => "012252",
            ],
            $iptc->sceneCodes->jsonSerialize()
        );
    }

    public function testIptcCoreCreditHeadlineSource()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Credit Line (ref2025.1)",
            $iptc->creditLine
        );

        $this->assertEquals(
            "The Headline (ref2025.1)",
            $iptc->headline
        );

        $this->assertEquals(
            "Source (ref2025.1)",
            $iptc->source
        );
    }

    public function testIptcExtAboutCvTerms()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "cvId"               => "http://example.com/cv/about/ref2025.1",
                    "cvTermId"           => "http://example.com/cv/about/ref2025.1/code987",
                    "cvTermName"         => [
                        "en" => "CV-Term Name 1 (ref2025.1)"
                    ],
                    "cvTermRefinedAbout" => "http://example.com/cv/refinements2/ref2025.1/codeX145"
                ]
            ],
            $iptc->aboutCvTerms->jsonSerialize()
        );
    }

    public function testIptcExtAdditionalModelInfo()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Additional Model Info (ref2025.1)",
            $iptc->additionalModelInfo
        );
    }

    public function testIptcExtAIProperties()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "IPTC Reference image for version 2025.1 of the standard",
            $iptc->aIPromptInformation
        );

        $this->assertEquals(
            "IPTC PMD Working Group (ref 2025.1)",
            $iptc->aIPromptWriterNam
        );

        $this->assertEquals(
            "IPTC AI Generator251",
            $iptc->aISystemUsed
        );

        $this->assertEquals(
            "2025.1",
            $iptc->aISystemVersionUsed
        );
    }

    public function testIptcExtArtworkOrObjects()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "circaDateCreated"                => "AO Circa Date: between 1550 and 1600 (ref2025.1)",
                    "contentDescription"              => [
                        "en" => "AO Content Description 1 (ref2025.1)"
                    ],
                    "contributionDescription"         => [
                        "en" => "AO Contribution Description 1 (ref2025.1)"
                    ],
                    "copyrightNotice"                 => "AO Copyright Notice 1 (ref2025.1)",
                    "creatorNames"                    => [
                        0 => "AO Creator Name 1a (ref2025.1)",
                        1 => "AO Creator Name 1b (ref2025.1)"
                    ],
                    "creatorIdentifiers"              => [
                        0 => "AO Creator Id 1a (ref2025.1)",
                        1 => "AO Creator Id 1b (ref2025.1)"
                    ],
                    "currentCopyrightOwnerIdentifier" => "AO Current Copyright Owner ID 1 (ref2025.1)",
                    "currentCopyrightOwnerName"       => "AO Current Copyright Owner Name 1 (ref2025.1)",
                    "currentLicensorIdentifier"       => "AO Current Licensor ID 1 (ref2025.1)",
                    "currentLicensorName"             => "AO Current Licensor Name 1 (ref2025.1)",
                    "dateCreated"                     => "1925-10-29T00:25:01+00:00",
                    "physicalDescription"             => [
                        "en" => "AO Physical Description 1 (ref2025.1)"
                    ],
                    "source"                          => "AO Source 1 (ref2025.1)",
                    "sourceInventoryNr"               => "AO Source Inventory No 1 (ref2025.1)",
                    "sourceInventoryUrl"              => "AO Source Inventory URL (ref2025.1)",
                    "stylePeriod"                     => [
                        0 => "AO Style Baroque (ref2025.1)",
                        1 => "AO Style Italian Baroque (ref2025.1)"
                    ],
                    "title"                           => [
                        "en" => "AO Title 1 (ref2025.1)"
                    ]
                ]
            ],
            $iptc->artworkOrObjects->jsonSerialize()
        );
    }

    public function testIptcExtContributors()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "identifiers" => [
                        0 => "Contributor Id 1 (ref2025.1)"
                    ],
                    "name"        => [
                        "en" => "Contributor Name 1 (ref2025.1)"
                    ],
                    "role"        => [
                        0 => "https://example.com/contributor-role-cv/ref2025.1-1)"
                    ]
                ],
                [
                    "identifiers" => [
                        0 => "Contributor Id 2 (ref2025.1)"
                    ],
                    "name"        => [
                        "en" => "Contributor Name 2 (ref2025.1)"
                    ],
                    "role"        => [
                        0 => "https://example.com/contributor-role-cv/ref2025.1-2)"
                    ]
                ]
            ],
            $iptc->contributors->jsonSerialize()
        );
    }

    public function testIptcExtDigitalImageGuid()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "http://example.com/imageGUIDs/TestGUID12345/ref2025.1",
            $iptc->digitalImageGuid
        );
    }

    public function testIptcExtEmbdEncRightsExprs()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "encRightsExpr"     => "The Encoded Rights Expression (ERE) (ref2025.1)",
                    "rightsExprEncType" => "IANA Media Type of ERE (ref2025.1)",
                    "rightsExprLangId"  => "http://example.org/RELids/id4711/ref2025.1"
                ]
            ],
            $iptc->embdEncRightsExprs->jsonSerialize()
        );
    }

    public function testIptcExtEvent()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Event Name (ref2025.1)",
            $iptc->eventName
        );

        $this->assertEquals(
            [
                0 => "https://example.com/events/ref2025.1-a",
                1 => "https://example.com/events/ref2025.1-b"
            ],
            $iptc->eventId->jsonSerialize()
        );
    }

    public function testIptcExtGenres()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "cvId"               => "http://example.com/cv/genre/ref2025.1",
                    "cvTermId"           => "http://example.com/cv/genre/ref2025.1/code1369",
                    "cvTermName"         => [
                        "en" => "Genre CV-Term Name 1 (ref2025.1)"
                    ],
                    "cvTermRefinedAbout" => "http://example.com/cv/genrerefinements2/ref2025.1/codeY864"
                ]
            ],
            $iptc->genres->jsonSerialize()
        );
    }

    public function testIptcExtImageRegion()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "name"           => [
                        "en" => "Listener 1"
                    ],
                    "rCtype"         => [
                        0 => [
                            "identifiers" => [
                                0 => "https://example.org/rctype/type_ref2025.1-a",
                                1 => "https://example.org/rctype/type_ref2025.1-b"
                            ],
                            "name"        => [
                                "en" => "Region Boundary Content Type Name (ref2025.1)"
                            ]
                        ]
                    ],
                    "rId"            => "persltr1",
                    "rRole"          => [
                        0 => [
                            "identifiers" => [
                                0 => "https://example.org/rrole/role_ref2025.1-a",
                                1 => "https://example.org/rrole/role_ref2025.1-b"
                            ],
                            "name"        => [
                                "en" => "Region Boundary Content Role Name (ref2025.1)"
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
                [
                    "name"           => [
                        "en" => "Listener 2"
                    ],
                    "rCtype"         => [
                        0 => [
                            "identifiers" => [
                                0 => "https://example.org/rctype/type_ref2025.1-a",
                                1 => "https://example.org/rctype/type_ref2025.1-b"
                            ],
                            "name"        => [
                                "en" => "Region Boundary Content Type Name (ref2025.1)"
                            ]
                        ]
                    ],
                    "rId"            => "persltr2",
                    "rRole"          => [
                        0 => [
                            "identifiers" => [
                                0 => "https://example.org/rrole/role_ref2025.1-a",
                                1 => "https://example.org/rrole/role_ref2025.1-b"
                            ],
                            "name"        => [
                                "en" => "Region Boundary Content Role Name (ref2025.1)"
                            ]
                        ]
                    ],
                    "regionBoundary" => [
                        "rbRx"    => "0.068",
                        "rbShape" => "Circle",
                        "rbUnit"  => "Relative",
                        "rbX"     => "0.59",
                        "rbY"     => "0.426"
                    ]
                ],
                [
                    "name"           => [
                        "en" => "Speaker 1"
                    ],
                    "rCtype"         => [
                        0 => [
                            "identifiers" => [
                                0 => "https://example.org/rctype/type_ref2025.1-a",
                                1 => "https://example.org/rctype/type_ref2025.1-b"
                            ],
                            "name"        => [
                                "en" => "Region Boundary Content Type Name (ref2025.1)"
                            ]
                        ]
                    ],
                    "rId"            => "persspkr1",
                    "rRole"          => [
                        0 => [
                            "identifiers" => [
                                0 => "https://example.org/rrole/role_ref2025.1-a",
                                1 => "https://example.org/rrole/role_ref2025.1-b"
                            ],
                            "name"        => [
                                "en" => "Region Boundary Content Role Name (ref2025.1)"
                            ]
                        ]
                    ],
                    "regionBoundary" => [
                        "rbShape"    => "Polygon",
                        "rbUnit"     => "Relative",
                        "rbVertices" => [
                            [
                                "rbX" => "0.05",
                                "rbY" => "0.713"
                            ],
                            [
                                "rbX" => "0.148",
                                "rbY" => "0.041"
                            ],
                            [
                                "rbX" => "0.375",
                                "rbY" => "0.863"
                            ]
                        ]
                    ]
                ]
            ],
            $iptc->imageRegion->jsonSerialize()
        );
    }

    public function testIptcExtLinkedEncRightsExprs()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "linkedRightsExpr"  => "http://example.org/linkedrightsexpression/id986/ref2025.1",
                    "rightsExprEncType" => "IANA Media Type of ERE (ref2025.1)",
                    "rightsExprLangId"  => "http://example.org/RELids/id4712/ref2025.1"
                ]
            ],
            $iptc->linkedEncRightsExprs->jsonSerialize()
        );
    }

    public function testIptcExtLocationsShown()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "city"           => "City (Location shown1) (ref2025.1)",
                    "countryCode"    => "R25",
                    "countryName"    => "CountryName (Location shown1) (ref2025.1)",
                    "gpsAltitude"    => "140 m",
                    "gpsAltitudeRef" => "Above Sea Level",
                    "gpsLatitude"    => "48 deg 8' 49.20\" N",
                    "gpsLongitude"   => "17 deg 5' 52.80\" E",
                    "identifiers"    => [
                        0 => "Location Id 1a(Location shown1) (ref2025.1)",
                        1 => "Location Id 1b(Location shown1) (ref2025.1)"
                    ],
                    "name"           => [
                        "en" => "Location Name (Location shown1) (ref2025.1)"
                    ],
                    "provinceState"  => "Province/State (Location shown1) (ref2025.1)",
                    "sublocation"    => "Sublocation (Location shown1) (ref2025.1)",
                    "worldRegion"    => "Worldregion (Location shown1) (ref2025.1)"
                ],
                [
                    "city"           => "City (Location shown2) (ref2025.1)",
                    "countryCode"    => "R25",
                    "countryName"    => "CountryName (Location shown2) (ref2025.1)",
                    "gpsAltitude"    => "120 m",
                    "gpsAltitudeRef" => "Above Sea Level",
                    "gpsLatitude"    => "47 deg 57' 7.20\" N",
                    "gpsLongitude"   => "16 deg 49' 48.00\" E",
                    "identifiers"    => [
                        0 => "Location Id 2a(Location shown2) (ref2025.1)",
                        1 => "Location Id 2b(Location shown2) (ref2025.1)"
                    ],
                    "name"           => [
                        "en" => "Location Name (Location shown2) (ref2025.1)"
                    ],
                    "provinceState"  => "Province/State (Location shown2) (ref2025.1)",
                    "sublocation"    => "Sublocation (Location shown2) (ref2025.1)",
                    "worldRegion"    => "Worldregion (Location shown2) (ref2025.1)"
                ]
            ],
            $iptc->locationsShown->jsonSerialize()
        );
    }

    public function testIptcExtMaxAvailDimensions()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "20",
            $iptc->maxAvailHeight
        );

        $this->assertEquals(
            "25",
            $iptc->maxAvailWidth
        );
    }

    public function testIptcExtOrganisations()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "Organisation Code 1 (ref2025.1)",
                1 => "Organisation Code 2 (ref2025.1)",
                2 => "Organisation Code 3 (ref2025.1)"
            ],
            $iptc->organisationInImageCodes->jsonSerialize()
        );

        $this->assertEquals(
            [
                0 => "Organisation Name 1 (ref2025.1)",
                1 => "Organisation Name 2 (ref2025.1)",
                2 => "Organisation Name 3 (ref2025.1)"
            ],
            $iptc->organisationInImageNames->jsonSerialize()
        );
    }

    public function testIptcExtPersonInImageNames()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "Person Shown 1 (ref2025.1)",
                1 => "Person Shown 2 (ref2025.1)"
            ],
            $iptc->personInImageNames->jsonSerialize()
        );
    }

    public function testIptcExtPersonsShown()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "characteristics" => [
                        0 => [
                            "cvId"               => "http://example.com/cv/test99/ref2025.1",
                            "cvTermId"           => "http://example.com/cv/test99/code987/ref2025.1",
                            "cvTermName"         => [
                                "en" => "Person Characteristic Name 1 (ref2025.1)"
                            ],
                            "cvTermRefinedAbout" => "http://example.com/cv/refinements987/codeY765/ref2025.1"
                        ]
                    ],
                    "description"     => [
                        "en" => "Person Description 1 (ref2025.1)"
                    ],
                    "identifiers"     => [
                        0 => "http://wikidata.org/item/Q123456789/ref2025.1",
                        1 => "http://freebase.com/m/987654321/ref2025.1"
                    ],
                    "name"            => [
                        "en" => "Person Name 1 (ref2025.1)"
                    ]
                ]
            ],
            $iptc->personsShown->jsonSerialize()
        );
    }

    public function testIptcExtProductsShown()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "description" => [
                        "en" => "Product Description 1 (ref2025.1)"
                    ],
                    "gtin"        => "123456782025.1",
                    "identifiers" => [
                        "Product ID 1 (ref2025.1)"
                    ],
                    "name"        => [
                        "en" => "Product Name 1 (ref2025.1)"
                    ]
                ]
            ],
            $iptc->productsShown->jsonSerialize()
        );
    }

    public function testIptcExtRegistryEntries()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "role"               => "Registry Entry Role ID 1 (ref2025.1)",
                    "assetIdentifier"    => "Registry Image ID 1 (ref2025.1)",
                    "registryIdentifier" => "Registry Organisation ID 1 (ref2025.1)"
                ],
                [
                    "role"               => "Registry Entry Role ID 2 (ref2025.1)",
                    "assetIdentifier"    => "Registry Image ID 2 (ref2025.1)",
                    "registryIdentifier" => "Registry Organisation ID 2 (ref2025.1)"
                ]
            ],
            $iptc->registryEntries->jsonSerialize()
        );
    }

    public function testIptcExtCopyrightOwners()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "copyrightOwnerId"   => "Copyright Owner Id 1 (ref2025.1)",
                    "copyrightOwnerName" => "Copyright Owner Name 1 (ref2025.1)"
                ],
                [
                    "copyrightOwnerId"   => "Copyright Owner Id 2 (ref2025.1)",
                    "copyrightOwnerName" => "Copyright Owner Name 2 (ref2025.1)"
                ]
            ],
            $iptc->copyrightOwners->jsonSerialize()
        );
    }

    public function testIptcExtImageCreators()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "imageCreatorId"   => "Image Creator Id 1 (ref2025.1)",
                    "imageCreatorName" => "Image Creator Name 1 (ref2025.1)"
                ]
            ],
            $iptc->imageCreators->jsonSerialize()
        );
    }

    public function testIptcExtSuppliers()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "imageSupplierId"   => "Image Supplier Id (ref2025.1)",
                    "imageSupplierName" => "Image Supplier Name (ref2025.1)"
                ]
            ],
            $iptc->suppliers->jsonSerialize()
        );

        $this->assertEquals(
            "Image Supplier Image ID (ref2025.1)",
            $iptc->imageSupplierImageId
        );
    }

    public function testIptcExtLicensors()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "licensorId"             => "Licensor ID 1 (ref2025.1)",
                    "licensorName"           => "Licensor Name 1 (ref2025.1)",
                    "licensorAddress"        => "Licensor Street Addr 1 (ref2025.1)",
                    "licensorAddressDetail"  => "Licensor Ext Addr 1 (ref2025.1)",
                    "licensorCity"           => "Licensor City 1 (ref2025.1)",
                    "licensorStateProvince"  => "Licensor Region 1 (ref2025.1)",
                    "licensorPostalCode"     => "Licensor Postcode 1 (ref2025.1)",
                    "licensorCountryName"    => "Licensor Country 1 (ref2025.1)",
                    "licensorTelephoneType1" => "Work",
                    "licensorTelephone1"     => "Licensor Phone1 1 (ref2025.1)",
                    "licensorTelephoneType2" => "Cell",
                    "licensorTelephone2"     => "Licensor Phone2 1 (ref2025.1)",
                    "licensorEmail"          => "Licensor Email 1 (ref2025.1)",
                    "licensorUrl"            => "https://example.com/LicensorURL_1_ref2025.1"
                ],
                [
                    "licensorId"             => "Licensor ID 2 (ref2025.1)",
                    "licensorName"           => "Licensor Name 2 (ref2025.1)",
                    "licensorAddress"        => "Licensor Street Addr 2 (ref2025.1)",
                    "licensorAddressDetail"  => "Licensor Ext Addr 2 (ref2025.1)",
                    "licensorCity"           => "Licensor City 2 (ref2025.1)",
                    "licensorStateProvince"  => "Licensor Region 2 (ref2025.1)",
                    "licensorPostalCode"     => "Licensor Postcode 2 (ref2025.1)",
                    "licensorCountryName"    => "Licensor Country 2 (ref2025.1)",
                    "licensorTelephoneType1" => "Work",
                    "licensorTelephone1"     => "Licensor Phone1 2 (ref2025.1)",
                    "licensorTelephoneType2" => "Cell",
                    "licensorTelephone2"     => "Licensor Phone2 2 (ref2025.1)",
                    "licensorEmail"          => "Licensor Email 2 (ref2025.1)",
                    "licensorUrl"            => "https://example.com/LicensorURL_2_ref2025.1"
                ]
            ],
            $iptc->licensors->jsonSerialize()
        );
    }

    public function testIptcExtModelRelease()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Age 25 or Over",
            $iptc->minorModelAgeDisclosure
        );

        $this->assertEquals(
            [
                0 => "Model Release ID 1 (ref2025.1)",
                1 => "Model Release ID 2 (ref2025.1)"
            ],
            $iptc->modelReleaseDocuments->jsonSerialize()
        );

        $this->assertEquals(
            "Not Applicable",
            $iptc->modelReleaseStatus
        );
    }

    public function testIptcExtPropertyRelease()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                0 => "Property Release ID 1 (ref2025.1)",
                1 => "Property Release ID 2 (ref2025.1)"
            ],
            $iptc->propertyReleaseDocuments->jsonSerialize()
        );

        $this->assertEquals(
            "Not Applicable",
            $iptc->propertyReleaseStatus
        );
    }

    public function testIptcExtUsageTerms()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "Rights Usage Terms (ref2025.1)",
            $iptc->usageTerms
        );
    }

    public function testIptcExtWebstatementRights()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "https://example.com/WebStatementOfRights/ref2025.1",
            $iptc->webstatementRights
        );
    }

    public function testIptcExtLocationCreated()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "city"           => "City (Location created1) (ref2025.1)",
                    "countryCode"    => "R25",
                    "countryName"    => "CountryName (Location created1) (ref2025.1)",
                    "gpsAltitude"    => "480 m",
                    "gpsAltitudeRef" => "Above Sea Level",
                    "gpsLatitude"    => "48 deg 16' 30.00\" N",
                    "gpsLongitude"   => "16 deg 20' 16.80\" E",
                    "identifiers"    => [
                        0 => "Location Id (Location created1) (ref2025.1)"
                    ],
                    "name"           => [
                        "en" => "Location Name (Location created1) (ref2025.1)"
                    ],
                    "provinceState"  => "Province/State (Location created1) (ref2025.1)",
                    "sublocation"    => "Sublocation (Location created1) (ref2025.1)",
                    "worldRegion"    => "Worldregion (Location created1) (ref2025.1)"
                ]
            ],
            $iptc->locationCreated->jsonSerialize()
        );
    }
}