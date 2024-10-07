<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Codewiser\Exiftool\Traits\HasAltLang;
use Closure;
use Faker\Factory;

class AltLangAttribute implements Contracts\AltLang
{
    use HasAltLang;

    public static bool $collapsed = false;
    public static string $currentLocale = 'en';
    public static string $fallbackLocale = 'en';

    protected array $et = [];
    protected array $values = [];

    public static function useLocale(string $locale, ?string $fallback_locale = null): void
    {
        self::$currentLocale = $locale;
        if ($fallback_locale) {
            self::$fallbackLocale = $fallback_locale;
        }
    }

    /**
     * When collapsed, AltLang would be jsonSerialized to string, not to array.
     */
    public static function collapse(bool $collapse = true): void
    {
        self::$collapsed = $collapse;
    }

    public function isCollapsed(): bool
    {
        return self::$collapsed;
    }

    public function getLocale(): string
    {
        return self::$currentLocale;
    }

    public function getFallbackLocale(): string
    {
        return self::$fallbackLocale;
    }

    public function fake(?AttributeSpec $spec = null): static
    {
        $this->values = match ($spec->jsonName()) {
            'name' =>[
                'en' => Factory::create('en_GB')->name(),
                'es' => Factory::create('es_ES')->name(),
            ],
            default => [
                'en' => Factory::create('en_GB')->realText(50),
                'es' => Factory::create('es_ES')->realText(50),
            ]
        };

        return $this;
    }

    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static
    {
        $this->et = array_merge($this->et, $values);

        foreach ($this->et as $etName => $value) {
            $lang = locale_canonicalize($this->langFromEtName($etName) ?? $this->getLocale());
            $this->values[$lang] = $value;
        }

        ksort($this->values);

        return $this;
    }

    public function toExiftool(AttributeSpec $spec): array
    {
        $data = [];

        foreach ($spec->etNamesWithPrefix() as $etName) {
            foreach ($this->values as $lang => $value) {
                if ($lang === $this->getLocale()) {
                    $key = $etName;
                } else {
                    $key = $etName.'-'.str_replace('_', '-', $lang);
                }
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function fromJson(array $values): static
    {
        $values = current($values);

        if (!is_array($values)) {
            $values = [$this->getLocale() => $values];
        }

        $values = array_filter($values);

        if (!$values) {
            throw new MistypeException();
        }

        $this->values = $values;

        ksort($this->values);

        return $this;
    }

    public function jsonSerialize(): string|array
    {
        return $this->isCollapsed() ? $this->toString() : array_filter($this->toArray());
    }

    public function offsetExists(mixed $offset): bool
    {
        return (bool) $this->offsetToLang($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->values[$this->offsetToLang($offset)];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->values[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->values[$offset]);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return
            $this[$this->getLocale()] ??
            $this[$this->getFallbackLocale()] ??
            current($this->values) ?? '';
    }

    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * If AltLang has `en_GB`, it will return map [en => en_GB]. It means that `en_GB` also accessible by `en`.
     */
    protected function mapToPrimaryLanguage(): array
    {
        $map = [];

        foreach (array_keys($this->values) as $lang) {
            $primary = locale_get_primary_language($lang);
            $map[$primary] = $map[$primary] ?? $lang;
        }

        return $map;
    }

    /**
     * Whatever lang is requested, if AltLang has some regional variants, it tries to find proper one.
     */
    protected function offsetToLang(mixed $offset): ?string
    {
        if ($lang = locale_canonicalize($offset)) {

            if (isset($this->values[$lang])) {
                return $lang;
            }

            $lang = locale_get_primary_language($lang);
            $primary = $this->mapToPrimaryLanguage();
            if (isset($primary[$lang])) {
                return $primary[$lang];
            }
        }

        return null;
    }
}
