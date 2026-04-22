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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Reflection;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use StusDevKit\MissingBitsKit\Reflection\ClassInstantiability;
use StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\AbstractSampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\NoCtorClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\PrivateCtorClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\ProtectedCtorClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\PublicCtorClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\ReadonlyClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\SampleEnum;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\SampleInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Reflection\Instantiability\SampleTrait;

/**
 * Contract + behaviour tests for the GetClassInstantiability inspector.
 *
 * These tests act as a lockdown on the class's published shape and
 * observed runtime behaviour: renaming `from()`, changing its
 * signature, or altering which ClassInstantiability case it returns
 * for a given input shape must be an intentional act that updates
 * these tests at the same time.
 */
#[TestDox(GetClassInstantiability::class)]
class GetClassInstantiabilityTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    /**
     * the published namespace is part of the contract - callers
     * import by FQN, so moving the class is a breaking change
     * that must go through a major version bump.
     */
    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Reflection namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\MissingBitsKit\\Reflection';
        $actual = (new ReflectionClass(GetClassInstantiability::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() method shape
    //
    // ----------------------------------------------------------------

    /**
     * the single entry point to this class is `from()`. Renaming
     * it is a breaking change for every caller.
     */
    #[TestDox('::from() is declared')]
    public function test_declares_a_from_method(): void
    {
        $reflection = new ReflectionClass(GetClassInstantiability::class);
        $actual = $reflection->hasMethod('from');
        $this->assertTrue($actual);
    }

    /**
     * the method must be public for callers to reach it. A
     * silent downgrade to protected / private would break every
     * call site.
     */
    #[TestDox('::from() is public')]
    public function test_from_is_public(): void
    {
        $method = (new ReflectionClass(GetClassInstantiability::class))
            ->getMethod('from');
        $actual = $method->isPublic();
        $this->assertTrue($actual);
    }

    /**
     * GetClassInstantiability is a stateless utility; its single
     * method is called without an instance. Silently dropping
     * `static` would force every call site to instantiate.
     */
    #[TestDox('::from() is static')]
    public function test_from_is_static(): void
    {
        $method = (new ReflectionClass(GetClassInstantiability::class))
            ->getMethod('from');
        $actual = $method->isStatic();
        $this->assertTrue($actual);
    }

    /**
     * the contract accepts a single class-string and nothing
     * else. Adding a required parameter would break every call
     * site; adding an optional one would widen the contract in
     * a way that deserves a deliberate decision.
     */
    #[TestDox('::from() takes exactly one parameter')]
    public function test_from_takes_exactly_one_parameter(): void
    {
        $expected = 1;
        $method = (new ReflectionClass(GetClassInstantiability::class))
            ->getMethod('from');
        $actual = $method->getNumberOfParameters();
        $this->assertSame($expected, $actual);
    }

    /**
     * the runtime parameter type is plain `string`. Narrower
     * types (class-string, non-empty-string) live in the
     * docblock for PHPStan only - the runtime accepts any
     * string, because the inspector's job includes reporting
     * CLASS_DOES_NOT_EXIST for garbage input.
     */
    #[TestDox("::from()'s parameter has a string type")]
    public function test_from_parameter_has_a_string_type(): void
    {
        $expected = 'string';
        $method = (new ReflectionClass(GetClassInstantiability::class))
            ->getMethod('from');
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $paramType = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);
        $actual = $paramType->getName();
        $this->assertSame($expected, $actual);
    }

    /**
     * the return type is the enum itself, not a bool or string
     * or mixed. Callers pattern-match on the returned case, so
     * the return type is the primary shape callers depend on.
     */
    #[TestDox('::from() declares a ClassInstantiability return type')]
    public function test_from_declares_a_ClassInstantiability_return_type(): void
    {
        $expected = ClassInstantiability::class;
        $method = (new ReflectionClass(GetClassInstantiability::class))
            ->getMethod('from');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $actual = $returnType->getName();
        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() behaviour
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function inputProvider(): array
    {
        $unknownClassString = 'StusDevKit\\MissingBitsKit\\Tests\\Fixtures'
            . '\\Reflection\\Instantiability\\ThisClassDoesNotExist';

        return [
            'a class with a public constructor' => [
                'a class with a public constructor',
                PublicCtorClass::class,
                'INSTANTIABLE',
            ],
            'a class with no declared constructor' => [
                'a class with no declared constructor',
                NoCtorClass::class,
                'INSTANTIABLE',
            ],
            'a readonly class' => [
                'a readonly class',
                ReadonlyClass::class,
                'INSTANTIABLE',
            ],
            'a PHP-internal class' => [
                'a PHP-internal class',
                \ArrayObject::class,
                'INSTANTIABLE',
            ],
            'an engine-restricted PHP-internal class (known reflection gap)' => [
                // `new Generator()` throws at runtime ("reserved for
                // internal use"), but reflection reports the class as
                // instantiable with no constructor - an engine-level
                // restriction that reflection does not expose. Our
                // inspector faithfully reports what reflection sees;
                // the limitation is documented in the class's Here Be
                // Dragons section. This row pins the known-imperfect
                // behaviour so it does not drift silently.
                'an engine-restricted PHP-internal class (known reflection gap)',
                \Generator::class,
                'INSTANTIABLE',
            ],
            'an unknown class-string' => [
                'an unknown class-string',
                $unknownClassString,
                'CLASS_DOES_NOT_EXIST',
            ],
            'an interface' => [
                'an interface',
                SampleInterface::class,
                'IS_INTERFACE',
            ],
            'a trait' => [
                'a trait',
                SampleTrait::class,
                'IS_TRAIT',
            ],
            'an enum' => [
                'an enum',
                SampleEnum::class,
                'IS_ENUM',
            ],
            'an abstract class' => [
                'an abstract class',
                AbstractSampleClass::class,
                'IS_ABSTRACT',
            ],
            'a class with a private constructor' => [
                'a class with a private constructor',
                PrivateCtorClass::class,
                'CONSTRUCTOR_NOT_PUBLIC',
            ],
            'a class with a protected constructor' => [
                'a class with a protected constructor',
                ProtectedCtorClass::class,
                'CONSTRUCTOR_NOT_PUBLIC',
            ],
        ];
    }

    /**
     * each row pins the mapping from an input shape to the
     * single ClassInstantiability case the inspector should
     * return. Together the rows enumerate every reason the
     * enum documents, plus the happy-path cases.
     */
    #[TestDox('::from() returns $expectedCaseName for $inputDescription')]
    #[DataProvider('inputProvider')]
    public function test_from_returns_expected_case_for_input(
        string $inputDescription,
        string $classname,
        string $expectedCaseName,
    ): void {
        // `$inputDescription` is carried for TestDox interpolation;
        // it does not drive the assertion.
        unset($inputDescription);

        // look up the expected enum case by name. `constant()` on a
        // Class::NAME string returns the case; assertInstanceOf
        // narrows the type for PHPStan.
        $expected = constant(ClassInstantiability::class . '::' . $expectedCaseName);
        $this->assertInstanceOf(ClassInstantiability::class, $expected);
        $actual = GetClassInstantiability::from($classname);
        $this->assertSame($expected, $actual);
    }
}
