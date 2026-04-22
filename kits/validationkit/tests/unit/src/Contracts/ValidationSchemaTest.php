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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ParseResult;

#[TestDox(ValidationSchema::class)]
class ValidationSchemaTest extends TestCase
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
            ValidationSchema::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            ValidationSchema::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes the expected public method set')]
    public function test_exposes_the_expected_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ValidationSchema is a wide interface - every
        // implementer must supply all of these methods, and every
        // downstream consumer relies on the set being exactly
        // this list. Pinning the set by enumeration means a
        // silent addition or removal shows up as a named diff
        // instead of breaking consumers at runtime.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'decode',
            'encode',
            'encodeWithContext',
            'getExamples',
            'getMetadata',
            'isDeprecated',
            'isReadOnly',
            'isWriteOnly',
            'maybeDescription',
            'maybeRefTarget',
            'maybeSchemaId',
            'maybeTitle',
            'parse',
            'parseWithContext',
            'safeDecode',
            'safeEncode',
            'safeParse',
            'withCatch',
            'withConstraint',
            'withCustomConstraint',
            'withCustomTransform',
            'withDefault',
            'withDeprecated',
            'withDescription',
            'withExamples',
            'withMetadata',
            'withNormaliser',
            'withPipe',
            'withReadOnly',
            'withRefTarget',
            'withSchemaId',
            'withTitle',
            'withTransformer',
            'withWriteOnly',
        ];
        $reflection = new ReflectionClass(
            ValidationSchema::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Method shape - existence & visibility
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string}>
     */
    public static function methodNameProvider(): array
    {
        $names = [
            'parse',
            'safeParse',
            'decode',
            'safeDecode',
            'encode',
            'safeEncode',
            'withConstraint',
            'withNormaliser',
            'withTransformer',
            'withCustomTransform',
            'withCustomConstraint',
            'withPipe',
            'withCatch',
            'withDefault',
            'withRefTarget',
            'maybeRefTarget',
            'withSchemaId',
            'maybeSchemaId',
            'withTitle',
            'maybeTitle',
            'withDescription',
            'maybeDescription',
            'withExamples',
            'getExamples',
            'withDeprecated',
            'isDeprecated',
            'withReadOnly',
            'isReadOnly',
            'withWriteOnly',
            'isWriteOnly',
            'withMetadata',
            'getMetadata',
            'parseWithContext',
            'encodeWithContext',
        ];

        $out = [];
        foreach ($names as $name) {
            $out[$name] = [$name];
        }
        return $out;
    }

    #[TestDox('->$method() is declared')]
    #[DataProvider('methodNameProvider')]
    public function test_method_is_declared(string $method): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            ValidationSchema::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod($method);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->$method() is public')]
    #[DataProvider('methodNameProvider')]
    public function test_method_is_public(string $method): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflected = (new ReflectionClass(
            ValidationSchema::class,
        ))->getMethod($method);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflected->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // Core Validation - method signatures
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() declares $data as its only parameter')]
    public function test_parse_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'parse');
    }

    #[TestDox('->parse() declares $data as mixed')]
    public function test_parse_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'parse', 0);
    }

    #[TestDox('->parse() returns mixed')]
    public function test_parse_returns_mixed(): void
    {
        $this->assertSameReturnType('mixed', 'parse');
    }

    #[TestDox('->safeParse() declares $data as its only parameter')]
    public function test_safeParse_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'safeParse');
    }

    #[TestDox('->safeParse() declares $data as mixed')]
    public function test_safeParse_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'safeParse', 0);
    }

    #[TestDox('->safeParse() returns ParseResult')]
    public function test_safeParse_returns_ParseResult(): void
    {
        $this->assertSameReturnType(ParseResult::class, 'safeParse');
    }

    #[TestDox('->decode() declares $data as its only parameter')]
    public function test_decode_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'decode');
    }

    #[TestDox('->decode() declares $data as mixed')]
    public function test_decode_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'decode', 0);
    }

    #[TestDox('->decode() returns mixed')]
    public function test_decode_returns_mixed(): void
    {
        $this->assertSameReturnType('mixed', 'decode');
    }

    #[TestDox('->safeDecode() declares $data as its only parameter')]
    public function test_safeDecode_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'safeDecode');
    }

    #[TestDox('->safeDecode() declares $data as mixed')]
    public function test_safeDecode_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'safeDecode', 0);
    }

    #[TestDox('->safeDecode() returns ParseResult')]
    public function test_safeDecode_returns_ParseResult(): void
    {
        $this->assertSameReturnType(ParseResult::class, 'safeDecode');
    }

    #[TestDox('->encode() declares $data as its only parameter')]
    public function test_encode_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'encode');
    }

    #[TestDox('->encode() declares $data as mixed')]
    public function test_encode_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'encode', 0);
    }

    #[TestDox('->encode() returns mixed')]
    public function test_encode_returns_mixed(): void
    {
        $this->assertSameReturnType('mixed', 'encode');
    }

    #[TestDox('->safeEncode() declares $data as its only parameter')]
    public function test_safeEncode_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'safeEncode');
    }

    #[TestDox('->safeEncode() declares $data as mixed')]
    public function test_safeEncode_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'safeEncode', 0);
    }

    #[TestDox('->safeEncode() returns ParseResult')]
    public function test_safeEncode_returns_ParseResult(): void
    {
        $this->assertSameReturnType(ParseResult::class, 'safeEncode');
    }

    // ================================================================
    //
    // Builder Methods - method signatures
    //
    // ----------------------------------------------------------------

    #[TestDox('->withConstraint() declares $constraint as its only parameter')]
    public function test_withConstraint_declares_constraint_parameter(): void
    {
        $this->assertSameParameters(['constraint'], 'withConstraint');
    }

    #[TestDox('->withConstraint() declares $constraint as ValidationConstraint')]
    public function test_withConstraint_constraint_type(): void
    {
        $this->assertSameParameterType(
            ValidationConstraint::class,
            'withConstraint',
            0,
        );
    }

    #[TestDox('->withConstraint() returns static')]
    public function test_withConstraint_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withConstraint');
    }

    #[TestDox('->withNormaliser() declares $normaliser as its only parameter')]
    public function test_withNormaliser_declares_normaliser_parameter(): void
    {
        $this->assertSameParameters(['normaliser'], 'withNormaliser');
    }

    #[TestDox('->withNormaliser() declares $normaliser as ValueTransformer')]
    public function test_withNormaliser_normaliser_type(): void
    {
        $this->assertSameParameterType(
            ValueTransformer::class,
            'withNormaliser',
            0,
        );
    }

    #[TestDox('->withNormaliser() returns static')]
    public function test_withNormaliser_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withNormaliser');
    }

    #[TestDox('->withTransformer() declares $transformer as its only parameter')]
    public function test_withTransformer_declares_transformer_parameter(): void
    {
        $this->assertSameParameters(['transformer'], 'withTransformer');
    }

    #[TestDox('->withTransformer() declares $transformer as ValueTransformer')]
    public function test_withTransformer_transformer_type(): void
    {
        $this->assertSameParameterType(
            ValueTransformer::class,
            'withTransformer',
            0,
        );
    }

    #[TestDox('->withTransformer() returns static')]
    public function test_withTransformer_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withTransformer');
    }

    #[TestDox('->withCustomTransform() declares $fn as its only parameter')]
    public function test_withCustomTransform_declares_fn_parameter(): void
    {
        $this->assertSameParameters(['fn'], 'withCustomTransform');
    }

    #[TestDox('->withCustomTransform() declares $fn as callable')]
    public function test_withCustomTransform_fn_type(): void
    {
        $this->assertSameParameterType(
            'callable',
            'withCustomTransform',
            0,
        );
    }

    #[TestDox('->withCustomTransform() returns static')]
    public function test_withCustomTransform_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withCustomTransform');
    }

    #[TestDox('->withCustomConstraint() declares $fn as its only parameter')]
    public function test_withCustomConstraint_declares_fn_parameter(): void
    {
        $this->assertSameParameters(['fn'], 'withCustomConstraint');
    }

    #[TestDox('->withCustomConstraint() declares $fn as callable')]
    public function test_withCustomConstraint_fn_type(): void
    {
        $this->assertSameParameterType(
            'callable',
            'withCustomConstraint',
            0,
        );
    }

    #[TestDox('->withCustomConstraint() returns static')]
    public function test_withCustomConstraint_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withCustomConstraint');
    }

    #[TestDox('->withPipe() declares $schema as its only parameter')]
    public function test_withPipe_declares_schema_parameter(): void
    {
        $this->assertSameParameters(['schema'], 'withPipe');
    }

    #[TestDox('->withPipe() declares $schema as ValidationSchema')]
    public function test_withPipe_schema_type(): void
    {
        $this->assertSameParameterType(
            ValidationSchema::class,
            'withPipe',
            0,
        );
    }

    #[TestDox('->withPipe() returns static')]
    public function test_withPipe_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withPipe');
    }

    #[TestDox('->withCatch() declares $fallback as its only parameter')]
    public function test_withCatch_declares_fallback_parameter(): void
    {
        $this->assertSameParameters(['fallback'], 'withCatch');
    }

    #[TestDox('->withCatch() declares $fallback as mixed')]
    public function test_withCatch_fallback_type(): void
    {
        $this->assertSameParameterType('mixed', 'withCatch', 0);
    }

    #[TestDox('->withCatch() returns static')]
    public function test_withCatch_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withCatch');
    }

    #[TestDox('->withDefault() declares $value as its only parameter')]
    public function test_withDefault_declares_value_parameter(): void
    {
        $this->assertSameParameters(['value'], 'withDefault');
    }

    #[TestDox('->withDefault() declares $value as mixed')]
    public function test_withDefault_value_type(): void
    {
        $this->assertSameParameterType('mixed', 'withDefault', 0);
    }

    #[TestDox('->withDefault() returns static')]
    public function test_withDefault_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withDefault');
    }

    // ================================================================
    //
    // Metadata - method signatures
    //
    // ----------------------------------------------------------------

    #[TestDox('->withRefTarget() declares $ref as its only parameter')]
    public function test_withRefTarget_declares_ref_parameter(): void
    {
        $this->assertSameParameters(['ref'], 'withRefTarget');
    }

    #[TestDox('->withRefTarget() declares $ref as string')]
    public function test_withRefTarget_ref_type(): void
    {
        $this->assertSameParameterType('string', 'withRefTarget', 0);
    }

    #[TestDox('->withRefTarget() returns static')]
    public function test_withRefTarget_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withRefTarget');
    }

    #[TestDox('->maybeRefTarget() takes no parameters')]
    public function test_maybeRefTarget_no_parameters(): void
    {
        $this->assertSameParameters([], 'maybeRefTarget');
    }

    #[TestDox('->maybeRefTarget() returns ?string')]
    public function test_maybeRefTarget_returns_nullable_string(): void
    {
        $this->assertSameReturnType('string', 'maybeRefTarget');
        $this->assertReturnTypeAllowsNull('maybeRefTarget');
    }

    #[TestDox('->withSchemaId() declares $id as its only parameter')]
    public function test_withSchemaId_declares_id_parameter(): void
    {
        $this->assertSameParameters(['id'], 'withSchemaId');
    }

    #[TestDox('->withSchemaId() declares $id as string')]
    public function test_withSchemaId_id_type(): void
    {
        $this->assertSameParameterType('string', 'withSchemaId', 0);
    }

    #[TestDox('->withSchemaId() returns static')]
    public function test_withSchemaId_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withSchemaId');
    }

    #[TestDox('->maybeSchemaId() takes no parameters')]
    public function test_maybeSchemaId_no_parameters(): void
    {
        $this->assertSameParameters([], 'maybeSchemaId');
    }

    #[TestDox('->maybeSchemaId() returns ?string')]
    public function test_maybeSchemaId_returns_nullable_string(): void
    {
        $this->assertSameReturnType('string', 'maybeSchemaId');
        $this->assertReturnTypeAllowsNull('maybeSchemaId');
    }

    #[TestDox('->withTitle() declares $text as its only parameter')]
    public function test_withTitle_declares_text_parameter(): void
    {
        $this->assertSameParameters(['text'], 'withTitle');
    }

    #[TestDox('->withTitle() declares $text as string')]
    public function test_withTitle_text_type(): void
    {
        $this->assertSameParameterType('string', 'withTitle', 0);
    }

    #[TestDox('->withTitle() returns static')]
    public function test_withTitle_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withTitle');
    }

    #[TestDox('->maybeTitle() takes no parameters')]
    public function test_maybeTitle_no_parameters(): void
    {
        $this->assertSameParameters([], 'maybeTitle');
    }

    #[TestDox('->maybeTitle() returns ?string')]
    public function test_maybeTitle_returns_nullable_string(): void
    {
        $this->assertSameReturnType('string', 'maybeTitle');
        $this->assertReturnTypeAllowsNull('maybeTitle');
    }

    #[TestDox('->withDescription() declares $text as its only parameter')]
    public function test_withDescription_declares_text_parameter(): void
    {
        $this->assertSameParameters(['text'], 'withDescription');
    }

    #[TestDox('->withDescription() declares $text as string')]
    public function test_withDescription_text_type(): void
    {
        $this->assertSameParameterType('string', 'withDescription', 0);
    }

    #[TestDox('->withDescription() returns static')]
    public function test_withDescription_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withDescription');
    }

    #[TestDox('->maybeDescription() takes no parameters')]
    public function test_maybeDescription_no_parameters(): void
    {
        $this->assertSameParameters([], 'maybeDescription');
    }

    #[TestDox('->maybeDescription() returns ?string')]
    public function test_maybeDescription_returns_nullable_string(): void
    {
        $this->assertSameReturnType('string', 'maybeDescription');
        $this->assertReturnTypeAllowsNull('maybeDescription');
    }

    #[TestDox('->withExamples() declares $values as its only parameter')]
    public function test_withExamples_declares_values_parameter(): void
    {
        $this->assertSameParameters(['values'], 'withExamples');
    }

    #[TestDox('->withExamples() declares $values as array')]
    public function test_withExamples_values_type(): void
    {
        $this->assertSameParameterType('array', 'withExamples', 0);
    }

    #[TestDox('->withExamples() returns static')]
    public function test_withExamples_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withExamples');
    }

    #[TestDox('->getExamples() takes no parameters')]
    public function test_getExamples_no_parameters(): void
    {
        $this->assertSameParameters([], 'getExamples');
    }

    #[TestDox('->getExamples() returns array')]
    public function test_getExamples_returns_array(): void
    {
        $this->assertSameReturnType('array', 'getExamples');
    }

    #[TestDox('->withDeprecated() declares $deprecated as its only parameter')]
    public function test_withDeprecated_declares_deprecated_parameter(): void
    {
        $this->assertSameParameters(['deprecated'], 'withDeprecated');
    }

    #[TestDox('->withDeprecated() declares $deprecated as bool')]
    public function test_withDeprecated_deprecated_type(): void
    {
        $this->assertSameParameterType('bool', 'withDeprecated', 0);
    }

    #[TestDox('->withDeprecated() returns static')]
    public function test_withDeprecated_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withDeprecated');
    }

    #[TestDox('->isDeprecated() takes no parameters')]
    public function test_isDeprecated_no_parameters(): void
    {
        $this->assertSameParameters([], 'isDeprecated');
    }

    #[TestDox('->isDeprecated() returns bool')]
    public function test_isDeprecated_returns_bool(): void
    {
        $this->assertSameReturnType('bool', 'isDeprecated');
    }

    #[TestDox('->withReadOnly() declares $readOnly as its only parameter')]
    public function test_withReadOnly_declares_readOnly_parameter(): void
    {
        $this->assertSameParameters(['readOnly'], 'withReadOnly');
    }

    #[TestDox('->withReadOnly() declares $readOnly as bool')]
    public function test_withReadOnly_readOnly_type(): void
    {
        $this->assertSameParameterType('bool', 'withReadOnly', 0);
    }

    #[TestDox('->withReadOnly() returns static')]
    public function test_withReadOnly_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withReadOnly');
    }

    #[TestDox('->isReadOnly() takes no parameters')]
    public function test_isReadOnly_no_parameters(): void
    {
        $this->assertSameParameters([], 'isReadOnly');
    }

    #[TestDox('->isReadOnly() returns bool')]
    public function test_isReadOnly_returns_bool(): void
    {
        $this->assertSameReturnType('bool', 'isReadOnly');
    }

    #[TestDox('->withWriteOnly() declares $writeOnly as its only parameter')]
    public function test_withWriteOnly_declares_writeOnly_parameter(): void
    {
        $this->assertSameParameters(['writeOnly'], 'withWriteOnly');
    }

    #[TestDox('->withWriteOnly() declares $writeOnly as bool')]
    public function test_withWriteOnly_writeOnly_type(): void
    {
        $this->assertSameParameterType('bool', 'withWriteOnly', 0);
    }

    #[TestDox('->withWriteOnly() returns static')]
    public function test_withWriteOnly_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withWriteOnly');
    }

    #[TestDox('->isWriteOnly() takes no parameters')]
    public function test_isWriteOnly_no_parameters(): void
    {
        $this->assertSameParameters([], 'isWriteOnly');
    }

    #[TestDox('->isWriteOnly() returns bool')]
    public function test_isWriteOnly_returns_bool(): void
    {
        $this->assertSameReturnType('bool', 'isWriteOnly');
    }

    #[TestDox('->withMetadata() declares $data as its only parameter')]
    public function test_withMetadata_declares_data_parameter(): void
    {
        $this->assertSameParameters(['data'], 'withMetadata');
    }

    #[TestDox('->withMetadata() declares $data as array')]
    public function test_withMetadata_data_type(): void
    {
        $this->assertSameParameterType('array', 'withMetadata', 0);
    }

    #[TestDox('->withMetadata() returns static')]
    public function test_withMetadata_returns_static(): void
    {
        $this->assertSameReturnType('static', 'withMetadata');
    }

    #[TestDox('->getMetadata() takes no parameters')]
    public function test_getMetadata_no_parameters(): void
    {
        $this->assertSameParameters([], 'getMetadata');
    }

    #[TestDox('->getMetadata() returns array')]
    public function test_getMetadata_returns_array(): void
    {
        $this->assertSameReturnType('array', 'getMetadata');
    }

    // ================================================================
    //
    // Internal Composition - method signatures
    //
    // ----------------------------------------------------------------

    #[TestDox('->parseWithContext() declares $data and $context as its parameters')]
    public function test_parseWithContext_declares_expected_parameters(): void
    {
        $this->assertSameParameters(['data', 'context'], 'parseWithContext');
    }

    #[TestDox('->parseWithContext() declares $data as mixed')]
    public function test_parseWithContext_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'parseWithContext', 0);
    }

    #[TestDox('->parseWithContext() declares $context as ValidationContext')]
    public function test_parseWithContext_context_type(): void
    {
        $this->assertSameParameterType(
            ValidationContext::class,
            'parseWithContext',
            1,
        );
    }

    #[TestDox('->parseWithContext() returns mixed')]
    public function test_parseWithContext_returns_mixed(): void
    {
        $this->assertSameReturnType('mixed', 'parseWithContext');
    }

    #[TestDox('->encodeWithContext() declares $data and $context as its parameters')]
    public function test_encodeWithContext_declares_expected_parameters(): void
    {
        $this->assertSameParameters(['data', 'context'], 'encodeWithContext');
    }

    #[TestDox('->encodeWithContext() declares $data as mixed')]
    public function test_encodeWithContext_data_type(): void
    {
        $this->assertSameParameterType('mixed', 'encodeWithContext', 0);
    }

    #[TestDox('->encodeWithContext() declares $context as ValidationContext')]
    public function test_encodeWithContext_context_type(): void
    {
        $this->assertSameParameterType(
            ValidationContext::class,
            'encodeWithContext',
            1,
        );
    }

    #[TestDox('->encodeWithContext() returns mixed')]
    public function test_encodeWithContext_returns_mixed(): void
    {
        $this->assertSameReturnType('mixed', 'encodeWithContext');
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * @param list<string> $expected
     */
    private function assertSameParameters(
        array $expected,
        string $methodName,
    ): void {
        $method = (new ReflectionClass(
            ValidationSchema::class,
        ))->getMethod($methodName);

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    private function assertSameParameterType(
        string $expected,
        string $methodName,
        int $paramIndex,
    ): void {
        $param = (new ReflectionClass(
            ValidationSchema::class,
        ))->getMethod($methodName)->getParameters()[$paramIndex];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        $this->assertSame($expected, $paramType->getName());
    }

    private function assertSameReturnType(
        string $expected,
        string $methodName,
    ): void {
        $method = (new ReflectionClass(
            ValidationSchema::class,
        ))->getMethod($methodName);
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        $this->assertSame($expected, $type->getName());
    }

    private function assertReturnTypeAllowsNull(
        string $methodName,
    ): void {
        $method = (new ReflectionClass(
            ValidationSchema::class,
        ))->getMethod($methodName);
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        $this->assertTrue($type->allowsNull());
    }
}
