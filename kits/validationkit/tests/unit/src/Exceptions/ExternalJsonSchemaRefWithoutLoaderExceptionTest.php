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
use ReflectionNamedType;
use StusDevKit\ValidationKit\Exceptions\ExternalJsonSchemaRefWithoutLoaderException;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;

/**
 * Contract + behaviour tests for
 * ExternalJsonSchemaRefWithoutLoaderException.
 *
 * Specialises InvalidJsonSchemaException for the case where an
 * external $ref appears in a schema but no JsonSchemaLoader was
 * supplied to import(). Tests pin the parent class, the
 * constructor shape, and the literal message pointing the
 * developer at the missing loader.
 */
#[TestDox(ExternalJsonSchemaRefWithoutLoaderException::class)]
class ExternalJsonSchemaRefWithoutLoaderExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\Exceptions';

        $actual = (new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        ))->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        $reflection = new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        );

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('extends InvalidJsonSchemaException')]
    public function test_extends_InvalidJsonSchemaException(): void
    {
        // callers typically catch the parent type in order to
        // aggregate all schema-authoring errors into one
        // diagnostic. Swapping the parent would silently
        // reshape that contract.

        $reflection = new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        );

        $actual = $reflection->getParentClass();

        $this->assertNotFalse($actual);
        $this->assertSame(
            InvalidJsonSchemaException::class,
            $actual->getName(),
        );
    }

    #[TestDox('declares only __construct as its own public method')]
    public function test_declares_only_constructor(): void
    {
        $expected = ['__construct'];
        $reflection = new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        );

        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName()
                === ExternalJsonSchemaRefWithoutLoaderException::class) {
                $ownMethods[] = $m->getName();
            }
        }

        $this->assertSame($expected, $ownMethods);
    }

    // ================================================================
    //
    // ::__construct() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() is public')]
    public function test_construct_is_public(): void
    {
        $method = (new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        ))->getMethod('__construct');

        $this->assertTrue($method->isPublic());
    }

    #[TestDox('::__construct() declares $ref as its only parameter')]
    public function test_construct_declares_ref_as_only_parameter(): void
    {
        $expected = ['ref'];
        $method = (new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        ))->getMethod('__construct');

        $actual = array_map(
            static fn ($p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() declares $ref as string')]
    public function test_construct_declares_ref_as_string(): void
    {
        $expected = 'string';
        $param = (new ReflectionClass(
            ExternalJsonSchemaRefWithoutLoaderException::class,
        ))->getMethod('__construct')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        $actual = $paramType->getName();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a $ref string')]
    public function test_construct_accepts_a_ref_string(): void
    {
        $unit = new ExternalJsonSchemaRefWithoutLoaderException(
            ref: 'address.json',
        );

        $this->assertInstanceOf(
            ExternalJsonSchemaRefWithoutLoaderException::class,
            $unit,
        );
    }

    #[TestDox('->getMessage() embeds the $ref and names the missing dependency')]
    public function test_getMessage_embeds_ref_and_names_dependency(): void
    {
        // the message literal is the fix-me instruction for
        // the developer: the loader needs to be passed to
        // import(). Pinning the literal guarantees that
        // actionable guidance does not drift.

        $unit = new ExternalJsonSchemaRefWithoutLoaderException(
            ref: 'address.json',
        );

        $actual = $unit->getMessage();

        $this->assertSame(
            'External $ref "address.json" requires a'
                . ' JsonSchemaLoader to be passed to import()',
            $actual,
        );
    }

    #[TestDox('instances are throwable as InvalidJsonSchemaException')]
    public function test_instances_are_throwable_as_parent(): void
    {
        $this->expectException(InvalidJsonSchemaException::class);

        throw new ExternalJsonSchemaRefWithoutLoaderException(
            ref: 'address.json',
        );
    }
}
