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

namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;
use StusDevKit\CollectionsKit\Validators\RejectNullValue;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison;
use StusDevKit\MissingBitsKit\DataInspectors\GetNormalisedForComparison;
use StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext;

/**
 * CollectionAsDict holds a collection of data that has identity (ie, it has
 * a primary key of some kind).
 *
 * Extend this class to create stronger-typed collections for your specific
 * classes.
 *
 * Use CollectionAsList (or one of its child classes) to hold data that has
 * no identity (ie, no primary key).
 *
 * PHPSTAN NOTE:
 *
 * This class has template parameters (TKey, TValue). When you
 * create an empty instance (e.g. `new CollectionAsDict()`),
 * PHPStan resolves these templates as `*NEVER*` because the
 * empty array `[]` has no elements to infer types from. This
 * causes false errors on subsequent method calls like
 * `mergeArray()` or `get()`.
 *
 * To work around this, add a `@var` annotation when creating
 * empty instances:
 *
 *     // @var CollectionAsDict<string, string> $unit
 *     $unit = new CollectionAsDict();
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
 * @extends AccessibleCollection<TKey,TValue>
 */
class CollectionAsDict extends AccessibleCollection implements NormalisesForComparison
{
    // ================================================================
    //
    // Data Management
    //
    // ----------------------------------------------------------------

    /**
     * Store a value in the collection.
     *
     * If the key already exists in this collection, its value will
     * be replaced by the given `$value`.
     *
     * NOTE: you cannot store `null` values in this collection.
     *
     * @param TKey $key
     * @param TValue $value
     * @throws NullValueNotAllowedException
     */
    public function set(mixed $key, mixed $value): static
    {
        RejectNullValue::check(
            value: $value,
            collectionType: $this->getCollectionTypeAsString(),
        );

        $this->data[$key] = $value;

        return $this;
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    /**
     * Return a value from the collection.
     *
     * If we don't have a value for the given `$key`, we return `null`.
     *
     * @param TKey $key
     * @return TValue|null
     */
    public function maybeGet(mixed $key): mixed
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * Return a value from the collection.
     *
     * If we don't have a value for the given `$key`, we throw a
     * {@see NoValueForKeyInCollectionException}.
     *
     * @param TKey $key
     * @return TValue
     * @throws NoValueForKeyInCollectionException
     */
    public function get(mixed $key): mixed
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        throw new NoValueForKeyInCollectionException(
            collectionType: $this->getCollectionTypeAsString(),
            key: $key,
        );
    }

    // ================================================================
    //
    // Testers
    //
    // ----------------------------------------------------------------

    /**
     * Check to see if we have a value for the given `$key` in this
     * collection.
     *
     * @param TKey $key
     * @return bool
     * - `true` if we have a value for `$key`
     * - `false` otherwise
     */
    public function has(mixed $key): bool
    {
        return isset($this->data[$key]);
    }

    // ================================================================
    //
    // NormalisesForComparison contract
    //
    // ----------------------------------------------------------------

    /**
     * Return the dict's canonical form for structural comparison.
     *
     * Dict semantics: the keys ARE the identity of each entry, so
     * they must survive. The caller takes our return value verbatim
     * (per the `NormalisesForComparison` contract), so the keyed
     * map goes straight in - there is no downstream heuristic to
     * defend against, and no need to split the keys and values into
     * parallel structures.
     *
     * The data is `ksort`-ed (SORT_STRING) before being returned,
     * so the canonical form is order-independent: two dicts built
     * with the same key/value pairs in different insertion orders
     * canonicalise identically. SORT_STRING matches the choice
     * already in `GetNormalisedForComparison::fromArrayMapInternal`.
     *
     * Each value is recursively normalised through
     * {@see GetNormalisedForComparison::fromNested()}, threading the
     * parent walk's `$context`, so nested collections, value
     * objects, enums, and nested arrays all canonicalise correctly
     * AND cycles that loop back through this dict are detected
     * once across the whole walk.
     *
     * @return array<array-key, mixed>
     */
    public function getNormalisedForComparison(
        NormalisationContext $context,
    ): mixed {
        // ksort the data so two dicts with the same key/value
        // pairs in different insertion orders canonicalise the
        // same way. ksort() is in-place on a local copy - the
        // collection's own ordering is untouched.
        $data = $this->data;
        ksort($data, SORT_STRING);

        // array_map preserves array keys when given a single
        // array, so the int/string keys ride through unchanged.
        return array_map(
            static fn(mixed $value): mixed
                => GetNormalisedForComparison::fromNested($value, $context),
            $data,
        );
    }
}
