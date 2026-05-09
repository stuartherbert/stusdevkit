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
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Arrays\Arrayable;

use function StusDevKit\MissingBitsKit\get_class_basename;

/**
 * CollectionOfAnything is the root base class for every
 * collection type in CollectionsKit.
 *
 * It provides three foundational capabilities:
 *
 * - **storage** — a protected `$data` array that subclasses
 *   read and mutate directly;
 * - **counting** — via the `Countable` interface so callers
 *   can use PHP's built-in `count()`;
 * - **iteration** — via the `IteratorAggregate` interface so
 *   callers can use `foreach`.
 *
 * It also provides `toArray()` (via the `Arrayable` interface)
 * and a lightweight `empty()` predicate.
 *
 * ## Hierarchy
 *
 * All concrete collection types extend this class through one
 * of two intermediate bases:
 *
 * - `AccessibleCollection` — for collections that allow
 *   unrestricted element access (lists, dictionaries,
 *   indexes). This is the path for most collection types.
 * - `CollectionAsStack` — for LIFO collections that only
 *   expose the top element.
 *
 * Do not extend `CollectionOfAnything` directly. Always
 * extend the appropriate intermediate base class so that
 * your collection inherits the accessors and mutators
 * that match its access pattern.
 *
 * ## Null prohibition
 *
 * No collection may store `null` values. The constructor
 * delegates to `RejectNullArrayValues`, which throws a
 * `NullValueNotAllowedException` if any element in the
 * seed array is null. This invariant lets subclasses use
 * `null` as a sentinel in maybe-* accessors (e.g.
 * `maybeFirst()` returns null to mean "collection is
 * empty") without ambiguity.
 *
 * ## Type constraints
 *
 * The `TValue` template is constrained to `array|bool|callable|
 * float|int|object|string`. This deliberately excludes `null`
 * (see above) and `resource` (resources cannot be safely
 * serialized or cloned across collection boundaries).
 *
 * ## Here Be Dragons
 *
 * **PHPStan template inference on empty instances.** When you
 * instantiate `CollectionOfAnything` (or any subclass) with no
 * seed data, PHPStan resolves the template parameters as `*NEVER*
 * ` because an empty array carries no type information. This causes
 * false-positive errors on subsequent method calls.
 *
 * Workaround: annotate the variable with `@var` at the point of
 * creation:
 *
 *     // @var CollectionOfAnything<int, string> $unit
 *     $unit = new CollectionOfAnything();
 *
 * See the linked PHPStan issues for background.
 *
 * @see https://github.com/phpstan/phpstan/issues/5065
 * @see https://github.com/phpstan/phpstan/issues/4801
 * @see https://github.com/phpstan/phpstan/discussions/6731
 *
 * @template TKey of array-key
 * @template TValue of array|bool|callable|float|int|object|string
 * @implements Arrayable<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 */
class CollectionOfAnything implements Arrayable, Countable, IteratorAggregate
{
    /**
     * Create a new collection, optionally seeded with data.
     *
     * The constructor accepts an array of key-value pairs and
     * stores them as the collection's initial contents. The
     * array may be indexed (integer keys) or associative
     * (string keys); both are preserved as-is.
     *
     * The constructor rejects any array containing a `null`
     * value. This is enforced by the `RejectNullArrayValues`
     * validator, which throws a `NullValueNotAllowedException`
     * on violation. The null prohibition is a hard invariant
     * across all CollectionsKit collection types.
     *
     * @param array<TKey, TValue> $data
     *
     * @throws NullValueNotAllowedException if any element in
     *         `$data` is null.
     */
    public function __construct(
        protected array $data = [],
    ) {
        // reject null values — this is a hard invariant for
        // all collection types; it lets subclasses use null
        // as a sentinel in maybe-* accessors without ambiguity
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
     * Return the collection's stored data as a plain PHP array.
     *
     * The returned array preserves all keys (integer or string)
     * and all values exactly as stored. No transformation, copy,
     * or filtering is applied — the array is returned by value
     * (i.e., a copy), so mutating the return value does not
     * affect the collection.
     *
     * @return array<TKey,TValue>
     */
    public function toArray(): array
    {
        // our return value — the stored data, returned as a copy
        // so callers cannot mutate the collection from outside
        return $this->data;
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    /**
     * Return the number of elements stored in the collection.
     *
     * This method satisfies the `Countable` interface contract
     * and is also callable directly as `$collection->count()`.
     *
     * @return int the number of elements; zero for an empty
     *         collection.
     */
    public function count(): int
    {
        // delegate to PHP's built-in count — it is O(1) on
        // arrays in modern PHP, so there is no performance
        // concern with repeated calls
        return count($this->data);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    /**
     * Return an iterator over the collection's stored data.
     *
     * The iterator yields elements in insertion order — the
     * same order the caller supplied to the constructor or
     * that a subclass appended via `add()`. Keys are yielded
     * alongside values when the caller uses `foreach ($col as
     * $key => $value)`.
     *
     * @return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        // wrap the stored data in an ArrayIterator — this is
        // the standard PHP iterator for array-backed collections;
        // it preserves insertion order and yields both keys
        // and values
        return new ArrayIterator($this->data);
    }

    // ================================================================
    //
    // Logic helpers
    //
    // ----------------------------------------------------------------

    /**
     * Return true if the collection contains no elements.
     *
     * This is a convenience predicate. It is equivalent to
     * `$collection->count() === 0` but reads more naturally
     * in conditional expressions.
     *
     * @return bool true when the collection has zero elements;
     *         false otherwise.
     */
    public function empty(): bool
    {
        // our return value — a boolean predicate on the stored
        // data size
        return count($this->data) === 0;
    }

    // ================================================================
    //
    // Internal helpers
    //
    // ----------------------------------------------------------------

    /**
     * Return the unqualified class name of this collection type.
     *
     * Uses late-static binding (`static::class`) so that on a
     * subclass the returned name is the subclass's basename,
     * not `CollectionOfAnything`. This is primarily used by
     * the null-value validator to build human-readable error
     * messages that name the offending collection type.
     *
     * @return non-empty-string the class basename, e.g.
     *         `ListOfStrings` or `CollectionOfAnything`.
     */
    public function getCollectionTypeAsString(): string
    {
        // resolve the runtime class name via late-static binding
        // so subclasses get their own basename, not this parent's;
        // then strip the namespace to produce a clean label
        return get_class_basename(static::class);
    }
}
