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

use InvalidArgumentException;
use RuntimeException;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

/**
 * AccessibleCollection extends CollectionOfAnything with
 * methods for accessing arbitrary elements, merging
 * collections, and copying.
 *
 * This is the base class for Lists, Dictionaries, and
 * Indexes - collection types that allow unrestricted
 * access to their contents.
 *
 * Stacks intentionally do not extend this class because
 * they restrict access to the top element only.
 *
 * Originally added to share the first/last accessors,
 * the merge family, and copy() across every collection
 * type that allows unrestricted element access, while
 * keeping that surface off the stack collection
 * hierarchy.
 *
 * @template TKey of array-key
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends CollectionOfAnything<TKey, TValue>
 * @phpstan-consistent-constructor
 */
class AccessibleCollection extends CollectionOfAnything
{
    // ================================================================
    //
    // Data Management
    //
    // ----------------------------------------------------------------

    /**
     * Add the given `$input` to this collection.
     *
     * Additional details:
     * - Modifies this collection.
     * - Does not return a copy of this collection.
     *
     * @template TIn of TValue
     * @param AccessibleCollection<TKey, TIn>|array<TKey, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException when `$input` is an
     *         array containing a null value (propagated from
     *         {@see ::mergeArray}).
     * @throws InvalidArgumentException when `$input` is an
     *         AccessibleCollection that is not a subtype of
     *         `static` (propagated from {@see ::mergeSelf}).
     */
    public function merge(AccessibleCollection|array $input): static
    {
        // special case
        if (is_array($input)) {
            return $this->mergeArray($input);
        }

        // general case
        return $this->mergeSelf($input);
    }

    /**
     * Add the given `$input` to this collection.
     *
     * Useful if you already know that `$input` is an array.
     * Otherwise, call {@see ::merge} instead.
     *
     * Additional details:
     * - Modifies this collection.
     * - Does not return a copy of this collection.
     *
     * @template TIn of TValue
     * @param array<TKey, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException when any element in
     *         `$input` is null. Enforced by
     *         {@see RejectNullArrayValues} so that the
     *         no-null invariant holds across every merge path.
     */
    public function mergeArray(array $input): static
    {
        RejectNullArrayValues::check(
            data: $input,
            collectionType: $this->getCollectionTypeAsString(),
        );

        // TIn is bounded by `of TValue`, so every value in $input is
        // already a TValue at runtime; PHPStan cannot collapse the
        // spread's inferred TIn|TValue back down to TValue without
        // this hint
        /** @var array<TKey, TValue> $merged */
        $merged = [
            ...$this->data,
            ...$input,
        ];
        $this->data = $merged;

        return $this;
    }

    /**
     * Copies the contents of `$input` into this collection.
     *
     * Additional details:
     * - Does not modify `$input`.
     * - Modifies this collection.
     * - Does not return a copy of this collection.
     *
     * @template TIn of TValue
     * @param AccessibleCollection<TKey, TIn> $input
     * @return $this
     *
     * @throws InvalidArgumentException when `$input` is an
     *         AccessibleCollection that is not a subtype of
     *         `static` (e.g. a sibling or unrelated subclass).
     */
    public function mergeSelf(AccessibleCollection $input): static
    {
        // correctness!
        if (! $this->canMerge($input)) {
            throw new InvalidArgumentException(
                sprintf(
                    "type mismatch: cannot merge %s into %s",
                    $input->getCollectionTypeAsString(),
                    $this->getCollectionTypeAsString()
                )
            );
        }

        // TIn is bounded by `of TValue`, so every value in $input is
        // already a TValue at runtime; PHPStan cannot collapse the
        // spread's inferred TIn|TValue back down to TValue without
        // this hint
        /** @var array<TKey, TValue> $merged */
        $merged = [
            ...$this->data,
            ...$input->toArray(),
        ];
        $this->data = $merged;

        return $this;
    }

    /**
     * Determines if the given `$input` is compatible with this collection.
     *
     * Returns true when `$input` is an instance of the calling class
     * (resolved via late-static binding) or any of its subclasses.
     * For example, `ListOfNumbers::canMerge()` accepts any
     * `ListOfNumbers`, `ListOfIntegers`, or `ListOfFloats`, but
     * rejects `ListOfStrings` and unrelated `AccessibleCollection`
     * subclasses.
     *
     * @template TIn of TValue
     * @param AccessibleCollection<TKey, TIn> $input
     */
    protected function canMerge(AccessibleCollection $input): bool
    {
        // accept any instance of the late-static-bound class or its
        // subclasses; this lets a parent collection absorb data from
        // narrower child types (e.g. ListOfNumbers <- ListOfIntegers)
        // while still rejecting siblings and unrelated collections
        return $input instanceof static;
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    /**
     * Returns the first value stored in this collection.
     *
     * Returns `null` if the collection is empty.
     *
     * This method is defined in a parent class.
     * See your collection class's main docblock for a definition
     * of what "first" means.
     *
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
     * Returns the first value stored in this collection.
     *
     * Throws an exception if this collection is empty.
     *
     * This method is defined in a parent class.
     * See your collection class's main docblock for a definition
     * of what "first" means.
     *
     * @return TValue
     *
     * @throws RuntimeException when this collection is empty.
     *         The message names the offending collection type
     *         via {@see ::getCollectionTypeAsString} so the
     *         failure point is easy to diagnose.
     */
    public function first(): mixed
    {
        $firstValue = $this->maybeFirst();
        if ($firstValue !== null) {
            return $firstValue;
        }

        // uh oh - we're an empty collection
        throw new RuntimeException(
            $this->getCollectionTypeAsString()
                . " is empty",
        );
    }

    /**
     * Returns the last value of this collection.
     *
     * Returns `null` if the collection is empty.
     *
     * This method is defined in a parent class.
     * See your collection class's docblock for a definition
     * of what "last" means.
     *
     * @return TValue|null
     */
    public function maybeLast(): mixed
    {
        $lastKey = array_key_last($this->data);
        if ($lastKey === null) {
            return null;
        }

        return $this->data[$lastKey];
    }

    /**
     * Returns the last value of this collection.
     *
     * Throws an exception if the collection is empty.
     *
     * This method is defined in a parent class.
     * See your collection class's docblock for a definition
     * of what "last" means.
     *
     * @return TValue
     *
     * @throws RuntimeException when the collection is empty.
     *         The message names the offending collection type
     *         via {@see ::getCollectionTypeAsString} so the
     *         failure point is easy to diagnose.
     */
    public function last(): mixed
    {
        $lastValue = $this->maybeLast();
        if ($lastValue !== null) {
            return $lastValue;
        }

        // uh oh - we're an empty collection
        throw new RuntimeException(
            $this->getCollectionTypeAsString()
                . " is empty",
        );
    }
}
