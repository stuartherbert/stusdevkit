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

namespace StusDevKit\ValidationKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;

/**
 * Contract + behaviour tests for InvalidJsonSchemaException.
 *
 * InvalidJsonSchemaException is a plain RuntimeException with
 * four named static factories. Unlike ValidationException it is
 * NOT an RFC 9457 problem-details exception - it reports a
 * development-time schema authoring bug, not a runtime
 * validation failure. Tests pin the parent class, the factory
 * method set, and each factory's message format.
 */
#[TestDox(InvalidJsonSchemaException::class)]
class InvalidJsonSchemaExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the published namespace is part of the contract -
        // callers and subclasses both import this exception by
        // FQN, so moving it is a breaking change that must
        // go through a major version bump.

        $expected = 'StusDevKit\\ValidationKit\\Exceptions';

        $actual = (new ReflectionClass(
            InvalidJsonSchemaException::class,
        ))->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // InvalidJsonSchemaException is a concrete throwable
        // class, and its concrete subclasses extend it by
        // calling parent::__construct. Pinning this prevents
        // an accidental promotion to abstract or interface.

        $reflection = new ReflectionClass(
            InvalidJsonSchemaException::class,
        );

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum())
            && (! $reflection->isAbstract());

        $this->assertTrue($actual);
    }

    #[TestDox('extends RuntimeException')]
    public function test_extends_RuntimeException(): void
    {
        // RuntimeException is the correct SPL parent because
        // this exception reports a programming error that is
        // only detectable at runtime (schema import time).
        // It is deliberately NOT an RFC 9457 exception - a
        // malformed schema is a developer bug, not something
        // to serialise to API clients.

        $reflection = new ReflectionClass(
            InvalidJsonSchemaException::class,
        );

        $actual = $reflection->getParentClass();

        $this->assertNotFalse($actual);
        $this->assertSame(
            RuntimeException::class,
            $actual->getName(),
        );
    }

    #[TestDox('declares exactly four named static factories')]
    public function test_declares_expected_public_method_set(): void
    {
        // the public surface area is pinned as a set of named
        // factories. Adding a factory must update this list
        // deliberately, and removing one is a breaking change
        // for every throw-site that calls it.

        $expected = [
            'invalidKeyword',
            'malformed',
            'unknownType',
            'unresolvedRef',
        ];
        $reflection = new ReflectionClass(
            InvalidJsonSchemaException::class,
        );

        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName()
                === InvalidJsonSchemaException::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame($expected, $ownMethods);
    }

    // ================================================================
    //
    // ::unknownType() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::unknownType() returns an InvalidJsonSchemaException')]
    public function test_unknownType_returns_self(): void
    {
        $unit = InvalidJsonSchemaException::unknownType(
            type: 'foo',
        );

        $this->assertInstanceOf(
            InvalidJsonSchemaException::class,
            $unit,
        );
    }

    #[TestDox('::unknownType() embeds the offending type in the message')]
    public function test_unknownType_message_contains_the_type(): void
    {
        // the message is pinned as a literal so that log
        // searches for the exact text "Unknown JSON Schema
        // type:" continue to work. Rewording the template
        // would break those searches silently.

        $unit = InvalidJsonSchemaException::unknownType(
            type: 'foo',
        );

        $actual = $unit->getMessage();

        $this->assertSame(
            'Unknown JSON Schema type: "foo"',
            $actual,
        );
    }

    // ================================================================
    //
    // ::unresolvedRef() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::unresolvedRef() returns an InvalidJsonSchemaException')]
    public function test_unresolvedRef_returns_self(): void
    {
        $unit = InvalidJsonSchemaException::unresolvedRef(
            ref: '#/$defs/missing',
        );

        $this->assertInstanceOf(
            InvalidJsonSchemaException::class,
            $unit,
        );
    }

    #[TestDox('::unresolvedRef() embeds the offending ref in the message')]
    public function test_unresolvedRef_message_contains_the_ref(): void
    {
        // pinning the literal text means a $ref appearing in a
        // log line can always be matched back to this factory,
        // no matter which code path produced it.

        $unit = InvalidJsonSchemaException::unresolvedRef(
            ref: '#/$defs/missing',
        );

        $actual = $unit->getMessage();

        $this->assertSame(
            'Unresolved $ref: "#/$defs/missing"',
            $actual,
        );
    }

    // ================================================================
    //
    // ::invalidKeyword() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::invalidKeyword() returns an InvalidJsonSchemaException')]
    public function test_invalidKeyword_returns_self(): void
    {
        $unit = InvalidJsonSchemaException::invalidKeyword(
            keyword: 'minLength',
            type: 'integer',
        );

        $this->assertInstanceOf(
            InvalidJsonSchemaException::class,
            $unit,
        );
    }

    #[TestDox('::invalidKeyword() embeds both the keyword and the type in the message')]
    public function test_invalidKeyword_message_contains_keyword_and_type(): void
    {
        // both inputs must appear in the message so schema
        // authors can locate the offending keyword in their
        // document without guessing which combination
        // triggered the error.

        $unit = InvalidJsonSchemaException::invalidKeyword(
            keyword: 'minLength',
            type: 'integer',
        );

        $actual = $unit->getMessage();

        $this->assertSame(
            'Keyword "minLength" is not valid for type "integer"',
            $actual,
        );
    }

    // ================================================================
    //
    // ::malformed() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::malformed() returns an InvalidJsonSchemaException')]
    public function test_malformed_returns_self(): void
    {
        $unit = InvalidJsonSchemaException::malformed(
            reason: 'expected object, got string',
        );

        $this->assertInstanceOf(
            InvalidJsonSchemaException::class,
            $unit,
        );
    }

    #[TestDox('::malformed() prefixes the reason with "Invalid JSON Schema:"')]
    public function test_malformed_message_has_expected_prefix(): void
    {
        // the "Invalid JSON Schema:" prefix is the stable
        // grep anchor for this factory in log output; the
        // caller-supplied reason follows verbatim.

        $unit = InvalidJsonSchemaException::malformed(
            reason: 'expected object, got string',
        );

        $actual = $unit->getMessage();

        $this->assertSame(
            'Invalid JSON Schema: expected object, got string',
            $actual,
        );
    }

    // ================================================================
    //
    // Throwability
    //
    // ----------------------------------------------------------------

    #[TestDox('instances are throwable as RuntimeException')]
    public function test_instances_are_throwable_as_RuntimeException(): void
    {
        // because the parent is RuntimeException, call sites
        // can choose to catch the narrow type or the wider
        // SPL type. Pinning both avoids surprise if the
        // hierarchy is reshuffled later.

        $this->expectException(RuntimeException::class);

        throw InvalidJsonSchemaException::unknownType(
            type: 'foo',
        );
    }
}
