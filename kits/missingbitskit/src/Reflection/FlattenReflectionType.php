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

use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

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
     *  - **recursion** - union members are walked to the leaf level
     *  - **nullable split** - a `?Foo` named type is emitted as two
     *    leaves, `'Foo'` and `'null'`, matching what you get from an
     *    explicit `Foo|null` union so both spellings look the same
     *    to the caller
     *  - **deduplication** - each distinct leaf appears once
     *
     * **Ordering.** Union members are returned in the order PHP
     * produces when stringifying the type (via `(string)$refType`).
     * In practice this means:
     *
     *  - **class-only unions** preserve declaration order: for
     *    `A|B` you get `['A', 'B']`, for `B|A` you get `['B', 'A']`.
     *  - **scalar-only unions** are reported in PHP's canonical
     *    order (both `int|string` and `string|int` stringify as
     *    `string|int`), so the output is deterministic but not the
     *    order the developer wrote.
     *  - **mixed unions** put classes first (in declaration order)
     *    and scalars last (in canonical order).
     *
     * Here Be Dragons.
     * ================
     *
     * The ordering guarantee above is derived from PHP's text
     * representation, so it is **only as stable as that
     * representation**. A future PHP release could in principle
     * change the canonical scalar ordering, or alter how it
     * stringifies unions in some other way, and the output of
     * from() would shift with it. Callers that need guaranteed
     * behaviour across PHP versions - or that care about anything
     * stronger than "class-only unions preserve declaration order
     * on the PHP we tested against" - should treat this as
     * best-effort and write their own walker against the
     * ReflectionType tree directly.
     *
     * **Intersection types are refused.** An intersection `A&B`
     * means "a value that satisfies both A and B simultaneously".
     * Collapsing that to `['A', 'B']` discards the "and" semantics:
     * the flat list becomes indistinguishable from the list
     * produced for a union `A|B` (which means "a value that
     * satisfies either A or B"). Callers reasoning from the flat
     * list would draw wrong conclusions. Rather than silently
     * produce misleading output, from() throws
     * IntersectionTypesNotSupportedException for any input
     * containing an intersection anywhere in its tree - including
     * DNF types like `(A&B)|C`. Consumers that need to reason about
     * intersections must walk the ReflectionType structure
     * themselves.
     *
     * See {@see GetReflectionTypes} if you need a one-level unwrap
     * that keeps compound members intact.
     *
     * @throws IntersectionTypesNotSupportedException
     *   if $refType contains a ReflectionIntersectionType anywhere
     *   in its tree (bare intersection or a DNF union member)
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
     * single-pass helper for from() - dispatches on the concrete
     * ReflectionType subclass and produces the leaf list for that
     * subclass (before the final deduplication in from())
     *
     * @throws IntersectionTypesNotSupportedException
     * @throws UnsupportedReflectionTypeException
     * @return list<string>
     */
    private static function walk(ReflectionType $refType): array
    {
        // refuse intersections - see the class-level docblock for
        // the full rationale. In short, a flat list cannot
        // faithfully represent "A and B must both be satisfied".
        if ($refType instanceof ReflectionIntersectionType) {
            throw new IntersectionTypesNotSupportedException($refType);
        }

        // named type - emit its name, plus 'null' for nullable
        // forms (`?Foo`, `Foo|null`). `mixed` and `null` already
        // report allowsNull() = true but are whole leaves in their
        // own right, so they must not sprout an extra `null` entry.
        if ($refType instanceof ReflectionNamedType) {
            $name = $refType->getName();
            $retval = [$name];
            if (
                $refType->allowsNull()
                && $name !== 'mixed'
                && $name !== 'null'
            ) {
                $retval[] = 'null';
            }
            return $retval;
        }

        // union type - take the member order directly from PHP's
        // text representation rather than from getTypes(), whose
        // ordering is undocumented. The string form is what a
        // developer sees when they stringify the type, and it
        // preserves declaration order for class-only unions -
        // exactly the ordering signal a reflection-based DI
        // resolver needs.
        //
        // First, scan the members: a DNF union like `(A&B)|C` has
        // a ReflectionIntersectionType as one of its members. The
        // top-level intersection check above can't see it (the
        // outer type is a union), so we catch it here before
        // splitting the text. Once past this scan, every remaining
        // member is a plain named type, so the string contains no
        // parentheses and exploding on '|' is safe.
        if ($refType instanceof ReflectionUnionType) {
            foreach ($refType->getTypes() as $member) {
                if ($member instanceof ReflectionIntersectionType) {
                    throw new IntersectionTypesNotSupportedException(
                        $member,
                    );
                }
            }
            return explode('|', (string)$refType);
        }

        // unknown ReflectionType subclass - unreachable until PHP
        // adds new child classes. We raise the same exception
        // GetReflectionTypes would, so any caller who used to
        // catch that error keeps working.
        throw new UnsupportedReflectionTypeException($refType);
    }
}
