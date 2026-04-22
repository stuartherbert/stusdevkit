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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Arrays;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable;

/**
 * Contract tests for StaticallyArrayable.
 *
 * These tests act as a lockdown on the interface's published shape:
 * removing or reshaping any member of the contract must be an
 * intentional act that updates these tests at the same time.
 */
#[TestDox(StaticallyArrayable::class)]
class StaticallyArrayableTest extends TestCase
{
    // ================================================================
    //
    // Interface identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // StaticallyArrayable must be an interface (not a class,
        // abstract class, or trait). Implementations rely on this so
        // they can declare `implements StaticallyArrayable`.

        $reflection = new ReflectionClass(StaticallyArrayable::class);

        $actual = $reflection->isInterface();

        $this->assertTrue($actual);
    }

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Arrays namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the published namespace is part of the contract - callers
        // type-hint against the FQN, so moving it is a breaking
        // change that must go through a major version bump.

        $expected = 'StusDevKit\\MissingBitsKit\\Arrays';

        $actual = (new ReflectionClass(StaticallyArrayable::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only a toArray() method')]
    public function test_exposes_only_a_toArray_method(): void
    {
        // the interface exists to require a single method, toArray().
        // Adding a second method is a breaking change for every
        // implementer, so the method set is pinned by enumeration -
        // any addition fails with a diff that names the new method,
        // rather than a cryptic count mismatch.

        $expected = ['toArray'];
        $reflection = new ReflectionClass(StaticallyArrayable::class);

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::toArray() is declared')]
    public function test_declares_a_toArray_method(): void
    {
        // the single contract method is `toArray()`. Renaming it is
        // a breaking change for every implementer.

        $reflection = new ReflectionClass(StaticallyArrayable::class);

        $actual = $reflection->hasMethod('toArray');

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // toArray() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::toArray() is public')]
    public function test_toArray_is_public(): void
    {
        // interface methods must be public for implementers to
        // satisfy them. Pin the visibility so a silent downgrade to
        // protected / private (not currently legal in PHP interfaces,
        // but defensive against future language changes) is caught.

        $method = (new ReflectionClass(StaticallyArrayable::class))
            ->getMethod('toArray');

        $actual = $method->isPublic();

        $this->assertTrue($actual);
    }

    #[TestDox('::toArray() is static')]
    public function test_toArray_is_static(): void
    {
        // the whole point of StaticallyArrayable is that the data
        // lives on the type, not the instance. Removing `static`
        // silently turns the contract into an instance-level one
        // and defeats the reason for a separate interface.

        $method = (new ReflectionClass(StaticallyArrayable::class))
            ->getMethod('toArray');

        $actual = $method->isStatic();

        $this->assertTrue($actual);
    }

    #[TestDox('::toArray() takes no parameters')]
    public function test_toArray_takes_no_parameters(): void
    {
        // the contract promises a parameter-less call: the type's
        // full array representation, no options, no filters. Adding
        // a required parameter would break every call site.

        $expected = 0;
        $method = (new ReflectionClass(StaticallyArrayable::class))
            ->getMethod('toArray');

        $actual = $method->getNumberOfParameters();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::toArray() declares an `array` return type')]
    public function test_toArray_declares_an_array_return_type(): void
    {
        // the runtime return type is `array`. Richer element types
        // (e.g. `array<string, string>`) live in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        $expected = 'array';
        $method = (new ReflectionClass(StaticallyArrayable::class))
            ->getMethod('toArray');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        $actual = $returnType->getName();

        $this->assertSame($expected, $actual);
    }
}
