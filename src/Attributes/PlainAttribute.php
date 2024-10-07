<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Faker\Factory;

class PlainAttribute implements Contracts\Plain
{
    protected array $et = [];
    protected string $value = '';

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

        if (!$value) {
            throw new MistypeException();
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
        $faker = Factory::create('en_GB');

        $this->value = match ($spec->dataType()) {
            'string' => match ($spec->dataFormat()) {
                'url'   => $faker->url(),
                'uri'   => $faker->slug(1),
                default => $faker->word()
            },
            'number' => match ($spec->dataFormat()) {
                'integer' => $faker->randomNumber(),
                default   => $faker->randomFloat()
            },
            default  => $faker->word()
        };

        if ($enum = $spec->enum()) {
            $this->value = $faker->randomElement(Exiftool::$printConv
                ? array_keys($enum)
                : array_values($enum));
        }

        $this->value = match ($spec->jsonName()) {
            'countryCode'         => $faker->countryISOAlpha3(),
            'country',
            'licensorCountryName',
            'countryName'         => $faker->country(),
            'city',
            'licensorCity',
            'cityName'            => $faker->city(),
            'licensorAddress',
            'address'             => $faker->address(),
            'licensorEmail',
            'emailwork'           => $faker->email(),
            'licensorPostalCode',
            'postalCode'          => $faker->postcode(),
            'licensorTelephone1',
            'licensorTelephone2',
            'phonework'           => $faker->phoneNumber(),
            'jobtitle'            => $faker->jobTitle(),
            'headline',
            'instructions',
            'additionalModelInfo',
            'copyrightNotice'     => $faker->sentence(),
            'creditLine'          => $faker->realText(32),
            'jobid'               => $faker->slug(2),
            'sceneCodes'          => $faker->numerify('######'),
            'subjectCodes'        => 'medtop:'.$faker->numerify('########'),
            'webstatementRights',
            'cvId',
            'cvTermId',
            'imageCreatorId'      => $faker->url(),
            'captionWriter',
            'creatorNames',
            'currentCopyrightOwnerName',
            'licensorName',
            'copyrightOwnerName',
            'personInImageNames',
            'imageCreatorName'    => $faker->name(),
            'imageSupplierName',
            'organisationInImageNames',
            'currentLicensorName' => $faker->company(),
            'imageRating'         => rand(-1, 5),
            'modelAges'           => rand(10, 80),
            'gpsLatitude'         => round($faker->latitude(), 6),
            'gpsLongitude'        => round($faker->longitude(), 6),
            'gpsAltitude'         => $faker->randomFloat(2, -250, 2000),
            'gtin'                => $faker->numerify('##############'),
            default               => $this->value,
        };

        if (!Exiftool::$printConv) {
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
