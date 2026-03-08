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

namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\CollectionsKit\Validators\RejectNullValue;

/**
 * CollectionAsList holds a collection of data as an array with sequential
 * integer keys.
 *
 * Use this (or one of its child classes) to hold data that has no
 * identity (ie, no primary key).
 *
 * Use CollectionAsDict (or one of its child classes) if your data has
 * an identity (ie, it has a primary key).
 *
 * PHPSTAN NOTE:
 *
 * This class has a template parameter (TValue). When you
 * create an empty instance (e.g. `new CollectionAsList()`),
 * PHPStan resolves this template as `*NEVER*` because the
 * empty array `[]` has no elements to infer types from. This
 * causes false errors on subsequent method calls like
 * `mergeArray()` or `mergeSelf()`.
 *
 * To work around this, add a `@var` annotation when creating
 * empty instances:
 *
 *     // @var CollectionAsList<string> $unit
 *     $unit = new CollectionAsList();
 *
 * This is a known PHPStan limitation. There is no support for
 * template default types yet.
 *
 * @see https://github.com/phpstan/phpstan/issues/5065
 * @see https://github.com/phpstan/phpstan/issues/4801
 * @see https://github.com/phpstan/phpstan/discussions/6731
 *
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends CollectionOfAnything<int, TValue>
 */
class CollectionAsList extends CollectionOfAnything
{
    // ================================================================
    //
    // Data Management
    //
    // ----------------------------------------------------------------

    /**
     * @param TValue $value
     */
    public function add(mixed $value): static
    {
        RejectNullValue::check(
            value: $value,
            collectionType: $this->getCollectionTypeAsString(),
        );

        $this->data[] = $value;

        return $this;
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------
}
