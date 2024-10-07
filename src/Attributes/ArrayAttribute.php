<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Exceptions\MistypeException;
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Closure;

class ArrayAttribute implements Contracts\Multiple
{
    protected array $et = [];
    /**
     * @var array<Contracts\Attribute>
     */
    protected array $rows = [];

    protected int $iterator = 0;

    public function __construct(protected Closure $factory, protected bool $merge = false)
    {
        //
    }

    protected function factory(): Contracts\Attribute
    {
        return call_user_func($this->factory);
    }

    public function fake(?AttributeSpec $spec = null): static
    {
        $this->rows = $this->compact([
            $this->factory()->fake($spec),
            $this->factory()->fake($spec),
        ]);

        return $this;
    }

    public function fromExiftool(array $values, ?AttributeSpec $spec = null): static
    {
        // Some arrays come from exiftool as strings
        $values = array_map(fn($v) => is_string($v)
            ? explode(Exiftool::$separator, $v)
            : $v, $values);

        $this->et = array_merge($this->et, $values);

        $processed = [];

        foreach ($this->et as $etName => $value) {
            // Factory every value
            $produced = array_map(
                fn($item) => $this
                    ->factory()
                    ->fromExiftool([$etName => $item], $spec),
                // Expecting $value as array?
                is_array($value) ? $value : [$value]
            );

            if ($this->merge) {
                $processed = array_merge($processed, $produced);
            } else {
                $processed = $produced;
                break;
            }
        }

        $this->rows = $this->compact($processed);

        return $this;
    }

    public function toExiftool(AttributeSpec $spec): array
    {
        $data = [];

        foreach ($this->rows as $attribute) {
            foreach ($attribute->toExiftool($spec) as $etName => $value) {
                if (!isset($data[$etName])) {
                    $data[$etName] = [];
                }
                $data[$etName][] = $value;
            }
        }

        return $data;
    }

    /**
     * If merging is allowed, we could have duplicates. Remove it preserving keys.
     */
    protected function compact(array $rows): array
    {
        if ($this->merge) {

            $serialized = array_map(
                fn($item) => $item instanceof Contracts\Attribute
                    ? $item->jsonSerialize()
                    : $item,
                $rows
            );


            // We can not inspect arrays for duplicates. Actually, we dont expect it...
            $hasArrays = array_filter($serialized, fn($value) => !is_scalar($value));

            if (!$hasArrays) {
                $serialized = array_unique($serialized);
                // remove missing keys as duplicates

                foreach (array_keys($rows) as $key) {
                    if (!in_array($key, array_keys($serialized))) {
                        unset($rows[$key]);
                    }
                }

                $rows = array_values($rows);
            }
        }

        return $rows;
    }

    /**
     * Expecting [name => [value, value]]
     */
    public function fromJson(array $values): static
    {
        $attr = current(array_keys($values));
        // Scalars allowed too
        $values = (array) current($values);

        foreach ($values as $key => $value) {
            try {
                if ($value instanceof Contracts\Attribute) {
                    $values[$key] = $value;
                } else {
                    $values[$key] = $this->factory()->fromJson((array)$value);
                }
            } catch (MistypeException) {
                unset($values[$key]);
            }
        }

        $this->rows = $this->compact($values);

        return $this;
    }

    public function jsonSerialize(): array
    {
        $serialized = [];

        foreach ($this->rows as $key => $row) {
            $value = $row->jsonSerialize();
            if (is_array($value)) {
                // May return empty array
                if ($value) {
                    $serialized[$key] = $value;
                }
            } else {
                $serialized[$key] = $value;
            }
        }

        return $serialized;
    }

    public function toArray(): array
    {
        return $this->rows;
    }

    public function current(): Contracts\Attribute
    {
        return $this->rows[$this->iterator];
    }

    public function next(): void
    {
        ++$this->iterator;
    }

    public function key(): int
    {
        return $this->iterator;
    }

    public function valid(): bool
    {
        return isset($this->rows[$this->iterator]);
    }

    public function rewind(): void
    {
        $this->iterator = 0;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->rows[$offset]);
    }

    public function offsetGet(mixed $offset): Contracts\Attribute
    {
        return $this->rows[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        try {
            if (!($value instanceof Contracts\Attribute)) {

                $attr = $this->factory();

                if ($attr instanceof Contracts\Structure) {
                    $value = $attr->fromJson($value);
                } else {
                    $value = $attr->fromJson([$offset => $value]);
                }
            }

            if (is_null($offset)) {
                $this->rows[] = $value;
            } else {
                $this->rows[$offset] = $value;
            }
        } catch (MistypeException) {
            unset($this[$offset]);
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->rows[$offset]);
    }

    public function count(): int
    {
        return count($this->rows);
    }
}
