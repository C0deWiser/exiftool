<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Faker\Factory;

class DateTimeAttribute implements Contracts\DateTime
{
    protected array $et = [];
    protected DateTimeInterface $datetime;

    public function fake(?AttributeSpec $spec = null): static
    {
        $this->datetime = Factory::create()->dateTime();

        return $this;
    }

    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static
    {
        $this->et = array_merge($this->et, $values);

        // Date may be missing. It is possible, that first we get only `TimeCreated`.

        // Prefer `DateCreated`
        $value = $this->et['DateCreated'] ?? current($this->et);

        $datetime = null;

        if ($value) {
            $datetime = DateTime::createFromFormat('Y:m:d H:i:s.v', $value, new DateTimeZone('UTC'));
            try {
                $datetime = $datetime ?: new DateTime($value);
            } catch (Exception) {
                //
            }

            if ($datetime === false) {
                $datetime = null;
            }
        }

        $this->datetime = $datetime ?? new DateTime();

        return $this;
    }

    public function toExiftool(AttributeSpec $spec): array
    {
        $data = [];

        $datetime = $this->datetime;

        foreach ($spec->etNamesWithPrefix() as $etName) {

            if (str_starts_with($etName, 'ExifIFD')) {
                // skip
                continue;
            }

            $data[$etName] = match ($etName) {
                // Hardcode
                'IPTC:DateCreated' => $datetime->format('Y-m-d'),
                'IPTC:TimeCreated' => $datetime->format('H:i:sT'),
                default            => $datetime->format('c')
            };
        }

        return $data;
    }

    public function fromJson(array $values): static
    {
        $datetime = current($values);

        if (is_numeric($datetime)) {
            $datetime = new DateTime("@$datetime");
        }

        if (is_string($datetime)) {
            $datetime = new DateTime($datetime);
        }

        if (!($datetime instanceof DateTimeInterface)) {
            throw new MistypeException();
        }

        $this->datetime = $datetime;

        return $this;
    }

    public function jsonSerialize(): string
    {
        return $this->datetime->format('c');
    }

    public function __toString(): string
    {
        return $this->datetime->format('c');
    }

    public function toDateTime(): DateTimeInterface
    {
        return $this->datetime;
    }
}
