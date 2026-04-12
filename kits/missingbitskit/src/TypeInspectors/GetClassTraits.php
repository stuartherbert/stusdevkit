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
namespace StusDevKit\MissingBitsKit\TypeInspectors;

/**
 * get a full list of the traits used by a class *and* its parents
 */
class GetClassTraits
{
    /**
     * does this class use traits?
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
    {
        if (!is_string($input)) {
            return [];
        }

        if (!class_exists($input) && !trait_exists($input)) {
            return [];
        }

        return static::from($input);
    }

    /**
     * get a full list of the traits used by a class
     *
     * @param  class-string $item
     *         the item to examine
     * @return array<class-string,class-string>
     *         the class's traits list
     */
    public static function from(string $item): array
    {
        // our return value - built up across the whole class hierarchy
        $retval = [];

        // we need to walk the whole class hierarchy, because each
        // parent class can bring its own traits to the party
        $classHierarchy = GetClassHierarchy::from($item);

        foreach ($classHierarchy as $className) {
            // fold this class's direct traits (and their transitively
            // used traits) into the lookup table we are building up
            $retval = self::collectTraits(class_uses($className), $retval);
        }

        // all done
        return $retval;
    }

    /**
     * fold $traits (and the traits they transitively `use`) into
     * $seen, and return the combined lookup table.
     *
     * we pass $seen in and return the updated copy, so that this
     * helper stays pure - the recursion and the outer loop in
     * `from()` thread the accumulator through explicitly instead of
     * mutating shared state.
     *
     * the `isset()` check on $seen doubles as our recursion-terminator
     * whenever we revisit a trait we have already walked.
     *
     * @param  array<string,class-string>|false $traits
     *         the list of traits to fold in, as returned by `class_uses()`
     * @param  array<class-string,class-string> $seen
     *         the lookup table accumulated so far
     * @return array<class-string,class-string>
     *         $seen, with any newly-discovered traits merged in
     */
    private static function collectTraits(array|false $traits, array $seen): array
    {
        // `class_uses()` returns false when the argument is not a
        // known class or trait. we only call this helper with names
        // that PHP has already resolved, so this is a belt-and-braces
        // guard to keep PHPStan happy.
        if ($traits === false) {
            return $seen;
        }

        foreach ($traits as $trait) {
            // skip traits we have already walked, to avoid doing the
            // same work twice (and to avoid infinite loops, even though
            // PHP itself rejects circular trait usage)
            if (isset($seen[$trait])) {
                continue;
            }

            $seen[$trait] = $trait;

            // a trait can itself `use` other traits - follow those too
            $seen = self::collectTraits(class_uses($trait), $seen);
        }

        return $seen;
    }
}
