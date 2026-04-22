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
use StusDevKit\ValidationKit\Exceptions\ExternalJsonSchemaRefNotFoundException;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;

/**
 * Contract + behaviour tests for ExternalJsonSchemaRefNotFoundException.
 *
 * Specialises InvalidJsonSchemaException with a single-purpose
 * constructor: given the unresolvable $ref URI, produce an
 * exception whose message names it. Tests pin the parent class,
 * the constructor shape, and the message format that log
 * searches rely on.
 */
#[TestDox(ExternalJsonSchemaRefNotFoundException::class)]
class ExternalJsonSchemaRefNotFoundExceptionTest extends TestCase
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
            ExternalJsonSchemaRefNotFoundException::class,
        ))->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        $reflection = new ReflectionClass(
            ExternalJsonSchemaRefNotFoundException::class,
        );

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('extends InvalidJsonSchemaException')]
    public function test_extends_InvalidJsonSchemaException(): void
    {
        // the parent is the kit's generic schema-invalid
        // exception. Catching the parent must also catch
        // every specialised subclass, so pinning the
        // hierarchy here protects every `catch
        // (InvalidJsonSchemaException $e)` block in the
        // codebase.

        $reflection = new ReflectionClass(
            ExternalJsonSchemaRefNotFoundException::class,
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
        // this subclass exists solely to pin a message format
        // for a specific loader failure. Any additional public
        // method is a surface-area expansion that must be
        // reviewed deliberately.

        $expected = ['__construct'];
        $reflection = new ReflectionClass(
            ExternalJsonSchemaRefNotFoundException::class,
        );

        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName()
                === ExternalJsonSchemaRefNotFoundException::class) {
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
            ExternalJsonSchemaRefNotFoundException::class,
        ))->getMethod('__construct');

        $this->assertTrue($method->isPublic());
    }

    #[TestDox('::__construct() declares $ref as its only parameter')]
    public function test_construct_declares_ref_as_its_only_parameter(): void
    {
        // the parameter list is pinned by enumeration. Adding
        // or renaming a parameter is a breaking change for
        // every throw-site.

        $expected = ['ref'];
        $method = (new ReflectionClass(
            ExternalJsonSchemaRefNotFoundException::class,
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
        // `$ref` must be string - a URI is always a string at
        // the PHP type level. Widening to `mixed` or swapping
        // for a URI value object would break every throw-site
        // that passes a plain URL from the JSON schema.

        $expected = 'string';
        $param = (new ReflectionClass(
            ExternalJsonSchemaRefNotFoundException::class,
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
        $unit = new ExternalJsonSchemaRefNotFoundException(
            ref: 'https://example.com/missing.json',
        );

        $this->assertInstanceOf(
            ExternalJsonSchemaRefNotFoundException::class,
            $unit,
        );
    }

    #[TestDox('->getMessage() embeds the $ref URI and names the loader')]
    public function test_getMessage_embeds_the_ref_and_names_the_loader(): void
    {
        // the literal message is pinned because log searches
        // for "could not be loaded" and "JsonSchemaLoader
        // returned null" are the primary way of diagnosing
        // this failure in production.

        $unit = new ExternalJsonSchemaRefNotFoundException(
            ref: 'https://example.com/missing.json',
        );

        $actual = $unit->getMessage();

        $this->assertSame(
            'External $ref "https://example.com/missing.json"'
                . ' could not be loaded; the JsonSchemaLoader'
                . ' returned null for this URI',
            $actual,
        );
    }

    #[TestDox('instances are throwable as InvalidJsonSchemaException')]
    public function test_instances_are_throwable_as_parent(): void
    {
        // catching the parent type must work, because that is
        // the recommended way to handle all schema-authoring
        // errors in one place.

        $this->expectException(InvalidJsonSchemaException::class);

        throw new ExternalJsonSchemaRefNotFoundException(
            ref: 'https://example.com/missing.json',
        );
    }
}
