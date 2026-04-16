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

namespace StusDevKit\DependencyKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

#[TestDox(UntypedParameterException::class)]
class UntypedParameterExceptionTest extends TestCase
{
    #[TestDox('extends Rfc9457ProblemDetailsException')]
    public function test_extends_rfc9457_problem_details_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UntypedParameterException is part of DependencyKit's
        // RFC 9457 error surface, so it must extend the shared
        // problem-details base class for the payload (type, status,
        // title, extra) to be reachable through the common API.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UntypedParameterException(paramName: 'x');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            Rfc9457ProblemDetailsException::class,
            $unit,
        );
    }

    #[TestDox('does NOT implement Psr\Container\NotFoundExceptionInterface')]
    public function test_does_not_implement_not_found_exception_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11's NotFoundExceptionInterface is specifically for
        // "container has no entry for this id" failures raised by
        // ContainerInterface::get(). UntypedParameterException is a
        // structural error - the parameter has no declared type, so
        // the resolver has nothing to look up in the first place -
        // so it must NOT advertise itself as a PSR-11 "not found"
        // case. Callers catching NotFoundExceptionInterface for
        // missing-service handling would otherwise silently swallow
        // this design error.

        // we check the implemented-interface list via reflection
        // rather than `instanceof` so that PHPStan cannot statically
        // narrow the assertion - it's the regression we want to
        // catch (someone re-adding `implements
        // NotFoundExceptionInterface`) that matters here.

        // ----------------------------------------------------------------
        // setup your test

        $interfaces = (new ReflectionClass(UntypedParameterException::class))
            ->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotContains(
            NotFoundExceptionInterface::class,
            $interfaces,
        );
    }
}
