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

namespace StusDevKit\ValidationKit\Tests\Unit\Coercions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use StusDevKit\ValidationKit\Coercions\CoerceToUuid;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;

#[TestDox(CoerceToUuid::class)]
class CoerceToUuidTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Coercions namespace')]
    public function test_lives_in_coercions_namespace(): void
    {

        // this test pins the namespace of CoerceToUuid so that a rename
        // or move is caught as a deliberate API change

        $reflection = new ReflectionClass(CoerceToUuid::class);

        $actualNamespace = $reflection->getNamespaceName();

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Coercions',
            $actualNamespace,
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_declared_as_final_class(): void
    {

        // this test pins the class kind: CoerceToUuid must be a final
        // class, not an interface, trait, or an extensible class

        $reflection = new ReflectionClass(CoerceToUuid::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('implements ValueCoercion')]
    public function test_implements_value_coercion(): void
    {

        // this test pins the contract: CoerceToUuid must be usable
        // anywhere a ValueCoercion is expected

        $unit = new CoerceToUuid();

        $this->assertInstanceOf(ValueCoercion::class, $unit);
    }

    #[TestDox("declares ['coerce'] as its own public methods")]
    public function test_declares_coerce_as_own_public_method(): void
    {

        // this test pins the public API surface of CoerceToUuid:
        // only coerce() is defined on the class itself

        $reflection = new ReflectionClass(CoerceToUuid::class);

        // only methods declared on this class, not inherited ones
        $ownMethods = array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            fn(ReflectionMethod $m) => $m->getDeclaringClass()->getName()
                === CoerceToUuid::class,
        );

        $actualMethodNames = array_values(array_map(
            fn(ReflectionMethod $m) => $m->getName(),
            $ownMethods,
        ));
        sort($actualMethodNames);

        $this->assertSame(['coerce'], $actualMethodNames);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::coerce() is declared')]
    public function test_coerce_is_declared(): void
    {

        // this test pins the existence of the coerce() method on the
        // class itself, to catch accidental removal or rename

        $reflection = new ReflectionClass(CoerceToUuid::class);

        $this->assertTrue($reflection->hasMethod('coerce'));
    }

    #[TestDox('->coerce() is a public instance method')]
    public function test_coerce_is_public_instance_method(): void
    {

        // this test pins the visibility and binding of coerce():
        // it must be callable on an instance, from outside the class

        $method = new ReflectionMethod(CoerceToUuid::class, 'coerce');

        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->coerce() return type is mixed')]
    public function test_coerce_return_type_is_mixed(): void
    {

        // this test pins the declared return type of coerce():
        // the coercion may pass through any value unchanged, so the
        // return type is mixed by contract

        $method = new ReflectionMethod(CoerceToUuid::class, 'coerce');
        $returnType = $method->getReturnType();

        // keep phpstan happy
        //
        // a declared return type of `mixed` materialises as a
        // ReflectionNamedType; the assertion below guards the
        // narrowing before we call getName()
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox("->coerce() parameter names in order are ['data']")]
    public function test_coerce_parameter_names_in_order(): void
    {

        // this test pins the parameter names of coerce() in order,
        // because callers may use named arguments

        $method = new ReflectionMethod(CoerceToUuid::class, 'coerce');

        $actualParamNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(['data'], $actualParamNames);
    }

    #[TestDox("->coerce() parameter types are ['mixed']")]
    public function test_coerce_parameter_types(): void
    {

        // this test pins the declared parameter types of coerce():
        // data must accept any value, so its type is mixed

        $method = new ReflectionMethod(CoerceToUuid::class, 'coerce');

        // our return value
        $actualParamTypes = [];
        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();

            // keep phpstan happy
            //
            // every parameter on coerce() carries an explicit type
            // declaration; the assertion narrows ReflectionType to
            // ReflectionNamedType before we call getName()
            $this->assertInstanceOf(ReflectionNamedType::class, $type);

            $actualParamTypes[] = $type->getName();
        }

        $this->assertSame(['mixed'], $actualParamTypes);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() returns a UuidInterface when given a valid UUID string')]
    public function test_coerce_returns_uuid_for_valid_string(): void
    {

        // a fixed literal UUID keeps the expected value purely
        // declarative and rename-safe
        $inputString = '550e8400-e29b-41d4-a716-446655440000';

        $unit = new CoerceToUuid();

        $actualResult = $unit->coerce($inputString);

        $this->assertInstanceOf(UuidInterface::class, $actualResult);
        $this->assertSame($inputString, $actualResult->toString());
    }

    #[TestDox('->coerce() returns the same instance when given an already-constructed UuidInterface')]
    public function test_coerce_returns_same_instance_for_uuid_input(): void
    {

        $inputUuid = Uuid::fromString('550e8400-e29b-41d4-a716-446655440000');

        $unit = new CoerceToUuid();

        $actualResult = $unit->coerce($inputUuid);

        $this->assertSame($inputUuid, $actualResult);
    }

    #[TestDox('->coerce() returns the original string unchanged when given an unparseable string')]
    public function test_coerce_returns_unparseable_string_unchanged(): void
    {

        $inputString = 'not-a-uuid';

        $unit = new CoerceToUuid();

        $actualResult = $unit->coerce($inputString);

        $this->assertSame($inputString, $actualResult);
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function provideNonStringValues(): array
    {
        return [
            'int'       => [42],
            'array'     => [['a', 'b']],
            'null'      => [null],
            'object'    => [new \stdClass()],
        ];
    }

    #[DataProvider('provideNonStringValues')]
    #[TestDox('->coerce() returns non-string types unchanged')]
    public function test_coerce_returns_non_string_unchanged(
        mixed $inputValue,
    ): void {

        $unit = new CoerceToUuid();

        $actualResult = $unit->coerce($inputValue);

        $this->assertSame($inputValue, $actualResult);
    }
}
