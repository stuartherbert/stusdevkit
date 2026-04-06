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

use RuntimeException;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;

/**
 * AccessibleCollection extends CollectionOfAnything with
 * methods for accessing arbitrary elements, merging
 * collections, and copying.
 *
 * This is the base class for Lists, Dictionaries, and
 * Indexes — collection types that allow unrestricted
 * access to their contents.
 *
 * Stacks intentionally do not extend this class because
 * they restrict access to the top element only.
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
    public function mergeSelf(CollectionOfAnything $input): static
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
        throw new RuntimeException(
            $this->getCollectionTypeAsString()
                . " is empty",
        );
    }

    /**
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
     * @return TValue
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

    /**
     * @return static<TKey,TValue>
     */
    public function copy(): static
    {
        return new static($this->data);
    }
}
