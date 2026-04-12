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
use StusDevKit\MissingBitsKit\Reflection\UnsupportedReflectionTypeException;

/**
 * GetReflectionTypes is a helper. Use it to get the list
 * of types that a ReflectionType can be referring to, no matter which
 * child of ReflectionType you have.
 *
 * ReflectionType represents the type of a function/method parameter
 * or return type. This can be a simple type, or a compound type.
 *
 * Unfortunately, there's no `getTypes()` method on ReflectionType itself,
 * to return a normalised list for callers to consume.
 *
 * Instead, each child class has to define its own method. Some return
 * a list of more ReflectionTypes. One child class (ReflectionNamedType)
 * *is* the leaf node in its own right, and has no `getTypes()` or
 * equivalent.
 *
 * NOTES:
 * - if you want a flat list, with everything resolved to the leaf
 *   ReflectionNamedType instances, use FlattenReflectionType::from()
 */
class GetReflectionTypes
{
    /**
     * return the list of ReflectionType that a $refType can be
     *
     * @return ReflectionType[]
     */
    public static function from(ReflectionType $refType): array
    {
        // general case - named type
        if ($refType instanceof ReflectionNamedType) {
            return [ $refType ];
        }

        // special case - supports multiple types
        if ($refType instanceof ReflectionUnionType) {
            return $refType->getTypes();
        }

        // special case - also supports multiple types
        if ($refType instanceof ReflectionIntersectionType) {
            return $refType->getTypes();
        }

        // if we get here, then we don't support this ReflectionType
        //
        // unreachable until PHP adds new ReflectionType child classes
        throw new UnsupportedReflectionTypeException($refType);
    }
}
