<?php

namespace Tests\Spec2025;

class IptcReadPrintConvTest extends \Tests\Spec2025\IptcReadTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->exiftool->useMachineValues();
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
                        "rbShape" => "rectangle",
                        "rbUnit"  => "relative",
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
                        "rbShape" => "circle",
                        "rbUnit"  => "relative",
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
                        "rbShape"    => "polygon",
                        "rbUnit"     => "relative",
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

    public function testIptcExtLocationsShown()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            [
                [
                    "city"           => "City (Location shown1) (ref2025.1)",
                    "countryCode"    => "R25",
                    "countryName"    => "CountryName (Location shown1) (ref2025.1)",
                    "gpsAltitude"    => "140",
                    "gpsAltitudeRef" => "0",
                    "gpsLatitude"    => "48.147",
                    "gpsLongitude"   => "17.098",
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
                    "gpsAltitude"    => "120",
                    "gpsAltitudeRef" => "0",
                    "gpsLatitude"    => "47.952",
                    "gpsLongitude"   => "16.83",
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

    public function testIptcExtDataMining()
    {
        $iptc = $this->readIptc();

        $this->assertEquals(
            "DMI-PROHIBITED-GENAIMLTRAINING",
            $iptc->dataMining
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
                    "licensorTelephoneType1" => "work",
                    "licensorTelephone1"     => "Licensor Phone1 1 (ref2025.1)",
                    "licensorTelephoneType2" => "cell",
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
                    "licensorTelephoneType1" => "work",
                    "licensorTelephone1"     => "Licensor Phone1 2 (ref2025.1)",
                    "licensorTelephoneType2" => "cell",
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
            "AG-A25",
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
            "MR-NAP",
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
            "PR-NAP",
            $iptc->propertyReleaseStatus
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
                    "gpsAltitude"    => "480",
                    "gpsAltitudeRef" => "0",
                    "gpsLatitude"    => "48.275",
                    "gpsLongitude"   => "16.338",
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