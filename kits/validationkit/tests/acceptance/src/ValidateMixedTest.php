<?php

//
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
//

declare(strict_types=1);
namespace StusDevKit\ValidationKit\Tests\Acceptance;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\ValidationKit\Tests\Fixtures\CallableTransformer;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;

#[TestDox('Validate::mixed()')]
class ValidateMixedTest extends TestCase
{
    // ================================================================
    //
    // Type Checking - Accepts Any Value
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideAnyValues(): array
    {
        return [
            'string' => [ 'hello', 'hello' ],
            'int' => [ 42, 42 ],
            'float' => [ 3.14, 3.14 ],
            'bool true' => [ true, true ],
            'bool false' => [ false, false ],
            'null' => [ null, null ],
            'array' => [ ['a', 'b'], ['a', 'b'] ],
        ];
    }

    #[DataProvider('provideAnyValues')]
    #[TestDox('accepts any value type')]
    public function test_accepts_any_value(
        mixed $inputValue,
        mixed $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::mixed()->parse()
        // accepts any value type and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('accepts an object value')]
    public function test_accepts_object_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::mixed()->parse()
        // accepts an object and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed();
        $inputValue = new stdClass();
        $inputValue->name = 'test';

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // parse() and safeParse()
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() returns the value unchanged')]
    public function test_parse_returns_value_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parse() returns the input
        // value with no modifications

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns successful result for any input')]
    public function test_safe_parse_returns_success(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // successful ParseResult for any input value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(42, $result->data());
        $this->assertNull($result->maybeError());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns successful result for null')]
    public function test_safe_parse_returns_success_for_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // successful ParseResult when given null, since
        // mixed accepts all values including null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertNull($result->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Constraint
    //
    // ----------------------------------------------------------------

    #[TestDox('withCustomConstraint() adds custom validation')]
    public function test_with_custom_constraint_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomConstraint() can
        // reject a value that would otherwise pass the mixed
        // type check

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withCustomConstraint(
            fn(mixed $data) => $data !== ''
                ? null
                : 'Value must not be empty string',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'Value must not be empty string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCustomConstraint() passes when custom validation succeeds')]
    public function test_with_custom_constraint_passes_when_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomConstraint() allows
        // a value through when the callable returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withCustomConstraint(
            fn(mixed $data) => $data !== ''
                ? null
                : 'Value must not be empty string',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Transform
    //
    // ----------------------------------------------------------------

    #[TestDox('withCustomTransform() modifies the validated data')]
    public function test_with_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomTransform() applies a
        // transformation to the validated data

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withCustomTransform(
            fn(mixed $data) => is_string($data)
                ? strtoupper($data)
                : $data,
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via parse()')]
    public function test_with_transformer_transforms_via_parse(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withTransformer(
            new CallableTransformer(
                fn(mixed $data) => is_string($data) ? strtoupper($data) : $data,
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via safeParse()')]
    public function test_with_transformer_transforms_via_safe_parse(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withTransformer(
            new CallableTransformer(
                fn(mixed $data) => is_string($data) ? strtoupper($data) : $data,
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('HELLO', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via encode()')]
    public function test_with_transformer_transforms_via_encode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withTransformer(
            new CallableTransformer(
                fn(mixed $data) => is_string($data) ? strtoupper($data) : $data,
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via safeEncode()')]
    public function test_with_transformer_transforms_via_safe_encode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withTransformer(
            new CallableTransformer(
                fn(mixed $data) => is_string($data) ? strtoupper($data) : $data,
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('HELLO', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCustomTransform() can change the value type')]
    public function test_with_transform_can_change_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomTransform() can convert a
        // value to a completely different type

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withCustomTransform(
            fn(mixed $data) => is_string($data)
                ? strlen($data)
                : 0,
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(5, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Pipe
    //
    // ----------------------------------------------------------------

    #[TestDox('withPipe() chains to another schema')]
    public function test_with_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withPipe() passes the output
        // of this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->withCustomTransform(function (mixed $data) {
                /** @var string $data */
                return $data;
            })
            ->withPipe(Validate::string()->min(length: 3));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withPipe() rejects when downstream schema fails')]
    public function test_with_pipe_rejects_on_downstream_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withPipe() fails when the
        // downstream schema rejects the transformed value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->withCustomTransform(function (mixed $data) {
                /** @var string $data */
                return $data;
            })
            ->withPipe(Validate::string()->min(length: 10));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('hi');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => [],
                    'message' => 'String must be at least 10'
                        . ' characters',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Catch
    //
    // ----------------------------------------------------------------

    #[TestDox('withCatch() provides fallback on validation failure')]
    public function test_with_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCatch() returns the fallback
        // value when a refinement causes validation to fail

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->withCustomConstraint(
                fn(mixed $data) => is_string($data)
                    ? null
                    : 'Must be a string',
            )
            ->withCatch('fallback');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('fallback', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    #[TestDox('withDescription() sets the description')]
    public function test_with_description_sets_description(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withDescription('Any value');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Any value', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withMeta() sets the metadata')]
    public function test_with_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->withMeta(['label' => 'Payload']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Payload'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('withConstraint() adds custom constraint to pipeline')]
    public function test_with_constraint_adds_custom_constraint(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withConstraint() correctly
        // wires a custom ValidationConstraint into the
        // schema's validation pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'rejected by custom constraint',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
