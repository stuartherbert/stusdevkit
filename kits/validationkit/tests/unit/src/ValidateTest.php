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

namespace StusDevKit\ValidationKit\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Schemas\BuiltinObjects\DateTimeInterfaceSchema;
use StusDevKit\ValidationKit\Schemas\BuiltinObjects\InstanceOfSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\AssocArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\BooleanSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\FloatSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\LiteralSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\MixedSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullableSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullishSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NumberSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ObjectSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\OptionalSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Codec;
use StusDevKit\ValidationKit\Schemas\Collections\RecordSchema;
use StusDevKit\ValidationKit\Schemas\Collections\TupleSchema;
use StusDevKit\ValidationKit\Schemas\DevKit\WhenSchema;
use StusDevKit\ValidationKit\Schemas\LazySchema;
use StusDevKit\ValidationKit\Schemas\Logic\AllOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\ConditionalSchema;
use StusDevKit\ValidationKit\Schemas\Logic\DiscriminatedAnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\EnumSchema;
use StusDevKit\ValidationKit\Schemas\Logic\NotSchema;
use StusDevKit\ValidationKit\Schemas\Logic\OneOfSchema;
use StusDevKit\ValidationKit\Schemas\UuidSchema;
use StusDevKit\ValidationKit\Transformers\CallableTransformer;
use StusDevKit\ValidationKit\Transformers\CustomConstraint;
use StusDevKit\ValidationKit\Validate;

/**
 * Contract + behaviour tests for Validate.
 *
 * Validate is the library's single-entry factory: it owns
 * the public vocabulary for describing shapes (primitives,
 * collections, logic combinators, specialised schemas,
 * codecs, and reusable constraint/transformer wrappers).
 * Because every caller reaches the library through a
 * Validate::something() call, the return-type mapping is
 * part of the public contract and must be pinned by test
 * rather than left to docblock inspection. The factory is
 * static-only; the constructor is private so the class can
 * never be instantiated. A data-provider covers the
 * zero-argument factories as a set, while factories that
 * need real arguments (collection/logic/specialised) are
 * covered one-per-method so the wiring of each parameter is
 * visible in the test name.
 */
#[TestDox(Validate::class)]
class ValidateTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the namespace is part of the published API contract;
        // callers type it out in `use` statements, so a rename
        // would be a breaking change we want a test to catch.
        $expected = 'StusDevKit\\ValidationKit';

        $actual = (new ReflectionClass(Validate::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a final class')]
    public function test_is_final(): void
    {
        // Validate is a static-only factory; subclassing it
        // would only encourage abuse, so the class is sealed.
        $reflection = new ReflectionClass(Validate::class);

        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('has a private constructor so it cannot be instantiated')]
    public function test_constructor_is_private(): void
    {
        // the factory is purely static; the private
        // constructor enforces that callers never hold a
        // Validate instance.
        $reflection = new ReflectionClass(Validate::class);
        $ctor = $reflection->getConstructor();

        $this->assertNotNull($ctor);
        $this->assertTrue($ctor->isPrivate());
    }

    // ================================================================
    //
    // Zero-argument factories
    //
    // ----------------------------------------------------------------

    /**
     * @return list<array{string, class-string<ValidationSchema<mixed>>}>
     */
    public static function provideZeroArgumentFactories(): array
    {
        // each row lists the factory method name and the exact
        // class it is contracted to return. literal class
        // constants survive renames; string names would not.
        return [
            ['string',  StringSchema::class],
            ['int',     IntSchema::class],
            ['float',   FloatSchema::class],
            ['number',  NumberSchema::class],
            ['boolean', BooleanSchema::class],
            ['null',    NullSchema::class],
            ['mixed',   MixedSchema::class],
            ['uuid',    UuidSchema::class],
            ['dateTime', DateTimeInterfaceSchema::class],
            ['when',    WhenSchema::class],
        ];
    }

    #[DataProvider('provideZeroArgumentFactories')]
    #[TestDox('::$method() returns a $schemaClass')]
    public function test_zero_argument_factory_returns_expected_schema(
        string $method,
        string $schemaClass,
    ): void {
        // exercise the named factory without arguments and
        // confirm the concrete return type matches the
        // documented schema class.
        $schema = Validate::$method();

        /** @phpstan-ignore argument.type */
        $this->assertInstanceOf($schemaClass, $schema);
    }

    // ================================================================
    //
    // Nullability wrappers
    //
    // ----------------------------------------------------------------

    #[TestDox('::nullable() wraps a schema in a NullableSchema')]
    public function test_nullable_returns_nullable_schema(): void
    {
        // nullable() is a wrapper, so we need an inner schema
        // to wrap; any concrete schema suffices.
        $inner = Validate::string();

        $schema = Validate::nullable($inner);

        $this->assertInstanceOf(NullableSchema::class, $schema);
    }

    #[TestDox('::optional() wraps a schema in an OptionalSchema')]
    public function test_optional_returns_optional_schema(): void
    {
        $inner = Validate::string();

        $schema = Validate::optional($inner);

        $this->assertInstanceOf(OptionalSchema::class, $schema);
    }

    #[TestDox('::nullish() wraps a schema in a NullishSchema')]
    public function test_nullish_returns_nullish_schema(): void
    {
        $inner = Validate::string();

        $schema = Validate::nullish($inner);

        $this->assertInstanceOf(NullishSchema::class, $schema);
    }

    // ================================================================
    //
    // Literal factory
    //
    // ----------------------------------------------------------------

    #[TestDox('::literal() returns a LiteralSchema')]
    public function test_literal_returns_literal_schema(): void
    {
        // literal() carries a value; we pick a string so the
        // schema is trivially constructable without touching
        // type coercion logic.
        $schema = Validate::literal('active');

        $this->assertInstanceOf(LiteralSchema::class, $schema);
    }

    // ================================================================
    //
    // Collection factories
    //
    // ----------------------------------------------------------------

    #[TestDox('::array() returns an ArraySchema')]
    public function test_array_returns_array_schema(): void
    {
        // array() needs an element schema describing each
        // member; a string element is the simplest case.
        $schema = Validate::array(Validate::string());

        $this->assertInstanceOf(ArraySchema::class, $schema);
    }

    #[TestDox('::assocArray() returns an AssocArraySchema')]
    public function test_assocArray_returns_assoc_array_schema(): void
    {
        // assocArray() validates a fixed-shape map; a
        // single-key shape is enough to exercise construction.
        $schema = Validate::assocArray(['name' => Validate::string()]);

        $this->assertInstanceOf(AssocArraySchema::class, $schema);
    }

    #[TestDox('::object() returns an ObjectSchema')]
    public function test_object_returns_object_schema(): void
    {
        // object() shares shape semantics with assocArray() but
        // returns a distinct schema class for object-style
        // payloads.
        $schema = Validate::object(['name' => Validate::string()]);

        $this->assertInstanceOf(ObjectSchema::class, $schema);
    }

    #[TestDox('::record() returns a RecordSchema')]
    public function test_record_returns_record_schema(): void
    {
        // record() validates key/value pairs where both sides
        // have schemas; string keys with int values covers the
        // construction path.
        $schema = Validate::record(
            key: Validate::string(),
            value: Validate::int(),
        );

        $this->assertInstanceOf(RecordSchema::class, $schema);
    }

    #[TestDox('::tuple() returns a TupleSchema')]
    public function test_tuple_returns_tuple_schema(): void
    {
        // tuple() takes an ordered list of per-position
        // schemas; a two-position list shows both slots are
        // accepted.
        $schema = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        $this->assertInstanceOf(TupleSchema::class, $schema);
    }

    // ================================================================
    //
    // Logic factories
    //
    // ----------------------------------------------------------------

    #[TestDox('::anyOf() returns an AnyOfSchema')]
    public function test_anyOf_returns_anyOf_schema(): void
    {
        $schema = Validate::anyOf([
            Validate::string(),
            Validate::int(),
        ]);

        $this->assertInstanceOf(AnyOfSchema::class, $schema);
    }

    #[TestDox('::allOf() returns an AllOfSchema')]
    public function test_allOf_returns_allOf_schema(): void
    {
        $schema = Validate::allOf([
            Validate::string(),
            Validate::string(),
        ]);

        $this->assertInstanceOf(AllOfSchema::class, $schema);
    }

    #[TestDox('::oneOf() returns a OneOfSchema')]
    public function test_oneOf_returns_oneOf_schema(): void
    {
        $schema = Validate::oneOf([
            Validate::string(),
            Validate::int(),
        ]);

        $this->assertInstanceOf(OneOfSchema::class, $schema);
    }

    #[TestDox('::not() returns a NotSchema')]
    public function test_not_returns_not_schema(): void
    {
        $schema = Validate::not(Validate::string());

        $this->assertInstanceOf(NotSchema::class, $schema);
    }

    #[TestDox('::discriminatedAnyOf() returns a DiscriminatedAnyOfSchema')]
    public function test_discriminatedAnyOf_returns_expected_schema(): void
    {
        // discriminatedAnyOf() needs a non-empty discriminator
        // key and a list of object schemas; a minimal
        // two-branch union is enough to construct.
        $schema = Validate::discriminatedAnyOf(
            discriminator: 'type',
            schemas: [
                Validate::object(['type' => Validate::literal('a')]),
                Validate::object(['type' => Validate::literal('b')]),
            ],
        );

        $this->assertInstanceOf(DiscriminatedAnyOfSchema::class, $schema);
    }

    #[TestDox('::conditional() returns a ConditionalSchema')]
    public function test_conditional_returns_conditional_schema(): void
    {
        // conditional() requires an if-schema; then/else are
        // optional. passing only the required slot exercises
        // the narrowest construction path.
        $schema = Validate::conditional(if: Validate::string());

        $this->assertInstanceOf(ConditionalSchema::class, $schema);
    }

    #[TestDox('::enum() returns an EnumSchema when given a list of values')]
    public function test_enum_with_values_returns_enum_schema(): void
    {
        // enum() has two modes; the list-of-strings mode is
        // the one that does not require a real BackedEnum
        // fixture on disk.
        $schema = Validate::enum(['draft', 'published']);

        $this->assertInstanceOf(EnumSchema::class, $schema);
    }

    // ================================================================
    //
    // Specialised factories
    //
    // ----------------------------------------------------------------

    #[TestDox('::lazy() returns a LazySchema')]
    public function test_lazy_returns_lazy_schema(): void
    {
        // lazy() defers schema construction; the closure will
        // not be called by the factory itself, so any closure
        // matching the expected signature is acceptable here.
        $schema = Validate::lazy(fn() => Validate::string());

        $this->assertInstanceOf(LazySchema::class, $schema);
    }

    #[TestDox('::instanceOf() returns an InstanceOfSchema')]
    public function test_instanceOf_returns_instanceOf_schema(): void
    {
        // instanceOf() accepts any class-string; TestCase is
        // guaranteed to exist in the test environment.
        $schema = Validate::instanceOf(TestCase::class);

        $this->assertInstanceOf(InstanceOfSchema::class, $schema);
    }

    // ================================================================
    //
    // Codec factory
    //
    // ----------------------------------------------------------------

    #[TestDox('::codec() returns a Codec bridging input and output schemas')]
    public function test_codec_returns_codec(): void
    {
        // codec() is the only factory that takes two schemas
        // plus two closures; the identity-style decoder/encoder
        // exercise the wiring without depending on domain
        // logic.
        $schema = Validate::codec(
            input: Validate::string(),
            output: Validate::string(),
            decode: fn(string $s): string => $s,
            encode: fn(string $s): string => $s,
        );

        $this->assertInstanceOf(Codec::class, $schema);
    }

    // ================================================================
    //
    // Reusable constraints and transformers
    //
    // ----------------------------------------------------------------

    #[TestDox('::constraintFrom() returns a ValidationConstraint (CustomConstraint)')]
    public function test_constraintFrom_returns_custom_constraint(): void
    {
        // the callable is only the wrapped behaviour; the
        // factory contract is that the returned object
        // satisfies the public ValidationConstraint interface
        // and uses the CustomConstraint wrapper internally.
        $constraint = Validate::constraintFrom(
            fn(mixed $data): ?string => null,
        );

        $this->assertInstanceOf(ValidationConstraint::class, $constraint);
        $this->assertInstanceOf(CustomConstraint::class, $constraint);
    }

    #[TestDox('::transformerFrom() returns a ValueTransformer (CallableTransformer)')]
    public function test_transformerFrom_returns_callable_transformer(): void
    {
        // same two-level assertion as constraintFrom(): the
        // public contract is the interface; the concrete
        // wrapper type is also pinned because callers may
        // legitimately switch on it.
        $transformer = Validate::transformerFrom(
            fn(mixed $data): mixed => $data,
        );

        $this->assertInstanceOf(ValueTransformer::class, $transformer);
        $this->assertInstanceOf(CallableTransformer::class, $transformer);
    }
}
