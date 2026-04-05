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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Validate;

#[TestDox('Validate::lazy()')]
class ValidateLazyTest extends TestCase
{
    // ================================================================
    //
    // Basic Delegation
    //
    // ----------------------------------------------------------------

    #[TestDox('delegates parse() to the resolved schema')]
    public function test_delegates_parse_to_resolved_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::lazy() resolves the
        // factory closure and delegates parse() to the
        // resulting schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string()->min(length: 1),
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

    #[TestDox('delegates safeParse() to the resolved schema')]
    public function test_delegates_safe_parse_to_resolved_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() delegates to the
        // resolved schema and returns a ParseResult

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertSame('hello', $result->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('delegates encode() to the resolved schema')]
    public function test_delegates_encode_to_resolved_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() delegates to the
        // resolved schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
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

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('delegates safeEncode() to the resolved schema')]
    public function test_delegates_safe_encode_to_resolved_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeEncode() delegates to the
        // resolved schema and returns a ParseResult

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeEncode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertSame('hello', $result->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Validation Errors
    //
    // ----------------------------------------------------------------

    #[TestDox('rejects invalid data via the resolved schema')]
    public function test_rejects_invalid_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors from the
        // resolved schema propagate through the lazy wrapper

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
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
    // Factory Caching
    //
    // ----------------------------------------------------------------

    #[TestDox('calls the factory closure only once')]
    public function test_factory_called_once(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the factory closure is called
        // exactly once and the result is cached for subsequent
        // parse calls

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $callCount = 0;
        $unit = Validate::lazy(
            function () use (&$callCount) {
                $callCount++;
                return Validate::string();
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $unit->parse('first');
        $unit->parse('second');
        $unit->parse('third');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $callCount);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Recursive Schemas
    //
    // ----------------------------------------------------------------

    #[TestDox('validates a recursive tree structure')]
    public function test_validates_recursive_tree(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::lazy() enables
        // recursive schema definitions by validating a tree
        // with nested children

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $treeNode = Validate::object([
            'value' => Validate::string(),
            'children' => Validate::array(
                Validate::lazy(
                    function () use (&$treeNode) {
                        /** @var \StusDevKit\ValidationKit\Contracts\ValidationSchema<\stdClass> $treeNode */
                        return $treeNode;
                    },
                ),
            ),
        ]);

        $input = (object) [
            'value' => 'root',
            'children' => [
                (object) [
                    'value' => 'child-1',
                    'children' => [
                        (object) [
                            'value' => 'grandchild-1',
                            'children' => [],
                        ],
                    ],
                ],
                (object) [
                    'value' => 'child-2',
                    'children' => [],
                ],
            ],
        ];

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $treeNode->parse($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($input, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects invalid data in a recursive structure')]
    public function test_rejects_invalid_recursive_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors within a
        // recursive structure include the correct nested path

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $treeNode = Validate::object([
            'value' => Validate::string(),
            'children' => Validate::array(
                Validate::lazy(
                    function () use (&$treeNode) {
                        /** @var \StusDevKit\ValidationKit\Contracts\ValidationSchema<\stdClass> $treeNode */
                        return $treeNode;
                    },
                ),
            ),
        ]);

        $input = (object) [
            'value' => 'root',
            'children' => [
                (object) [
                    'value' => 123,
                    'children' => [],
                ],
            ],
        ];

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $treeNode->safeParse($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $issues = $result->maybeError()->issues()->jsonSerialize();
        $this->assertCount(1, $issues);
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_type',
            $issues[0]['type'],
        );
        $this->assertSame(
            ['children', 0, 'value'],
            $issues[0]['path'],
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('validates deeply nested recursive structures')]
    public function test_validates_deeply_nested_recursive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that recursive schemas work at
        // arbitrary depth, not just one or two levels

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $linkedList = Validate::object([
            'value' => Validate::int(),
            'next' => Validate::nullable(
                Validate::lazy(
                    function () use (&$linkedList) {
                        /** @var \StusDevKit\ValidationKit\Contracts\ValidationSchema<\stdClass> $linkedList */
                        return $linkedList;
                    },
                ),
            ),
        ]);

        $input = (object) [
            'value' => 1,
            'next' => (object) [
                'value' => 2,
                'next' => (object) [
                    'value' => 3,
                    'next' => (object) [
                        'value' => 4,
                        'next' => null,
                    ],
                ],
            ],
        ];

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $linkedList->parse($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($input, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Builder Methods
    //
    // ----------------------------------------------------------------

    #[TestDox('withDefault() works through lazy wrapper')]
    public function test_with_default_works(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that builder methods like
        // withDefault() can be called on a lazy schema and
        // are correctly applied to the resolved schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        )->withDefault('fallback');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('fallback', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCatch() works through lazy wrapper')]
    public function test_with_catch_works(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCatch() provides a
        // fallback value when validation fails through the
        // lazy wrapper

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        )->withCatch('fallback');

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

    #[TestDox('withDescription() works through lazy wrapper')]
    public function test_with_description_works(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that metadata methods work
        // through the lazy wrapper

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        )->withDescription('a lazy string');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('a lazy string', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Aliases
    //
    // ----------------------------------------------------------------

    #[TestDox('decode() delegates to resolved schema')]
    public function test_decode_delegates(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() (alias for parse())
        // delegates to the resolved schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->decode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeDecode() delegates to resolved schema')]
    public function test_safe_decode_delegates(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeDecode() (alias for
        // safeParse()) delegates to the resolved schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::lazy(
            fn() => Validate::string(),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeDecode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertSame('hello', $result->data());

        // ----------------------------------------------------------------
        // clean up the database

    }
}
