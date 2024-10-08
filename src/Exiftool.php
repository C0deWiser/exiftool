<?php

namespace Codewiser\Exiftool;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Spec\Concerns\AttributeBag;
use Codewiser\Exiftool\Spec\Specification;
use Codewiser\Exiftool\Spec\StructureFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class Exiftool
{
    protected string|array|Specification $specification;
    public static bool $printConv = false;
    public static string $separator = ',';

    public function __construct(protected string $binary = 'exiftool', null|string|array $specification = null)
    {
        $this->specification = $specification ?? __DIR__.'/../iptc-pmd-techreference_2023.2.json';
    }

    /**
     * Enable `print conversion` exiftool mode.
     */
    public function printConv(bool $enable = true): static
    {
        self::$printConv = $enable;

        return $this;
    }

    /**
     * Get IPTC Specification.
     */
    public function specification(): Specification
    {
        if (is_string($this->specification)) {
            $this->specification = Specification::fetch($this->specification);
        }
        if (is_array($this->specification)) {
            $this->specification = Specification::set($this->specification);
        }
        return $this->specification;
    }

    /**
     * Get new empty Iptc object.
     */
    public function newMetadata(): Iptc
    {
        return new Iptc($this->specification()->topLevel());
    }

    /**
     * Get factory to make new structure.
     */
    public function newStructure(): StructureFactory
    {
        return $this->specification()->factory();
    }

    /**
     * Get command to read metadata from a file.
     */
    protected function readArguments(): array
    {
        return array_filter([
            '-struct',
            '-json',
            //'-lang '.AltLangAttribute::$currentLocale,
            self::$printConv ? '-n' : null
        ]);
    }

    /**
     * Get command to drop all metadata from a file.
     */
    protected function truncateArguments(): array
    {
        return [
            '-all=',
        ];
    }

    /**
     * Get command to drop some metadata from a file.
     */
    protected function clearArguments(AttributeBag $attributes): array
    {
        $args = [];

        foreach ($attributes->getAttributes() as $attribute) {
            foreach ($attribute->etNamesWithPrefix() as $etName) {
                $args[] = "-$etName=";
            }
        }

        return $args;
    }

    protected function getExecutable(): string
    {
        $executableFinder = new ExecutableFinder();

        return $executableFinder->find('exiftool', $this->binary);
    }

    protected function getProcess(string $filename, array $arguments): Process
    {
        $arguments = array_merge([$this->getExecutable()], $arguments, [$filename]);

        return new Process($arguments);
    }

    protected function runProcess(string $filename, array $arguments): Process
    {
        $process = $this->getProcess($filename, $arguments);

        $process->run();

        $this->removeTemp($filename);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }

    protected function removeTemp(string $filename): void
    {
        $temp = $filename.'_original';

        if (file_exists($temp)) {
            unlink($temp);
        }
    }

    /**
     * Read metadata from a file.
     */
    public function read(string $filename): Iptc
    {
        $process = $this->runProcess($filename, $this->readArguments());

        $unicode = json_decode($process->getOutput(), true);

        return $this->newMetadata()->fromExiftool($unicode[0]);
    }

    /**
     * Write given metadata to a file.
     */
    public function write(string $filename, Iptc $data, string $characterSet = 'UTF8'): Process
    {
        $tags = $data->toExiftool();
        $tags[] = "-IPTC:CodedCharacterSet=$characterSet";

        $tags = array_map(fn($tag) => escapeshellarg($tag), $tags);

        // Symphony process escapes arguments so exiftool confused to interprets it
        $args = array_filter([
            '-separator "'.self::$separator.'"',
            '-preserve',
            '-lang '.AltLangAttribute::$currentLocale,
            self::$printConv ? '-n' : null
        ]);

        $cmd =
            $this->getExecutable() . ' ' .
            implode(' ', $tags) . ' '.
            implode(' ', $args) . ' '.
            $filename;

        $process = Process::fromShellCommandline($cmd);

        $process->run();

        $this->removeTemp($filename);

        return $process;
    }

    /**
     * Drop all metadata from a file.
     */
    public function clear(string $filename): Process
    {
        return $this->runProcess($filename, $this->truncateArguments());
    }

    /**
     * Drop some metadata from a file.
     * @deprecated
     */
    protected function blank(string $filename, AttributeBag $attributes): Process
    {
        return $this->runProcess($filename, $this->clearArguments($attributes));
    }
}
