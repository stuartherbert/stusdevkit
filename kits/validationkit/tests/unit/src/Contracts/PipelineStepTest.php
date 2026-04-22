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

namespace StusDevKit\ValidationKit\Tests\Unit\Contracts;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ValidationKit\Contracts\PipelineStep;
use StusDevKit\ValidationKit\Internals\ValidationContext;

#[TestDox(PipelineStep::class)]
class PipelineStepTest extends TestCase
{
    // ================================================================
    //
    // Interface identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Contracts namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - every
        // implementer imports the interface by FQN, so moving it
        // is a breaking change that must go through a major
        // version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\ValidationKit\\Contracts';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            PipelineStep::class,
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

        // the extension point is designed to be *implemented* by
        // any number of pipeline step classes. Turning it into a
        // class (abstract or concrete) or a trait would break
        // `implements PipelineStep` across every consumer.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            PipelineStep::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only process() and skipOnIssues() as public methods')]
    public function test_exposes_only_expected_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pinning the member set by enumeration catches a silent
        // addition (like a new helper method that every
        // implementer would have to supply).

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['process', 'skipOnIssues'];
        $reflection = new ReflectionClass(
            PipelineStep::class,
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
    // ->process() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->process() is declared')]
    public function test_process_is_declared(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            PipelineStep::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('process');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->process() is public')]
    public function test_process_is_public(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('process');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->process() returns mixed')]
    public function test_process_returns_mixed(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = 'mixed';
        $method = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('process');
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $type->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->process() declares $data and $context as its parameters')]
    public function test_process_declares_expected_parameters(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = ['data', 'context'];
        $method = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('process');

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

    #[TestDox('->process() declares $data as mixed')]
    public function test_process_declares_data_as_mixed(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = 'mixed';
        $param = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('process')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->process() declares $context as ValidationContext')]
    public function test_process_declares_context_as_ValidationContext(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = ValidationContext::class;
        $param = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('process')->getParameters()[1];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->skipOnIssues() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->skipOnIssues() is declared')]
    public function test_skipOnIssues_is_declared(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            PipelineStep::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('skipOnIssues');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->skipOnIssues() is public')]
    public function test_skipOnIssues_is_public(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('skipOnIssues');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->skipOnIssues() takes no parameters')]
    public function test_skipOnIssues_takes_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $method = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('skipOnIssues');

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

    #[TestDox('->skipOnIssues() returns bool')]
    public function test_skipOnIssues_returns_bool(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = 'bool';
        $method = (new ReflectionClass(
            PipelineStep::class,
        ))->getMethod('skipOnIssues');
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $type->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
