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
 * get a full list of types that a class can satisfy
 */
class GetClassTypes
{
    /**
     * do we have a PHP class / interface / trait name?
     * If so, what types does it satisfy?
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
    {
        if (!is_string($input)) {
            return [];
        }

        if (
            !class_exists($input)
            && !interface_exists($input)
            && !trait_exists($input)
        ) {
            return [];
        }

        return static::from($input);
    }

    /**
     * get a full list of types that a class can satisfy
     *
     * @param  class-string $className
     *         the item to examine
     * @return array<string,string>
     *         all the types that a class can satisfy
     */
    public static function from(string $className): array
    {
        // our return value
        $retval = [];

        // robustness!
        if (!class_exists($className)
            && !interface_exists($className)
            && !trait_exists($className)
        ) {
            return [];
        }

        // add the class, its parents, interfaces and traits
        $retval = [
            $className => $className,
            ...GetClassHierarchy::from($className),
            ...GetClassInterfaces::from($className),
            ...GetClassTraits::from($className),
        ];

        // special case: is the class also a callable?
        //
        // this catches the rare case where a class-name also happens
        // to be a global function name. Instance-level callable
        // detection (Closures, __invoke) is handled by GetObjectTypes.
        if (method_exists($className, '__invoke')) {
            $retval['callable'] = 'callable';
        }

        // every class/interface/trait name resolves to an object at
        // runtime, so it also satisfies the universal 'object' type
        // hint.
        //
        // 'mixed' is deliberately NOT included. `mixed` is a
        // duck-type marker meaning "any value" - every PHP value
        // satisfies it, not just objects, so it adds no
        // information to a "what types does this class satisfy?"
        // answer. Consumers that want a universal-fallback leaf
        // should add one themselves (and own the footgun that
        // comes with it).
        $retval['object'] = 'object';

        // all done
        return $retval;
    }
}
