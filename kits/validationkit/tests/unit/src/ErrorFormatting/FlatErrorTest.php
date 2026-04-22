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

namespace StusDevKit\ValidationKit\Tests\Unit\ErrorFormatting;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\ValidationKit\ErrorFormatting\FlatError;

/**
 * Contract + behaviour tests for FlatError.
 *
 * FlatError is a read-only value object holding two
 * immutable lists: root-level errors (not associated with a
 * field) and field-level errors keyed by dot-path. Tests
 * pin the class shape, the final marker, and verify that
 * both accessors return what was passed into the constructor.
 */
#[TestDox(FlatError::class)]
class FlatErrorTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\ErrorFormatting namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\ErrorFormatting';

        $actual = (new ReflectionClass(FlatError::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a final class')]
    public function test_is_declared_final(): void
    {
        // the class is deliberately final - FlatError is a
        // wire-format DTO with two immutable arrays.
        // Extending it to override a getter would break the
        // "pure value" contract. Pinning `final` here makes
        // any future declass a deliberate, reviewed change.

        $reflection = new ReflectionClass(FlatError::class);

        $actual = $reflection->isFinal()
            && (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('declares __construct, getFieldErrors, and getRootErrors as its public methods')]
    public function test_declares_expected_public_methods(): void
    {
        // pinning the method set as a literal list guards
        // against silent surface-area drift. Adding a
        // setter, for instance, would turn the DTO into a
        // mutable container and break consumer assumptions.

        $expected = [
            '__construct',
            'getFieldErrors',
            'getRootErrors',
        ];
        $reflection = new ReflectionClass(FlatError::class);

        $methodNames = array_values(array_map(
            static fn ($m) => $m->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        ));
        sort($methodNames);

        $this->assertSame($expected, $methodNames);
    }

    // ================================================================
    //
    // ->getRootErrors() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getRootErrors() returns [] when the constructor was given no root errors')]
    public function test_getRootErrors_empty_list(): void
    {
        $unit = new FlatError(
            rootErrors: [],
            fieldErrors: [],
        );

        $actual = $unit->getRootErrors();

        $this->assertSame([], $actual);
    }

    #[TestDox('->getRootErrors() returns the list passed into the constructor')]
    public function test_getRootErrors_returns_constructor_input(): void
    {
        // the accessor is a pure getter - the DTO does not
        // sort, dedup, or transform the input. Callers see
        // exactly what they passed in, in the original
        // order.

        $expected = [
            'Payload must be an object',
            'Unrecognised top-level keys',
        ];

        $unit = new FlatError(
            rootErrors: $expected,
            fieldErrors: [],
        );

        $actual = $unit->getRootErrors();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->getFieldErrors() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getFieldErrors() returns [] when the constructor was given no field errors')]
    public function test_getFieldErrors_empty_map(): void
    {
        $unit = new FlatError(
            rootErrors: [],
            fieldErrors: [],
        );

        $actual = $unit->getFieldErrors();

        $this->assertSame([], $actual);
    }

    #[TestDox('->getFieldErrors() returns the map passed into the constructor')]
    public function test_getFieldErrors_returns_constructor_input(): void
    {
        // each field key maps to a list of message strings -
        // multiple failures against the same field (for
        // example a string that is both too short and
        // non-alphanumeric) collect into the same bucket.
        // Pinning the exact shape here keeps the wire
        // contract stable for form-handling consumers.

        $expected = [
            'username' => [
                'String must be at least 3 characters',
                'String must be alphanumeric',
            ],
            'email' => [
                'Not a valid email',
            ],
        ];

        $unit = new FlatError(
            rootErrors: [],
            fieldErrors: $expected,
        );

        $actual = $unit->getFieldErrors();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->getFieldErrors() and ->getRootErrors() are independent')]
    public function test_getters_return_independent_state(): void
    {
        // both constructor inputs are retained separately; a
        // non-empty field-errors map does not leak into the
        // root-errors list, and vice versa. This guards
        // against an implementation bug where one getter
        // accidentally reads the other's backing store.

        $unit = new FlatError(
            rootErrors: ['root message'],
            fieldErrors: ['username' => ['field message']],
        );

        $this->assertSame(
            ['root message'],
            $unit->getRootErrors(),
        );
        $this->assertSame(
            ['username' => ['field message']],
            $unit->getFieldErrors(),
        );
    }
}
