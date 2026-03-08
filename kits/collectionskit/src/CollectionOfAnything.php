<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
// Copyright (c) 2026-present Stuart Herbert
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
//
//   * Re-distributions of source code must retain the above copyright
//     notice, this list of conditions and the following disclaimer.
//
//   * Redistributions in binary form must reproduce the above copyright
//     notice, this list of conditions and the following disclaimer in
//     the documentation and/or other materials provided with the
//     distribution.
//
//   * Neither the names of the copyright holders nor the names of his
//     contributors may be used to endorse or promote products derived
//     from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
// BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
// LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
// CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
// ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\CollectionsKit;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use RuntimeException;
use StusDevKit\CollectionsKit\Contracts\Arrayable;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;

/**
 * CollectionOfAnything is the base class for all CollectionsKit collections.
 *
 * Most of the time, you should not extend this class directly. Extend
 * CollectionAsList, CollectionAsDict, or one of their child classes.
 *
 * NOTES:
 *
 * - you cannot store NULL values in any collection
 * - if you add methods to this class, make sure you write unit tests
 *   in `CollectionOfAnythingTest`
 * - if you add methods to this class, make sure you write new unit tests
 *   for all the child classes too
 *
 * PHPSTAN NOTE:
 *
 * This class has template parameters (TKey, TValue). When you
 * create an empty instance (e.g. `new CollectionOfAnything()`),
 * PHPStan resolves these templates as `*NEVER*` because the
 * empty array `[]` has no elements to infer types from. This
 * causes false errors on subsequent method calls like
 * `mergeArray()` or `get()`.
 *
 * To work around this, add a `@var` annotation when creating
 * empty instances:
 *
 *     // @var CollectionOfAnything<int, string> $unit
 *     $unit = new CollectionOfAnything();
 *
 * This is a known PHPStan limitation. There is no support for
 * template default types yet.
 *
 * @see https://github.com/phpstan/phpstan/issues/5065
 * @see https://github.com/phpstan/phpstan/issues/4801
 * @see https://github.com/phpstan/phpstan/discussions/6731
 *
 * @template TKey of array-key
 * @template TValue of array|bool|callable|float|int|object|string
 * @template-implements IteratorAggregate<TKey, TValue>
 * @template-implements Arrayable<TKey, TValue>
 * @phpstan-consistent-constructor
 */
class CollectionOfAnything implements Arrayable, Countable, IteratorAggregate
{
    /**
     * @param array<TKey, TValue> $data
     */
    public function __construct(
        protected array $data = [],
    ) {
        RejectNullArrayValues::check(
            data: $this->data,
            collectionType: $this->getCollectionTypeAsString(),
        );
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    /**
     * @return array<TKey,TValue>
     */
    public function toArray(): array
    {
        return $this->data;
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    public function count(): int
    {
        return count($this->data);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    /**
     * @return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    // ================================================================
    //
    // Data Management
    //
    // ----------------------------------------------------------------

    /**
     * @param static|array<TKey, TValue> $input
     */
    public function merge(self|array $input): static
    {
        // special case
        if (is_array($input)) {
            return $this->mergeArray($input);
        }

        // general case
        return $this->mergeSelf($input);
    }

    /**
     * @param array<TKey, TValue> $input
     */
    public function mergeArray(array $input): static
    {
        RejectNullArrayValues::check(
            data: $input,
            collectionType: $this->getCollectionTypeAsString(),
        );

        $this->data = [
            ...$this->data,
            ...$input,
        ];

        return $this;
    }

    /**
     * @param CollectionOfAnything<TKey,TValue> $input
     */
    public function mergeSelf(self $input): static
    {
        $this->data = [
            ...$this->data,
            ...$input->toArray(),
        ];

        return $this;
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    /**
     * @return TValue|null
     */
    public function maybeFirst(): mixed
    {
        $firstKey = array_key_first($this->data);
        if ($firstKey === null) {
            return null;
        }

        return $this->data[$firstKey];
    }

    /**
     * @return TValue
     */
    public function first(): mixed
    {
        $firstValue = $this->maybeFirst();
        if ($firstValue !== null) {
            return $firstValue;
        }

        // uh oh - we're an empty collection
        throw new RuntimeException($this->getCollectionTypeAsString() . " is empty");
    }

    /**
     * @return TValue|null
     */
    public function maybeLast(): mixed
    {
        $firstKey = array_key_last($this->data);
        if ($firstKey === null) {
            return null;
        }

        return $this->data[$firstKey];
    }

    /**
     * @return TValue
     */
    public function last(): mixed
    {
        $firstValue = $this->maybeLast();
        if ($firstValue !== null) {
            return $firstValue;
        }

        // uh oh - we're an empty collection
        throw new RuntimeException($this->getCollectionTypeAsString() . " is empty");
    }

    /**
     * @return static<TKey,TValue>
     */
    public function copy(): static
    {
        return new static($this->data);
    }

    // ================================================================
    //
    // Logic helpers
    //
    // ----------------------------------------------------------------

    public function empty(): bool
    {
        return count($this->data) === 0;
    }

    // ================================================================
    //
    // Internal helpers
    //
    // ----------------------------------------------------------------

    public function getCollectionTypeAsString(): string
    {
        return basename(str_replace("\\", "/", static::class));
    }
}
