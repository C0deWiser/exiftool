<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Faker\Factory;

class PlainAttribute implements Contracts\Plain
{
    protected ?AttributeSpec $spec = null;
    protected array $et = [];
    protected string $value = '';

    public function __construct(?AttributeSpec $spec = null)
    {
        $this->spec = $spec;
    }

    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static
    {
        $this->et = array_merge($this->et, $values);

        $this->value = current($this->et);

        return $this;
    }

    public function toExiftool(AttributeSpec $spec): array
    {
        $data = [];

        foreach ($spec->etNamesWithPrefix() as $etName) {
            $data[$etName] = $this->value;
        }

        return $data;
    }

    public function fromJson(array $values): static
    {
        $value = current($values);

        $isEmpty = match ($this->spec?->dataType()) {
            'any',
            'number' => $value === null || $value === '' || $value === false,
            default  => ! $value,
        };

        if ($isEmpty) {
            throw new MistypeException();
        }

        if ($enum = $this->spec?->enum()) {
            if (! in_array($value, $enum)) {
                throw new MistypeException();
            }
        }

        $this->value = $value;

        return $this;
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return trim($this->value);
    }

    public function fake(?AttributeSpec $spec = null): static
    {
        $faker = Factory::create('en_US');

        $id = fn(string $name) => match ($name) {
                'jobid'                           => 'job',
                'digitalImageGuid'                => 'guid',
                'modelReleaseDocuments',
                'propertyReleaseDocuments'        => 'document',
                'organisationInImageCodes'        => 'organisation',
                'imageCreatorId',
                'creatorIdentifiers'              => 'creator',
                'copyrightOwnerId',
                'currentCopyrightOwnerIdentifier' => 'owner',
                'licensorId',
                'currentLicensorIdentifier'       => 'licensor',
                'cvId'                            => 'cv',
                'cvTermId'                        => 'term',
                'cvTermRefinedAbout'              => 'about',
                'identifiers'                     => 'id',
                'rId'                             => 'region',
                'imageSupplierId',
                'imageSupplierImageId'            => 'supplier',
                'registryIdentifier'              => 'registry',
                'assetIdentifier'                 => 'asset',
                'digitalSourceType'               => 'type',
                'eventId'                         => 'event',
                default                           => $name
            }.':'.$faker->slug(1);

        $this->value = match ($spec->dataType()) {
            'string' => match ($spec->dataFormat()) {
                'url'   => $faker->url(),
                'uri'   => $id($spec->jsonName()),
                default => $faker->word()
            },
            'number' => match ($spec->dataFormat()) {
                'integer' => $faker->randomNumber(),
                default   => $faker->randomFloat()
            },
            default  => $faker->word()
        };

        if (
            str_ends_with($spec->jsonName(), 'Id') ||
            str_ends_with($spec->jsonName(), 'Identifier')
        ) {
            $this->value = $id($spec->jsonName());
        }

        if ($enum = $spec->enum()) {
            $this->value = $faker->randomElement($enum);
        }

        $this->value = match ($spec->jsonName()) {
            'role',
            'jobid',
            'digitalImageGuid',
            'modelReleaseDocuments',
            'propertyReleaseDocuments',
            'organisationInImageCodes' => $id($spec->jsonName()),
            'countryCode'              => $faker->countryISOAlpha3(),
            'country',
            'licensorCountryName',
            'countryName'              => $faker->country(),
            'city',
            'licensorCity',
            'cityName'                 => $faker->city(),
            'licensorAddress',
            'address'                  => $faker->address(),
            'licensorEmail',
            'emailwork'                => $faker->email(),
            'licensorPostalCode',
            'postalCode'               => $faker->postcode(),
            'licensorStateProvince',
            'provinceState',
            'region'                   => $faker->randomElement([
                'California', 'Texas', 'New York', 'Florida',
                'New South Wales', 'Queensland', 'Victoria',
                'Bavaria', 'Saxony', 'Hesse',
                'Maharashtra', 'Kerala', 'Punjab',
                'Ontario', 'Quebec', 'British Columbia', 'Alberta',
                'Gauteng', 'Western Cape', 'KwaZulu-Natal',
                'Málaga', 'Seville', 'Zaragoza'
            ]),
            'worldRegion'              => $faker->randomElement([
                'North America', 'Latin America and the Caribbean',
                'Europe', 'Sub-Saharan Africa',
                'Middle East and North Africa', 'Asia', 'Oceania'
            ]),
            'sublocationName',
            'sublocation'              => $faker->randomElement([
                'Manhattan', 'Brooklyn', 'Westminster', 'Camden',
                'Mitte', 'SoHo', 'Beverly Hills', 'Notting Hill',
                'Montmartre', 'Shibuya', 'City Centre Ward'
            ]),
            'rightsExprLangId'         => 'lang:'.$faker->languageCode(),
            'licensorTelephone1',
            'licensorTelephone2',
            'phonework'                => $faker->phoneNumber(),
            'keywords',
            'jobtitle'                 => $faker->jobTitle(),
            'headline',
            'instructions',
            'additionalModelInfo',
            'aIPromptInformation',
            'licensorAddressDetail'    => $faker->realText(80),
            'creditLine',
            'encRightsExpr',
            'copyrightNotice'          => 'All rights reserved. '.$faker->realText(80),
            'webstatementRights'       => $faker->url(),
            'circaDateCreated'         => 'between '.
                $faker->dateTimeBetween('-1000 years', '-500 years')->format('Y').
                ' and '.
                $faker->dateTimeBetween('-499 years', '-100 years')->format('Y'),
            'sceneCodes'               => 'scn:'.$faker->numerify('######'),
            'subjectCodes'             => 'medtop:'.$faker->numerify('########'),
            'rightsExprEncType'        => 'text/html',
            'captionWriter',
            'creatorNames',
            'currentCopyrightOwnerName',
            'licensorName',
            'copyrightOwnerName',
            'aIPromptWriterNam',
            'personInImageNames',
            'imageCreatorName'         => $faker->name(),
            'stylePeriod'              => $faker->randomElement([
                'Baroque', 'Ancient Egyptian', 'Classical Greek', 'Gothic',
                'Renaissance', 'Impressionism', 'Cubism', 'Surrealism'
            ]),
            'intellectualGenre'        => $faker->randomElement([
                'Feature', 'Reportage', 'Conceptual', 'Interview', 'Press release'
            ]),
            'aISystemUsed'             => $faker->linuxPlatformToken(),
            'aISystemVersionUsed'      => $faker->semver(),
            'imageSupplierName',
            'organisationInImageNames',
            'source',
            'currentLicensorName'      => $faker->company(),
            'imageRating'              => rand(-1, 5),
            'modelAges'                => rand(10, 80),
            'gpsLatitude'              => round($faker->latitude(), 6),
            'gpsLongitude'             => round($faker->longitude(), 6),
            'gpsAltitude'              => $faker->randomFloat(2, -250, 2000),
            'sourceInventoryNr',
            'gtin'                     => $faker->numerify('##############'),
            default                    => $this->value,
        };

        if (! Exiftool::$printConv) {
            if ($spec->jsonName() == 'gpsAltitude') {
                $this->value .= ' m';
            }
            if ($spec->jsonName() == 'gpsLatitude') {
                //42 deg 5' 10.68" N
                //42 deg 5' 10.68" S
                $value = round($faker->latitude());
                $this->value = abs($value).' deg '.
                    round(1, 59)."' ".
                    round(1, 59).'.'.round(10, 100).'" '.
                    ($value >= 0 ? 'N' : 'S');;
            }
            if ($spec->jsonName() == 'gpsLongitude') {
                //34 deg 0' 0.00" E
                //34 deg 0' 0.00" W
                $value = round($faker->longitude());
                $this->value = abs($value).' deg '.
                    round(1, 59)."' ".
                    round(1, 59).'.'.round(10, 100).'" '.
                    ($value >= 0 ? 'E' : 'W');;
            }
        }

        return $this;
    }

}
