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
use StusDevKit\ValidationKit\Internals\ValidationContext;
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
    // Refine
    //
    // ----------------------------------------------------------------

    #[TestDox('refine() adds custom validation')]
    public function test_refine_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that refine() can reject a value
        // that would otherwise pass the mixed type check

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->refine(
            fn(mixed $data) => $data !== '',
            'Value must not be empty string',
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

    #[TestDox('refine() passes when custom validation succeeds')]
    public function test_refine_passes_when_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that refine() allows a value
        // through when the custom check returns true

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->refine(
            fn(mixed $data) => $data !== '',
            'Value must not be empty string',
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
    // SuperRefine
    //
    // ----------------------------------------------------------------

    #[TestDox('superRefine() adds multiple custom issues')]
    public function test_super_refine_adds_multiple_issues(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that superRefine() can add
        // multiple validation issues via the context

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->superRefine(
            function (mixed $data, ValidationContext $ctx): void {
                if (! is_string($data)) {
                    $ctx->addIssue(
                        type: 'https://stusdevkit.dev/errors/validation/invalid_type',
                        input: $data,
                        message: 'Must be a string',
                    );
                    return;
                }

                if (strlen($data) < 3) {
                    $ctx->addIssue(
                        type: 'https://stusdevkit.dev/errors/validation/too_small',
                        input: $data,
                        message: 'Must be at least 3 characters',
                    );
                }

                if (strlen($data) > 10) {
                    $ctx->addIssue(
                        type: 'https://stusdevkit.dev/errors/validation/too_big',
                        input: $data,
                        message: 'Must be at most 10 characters',
                    );
                }
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('ab');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => [],
                    'message' => 'Must be at least 3 characters',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('superRefine() passes when no issues are added')]
    public function test_super_refine_passes_when_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that superRefine() allows a value
        // through when the callback adds no issues

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->superRefine(
            function (mixed $data, ValidationContext $ctx): void {
                if (! is_string($data) || strlen($data) < 3) {
                    $ctx->addIssue(
                        type: 'https://stusdevkit.dev/errors/validation/custom',
                        input: $data,
                        message: 'Must be a string of 3+ chars',
                    );
                }
            },
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

    #[TestDox('transform() modifies the validated data')]
    public function test_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that transform() applies a
        // transformation to the validated data

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->transform(
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

    #[TestDox('transform() can change the value type')]
    public function test_transform_can_change_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that transform() can convert a
        // value to a completely different type

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->transform(
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

    #[TestDox('pipe() chains to another schema')]
    public function test_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pipe() passes the output
        // of this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->transform(function (mixed $data) {
                /** @var string $data */
                return $data;
            })
            ->pipe(Validate::string()->min(length: 3));

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

    #[TestDox('pipe() rejects when downstream schema fails')]
    public function test_pipe_rejects_on_downstream_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pipe() fails when the
        // downstream schema rejects the transformed value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->transform(function (mixed $data) {
                /** @var string $data */
                return $data;
            })
            ->pipe(Validate::string()->min(length: 10));

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

    #[TestDox('catch() provides fallback on validation failure')]
    public function test_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that catch() returns the fallback
        // value when a refinement causes validation to fail

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()
            ->refine(
                fn(mixed $data) => is_string($data),
                'Must be a string',
            )
            ->catch('fallback');

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

    #[TestDox('describe() sets the description')]
    public function test_describe_sets_description(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->describe('Any value');

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

    #[TestDox('meta() sets the metadata')]
    public function test_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::mixed()->meta(['label' => 'Payload']);

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
