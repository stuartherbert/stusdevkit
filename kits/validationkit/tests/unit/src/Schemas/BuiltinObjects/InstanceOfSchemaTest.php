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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\BuiltinObjects;

use ArrayObject;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use stdClass;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\BuiltinObjects\InstanceOfSchema;

#[TestDox(InstanceOfSchema::class)]
class InstanceOfSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\BuiltinObjects namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the published namespace is part of the contract —
        // moving it breaks every caller that wires the schema
        // by FQN.
        $reflection = new ReflectionClass(InstanceOfSchema::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\BuiltinObjects',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is not declared final so it can be extended by bespoke subclasses')]
    public function test_is_not_final(): void
    {
        // consistent with the rest of the schema family.
        $reflection = new ReflectionClass(InstanceOfSchema::class);

        $this->assertFalse($reflection->isFinal());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema to inherit the full parse pipeline')]
    public function test_extends_BaseSchema(): void
    {
        // defaults / catch / steps / pipe all come from BaseSchema.
        $reflection = new ReflectionClass(InstanceOfSchema::class);

        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares __construct and className as its own public methods')]
    public function test_declares_own_public_method_set(): void
    {
        // these two methods are the entire locally-declared
        // public API. Pinning the set catches accidental
        // surface changes.
        $reflection = new ReflectionClass(InstanceOfSchema::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === InstanceOfSchema::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame(['__construct', 'className'], $ownMethods);
    }

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        // callers wire the schema with named arguments, so
        // parameter names are public.
        $method = new ReflectionMethod(InstanceOfSchema::class, '__construct');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(['className', 'typeCheckError'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->className() returns the class or interface name stored at construction time')]
    public function test_className_returns_the_configured_class(): void
    {
        // the stored class string is what the schema uses for
        // the `instanceof` check — exposed so that exporters
        // and introspection tooling can reach it.
        $unit = new InstanceOfSchema(className: DateTimeInterface::class);

        $this->assertSame(DateTimeInterface::class, $unit->className());
    }

    #[TestDox('->parse() returns the same object when it is an instance of the configured class')]
    public function test_parse_accepts_exact_class_match(): void
    {
        // happy path — input is an instance of the configured
        // class, so it passes through unchanged.
        $input = new stdClass();
        $unit = new InstanceOfSchema(className: stdClass::class);

        $actual = $unit->parse($input);

        $this->assertSame($input, $actual);
    }

    #[TestDox('->parse() returns the same object when it implements the configured interface')]
    public function test_parse_accepts_interface_implementation(): void
    {
        // `instanceof` matches subclasses and interface
        // implementations, which is exactly the behaviour we
        // want from this schema.
        $input = new DateTimeImmutable('2026-04-19T00:00:00+00:00');
        $unit = new InstanceOfSchema(className: DateTimeInterface::class);

        $actual = $unit->parse($input);

        $this->assertSame($input, $actual);
    }

    #[TestDox('->parse() throws ValidationException when the object is not an instance of the configured class')]
    public function test_parse_rejects_unrelated_object(): void
    {
        // the input is an object, but not one that satisfies
        // `instanceof $className` — rejected.
        $unit = new InstanceOfSchema(className: DateTimeInterface::class);

        $this->expectException(ValidationException::class);
        $unit->parse(new ArrayObject());
    }

    #[TestDox('->parse() throws ValidationException when the input is a scalar')]
    public function test_parse_rejects_scalar_input(): void
    {
        // a string is not an object, let alone an instance of
        // the configured class — rejected.
        $unit = new InstanceOfSchema(className: stdClass::class);

        $this->expectException(ValidationException::class);
        $unit->parse('not an object');
    }

    #[TestDox('->parse() throws ValidationException when the input is null')]
    public function test_parse_rejects_null_input(): void
    {
        // null cannot be an instance of anything — rejected.
        $unit = new InstanceOfSchema(className: stdClass::class);

        $this->expectException(ValidationException::class);
        $unit->parse(null);
    }

    #[TestDox('->parse() throws ValidationException when the input is an array')]
    public function test_parse_rejects_array_input(): void
    {
        // an array is not an object — rejected.
        $unit = new InstanceOfSchema(className: stdClass::class);

        $this->expectException(ValidationException::class);
        $unit->parse(['key' => 'value']);
    }
}
