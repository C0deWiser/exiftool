<?php

namespace Codewiser\Exiftool\Spec;

/**
 * IPTC Specification.
 */
class Specification
{
    # Keep it static to share across resources
    protected static array $specification = [];

    public static function fetch(string $url): static
    {
        return self::set(json_decode(file_get_contents($url), true));
    }

    public static function set(array $specification): static
    {
        self::$specification = $specification;

        return new static();
    }

    public static function get(): array
    {
        return self::$specification;
    }

    public static function make(): static
    {
        return new static();
    }

    public function __construct()
    {
        if (!self::$specification) {
            throw new \RuntimeException('Empty specification data');
        }

        // Exiftool holds LocationCreated as a Bag
        self::$specification['ipmd_top']['locationCreated']['propoccurrence'] = 'multi';

        // Exiftool holds ProductWGtin.identifiers as single value
        self::$specification['ipmd_struct']['ProductWGtin']['identifiers']['propoccurrence'] = 'single';
    }

    public function topLevel(): TopLevel
    {
        return new TopLevel();
    }

    public function struct(string $structName): StructureSpec
    {
        return new StructureSpec($structName);
    }

    public function factory(): StructureFactory
    {
        return new StructureFactory();
    }

    public function toArray(): array
    {
        return self::$specification;
    }
}
