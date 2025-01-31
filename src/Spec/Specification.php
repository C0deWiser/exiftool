<?php

namespace Codewiser\Exiftool\Spec;

/**
 * IPTC Specification.
 */
class Specification
{
    # Keep it static to share across resources
    protected static array $specification = [];

    /**
     * Create specification from URL.
     */
    public static function fetch(string $url): static
    {
        return self::set(json_decode(file_get_contents($url), true));
    }

    /**
     * Create specification from array.
     */
    public static function set(array $specification): static
    {
        self::$specification = $specification;

        return new static();
    }

    /**
     * Get raw specification.
     */
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
    }

    /**
     * Get specification release comment.
     */
    public function releaseComment(): string
    {
        return self::$specification['release_comment'];
    }

    /**
     * Get specification external reference.
     */
    public function externalDocumentation(): string
    {
        return self::$specification['documentation_available_at'];
    }

    /**
     * Get specification release date.
     */
    public function releaseDate(): \DateTimeInterface
    {
        return new \DateTime(self::$specification['release_timestamp']);
    }

    /**
     * Get top-level attributes specification.
     */
    public function topLevel(): TopLevel
    {
        return new TopLevel();
    }

    /**
     * Get structure specification by its exiftool-name.
     */
    public function struct(string $structName): StructureSpec
    {
        return new StructureSpec($structName);
    }

    /**
     * Get factory to make new structure.
     */
    public function factory(): StructureFactory
    {
        return new StructureFactory();
    }

    /**
     * Get raw specification.
     */
    public function toArray(): array
    {
        return self::$specification;
    }
}
