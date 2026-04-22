<?php

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
//

declare(strict_types=1);

namespace StusDevKit\MissingBitsKit\Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionFunction;
use ReflectionParameter;
use stdClass;

use function StusDevKit\MissingBitsKit\get_class_basename;

#[TestDox('get_class_basename()')]
class GetClassBasenameTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is a function in the StusDevKit\\MissingBitsKit namespace')]
    public function test_exists_in_expected_namespace(): void
    {
        $this->assertTrue(
            \function_exists(
                'StusDevKit\\MissingBitsKit\\get_class_basename',
            ),
        );
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\get_class_basename',
        );
        $this->assertSame(
            'StusDevKit\\MissingBitsKit',
            $reflection->getNamespaceName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::get_class_basename() parameter names in order')]
    public function test_parameter_names(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\get_class_basename',
        );
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $reflection->getParameters(),
        );
        $this->assertSame(['fqcn'], $paramNames);
    }

    #[TestDox('::get_class_basename() parameter types in order')]
    public function test_parameter_types(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\get_class_basename',
        );
        $paramTypes = array_map(
            fn(ReflectionParameter $p) => (string) $p->getType(),
            $reflection->getParameters(),
        );
        $this->assertSame(['string'], $paramTypes);
    }

    #[TestDox('::get_class_basename() return type')]
    public function test_return_type(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\get_class_basename',
        );
        $this->assertSame(
            'string',
            (string) $reflection->getReturnType(),
        );
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('returns short name for namespaced class')]
    public function test_returns_short_name(): void
    {
        /** strips the namespace and returns only the class name */
        $result = get_class_basename(self::class);

        $this->assertSame(
            'GetClassBasenameTest',
            $result,
        );
    }

    #[TestDox('returns class name for non-namespaced class')]
    public function test_returns_name_for_non_namespaced(): void
    {
        /** works for classes without a namespace */
        $result = get_class_basename(stdClass::class);

        $this->assertSame('stdClass', $result);
    }

    #[TestDox('returns short name from an object instance')]
    public function test_returns_short_name_from_object(): void
    {
        /**
         * works when passed a fully-qualified class name obtained
         * from an object via ::class
         */
        $obj = new stdClass();

        $result = get_class_basename($obj::class);

        $this->assertSame('stdClass', $result);
    }
}
