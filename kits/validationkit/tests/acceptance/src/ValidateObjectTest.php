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
use Ramsey\Uuid\Uuid;
use StusDevKit\DateTimeKit\Now;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Tests\Fixtures\CallableTransformer;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::object()')]
class ValidateObjectTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts an associative array')]
    public function test_accepts_associative_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::object()->parse()
        // accepts a valid associative array matching the
        // shape and returns the validated data

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
            'age' => 30,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([
            'name' => 'Alice',
            'age' => 30,
        ], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideNonObjectValues(): array
    {
        return [
            'string' => [ 'hello' ],
            'int' => [ 42 ],
            'null' => [ null ],
            'bool true' => [ true ],
            'float' => [ 3.14 ],
        ];
    }

    #[DataProvider('provideNonObjectValues')]
    #[TestDox('rejects non-object values')]
    public function test_rejects_non_object_value(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::object()->parse()
        // throws a ValidationException for non-array input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Shape Validation
    //
    // ----------------------------------------------------------------

    #[TestDox('validates each field against its schema')]
    public function test_validates_each_field(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each field in the object is
        // validated against its corresponding schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 42,
            'age' => 'not a number',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['name'],
                    'message' => 'Expected string, received int',
                ],
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['age'],
                    'message' => 'Expected int, received string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('missing required field produces error')]
    public function test_missing_required_field_error(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a required field is
        // missing from the input, the schema reports an
        // error for that field

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Alice',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['age'],
                    'message' => 'Expected int, received null',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('optional() field can be missing')]
    public function test_optional_field_can_be_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a field marked optional()
        // does not produce an error when missing from
        // the input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'bio' => Validate::optional(Validate::string()),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertNull($actualResult['bio']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withDefault() field uses fallback when missing')]
    public function test_with_default_field_uses_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a field with withDefault()
        // uses the fallback value when the field is missing

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'role' => Validate::string()->withDefault('user'),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertSame('user', $actualResult['role']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nested Path Tracking
    //
    // ----------------------------------------------------------------

    #[TestDox('nested object produces correct dot-notation path')]
    public function test_nested_object_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a field inside a nested
        // object fails, the path uses dot-notation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'address' => Validate::object([
                'zip' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'address' => [
                'zip' => 12345,
            ],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['address', 'zip'],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('deeply nested object produces correct path')]
    public function test_deeply_nested_object_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that deeply nested field errors
        // produce the correct multi-level dot-notation path

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'user' => Validate::object([
                'address' => Validate::object([
                    'city' => Validate::string(),
                ]),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'user' => [
                'address' => [
                    'city' => 42,
                ],
            ],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['user', 'address', 'city'],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('object containing array produces correct path')]
    public function test_object_containing_array_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when an object contains an
        // array field and an element fails, the path uses
        // bracket notation for the index

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'tags' => Validate::array(Validate::string()),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'tags' => [42],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['tags', 0],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('object containing array of objects produces correct path')]
    public function test_object_containing_array_of_objects_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when an object contains an
        // array of objects and a nested field fails, the
        // path includes key, index, and nested field

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'items' => Validate::array(
                Validate::object([
                    'price' => Validate::int(),
                ]),
            ),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'items' => [
                ['price' => 10],
                ['price' => 20],
                ['price' => 'free'],
            ],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['items', 2, 'price'],
                    'message' => 'Expected int, received string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Shape Modification Methods
    //
    // ----------------------------------------------------------------

    #[TestDox('extend() adds fields to the shape')]
    public function test_extend_adds_fields(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that extend() creates a new schema
        // with additional fields from the given shape

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $base = Validate::object([
            'name' => Validate::string(),
        ]);

        $unit = $base->extend([
            'age' => Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
            'age' => 30,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertSame(30, $actualResult['age']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('pick() keeps only the specified keys')]
    public function test_pick_keeps_specified_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pick() creates a new schema
        // that only validates the specified keys

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $base = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
            'email' => Validate::string(),
        ]);

        $unit = $base->pick('name', 'email');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertSame(
            'alice@example.com',
            $actualResult['email'],
        );
        $this->assertArrayNotHasKey('age', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('omit() removes the specified keys')]
    public function test_omit_removes_specified_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that omit() creates a new schema
        // without the specified keys

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $base = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
            'email' => Validate::string(),
        ]);

        $unit = $base->omit('email');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
            'age' => 30,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertSame(30, $actualResult['age']);
        $this->assertArrayNotHasKey('email', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('partial() makes all fields optional')]
    public function test_partial_makes_all_optional(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that partial() makes every field
        // in the shape optional, so missing fields do not
        // produce errors

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $base = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
        ]);

        $unit = $base->partial();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertNull($actualResult['age']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Unknown Key Policies
    //
    // ----------------------------------------------------------------

    #[TestDox('strip() removes unknown keys (default)')]
    public function test_strip_removes_unknown_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that by default (strip mode),
        // unknown keys are silently removed from the output

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
            'extra' => 'ignored',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertArrayNotHasKey('extra', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('strict() rejects unknown keys')]
    public function test_strict_rejects_unknown_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that strict() mode rejects input
        // that contains keys not defined in the shape

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->strict();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Alice',
            'extra' => 'not allowed',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/unrecognized_keys',
                    'path'    => [],
                    'message' => 'Unrecognized keys: extra',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('passthrough() keeps unknown keys')]
    public function test_passthrough_keeps_unknown_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that passthrough() mode keeps
        // unknown keys in the output unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->passthrough();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Alice',
            'extra' => 'kept',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult['name']);
        $this->assertSame('kept', $actualResult['extra']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // parse() and safeParse()
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() throws ValidationException on failure')]
    public function test_parse_throws_on_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parse() throws a
        // ValidationException with correct issue details
        // when validation fails

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse(42);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected object (associative'
                        . ' array), received int',
                ],
            ],
            $caughtException->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns successful result for valid input')]
    public function test_safe_parse_returns_success(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // successful ParseResult for valid object input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['name' => 'Alice']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(
            ['name' => 'Alice'],
            $result->data(),
        );
        $this->assertNull($result->maybeError());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns failed result for invalid input')]
    public function test_safe_parse_returns_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // failed ParseResult for non-object input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($result->succeeded());
        $this->assertTrue($result->failed());
        $this->assertNull($result->maybeData());
        $this->assertInstanceOf(
            ValidationException::class,
            $result->maybeError(),
        );

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected object (associative'
                        . ' array), received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Default
    //
    // ----------------------------------------------------------------

    #[TestDox('withDefault() provides fallback for null')]
    public function test_with_default_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->withDefault(['name' => 'anonymous']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['name' => 'anonymous'],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Transform, Refine, Pipe, Catch
    //
    // ----------------------------------------------------------------

    #[TestDox('withCustomTransform() modifies the validated data')]
    public function test_with_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'first' => Validate::string(),
            'last' => Validate::string(),
        ])->withCustomTransform(
            function (mixed $data) {
                /** @var array{first: string, last: string} $data */
                return $data['first'] . ' ' . $data['last'];
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'first' => 'Alice',
            'last' => 'Smith',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice Smith', $actualResult);

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

        $unit = Validate::object([
            'first' => Validate::string(),
            'last' => Validate::string(),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{first: string, last: string} $data */
                    return $data['first'] . ' ' . $data['last'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['first' => 'Alice', 'last' => 'Smith']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice Smith', $actualResult);

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

        $unit = Validate::object([
            'first' => Validate::string(),
            'last' => Validate::string(),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{first: string, last: string} $data */
                    return $data['first'] . ' ' . $data['last'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse(['first' => 'Alice', 'last' => 'Smith']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('Alice Smith', $actualResult->data());

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

        $unit = Validate::object([
            'first' => Validate::string(),
            'last' => Validate::string(),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{first: string, last: string} $data */
                    return $data['first'] . ' ' . $data['last'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode(['first' => 'Alice', 'last' => 'Smith']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice Smith', $actualResult);

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

        $unit = Validate::object([
            'first' => Validate::string(),
            'last' => Validate::string(),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{first: string, last: string} $data */
                    return $data['first'] . ' ' . $data['last'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode(['first' => 'Alice', 'last' => 'Smith']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('Alice Smith', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCustomConstraint() adds custom validation')]
    public function test_with_custom_constraint_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomConstraint() can
        // reject a value that passes type and field checks

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'password' => Validate::string(),
            'confirm' => Validate::string(),
        ])->withCustomConstraint(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['password'] === $data['confirm']
                    ? null
                    : 'Passwords do not match';
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'password' => 'secret',
            'confirm' => 'different',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'Passwords do not match',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withPipe() chains to another schema')]
    public function test_with_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withPipe() passes the output of
        // this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->withCustomTransform(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['name'];
            },
        )->withPipe(
            Validate::string()->min(length: 3),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['name' => 'Alice']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCatch() provides fallback on validation failure')]
    public function test_with_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->withCatch(['name' => 'unknown']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['name' => 'unknown'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Error Callbacks
    //
    // ----------------------------------------------------------------

    #[TestDox('custom type-check error callback is used on failure')]
    public function test_custom_type_check_error_callback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom error callback
        // on the type check produces a custom ValidationIssue

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object(
            shape: [
                'name' => Validate::string(),
            ],
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Custom: not an object',
                type: 'https://example.com/errors/not-object',
                title: 'Not an object',
            ),
        );

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
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'Custom: not an object',
            $issue->message,
        );
        $this->assertSame(
            'https://example.com/errors/not-object',
            $issue->type,
        );
        $this->assertSame('Not an object', $issue->title);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Issue Fields
    //
    // ----------------------------------------------------------------

    #[TestDox('issues carry default type URI and title')]
    public function test_issues_carry_default_type_and_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that ValidationIssues created by
        // default error callbacks carry the correct default
        // type URI and title from the issue type

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

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
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_type',
            $issue->type,
        );
        $this->assertSame('Validation failed', $issue->title);
        $this->assertSame('https://stusdevkit.dev/errors/validation/invalid_type', $issue->type);

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

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->withDescription('A user profile');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A user profile', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withMetadata() sets the metadata')]
    public function test_with_metadata_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->withMetadata(['label' => 'User']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getMetadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'User'], $actualResult);

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

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['name' => 'test']);

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
            $result->error()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // propertyNames()
    //
    // ----------------------------------------------------------------

    #[TestDox('propertyNames() accepts valid keys')]
    public function test_property_names_accepts_valid_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that propertyNames() accepts an
        // object when all property names pass the given
        // schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->propertyNames(
            schema: Validate::string()->min(length: 1),
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

    #[TestDox('propertyNames() rejects invalid keys')]
    public function test_property_names_rejects_invalid_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that propertyNames() rejects an
        // object when a property name does not pass the
        // given schema (key 'x' is only 1 char, minimum
        // is 3)

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'x' => Validate::string(),
        ])->propertyNames(
            schema: Validate::string()->min(length: 3),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['x' => 'val']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'One or more property names'
                        . ' are invalid',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // patternProperties()
    //
    // ----------------------------------------------------------------

    #[TestDox('patternProperties() validates matching keys')]
    public function test_pattern_properties_validates_matching_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that patternProperties() validates
        // values whose keys match the given regex pattern.
        // The key 's_name' matches /^s_/ so its value is
        // validated as a string, which passes. The key 'age'
        // does not match, so it is not validated by this
        // constraint.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->patternProperties(
                patterns: ['/^s_/' => Validate::string()],
            );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            's_name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([
            's_name' => 'Stuart',
            'age' => 42,
        ], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('patternProperties() rejects invalid values')]
    public function test_pattern_properties_rejects_invalid_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that patternProperties() rejects
        // a value when its key matches the pattern but the
        // value does not pass the corresponding schema
        // (123 is not a string)

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->patternProperties(
                patterns: ['/^s_/' => Validate::string()],
            );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['s_name' => 123]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['s_name'],
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
    // dependentSchemas()
    //
    // ----------------------------------------------------------------

    #[TestDox('dependentSchemas() applies when property is present')]
    public function test_dependent_schemas_applies_when_property_present(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dependentSchemas() applies
        // the dependent schema when the trigger property
        // is present. Here, 'name' is present so the
        // dependent schema requiring 'email' is applied,
        // and 'email' is also present so validation passes.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->dependentSchemas(
                dependencies: [
                    'name' => Validate::object([
                        'email' => Validate::string(),
                    ]),
                ],
            );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'email' => 'test@test.com',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([
            'name' => 'Stuart',
            'email' => 'test@test.com',
        ], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('dependentSchemas() rejects when dependency fails')]
    public function test_dependent_schemas_rejects_when_dependency_fails(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dependentSchemas() rejects
        // the input when the trigger property is present
        // but the dependent schema fails. Here, 'name' is
        // present so the dependent schema requiring 'email'
        // is applied, but 'email' is missing.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->dependentSchemas(
                dependencies: [
                    'name' => Validate::object([
                        'email' => Validate::string(),
                    ]),
                ],
            );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['name' => 'Stuart']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => ['email'],
                    'message' => 'Expected string, received null',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('dependentSchemas() skips when property is absent')]
    public function test_dependent_schemas_skips_when_property_absent(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dependentSchemas() does not
        // apply the dependent schema when the trigger
        // property is absent. Here, 'name' is not present
        // so the dependency on 'email' is not triggered.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->dependentSchemas(
                dependencies: [
                    'name' => Validate::object([
                        'email' => Validate::string(),
                    ]),
                ],
            );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['age' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['age' => 42], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // minProperties / maxProperties
    //
    // ----------------------------------------------------------------

    #[TestDox('minProperties() accepts object with enough properties')]
    public function test_min_properties_accepts_object_with_enough(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that minProperties() accepts an
        // object when it has at least the required number
        // of properties

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'a' => Validate::string(),
            'b' => Validate::string(),
            'c' => Validate::string(),
        ])->minProperties(count: 2);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'a' => 'one',
            'b' => 'two',
            'c' => 'three',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([
            'a' => 'one',
            'b' => 'two',
            'c' => 'three',
        ], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('minProperties() rejects object with too few properties')]
    public function test_min_properties_rejects_object_with_too_few(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that minProperties() rejects an
        // object when it has fewer properties than the
        // required minimum. We use passthrough() so
        // that the constraint counts actual input
        // properties, not the shape-expanded output.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'a' => Validate::optional(Validate::string()),
        ])->passthrough()
          ->minProperties(count: 2);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'a' => 'one',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/too_small',
            $issue->type,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('maxProperties() accepts object within limit')]
    public function test_max_properties_accepts_within_limit(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maxProperties() accepts an
        // object when it has no more than the allowed number
        // of properties

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'a' => Validate::string(),
            'b' => Validate::string(),
        ])->maxProperties(count: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'a' => 'one',
            'b' => 'two',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([
            'a' => 'one',
            'b' => 'two',
        ], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('maxProperties() rejects object with too many properties')]
    public function test_max_properties_rejects_too_many(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maxProperties() rejects an
        // object when it has more properties than the
        // allowed maximum. We use passthrough() so that
        // extra keys are not stripped before the constraint
        // runs.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'a' => Validate::string(),
            'b' => Validate::string(),
        ])->passthrough()
          ->maxProperties(count: 2);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'a' => 'one',
            'b' => 'two',
            'c' => 'three',
            'd' => 'four',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/too_big',
            $issue->type,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // dependentRequired
    //
    // ----------------------------------------------------------------

    #[TestDox('dependentRequired() passes when trigger property is absent')]
    public function test_dependent_required_passes_when_trigger_absent(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dependentRequired() does not
        // enforce the dependency when the trigger property
        // is absent from the input. We use passthrough()
        // with an empty shape so that the constraint sees
        // only the actual input properties.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->dependentRequired(['email' => ['name']]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'test',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('test', $actualResult['name']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('dependentRequired() passes when all dependencies are present')]
    public function test_dependent_required_passes_when_all_present(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dependentRequired() passes
        // when the trigger property is present and all of
        // the required dependent properties are also present.
        // We use passthrough() with an empty shape so that
        // the constraint sees only actual input properties.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->dependentRequired(['email' => ['name']]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'test',
            'email' => 'a@b.com',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('test', $actualResult['name']);
        $this->assertSame('a@b.com', $actualResult['email']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('dependentRequired() fails when dependent property is missing')]
    public function test_dependent_required_fails_when_dependent_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dependentRequired() rejects
        // the input when the trigger property is present but
        // a required dependent property is missing. We use
        // passthrough() with an empty shape so that the
        // constraint sees only actual input properties.

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([])
            ->passthrough()
            ->dependentRequired(['email' => ['name']]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'email' => 'a@b.com',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $issue = $result->maybeError()->issues()->first();
        $this->assertStringContainsString(
            'requires property',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    public function test_complex_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this tests proves that we can define a schema for a complex
        // object ... and that the schema works

        // ----------------------------------------------------------------
        // shorthand



        // ----------------------------------------------------------------
        // setup your test

        Now::init();

        $unit = Validate::allOf([
            Validate::object([
                'order_id' => Validate::uuid()->coerce(),
            ]),
            Validate::oneOf([
                Validate::object([
                    'stripe' => Validate::object([
                        'payment_intent' => Validate::string(),
                        'client_secret' => Validate::string(),
                    ]),
                ]),
                Validate::object([
                    'zero_cost' => Validate::object([
                        'confirm_token' => Validate::uuid()->coerce(),
                        'expires_at' => Validate::dateTime()->coerce(),
                    ]),
                ]),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations



        // ----------------------------------------------------------------
        // pre-test checks



        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'order_id' => (string)Uuid::uuid7(),
            'zero_cost' => [
                'confirm_token' => (string)Uuid::uuid7(),
                'expires_at' => Now::asFormat()->http()->rfc9110(),
            ],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($result->failed());
        // var_dump($result->error()->issues()->jsonSerialize());

        // ----------------------------------------------------------------
        // clean up the database


    }
}
