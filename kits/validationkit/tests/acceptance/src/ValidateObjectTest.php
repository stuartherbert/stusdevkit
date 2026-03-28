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
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\IssueCode;
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
        $issues = $result->maybeError()->issues();
        $this->assertGreaterThanOrEqual(2, count($issues));

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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('age', $issue->pathAsString());

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

    #[TestDox('default() field uses fallback when missing')]
    public function test_default_field_uses_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a field with default()
        // uses the fallback value when the field is missing

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'role' => Validate::string()->default('user'),
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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('address.zip', $issue->pathAsString());

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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(
            'user.address.city',
            $issue->pathAsString(),
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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('tags[0]', $issue->pathAsString());

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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(
            'items[2].price',
            $issue->pathAsString(),
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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(
            IssueCode::UnrecognizedKeys,
            $issue->code,
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
        $this->assertCount(1, $caughtException->issues());

        $issue = $caughtException->issues()[0];
        $this->assertSame(IssueCode::InvalidType, $issue->code);
        $this->assertSame(42, $issue->input);
        $this->assertSame([], $issue->path);
        $this->assertStringContainsString(
            'Expected object',
            $issue->message,
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

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nullable, Optional, Default (on the object itself)
    //
    // ----------------------------------------------------------------

    #[TestDox('nullable() allows null')]
    public function test_nullable_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullable(Validate::object([
            'name' => Validate::string(),
        ]));

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

    #[TestDox('optional() allows null')]
    public function test_optional_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::optional(Validate::object([
            'name' => Validate::string(),
        ]));

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

    #[TestDox('nullish() allows null')]
    public function test_nullish_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullish(Validate::object([
            'name' => Validate::string(),
        ]));

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

    #[TestDox('default() provides fallback for null')]
    public function test_default_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->default(['name' => 'anonymous']);

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

    #[TestDox('transform() modifies the validated data')]
    public function test_transform_modifies_data(): void
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
        ])->transform(
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

    #[TestDox('refine() adds custom validation')]
    public function test_refine_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that refine() can reject a value
        // that passes type and field checks

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'password' => Validate::string(),
            'confirm' => Validate::string(),
        ])->refine(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['password'] === $data['confirm'];
            },
            'Passwords do not match',
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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame(
            'Passwords do not match',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('pipe() chains to another schema')]
    public function test_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pipe() passes the output of
        // this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->transform(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['name'];
            },
        )->pipe(
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

    #[TestDox('catch() provides fallback on validation failure')]
    public function test_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->catch(['name' => 'unknown']);

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
                code: IssueCode::InvalidType,
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
        $issue = $result->maybeError()->issues()[0];
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
        // type URI and title from IssueCode

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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_type',
            $issue->type,
        );
        $this->assertSame('Invalid type', $issue->title);
        $this->assertSame(IssueCode::InvalidType, $issue->code);

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

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->describe('A user profile');

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

    #[TestDox('meta() sets the metadata')]
    public function test_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ])->meta(['label' => 'User']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

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
        $issue = $result->error()->issues()[0];
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame(
            'rejected by custom constraint',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
