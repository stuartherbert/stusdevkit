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

namespace StusDevKit\DateTimeKit\Tests\Unit\Formatters;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DateTimeKit\Formatters\WhenSingleFormatterInterface;
use StusDevKit\DateTimeKit\When;

/**
 * Contract test for WhenSingleFormatterInterface.
 *
 * The interface is a published extension point: callers who
 * already have a formatter object pass it to
 * `$when->formatUsing($fmt)` to convert a When to a string. The
 * tests below pin the published shape of that extension point.
 */
#[TestDox(WhenSingleFormatterInterface::class)]
class WhenSingleFormatterInterfaceTest extends TestCase
{
    // ================================================================
    //
    // Interface identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\DateTimeKit\\Formatters namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract -
        // every implementer imports the interface by FQN.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit\\Formatters';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            WhenSingleFormatterInterface::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the extension point is designed to be *implemented*
        // by third-party formatter classes.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            WhenSingleFormatterInterface::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only formatWhen() as a public method')]
    public function test_exposes_only_formatWhen(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the single published method is formatWhen(). Pinning
        // the method set by enumeration catches any silent
        // addition that would force every implementer to supply
        // a new method.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['formatWhen'];
        $reflection = new ReflectionClass(
            WhenSingleFormatterInterface::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->formatWhen() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->formatWhen() is declared')]
    public function test_formatWhen_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // renaming formatWhen() would break every implementer
        // and every caller that goes through
        // When::formatUsing().

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            WhenSingleFormatterInterface::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('formatWhen');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->formatWhen() is public')]
    public function test_formatWhen_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // interface methods are public by default, but pinning
        // it makes the contract explicit - When::formatUsing()
        // calls $formatter->formatWhen($this) unconditionally.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            WhenSingleFormatterInterface::class,
        ))->getMethod('formatWhen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->formatWhen() is an instance method')]
    public function test_formatWhen_is_instance_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method is called through an object reference
        // ($formatter->formatWhen($this)), so it must be an
        // instance method. Promoting it to static would break
        // that call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            WhenSingleFormatterInterface::class,
        ))->getMethod('formatWhen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->formatWhen() declares a string return type')]
    public function test_formatWhen_declares_string_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // single-formatters are the "this is definitely a
        // string" side of the contract (in contrast with
        // WhenSingleTransformerInterface, which returns mixed).
        // When::formatUsing() declares `: string`, so this
        // interface must too - or the narrower promise falls
        // over.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $method = (new ReflectionClass(
            WhenSingleFormatterInterface::class,
        ))->getMethod('formatWhen');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->formatWhen() declares $when as its only parameter')]
    public function test_formatWhen_declares_when_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the single-parameter When argument is the convention.
        // When::formatUsing() passes exactly $this.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['when'];
        $method = (new ReflectionClass(
            WhenSingleFormatterInterface::class,
        ))->getMethod('formatWhen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->formatWhen() declares $when as When')]
    public function test_formatWhen_declares_when_as_When(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter type must be When so implementers have
        // access to the extra When-specific methods and don't
        // need to re-hydrate.

        // ----------------------------------------------------------------
        // setup your test

        $expected = When::class;
        $param = (new ReflectionClass(
            WhenSingleFormatterInterface::class,
        ))->getMethod('formatWhen')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
