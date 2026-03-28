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
use StusDevKit\ValidationKit\Validate;

#[TestDox('Validate::nullish()')]
class ValidateNullishTest extends TestCase
{
    // ================================================================
    //
    // Allows Null With Each Inner Schema Type
    //
    // ----------------------------------------------------------------

    #[TestDox('allows null with string inner schema')]
    public function test_nullish_string_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::string())
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

    #[TestDox('allows null with int inner schema')]
    public function test_nullish_int_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::int())
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

    #[TestDox('allows null with float inner schema')]
    public function test_nullish_float_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::float())
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

    #[TestDox('allows null with number inner schema')]
    public function test_nullish_number_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::number())
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

    #[TestDox('allows null with boolean inner schema')]
    public function test_nullish_boolean_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::boolean())
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

    #[TestDox('allows null with dateTime inner schema')]
    public function test_nullish_datetime_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::dateTime())
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

        $unit = Validate::nullish(Validate::literal(value: 'active'));

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

        $unit = Validate::nullish(Validate::enum(['a', 'b']));

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

        $unit = Validate::nullish(Validate::array(Validate::string()));

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

    #[TestDox('allows null with union inner schema')]
    public function test_nullish_union_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that
        // Validate::nullish(Validate::union([...]))
        // accepts null input and returns null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(
            Validate::union([Validate::string(), Validate::int()]),
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

    // ================================================================
    //
    // Delegation and Rejection
    //
    // ----------------------------------------------------------------

    #[TestDox('delegates to inner schema for non-null input')]
    public function test_nullish_delegates_to_inner_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::string())
        // delegates non-null input to the inner string schema
        // and returns the parsed result

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

    #[TestDox('rejects invalid type via inner schema')]
    public function test_nullish_rejects_invalid_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullish(Validate::string())
        // rejects input that does not match the inner schema's
        // type, returning a failed ParseResult via safeParse()

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

        // ----------------------------------------------------------------
        // clean up the database

    }
}
