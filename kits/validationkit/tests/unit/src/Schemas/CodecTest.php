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

use BadMethodCallException;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Codec;

#[TestDox(Codec::class)]
class CodecTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the codec namespace is load-bearing — exporters and
        // Validate::codec() both depend on this FQN.

        $expected = 'StusDevKit\\ValidationKit\\Schemas';

        $actual = (new ReflectionClass(Codec::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(Codec::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(Codec::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('implements ValidationSchema via BaseSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(Codec::class);
        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    // ================================================================
    //
    // Shape — own public methods
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes inputSchema() as a public method')]
    public function test_inputSchema_is_public(): void
    {
        $reflection = new ReflectionClass(Codec::class);
        $this->assertTrue($reflection->hasMethod('inputSchema'));
        $this->assertTrue(
            $reflection->getMethod('inputSchema')->isPublic(),
        );
    }

    #[TestDox('exposes outputSchema() as a public method')]
    public function test_outputSchema_is_public(): void
    {
        $reflection = new ReflectionClass(Codec::class);
        $this->assertTrue($reflection->hasMethod('outputSchema'));
        $this->assertTrue(
            $reflection->getMethod('outputSchema')->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — decode / encode round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('->decode() validates input and returns transformed output')]
    public function test_decode_transforms_input_to_output(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        // numeric string → int codec
        $codec = $this->makeStringToIntCodec();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $codec->decode('42');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('->encode() validates output and returns encoded input')]
    public function test_encode_transforms_output_to_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $codec = $this->makeStringToIntCodec();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $codec->encode(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('42', $actual);
    }

    #[TestDox('->decode() then ->encode() round-trips back to the original input')]
    public function test_decode_encode_round_trip(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $codec = $this->makeStringToIntCodec();

        // ----------------------------------------------------------------
        // perform the change

        $decoded = $codec->decode('42');
        $encoded = $codec->encode($decoded);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('42', $encoded);
    }

    #[TestDox('->decode() throws ValidationException when input fails input-schema validation')]
    public function test_decode_throws_on_invalid_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $codec = $this->makeStringToIntCodec();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change
        // footgun! passing an int would skip input-schema validation
        // because it's already the output type — use an array to
        // force the input-schema path and trigger the failure

        $codec->decode(['not', 'a', 'string']);
    }

    #[TestDox('->encode() throws ValidationException when data fails output-schema validation')]
    public function test_encode_throws_on_invalid_output(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $codec = $this->makeStringToIntCodec();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $codec->encode('not-an-int');
    }

    #[TestDox('->inputSchema() returns the input schema passed to the constructor')]
    public function test_inputSchema_returns_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = new StringSchema();
        $output = new IntSchema();
        $codec = new Codec(
            inputSchema: $input,
            outputSchema: $output,
            decoder: static fn(string $s): int => (int) $s,
            encoder: static fn(int $i): string => (string) $i,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($input, $codec->inputSchema());
    }

    #[TestDox('->outputSchema() returns the output schema passed to the constructor')]
    public function test_outputSchema_returns_output(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = new StringSchema();
        $output = new IntSchema();
        $codec = new Codec(
            inputSchema: $input,
            outputSchema: $output,
            decoder: static fn(string $s): int => (int) $s,
            encoder: static fn(int $i): string => (string) $i,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($output, $codec->outputSchema());
    }

    // ================================================================
    //
    // Behaviour — blocked builder methods
    //
    // All pipeline features belong on the inner schemas —
    // calling them on a codec is a programmer error.
    //
    // ----------------------------------------------------------------

    #[TestDox('->withCustomConstraint() throws BadMethodCallException')]
    public function test_withCustomConstraint_blocked(): void
    {
        $codec = $this->makeStringToIntCodec();
        $this->expectException(BadMethodCallException::class);
        $codec->withCustomConstraint(
            static fn(mixed $v): ?string => null,
        );
    }

    #[TestDox('->withCustomTransform() throws BadMethodCallException')]
    public function test_withCustomTransform_blocked(): void
    {
        $codec = $this->makeStringToIntCodec();
        $this->expectException(BadMethodCallException::class);
        $codec->withCustomTransform(
            static fn(mixed $v): mixed => $v,
        );
    }

    #[TestDox('->withCatch() throws BadMethodCallException')]
    public function test_withCatch_blocked(): void
    {
        $codec = $this->makeStringToIntCodec();
        $this->expectException(BadMethodCallException::class);
        $codec->withCatch(0);
    }

    #[TestDox('->withDefault() throws BadMethodCallException')]
    public function test_withDefault_blocked(): void
    {
        $codec = $this->makeStringToIntCodec();
        $this->expectException(BadMethodCallException::class);
        $codec->withDefault(0);
    }

    #[TestDox('->withPipe() throws BadMethodCallException')]
    public function test_withPipe_blocked(): void
    {
        $codec = $this->makeStringToIntCodec();
        $this->expectException(BadMethodCallException::class);
        $codec->withPipe(new IntSchema());
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * @return Codec<string, int>
     */
    private function makeStringToIntCodec(): Codec
    {
        return new Codec(
            inputSchema: new StringSchema(),
            outputSchema: new IntSchema(),
            decoder: static fn(string $s): int => (int) $s,
            encoder: static fn(int $i): string => (string) $i,
        );
    }
}
