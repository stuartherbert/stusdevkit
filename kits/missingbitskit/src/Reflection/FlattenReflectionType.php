<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
// Copyright (c) 2015-2026 Ganbaro Digital Ltd
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
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionNamedType;
use ReflectionType;

class FlattenReflectionType
{
    /**
     * flatten a ReflectionType into the unique set of PHP type-name
     * strings it can satisfy
     *
     * Intended for callers that want to ask *"which named types does
     * this parameter / return position accept?"* without caring
     * about the ReflectionType hierarchy - a reflection-based DI
     * container is the motivating example.
     *
     * Three pieces of processing happen together:
     *
     *  - **recursion** - union and intersection members are walked
     *    to the leaf level, including DNF types like `(A&B)|C`
     *  - **nullable split** - a `?Foo` named type is emitted as two
     *    leaves, `'Foo'` and `'null'`, matching what you get from an
     *    explicit `Foo|null` union so both spellings look the same
     *    to the caller
     *  - **deduplication** - each distinct leaf appears once, even
     *    when PHP's type system would otherwise allow repeats (the
     *    reachable case is a DNF like `(A&B)|(A&C)`)
     *
     * The returned list is unordered. PHP normalises union and
     * intersection members at parse time, so source/declaration
     * order is not preserved - reflection never sees it. Callers
     * that need a predictable order should sort the result
     * themselves.
     *
     * See {@see GetReflectionTypes} if you need a one-level unwrap
     * that keeps compound members intact.
     *
     * @throws UnsupportedReflectionTypeException
     *   if $refType is a ReflectionType subclass we do not
     *   recognise (surfaced from the GetReflectionTypes delegation)
     * @return list<string>
     */
    public static function from(ReflectionType $refType): array
    {
        // deduplicate once, at the top level - walk() produces a
        // flat list of leaves that may contain duplicates, which we
        // collapse here before handing the result to the caller
        return array_values(
            array_unique(
                self::walk($refType)
            )
        );
    }

    /**
     * recursive helper for from() - produces the flat leaf list
     * without the final deduplication
     *
     * @throws UnsupportedReflectionTypeException
     * @return list<string>
     */
    private static function walk(ReflectionType $refType): array
    {
        // our return type
        $retval = [];

        // unwrap the top-level ReflectionType into its direct members
        $types = GetReflectionTypes::from($refType);

        foreach ($types as $type) {
            // general case - we have a concrete type
            if ($type instanceof ReflectionNamedType) {
                $name = $type->getName();
                $retval[] = $name;

                // split `?Foo` into [Foo, null]. `mixed` and `null`
                // both also report allowsNull() = true, but are
                // already whole leaves in their own right - they
                // must not sprout an extra `null` entry
                if (
                    $type->allowsNull()
                    && $name !== 'mixed'
                    && $name !== 'null'
                ) {
                    $retval[] = 'null';
                }
                continue;
            }

            // special case - we have a compound type that needs
            // further expansion
            foreach (self::walk($type) as $leaf) {
                $retval[] = $leaf;
            }
        }

        return $retval;
    }
}
