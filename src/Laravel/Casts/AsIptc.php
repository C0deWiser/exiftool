<?php

namespace Codewiser\Exiftool\Laravel\Casts;

use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\Iptc;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class AsIptc implements CastsAttributes
{
    protected Exiftool $exiftool;

    public function __construct()
    {
        $this->exiftool = app(Exiftool::class);
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): ?Iptc
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (is_array($value)) {
            return $this->exiftool->newMetadata()->fromJson($value);
        }

        return null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if ($value instanceof Iptc) {
            $value = $value->jsonSerialize();
        }

        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}
