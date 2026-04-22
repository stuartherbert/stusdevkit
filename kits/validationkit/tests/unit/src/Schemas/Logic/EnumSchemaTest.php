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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Logic;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Logic\EnumSchema;
use StusDevKit\ValidationKit\Tests\Unit\Schemas\Logic\Fixtures\EnumSchemaTestStatus;

#[TestDox(EnumSchema::class)]
class EnumSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Logic namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the published namespace is part of the contract —
        // callers wire the schema by FQN, so moving it breaks
        // them.
        $reflection = new ReflectionClass(EnumSchema::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Logic',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is not declared final so it can be extended by bespoke subclasses')]
    public function test_is_not_final(): void
    {
        // matches the rest of the schema family — subclasses
        // can specialise it.
        $reflection = new ReflectionClass(EnumSchema::class);

        $this->assertFalse($reflection->isFinal());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema to inherit the full parse pipeline')]
    public function test_extends_BaseSchema(): void
    {
        // defaults / catch / steps all come from BaseSchema.
        $reflection = new ReflectionClass(EnumSchema::class);

        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares __construct, maybeAllowedValues and maybeEnumClass as its own public methods')]
    public function test_declares_own_public_method_set(): void
    {
        // these three methods are the entire locally-declared
        // public API. The `maybe` prefixes signal the nullable
        // returns that pair with the two construction modes.
        $reflection = new ReflectionClass(EnumSchema::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === EnumSchema::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame(
            ['__construct', 'maybeAllowedValues', 'maybeEnumClass'],
            $ownMethods,
        );
    }

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        // callers use named arguments, so parameter names are
        // part of the public contract.
        $method = new ReflectionMethod(EnumSchema::class, '__construct');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(
            ['valuesOrEnumClass', 'typeCheckError'],
            $paramNames,
        );
    }

    // ================================================================
    //
    // Behaviour: string literal mode
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeAllowedValues() returns the list of allowed values in string-literal mode')]
    public function test_maybeAllowedValues_returns_list_in_literal_mode(): void
    {
        // literal mode stores the allowed values as a list.
        $unit = new EnumSchema(['active', 'inactive']);

        $this->assertSame(['active', 'inactive'], $unit->maybeAllowedValues());
    }

    #[TestDox('->maybeEnumClass() returns null in string-literal mode')]
    public function test_maybeEnumClass_returns_null_in_literal_mode(): void
    {
        // literal mode is not PHP-enum mode — the class slot is
        // empty.
        $unit = new EnumSchema(['active', 'inactive']);

        $this->assertNull($unit->maybeEnumClass());
    }

    #[TestDox('->parse() returns the value unchanged when it matches an allowed literal')]
    public function test_parse_accepts_allowed_literal_value(): void
    {
        // happy path — value is in the allowed set, returned
        // as-is.
        $unit = new EnumSchema(['active', 'inactive']);

        $actual = $unit->parse('active');

        $this->assertSame('active', $actual);
    }

    #[TestDox('->parse() throws ValidationException when the value is not in the allowed literal set')]
    public function test_parse_rejects_disallowed_literal_value(): void
    {
        // value is a string but not one of the allowed ones —
        // caller gets a validation failure.
        $unit = new EnumSchema(['active', 'inactive']);

        $this->expectException(ValidationException::class);
        $unit->parse('deleted');
    }

    #[TestDox('->parse() throws ValidationException when the input is null in literal mode')]
    public function test_parse_rejects_null_in_literal_mode(): void
    {
        // null is never a valid enum value — the schema opts
        // in to receiving null so that it can report the error.
        $unit = new EnumSchema(['active', 'inactive']);

        $this->expectException(ValidationException::class);
        $unit->parse(null);
    }

    // ================================================================
    //
    // Behaviour: PHP BackedEnum mode
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeEnumClass() returns the BackedEnum class name in enum mode')]
    public function test_maybeEnumClass_returns_class_name_in_enum_mode(): void
    {
        // enum mode stores the class string so exporters can
        // introspect it.
        $unit = new EnumSchema(EnumSchemaTestStatus::class);

        $this->assertSame(
            EnumSchemaTestStatus::class,
            $unit->maybeEnumClass(),
        );
    }

    #[TestDox('->maybeAllowedValues() returns null in PHP BackedEnum mode')]
    public function test_maybeAllowedValues_returns_null_in_enum_mode(): void
    {
        // enum mode is not literal mode — the allowed-values
        // slot is empty.
        $unit = new EnumSchema(EnumSchemaTestStatus::class);

        $this->assertNull($unit->maybeAllowedValues());
    }

    #[TestDox('->parse() returns the BackedEnum case for a known backing value')]
    public function test_parse_returns_enum_case_for_known_backing_value(): void
    {
        // enum mode promotes the input value into the matching
        // enum case, so callers work with the enum rather than
        // the raw string.
        $unit = new EnumSchema(EnumSchemaTestStatus::class);

        $actual = $unit->parse('active');

        $this->assertSame(EnumSchemaTestStatus::ACTIVE, $actual);
    }

    #[TestDox('->parse() throws ValidationException for an unknown backing value in enum mode')]
    public function test_parse_rejects_unknown_backing_value(): void
    {
        // no enum case has this backing value — caller gets a
        // validation failure.
        $unit = new EnumSchema(EnumSchemaTestStatus::class);

        $this->expectException(ValidationException::class);
        $unit->parse('deleted');
    }

    #[TestDox('->parse() throws ValidationException for non-scalar input in enum mode')]
    public function test_parse_rejects_non_scalar_input_in_enum_mode(): void
    {
        // BackedEnum::tryFrom() only accepts string|int — other
        // types cannot possibly be an enum case.
        $unit = new EnumSchema(EnumSchemaTestStatus::class);

        $this->expectException(ValidationException::class);
        $unit->parse(['not a scalar']);
    }
}
