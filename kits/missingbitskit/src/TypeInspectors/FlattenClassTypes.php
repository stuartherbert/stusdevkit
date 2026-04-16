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
namespace StusDevKit\MissingBitsKit\TypeInspectors;

/**
 * FlattenClassTypes takes a list of type-name strings and returns
 * a deduplicated, flat list of every PHP type that any class or
 * interface in the input can satisfy - their own name, their parent
 * classes, their interfaces, their traits, plus the universal
 * `'object'` and `'mixed'` leaves.
 *
 * This is the list form of {@see GetClassTypes}: where GetClassTypes
 * expands one class-string into its type surface, FlattenClassTypes
 * expands a whole list and merges the results.
 *
 * **Non-class-like inputs are silently dropped.** Scalar type names
 * (e.g. `'int'`, `'string'`), `'callable'`, `'iterable'`, and
 * anything else for which `class_exists()` and `interface_exists()`
 * both return false, have no class hierarchy to expand and are
 * skipped. The motivating caller (a reflection-based DI resolver's
 * second pass, walking for satisfiable ancestors) has already
 * handled scalar types in its first pass by exact-name lookup.
 *
 * **Ordering.** First-seen order is preserved: each distinct leaf
 * appears in the position where it was first produced by walking
 * the input left-to-right and expanding each class-like entry via
 * GetClassTypes. This means the developer's preferred types come
 * before their ancestors, which is what callers probing in list
 * order would expect.
 *
 * Here Be Dragons.
 * ================
 *
 * **Autoloading side effect.** The "is this a class-like?" probe
 * is implemented with `class_exists($name)` and
 * `interface_exists($name)`, both of which **fire the registered
 * autoloaders** by default for unknown names. In the motivating
 * caller (a reflection-driven DI resolver), every name in
 * $typeNames originates from a ReflectionParameter that PHP
 * could only have produced if the class was already resolvable,
 * so the autoloader either no-ops (already loaded) or runs once
 * for a class that would have been loaded anyway.
 *
 * Callers that pass arbitrary strings - especially strings
 * sourced from user input, configuration, or cross-machine wire
 * formats - need to be aware that this helper will trigger the
 * autoloader for each unknown name. Depending on how your
 * autoloader is wired up, that can mean disk I/O, file_exists
 * probes into every registered prefix, or (for PSR-0-style
 * loaders) surprising cascade loads. If that's a concern, strip
 * non-class-like names at the caller before handing them in.
 */
class FlattenClassTypes
{
    /**
     * flatten a list of type-name strings into the deduplicated
     * set of types any class or interface among them can satisfy
     *
     * @param  list<string> $typeNames
     *         the type names to expand - typically the output of
     *         {@see FlattenReflectionType::from()}
     * @return list<string>
     *         every distinct type that at least one class-like
     *         input in $typeNames can satisfy, in first-seen order
     */
    public static function from(array $typeNames): array
    {
        // use an associative array as the accumulator so that
        // "already seen?" is a hash lookup and the insertion order
        // (= first-seen order) is preserved by PHP's array
        // semantics. array_values() flattens to a list at the end.
        $seen = [];

        foreach ($typeNames as $typeName) {
            // drop type names that have no class hierarchy to
            // expand - scalar type names, 'callable', 'iterable',
            // etc. end up here
            if (
                !class_exists($typeName)
                && !interface_exists($typeName)
            ) {
                continue;
            }

            // GetClassTypes returns an associative array keyed by
            // the type name, so we can merge it into $seen with
            // duplicate keys overwriting (harmlessly, same value)
            foreach (GetClassTypes::from($typeName) as $expanded) {
                $seen[$expanded] = $expanded;
            }
        }

        return array_values($seen);
    }
}
