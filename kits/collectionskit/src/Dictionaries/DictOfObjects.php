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

/**
 * DictOfObjects holds a collection of objects that have identity (ie, they
 * have a primary key or equivalent of some kind).
 *
 * Create your own child classes to create type-safe collections of your
 * app / package's objects.
 *
 * HERE BE DRAGONS:
 *
 * Objects are stored by reference, not by value. Three knock-on
 * consequences bite callers who expect "collection" to mean
 * "self-contained":
 *
 *   1. Mutating a retrieved object mutates the dict's copy too.
 *      `$dict->get('key')->name = 'new'` is not a footgun — it's
 *      working as designed — but it surprises callers who treat
 *      get() as if it returned a value.
 *
 *   2. copy() is a shallow copy. The new dict has its own key set,
 *      but the values are the same object instances. Mutating an
 *      object retrieved from the copy mutates the same object in
 *      the original.
 *
 *   3. mergeSelf() and mergeArray() transfer object references,
 *      not clones. After the merge, both dicts hold pointers to
 *      the same objects.
 *
 * If you need value semantics, clone before storing or before
 * returning.
 *
 * PHPSTAN NOTE:
 *
 * This class has template parameters (TKey, TValue) so that
 * subclasses can narrow the allowed types. When you create
 * an empty instance (e.g. `new DictOfObjects()`), PHPStan
 * resolves these templates as `*NEVER*` because the empty
 * array `[]` has no elements to infer types from. This
 * causes false errors on subsequent method calls like
 * `mergeArray()` or `get()`.
 *
 * To work around this, add a `@var` annotation when creating
 * empty instances:
 *
 *     // @var DictOfObjects<string, stdClass> $unit
 *     $unit = new DictOfObjects();
 *
 * This is a known PHPStan limitation. There is no support
 * for template default types yet.
 *
 * @see https://github.com/phpstan/phpstan/issues/5065
 * @see https://github.com/phpstan/phpstan/issues/4801
 * @see https://github.com/phpstan/phpstan/discussions/6731
 *
 * @template TKey of array-key
 * @template TValue of object = object
 * @extends CollectionAsDict<TKey, TValue>
 * @phpstan-consistent-constructor
 */
class DictOfObjects extends CollectionAsDict
{
}
