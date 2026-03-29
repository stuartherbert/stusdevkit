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

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Tests\Fixtures\CallableTransformer;
use StusDevKit\ValidationKit\Validate;

#[TestDox('Validate::nullish()')]
class ValidateNullishTest extends TestCase
{
    // ================================================================
    //
    // array
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with array inner schema')]
    public function test_nullish_array_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::array(Validate::string()))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::array(Validate::string()),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with array inner schema')]
    public function test_nullish_array_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::array(Validate::string()))
        // passes valid array data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::array(Validate::string()),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['hello']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['hello'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // boolean
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with boolean inner schema')]
    public function test_nullish_boolean_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::boolean())
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::boolean());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with boolean inner schema')]
    public function test_nullish_boolean_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::boolean()) passes
        // valid boolean data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::boolean());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(true, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // dateTime
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with dateTime inner schema')]
    public function test_nullish_datetime_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::dateTime())
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::dateTime());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with dateTime inner schema')]
    public function test_nullish_datetime_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::dateTime()) passes
        // valid DateTimeImmutable data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::dateTime());
        $now = new DateTimeImmutable();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($now);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($now, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // enum
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with enum inner schema')]
    public function test_nullish_enum_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::enum(['a', 'b']))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::enum(['a', 'b']),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with enum inner schema')]
    public function test_nullish_enum_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::enum(['a', 'b']))
        // passes valid enum data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::enum(['a', 'b']),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('a', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // float
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with float inner schema')]
    public function test_nullish_float_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::float())
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::float());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with float inner schema')]
    public function test_nullish_float_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::float()) passes
        // valid float data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::float());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(3.14);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // instanceOf
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with instanceOf inner schema')]
    public function test_nullish_instance_of_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::instanceOf(...))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::instanceOf(DateTimeInterface::class),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with instanceOf inner schema')]
    public function test_nullish_instance_of_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::instanceOf(...))
        // passes valid instance data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::instanceOf(DateTimeInterface::class),
        );
        $now = new DateTimeImmutable();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($now);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($now, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // int
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with int inner schema')]
    public function test_nullish_int_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::int())
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::int());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with int inner schema')]
    public function test_nullish_int_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::int()) passes
        // valid int data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::int());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // literal
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with literal inner schema')]
    public function test_nullish_literal_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::literal(value: 'active'))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::literal(value: 'active'),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with literal inner schema')]
    public function test_nullish_literal_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::literal(value: 'active'))
        // passes valid literal data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::literal(value: 'active'),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('active');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('active', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // number
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with number inner schema')]
    public function test_nullish_number_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::number())
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::number());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with number inner schema')]
    public function test_nullish_number_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::number()) passes
        // valid number data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::number());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // object
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with object inner schema')]
    public function test_nullish_object_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::object([...]))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::object(['name' => Validate::string()]),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with object inner schema')]
    public function test_nullish_object_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::object([...]))
        // passes valid object data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::object(['name' => Validate::string()]),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['name' => 'Stuart']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['name' => 'Stuart'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // string
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with string inner schema')]
    public function test_nullish_string_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::string())
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with string inner schema')]
    public function test_nullish_string_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::string()) passes
        // valid string data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::string());

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
    // tuple
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with tuple inner schema')]
    public function test_nullish_tuple_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::tuple([...]))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::tuple([Validate::string(), Validate::int()]),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with tuple inner schema')]
    public function test_nullish_tuple_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::tuple([...]))
        // passes valid tuple data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::tuple([Validate::string(), Validate::int()]),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['hello', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['hello', 42], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // union
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with union inner schema')]
    public function test_nullish_union_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::anyOf([...]))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::anyOf([Validate::string(), Validate::int()]),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passes valid data through with union inner schema')]
    public function test_nullish_union_passes_valid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::anyOf([...]))
        // passes valid union data through unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::anyOf([Validate::string(), Validate::int()]),
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
    // Type Error Propagation
    //
    // ----------------------------------------------------------------

    #[TestDox('rejects invalid type via inner schema')]
    public function test_nullish_rejects_invalid_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a non-null value of the
        // wrong type is passed to a nullish schema, the inner
        // schema's type error propagates through safeParse()

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Transformer
    //
    // ----------------------------------------------------------------

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

        $unit = Validate::nullish(Validate::string())->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
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

        $unit = Validate::nullish(Validate::string())->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
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

        $unit = Validate::nullish(Validate::string())->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
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

        $unit = Validate::nullish(Validate::string())->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
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
}
