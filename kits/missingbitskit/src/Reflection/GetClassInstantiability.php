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

use ReflectionClass;

/**
 * GetClassInstantiability reports whether a given class-string can be
 * instantiated via `new`, and - if not - which single reason
 * disqualifies it. Result is returned as a {@see ClassInstantiability}
 * enum value.
 *
 * Use this as a guard in front of code that will call
 * `new $classname(...)`. Callers who only care about a yes/no answer
 * can use `GetClassInstantiability::from($classname)->isInstantiable()`.
 *
 * The inspector reports the **first** disqualifying reason it finds;
 * it does not enumerate every problem. Checks run in this order:
 *
 *   1. the symbol must exist at all (class, interface, trait, or enum);
 *   2. it must not be an interface;
 *   3. it must not be a trait;
 *   4. it must not be an enum;
 *   5. it must not be an abstract class;
 *   6. if it declares a constructor, that constructor must be public.
 *
 * The ordering means, for example, that an abstract class that also
 * declares a private constructor reports as `IsAbstract`, not
 * `ConstructorNotPublic`.
 *
 * ## Here Be Dragons
 *
 * - **Autoloading runs.** `new ReflectionClass($classname)` triggers
 *   the autoloader. Passing a class-string you don't trust will load
 *   that class's file, with all the top-level side effects that
 *   implies. Only pass class-strings you were ready to `new` anyway.
 *
 * - **Anonymous classes are out of scope.** They have mangled runtime
 *   names and no meaningful class-string caller. If you somehow pass
 *   one in, the result is whatever the reflection layer happens to
 *   say - not pinned.
 *
 * - **No `__construct` via `__callStatic` / magic.** If the class
 *   resolves construction through magic, reflection won't see it and
 *   this inspector will report whichever static fact actually holds
 *   (typically `ConstructorNotPublic` or `Instantiable` depending on
 *   what's declared). Magic construction is outside the scope of
 *   "can PHP `new` this directly?".
 *
 * - **Engine-restricted internal classes slip past.** A handful of
 *   PHP built-ins refuse direct `new` via engine-level flags that
 *   reflection does not expose - `Generator` and `WeakReference` are
 *   the obvious examples (`WeakReference` requires `::create()`,
 *   `Generator` is reserved for internal use). For these, every
 *   reflection signal says "instantiable" (`isInstantiable()` lies
 *   and returns true, `isAbstract()` is false, there is no
 *   non-public constructor), so this inspector returns `INSTANTIABLE`
 *   - but the caller's `new $classname(...)` will still fail with a
 *   clear PHP error at that point ("class is reserved for internal
 *   use and cannot be manually instantiated"). Trying to detect
 *   these ahead of time would require a hardcoded allow/deny list
 *   that silently rots between PHP versions; the trade-off is worse
 *   than letting the runtime error surface at the `new` site.
 */
class GetClassInstantiability
{
    /**
     * inspect a string and return the single reason that disqualifies
     * it from instantiation, or `Instantiable` if PHP will let you
     * `new` it.
     *
     * The parameter is declared as plain `string` on purpose: the
     * whole point of this inspector is to tell callers whether the
     * name they hold actually resolves to an instantiable class.
     * Strings that do not name any loaded symbol return
     * `ClassDoesNotExist`.
     *
     * @param string $classname
     *     the fully-qualified name to inspect.
     */
    public static function from(string $classname): ClassInstantiability
    {
        // step 1: does the symbol exist at all?
        //
        // class_exists() returns true for both real classes AND
        // enums - enums are full classes at runtime - so we don't
        // need a separate enum_exists() call here. Interfaces and
        // traits each need their own check.
        //
        // After this negated early-return, PHPStan narrows
        // $classname to class-string, which is what ReflectionClass
        // expects.
        if (
            ! class_exists($classname)
            && ! interface_exists($classname)
            && ! trait_exists($classname)
        ) {
            return ClassInstantiability::CLASS_DOES_NOT_EXIST;
        }

        $refClass = new ReflectionClass($classname);

        // step 2-4: rule out the non-class-shaped symbols first.
        //
        // interfaces, traits, and enums all pass class_exists()-ish
        // checks in confusing ways (enums in particular are full
        // classes at runtime), so we discriminate by asking
        // ReflectionClass directly.
        if ($refClass->isInterface()) {
            return ClassInstantiability::IS_INTERFACE;
        }

        if ($refClass->isTrait()) {
            return ClassInstantiability::IS_TRAIT;
        }

        if ($refClass->isEnum()) {
            return ClassInstantiability::IS_ENUM;
        }

        // step 5: abstract classes cannot be `new`'d directly, even
        // when their constructor is public.
        if ($refClass->isAbstract()) {
            return ClassInstantiability::IS_ABSTRACT;
        }

        // step 6: an explicit constructor must be public. A class
        // with no declared constructor has an implicit public no-arg
        // ctor, which is fine.
        $ctor = $refClass->getConstructor();
        if ($ctor !== null && ! $ctor->isPublic()) {
            return ClassInstantiability::CONSTRUCTOR_NOT_PUBLIC;
        }

        // if we get here, we've run out of reasons why the given
        // class name cannot be instantiated
        return ClassInstantiability::INSTANTIABLE;
    }
}
