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

namespace StusDevKit\MissingBitsKit\Reflection;

use StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable;
use StusDevKit\MissingBitsKit\Enums\EnumToArray;

/**
 * ClassInstantiability is a value object describing whether a given
 * class-string can be instantiated via `new`, and - if not - which
 * single reason disqualifies it.
 *
 * Each case carries a short, self-describing string value suitable for
 * dropping straight into error messages (e.g. "cannot use Foo: is an
 * interface").
 *
 * Create instances of this enum via
 * {@see GetClassInstantiability::from()}. This enum does not create
 * itself.
 *
 * @implements StaticallyArrayable<string,string>
 */
enum ClassInstantiability: string implements StaticallyArrayable
{
    /** @use EnumToArray<string> */
    use EnumToArray;

    /**
     * the class-string names a concrete class that PHP will let you
     * `new`. Covers classes with no constructor (default public
     * zero-arg) and classes with an explicit public constructor.
     */
    case INSTANTIABLE = 'instantiable';

    /**
     * no symbol with this name is loaded: not a class, interface,
     * trait, or enum. Checked first because every other case assumes
     * the symbol exists.
     */
    case CLASS_DOES_NOT_EXIST = 'class does not exist';

    /**
     * the symbol is an interface. Interfaces have no runtime
     * representation that `new` can produce.
     */
    case IS_INTERFACE = 'is an interface';

    /**
     * the symbol is a trait. Traits are compile-time composition units,
     * not classes; `new` on one is a fatal error.
     */
    case IS_TRAIT = 'is a trait';

    /**
     * the symbol is an enum (pure or backed). `class_exists()` returns
     * true for enums, but PHP forbids `new` on them: cases are produced
     * by the runtime.
     */
    case IS_ENUM = 'is an enum';

    /**
     * the symbol is an abstract class. `class_exists()` returns true
     * and the constructor may be public, but PHP forbids `new` on
     * abstract classes directly.
     */
    case IS_ABSTRACT = 'is an abstract class';

    /**
     * the class has an explicit constructor that is not `public`.
     * Typically a private-ctor singleton or a protected-ctor
     * factory-only shape.
     */
    case CONSTRUCTOR_NOT_PUBLIC = 'constructor is not public';

    /**
     * convenience predicate for callers that only care whether the
     * class-string can be instantiated, not why.
     */
    public function isInstantiable(): bool
    {
        return $this === self::INSTANTIABLE;
    }
}
