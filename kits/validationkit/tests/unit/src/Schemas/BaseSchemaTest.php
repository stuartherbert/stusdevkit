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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\ParseResult;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;

#[TestDox(BaseSchema::class)]
class BaseSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract — every
        // concrete schema extends BaseSchema by FQN, so moving it
        // is a breaking change that must go through a major
        // version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\ValidationKit\\Schemas';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(BaseSchema::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as an abstract class')]
    public function test_is_an_abstract_class(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(BaseSchema::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isAbstract());
    }

    #[TestDox('implements ValidationSchema')]
    public function test_implements_ValidationSchema(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(BaseSchema::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares checkType() as abstract')]
    public function test_checkType_is_abstract(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // checkType() is the single extension point concrete
        // schemas must implement — marking it abstract is how
        // BaseSchema forces every subclass to supply type logic.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(BaseSchema::class))
            ->getMethod('checkType');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($method->isAbstract());
    }

    // ================================================================
    //
    // Introspection methods (own public surface beyond the
    // ValidationSchema contract)
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes getSteps() as a public method')]
    public function test_has_getSteps_method(): void
    {
        $reflection = new ReflectionClass(BaseSchema::class);
        $this->assertTrue($reflection->hasMethod('getSteps'));
        $this->assertTrue(
            $reflection->getMethod('getSteps')->isPublic(),
        );
    }

    #[TestDox('exposes hasDefaultValue() as a public method')]
    public function test_has_hasDefaultValue_method(): void
    {
        $reflection = new ReflectionClass(BaseSchema::class);
        $this->assertTrue(
            $reflection->hasMethod('hasDefaultValue'),
        );
        $this->assertTrue(
            $reflection->getMethod('hasDefaultValue')->isPublic(),
        );
    }

    #[TestDox('exposes getDefaultValue() as a public method')]
    public function test_has_getDefaultValue_method(): void
    {
        $reflection = new ReflectionClass(BaseSchema::class);
        $this->assertTrue(
            $reflection->hasMethod('getDefaultValue'),
        );
        $this->assertTrue(
            $reflection->getMethod('getDefaultValue')->isPublic(),
        );
    }

    #[TestDox('exposes hasCatchFallback() as a public method')]
    public function test_has_hasCatchFallback_method(): void
    {
        $reflection = new ReflectionClass(BaseSchema::class);
        $this->assertTrue(
            $reflection->hasMethod('hasCatchFallback'),
        );
        $this->assertTrue(
            $reflection->getMethod('hasCatchFallback')->isPublic(),
        );
    }

    #[TestDox('exposes getCatchFallback() as a public method')]
    public function test_has_getCatchFallback_method(): void
    {
        $reflection = new ReflectionClass(BaseSchema::class);
        $this->assertTrue(
            $reflection->hasMethod('getCatchFallback'),
        );
        $this->assertTrue(
            $reflection->getMethod('getCatchFallback')->isPublic(),
        );
    }

    #[TestDox('exposes maybePipeTarget() as a public method')]
    public function test_has_maybePipeTarget_method(): void
    {
        $reflection = new ReflectionClass(BaseSchema::class);
        $this->assertTrue(
            $reflection->hasMethod('maybePipeTarget'),
        );
        $this->assertTrue(
            $reflection->getMethod('maybePipeTarget')->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — ->parse() happy path via a concrete subclass
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() returns the validated value on a valid input')]
    public function test_parse_returns_valid_value(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new IntSchema();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('->parse() throws ValidationException on an invalid input')]
    public function test_parse_throws_on_invalid_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new IntSchema();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse('not-an-int');
    }

    #[TestDox('->safeParse() returns a successful ParseResult on a valid input')]
    public function test_safeParse_ok_on_valid_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new IntSchema();

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ParseResult::class, $result);
        $this->assertTrue($result->succeeded());
        $this->assertSame(42, $result->data());
    }

    #[TestDox('->safeParse() returns a failed ParseResult on an invalid input')]
    public function test_safeParse_fail_on_invalid_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new IntSchema();

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse('not-an-int');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // Behaviour — withDefault(), withCatch()
    //
    // ----------------------------------------------------------------

    #[TestDox('->withDefault() returns a new instance (immutable)')]
    public function test_withDefault_returns_new_instance(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new IntSchema();

        // ----------------------------------------------------------------
        // perform the change

        $clone = $schema->withDefault(99);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($schema, $clone);
    }

    #[TestDox('->withDefault() value is used when the input is null')]
    public function test_withDefault_substitutes_null(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = (new IntSchema())->withDefault(99);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(99, $actual);
    }

    #[TestDox('->hasDefaultValue() returns true after ->withDefault() is applied')]
    public function test_hasDefaultValue_true_after_withDefault(): void
    {
        $schema = (new IntSchema())->withDefault(99);
        $this->assertTrue($schema->hasDefaultValue());
    }

    #[TestDox('->getDefaultValue() returns the value passed to ->withDefault()')]
    public function test_getDefaultValue_returns_default(): void
    {
        $schema = (new IntSchema())->withDefault(99);
        $this->assertSame(99, $schema->getDefaultValue());
    }

    #[TestDox('->withCatch() returns a fallback when parse fails')]
    public function test_withCatch_returns_fallback_on_failure(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = (new IntSchema())->withCatch(0);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse('not-an-int');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actual);
    }

    #[TestDox('->hasCatchFallback() returns true after ->withCatch() is applied')]
    public function test_hasCatchFallback_true_after_withCatch(): void
    {
        $schema = (new IntSchema())->withCatch(0);
        $this->assertTrue($schema->hasCatchFallback());
    }

    // ================================================================
    //
    // Behaviour — encode skips default and catch
    //
    // ----------------------------------------------------------------

    #[TestDox('->encode() does not apply ->withDefault() fallback for null input')]
    public function test_encode_does_not_apply_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the encode path must not silently substitute values —
        // defaults are a decode-time convenience for deserialised
        // payloads; on encode we refuse null unless the schema
        // explicitly accepts it.

        // ----------------------------------------------------------------
        // setup your test

        $schema = (new IntSchema())->withDefault(99);

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->encode(null);
    }

    #[TestDox('->encode() does not apply ->withCatch() fallback on failure')]
    public function test_encode_does_not_apply_catch(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = (new IntSchema())->withCatch(0);

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->encode('not-an-int');
    }

    // ================================================================
    //
    // Behaviour — metadata accessors round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('->withTitle() round-trips through ->maybeTitle()')]
    public function test_withTitle_round_trips(): void
    {
        $schema = (new IntSchema())->withTitle('My Title');
        $this->assertSame('My Title', $schema->maybeTitle());
    }

    #[TestDox('->withDescription() round-trips through ->maybeDescription()')]
    public function test_withDescription_round_trips(): void
    {
        $schema = (new IntSchema())
            ->withDescription('A helpful description.');
        $this->assertSame(
            'A helpful description.',
            $schema->maybeDescription(),
        );
    }

    #[TestDox('->withSchemaId() round-trips through ->maybeSchemaId()')]
    public function test_withSchemaId_round_trips(): void
    {
        $schema = (new IntSchema())
            ->withSchemaId('https://example.com/schemas/int');
        $this->assertSame(
            'https://example.com/schemas/int',
            $schema->maybeSchemaId(),
        );
    }

    #[TestDox('->withRefTarget() round-trips through ->maybeRefTarget()')]
    public function test_withRefTarget_round_trips(): void
    {
        $schema = (new IntSchema())
            ->withRefTarget('#/definitions/MyInt');
        $this->assertSame(
            '#/definitions/MyInt',
            $schema->maybeRefTarget(),
        );
    }

    #[TestDox('->withExamples() round-trips through ->getExamples()')]
    public function test_withExamples_round_trips(): void
    {
        $schema = (new IntSchema())->withExamples([1, 2, 3]);
        $this->assertSame([1, 2, 3], $schema->getExamples());
    }

    #[TestDox('->withMetadata() round-trips through ->getMetadata()')]
    public function test_withMetadata_round_trips(): void
    {
        $schema = (new IntSchema())
            ->withMetadata(['key' => 'value']);
        $this->assertSame(
            ['key' => 'value'],
            $schema->getMetadata(),
        );
    }

    #[TestDox('->withDeprecated() round-trips through ->isDeprecated()')]
    public function test_withDeprecated_round_trips(): void
    {
        $schema = (new IntSchema())->withDeprecated();
        $this->assertTrue($schema->isDeprecated());
    }

    #[TestDox('->withReadOnly() round-trips through ->isReadOnly()')]
    public function test_withReadOnly_round_trips(): void
    {
        $schema = (new IntSchema())->withReadOnly();
        $this->assertTrue($schema->isReadOnly());
    }

    #[TestDox('->withWriteOnly() round-trips through ->isWriteOnly()')]
    public function test_withWriteOnly_round_trips(): void
    {
        $schema = (new IntSchema())->withWriteOnly();
        $this->assertTrue($schema->isWriteOnly());
    }

    // ================================================================
    //
    // Behaviour — withPipe() chains schemas
    //
    // ----------------------------------------------------------------

    #[TestDox('->withPipe() chains output into a follow-on schema')]
    public function test_withPipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        // pipe string through to int via a transform
        $piped = (new IntSchema());
        $schema = (new StringSchema())
            ->withCustomTransform(
                static fn(mixed $s) => is_scalar($s) ? (int) $s : 0,
            )
            ->withPipe($piped);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse('42');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('->maybePipeTarget() returns the pipe target set by ->withPipe()')]
    public function test_maybePipeTarget_returns_target(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $target = new IntSchema();
        $schema = (new StringSchema())->withPipe($target);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($target, $schema->maybePipeTarget());
    }
}
