<?php

namespace Clinic\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use ArrayIterator;

class Collect implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array<int|string,mixed>
     */
    protected array $items = [];

    /**
     * @param iterable<mixed> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->items = is_array($items) ? $items : iterator_to_array($items, true);
    }

    public static function wrap(iterable $items = []): self
    {
        return new self($items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function get($key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    public function set($key, $value): self
    {
        $this->items[$key] = $value;
        return $this;
    }

    public function map(callable $callback): self
    {
        $result = [];
        foreach ($this->items as $key => $value) {
            $result[$key] = $callback($value, $key);
        }
        return new self($result);
    }

    public function filter(callable $callback = null): self
    {
        if ($callback === null) {
            return new self(array_filter($this->items));
        }

        $result = [];
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                $result[$key] = $value;
            }
        }
        return new self($result);
    }

    public function reduce(callable $callback, $carry = null)
    {
        foreach ($this->items as $key => $value) {
            $carry = $callback($carry, $value, $key);
        }
        return $carry;
    }

    public function pluck(string $key): self
    {
        $result = [];
        foreach ($this->items as $item) {
            if (is_array($item) && array_key_exists($key, $item)) {
                $result[] = $item[$key];
            } elseif (is_object($item) && isset($item->{$key})) {
                $result[] = $item->{$key};
            }
        }
        return new self($result);
    }

    public function first(callable $callback = null, $default = null)
    {
        if ($callback === null) {
            foreach ($this->items as $item) {
                return $item;
            }
            return $default;
        }

        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        return $default;
    }

    public function last($default = null)
    {
        if (empty($this->items)) {
            return $default;
        }
        return end($this->items) ?: $default;
    }

    public function sum($key = null)
    {
        if ($key === null) {
            return array_sum($this->items);
        }

        return $this->pluck($key)->sum();
    }

    public function avg($key = null)
    {
        $items = $key === null ? $this->items : $this->pluck($key)->all();
        $count = count($items);
        return $count ? array_sum($items) / $count : 0;
    }

    public function groupBy($key): self
    {
        $result = [];
        foreach ($this->items as $item) {
            $groupKey = null;
            if (is_callable($key)) {
                $groupKey = $key($item);
            } elseif (is_array($item) && array_key_exists($key, $item)) {
                $groupKey = $item[$key];
            } elseif (is_object($item) && isset($item->{$key})) {
                $groupKey = $item->{$key};
            }

            $result[$groupKey][] = $item;
        }
        return new self($result);
    }

    public function sortBy($key, $descending = false): self
    {
        $items = $this->items;
        usort($items, function ($a, $b) use ($key, $descending) {
            $valA = is_array($a) ? ($a[$key] ?? null) : ($a->{$key} ?? null);
            $valB = is_array($b) ? ($b[$key] ?? null) : ($b->{$key} ?? null);
            if ($valA == $valB) {
                return 0;
            }
            $result = $valA <=> $valB;
            return $descending ? -$result : $result;
        });
        return new self($items);
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->items, $options | JSON_UNESCAPED_UNICODE);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
            return;
        }
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}


