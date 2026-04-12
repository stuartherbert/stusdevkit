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

namespace StusDevKit\MissingBitsKit\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use StusDevKit\MissingBitsKit\Json;

/**
 * Tests for the {@link Json} class.
 */
#[TestDox(Json::class)]
class JsonTest extends TestCase
{
    private Json $json;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->json = new Json();
    }

    // ================================================================
    //
    // Constants Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test DEFAULT_DECODE_FLAGS has expected value.
     */
    #[TestDox('DEFAULT_DECODE_FLAGS is zero')]
    public function test_default_decode_flags_is_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the DEFAULT_DECODE_FLAGS constant
        // has the expected value of 0

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, Json::DEFAULT_DECODE_FLAGS);
    }

    /**
     * Test DEFAULT_ENCODE_FLAGS has expected value.
     */
    #[TestDox('DEFAULT_ENCODE_FLAGS is zero')]
    public function test_default_encode_flags_is_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the DEFAULT_ENCODE_FLAGS constant
        // has the expected value of 0

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, Json::DEFAULT_ENCODE_FLAGS);
    }

    /**
     * Test DEFAULT_VALIDATE_FLAGS has expected value.
     */
    #[TestDox('DEFAULT_VALIDATE_FLAGS is zero')]
    public function test_default_validate_flags_is_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the DEFAULT_VALIDATE_FLAGS constant
        // has the expected value of 0

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, Json::DEFAULT_VALIDATE_FLAGS);
    }

    /**
     * Test DEFAULT_DEPTH has expected value.
     */
    #[TestDox('DEFAULT_DEPTH is 512')]
    public function test_default_depth_is_512(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the DEFAULT_DEPTH constant has
        // the expected value of 512

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(512, Json::DEFAULT_DEPTH);
    }

    // ================================================================
    //
    // Encode Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test encoding basic types.
     */
    #[TestDox('can encode basic types')]
    public function test_encode_basic_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() can handle all basic PHP types
        // and produces valid JSON output

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'string' => 'value',
            'int' => 123,
            'bool' => true,
            'null' => null,
            'array' => [1, 2, 3],
        ];

        $expected = '{"string":"value","int":123,"bool":true,"null":null,"array":[1,2,3]}';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actualResult);
    }

    /**
     * Test encoding empty values.
     */
    #[TestDox('can encode empty array')]
    public function test_encode_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles empty arrays correctly

        // ----------------------------------------------------------------
        // setup your test

        $input = [];
        $expected = '[]';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actualResult);
    }

    #[TestDox('can encode empty object')]
    public function test_encode_empty_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles empty objects correctly

        // ----------------------------------------------------------------
        // setup your test

        $input = new \stdClass();
        $expected = '{}';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actualResult);
    }

    #[TestDox('can encode null value')]
    public function test_encode_null_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles null as the root value

        // ----------------------------------------------------------------
        // setup your test

        $input = null;
        $expected = 'null';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actualResult);
    }

    /**
     * Test encoding strings with special characters.
     */
    #[TestDox('can encode strings with special characters')]
    public function test_encode_strings_with_special_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() properly escapes special
        // characters in strings

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'quotes' => 'He said "Hello"',
            'backslash' => 'C:\\Users',
            'newline' => "line1\nline2",
            'tab' => "col1\tcol2",
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        // decode the result to verify it's valid JSON
        $decoded = $this->json->decode($actualResult, true);
        /** @var array<string, string> $decoded */
        $this->assertSame('He said "Hello"', $decoded['quotes']);
        $this->assertSame('C:\\Users', $decoded['backslash']);
        $this->assertSame("line1\nline2", $decoded['newline']);
        $this->assertSame("col1\tcol2", $decoded['tab']);
    }

    #[TestDox('can encode unicode strings')]
    public function test_encode_unicode_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles unicode characters
        // including emoji

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'emoji' => '👋',
            'chinese' => '你好',
            'russian' => 'Привет',
            'arabic' => 'مرحبا',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        // verify round-trip
        $decoded = $this->json->decode($actualResult, true);
        $this->assertSame($input, $decoded);
    }

    /**
     * Test encoding numbers.
     */
    #[TestDox('can encode various number types')]
    public function test_encode_number_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles integers, floats,
        // negative numbers, and large numbers

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'positive_int' => 42,
            'negative_int' => -17,
            'zero' => 0,
            'float' => 3.14159,
            'negative_float' => -2.718,
            'large_int' => 9223372036854775807,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $decoded = $this->json->decode($actualResult, true);
        /** @var array<string, mixed> $decoded */
        $this->assertSame(42, $decoded['positive_int']);
        $this->assertSame(-17, $decoded['negative_int']);
        $this->assertSame(0, $decoded['zero']);
        $this->assertIsFloat($decoded['float']);
        $this->assertEqualsWithDelta(3.14159, $decoded['float'], 0.00001);
    }

    /**
     * Test encoding nested structures.
     */
    #[TestDox('can encode deeply nested structures')]
    public function test_encode_nested_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles deeply nested data
        // structures

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'value' => 'deep',
                    ],
                ],
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $decoded = $this->json->decode($actualResult, true);
        /** @var array<string, array<string, array<string, array<string, string>>>> $decoded */
        $this->assertSame('deep', $decoded['level1']['level2']['level3']['value']);
    }

    /**
     * Test encoding with custom depth.
     */
    #[TestDox('respects custom depth parameter')]
    public function test_encode_custom_depth(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() respects the depth parameter
        // and throws an exception when depth is exceeded

        // ----------------------------------------------------------------
        // setup your test

        // create a deeply nested structure
        $input = ['level1' => ['level2' => ['level3' => 'value']]];

        // ----------------------------------------------------------------
        // perform the change

        // depth of 2 should fail for this 3-level structure
        $this->expectException(\JsonException::class);
        $this->json->encode($input, flags: 0, depth: 2);
    }

    /**
     * Test encoding with named parameters.
     */
    #[TestDox('supports named parameters')]
    public function test_encode_named_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() works with named parameters

        // ----------------------------------------------------------------
        // setup your test

        $input = ['a', 'b'];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode(
            input: $input,
            flags: JSON_PRETTY_PRINT,
            depth: 512,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString("\n", $actualResult);
    }

    /**
     * Test encoding with custom flags.
     */
    #[TestDox('can encode with custom flags')]
    public function test_encode_with_flags(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() respects custom flags
        // like JSON_PRETTY_PRINT

        // ----------------------------------------------------------------
        // setup your test

        $input = ['a', 'b'];
        $expected = <<<'JSON'
            [
                "a",
                "b"
            ]
            JSON;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input, JSON_PRETTY_PRINT);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actualResult);
    }

    /**
     * Test encoding when caller passes JSON_THROW_ON_ERROR.
     */
    #[TestDox('handles caller passing JSON_THROW_ON_ERROR')]
    public function test_encode_with_throw_on_error_flag(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() works correctly when the
        // caller redundantly passes JSON_THROW_ON_ERROR, since
        // encode() always ORs this flag in

        // ----------------------------------------------------------------
        // setup your test

        $input = ['key' => 'value'];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode(
            $input,
            JSON_THROW_ON_ERROR,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('{"key":"value"}', $actualResult);
    }

    /**
     * Test encoding with multiple combined flags.
     */
    #[TestDox('can encode with multiple combined flags')]
    public function test_encode_with_combined_flags(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() works correctly when
        // multiple flags are combined via bitwise OR

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'url' => 'https://example.com/path',
            'greeting' => '你好',
        ];
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input, $flags);

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            'https://example.com/path',
            $actualResult,
        );
        $this->assertStringContainsString('你好', $actualResult);
    }

    /**
     * Test encoding error triggers exception.
     */
    #[TestDox('throws exception on encoding error')]
    public function test_encode_throws_exception_on_error(): void
    {
        // We use a value that cannot be encoded (e.g., circular reference)
        $input = [];
        $input['self'] = &$input;

        $this->expectException(\JsonException::class);
        $this->json->encode($input);
    }

    // ================================================================
    //
    // Decode Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test decoding valid JSON strings.
     */
    #[TestDox('can decode valid JSON strings')]
    public function test_decode_valid_json(): void
    {
        $json = '{"key":"value","number":123}';
        $expected = ['key' => 'value', 'number' => 123];

        $this->assertSame($expected, $this->json->decode($json, true));
    }

    /**
     * Test decoding associative array requirement.
     */
    #[TestDox('can decode into associative arrays')]
    public function test_decode_associative_true(): void
    {
        $json = '{"a":1}';
        $this->assertIsArray($this->json->decode($json, true));
        $this->assertArrayHasKey('a', $this->json->decode($json, true));
    }

    /**
     * Test decoding error triggers exception.
     */
    #[TestDox('throws exception on decoding error')]
    public function test_decode_throws_exception_on_error(): void
    {
        $invalidJson = '{"key": "unclosed quote}';

        $this->expectException(\JsonException::class);
        $this->json->decode($invalidJson);
    }

    /**
     * Test decoding empty string throws exception.
     */
    #[TestDox('throws exception on empty string')]
    public function test_decode_throws_exception_on_empty_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() throws a \JsonException
        // when given an empty string, which is not valid JSON

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(\JsonException::class);
        $this->json->decode('');
    }

    /**
     * Test decoding whitespace-only string throws exception.
     */
    #[TestDox('throws exception on whitespace-only string')]
    public function test_decode_throws_exception_on_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() throws a \JsonException
        // when given a string containing only whitespace

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(\JsonException::class);
        $this->json->decode("   \n\t  ");
    }

    // ================================================================
    //
    // Validate Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test validating valid JSON.
     */
    #[TestDox('returns empty array for valid JSON')]
    public function test_validate_valid_json(): void
    {
        $json = '{"a":1}';

        $this->assertEmpty($this->json->validate($json));
    }

    /**
     * Test validating invalid JSON.
     */
    #[TestDox('returns error details for invalid JSON')]
    public function test_validate_invalid_json(): void
    {
        $invalidJson = '{"a":1'; // missing closing brace

        $result = $this->json->validate($invalidJson);

        $this->assertNotEmpty($result);
        /** @var array{0: int, 1: string} $result */
        $this->assertIsInt($result[0]);
        $this->assertIsString($result[1]);
    }

    // ================================================================
    //
    // Additional Decode Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test decoding JSON objects as stdClass.
     */
    #[TestDox('can decode JSON object as stdClass')]
    public function test_decode_object_as_stdclass(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() with associative=false
        // returns stdClass objects for JSON objects

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"name":"John","age":30}';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode($json, false);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertSame('John', $result->name);
        $this->assertSame(30, $result->age);
    }

    /**
     * Test decoding JSON arrays.
     */
    #[TestDox('can decode JSON array')]
    public function test_decode_json_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() correctly handles JSON
        // arrays as the root element

        // ----------------------------------------------------------------
        // setup your test

        $json = '[1, 2, 3, "four", true]';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode($json, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsArray($result);
        $this->assertSame([1, 2, 3, 'four', true], $result);
    }

    /**
     * Test decoding JSON string primitive.
     */
    #[TestDox('can decode JSON string primitive')]
    public function test_decode_string_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles a JSON string
        // as the root element

        // ----------------------------------------------------------------
        // setup your test

        $json = '"hello world"';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsString($result);
        $this->assertSame('hello world', $result);
    }

    /**
     * Test decoding JSON integer primitive.
     */
    #[TestDox('can decode JSON integer primitive')]
    public function test_decode_integer_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles an integer as
        // the root element

        // ----------------------------------------------------------------
        // setup your test

        $json = '42';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsInt($actualResult);
        $this->assertSame(42, $actualResult);
    }

    /**
     * Test decoding JSON float primitive.
     */
    #[TestDox('can decode JSON float primitive')]
    public function test_decode_float_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles a float as the
        // root element

        // ----------------------------------------------------------------
        // setup your test

        $json = '3.14159';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsFloat($actualResult);
        $this->assertEqualsWithDelta(
            3.14159,
            $actualResult,
            0.00001,
        );
    }

    /**
     * Test decoding JSON negative integer primitive.
     */
    #[TestDox('can decode JSON negative integer primitive')]
    public function test_decode_negative_integer_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles a negative integer
        // as the root element

        // ----------------------------------------------------------------
        // setup your test

        $json = '-17';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsInt($actualResult);
        $this->assertSame(-17, $actualResult);
    }

    /**
     * Test decoding JSON boolean true primitive.
     */
    #[TestDox('can decode JSON boolean true primitive')]
    public function test_decode_boolean_true_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles true as a root
        // element

        // ----------------------------------------------------------------
        // setup your test

        $json = 'true';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * Test decoding JSON boolean false primitive.
     */
    #[TestDox('can decode JSON boolean false primitive')]
    public function test_decode_boolean_false_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles false as a root
        // element

        // ----------------------------------------------------------------
        // setup your test

        $json = 'false';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    /**
     * Test decoding JSON null primitive.
     */
    #[TestDox('can decode JSON null primitive')]
    public function test_decode_null_primitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() handles null as a root
        // element

        // ----------------------------------------------------------------
        // setup your test

        $json = 'null';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    /**
     * Test decoding with default associative parameter (null).
     */
    #[TestDox('decodes JSON objects as stdClass by default')]
    public function test_decode_default_associative_returns_stdclass(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() with the default
        // associative parameter (null) returns stdClass objects
        // for JSON objects, matching json_decode() default

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"name":"John","age":30}';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertSame('John', $result->name);
        $this->assertSame(30, $result->age);
    }

    /**
     * Test decoding with JSON_BIGINT_AS_STRING flag.
     */
    #[TestDox('can decode with JSON_BIGINT_AS_STRING flag')]
    public function test_decode_with_bigint_as_string_flag(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() respects the
        // JSON_BIGINT_AS_STRING flag, returning large integers
        // as strings to preserve precision

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"big":99999999999999999999}';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode(
            input: $json,
            associative: true,
            flags: JSON_BIGINT_AS_STRING,
        );

        // ----------------------------------------------------------------
        // test the results

        /** @var array<string, string> $result */
        $this->assertIsString($result['big']);
        $this->assertSame(
            '99999999999999999999',
            $result['big'],
        );
    }

    /**
     * Test decoding with named parameters.
     */
    #[TestDox('supports named parameters in decode')]
    public function test_decode_named_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() works correctly with
        // named parameters

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"a":1}';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->decode(
            input: $json,
            associative: true,
            depth: 512,
            flags: 0,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a' => 1], $actualResult);
    }

    /**
     * Test round-trip encode/decode.
     */
    #[TestDox('encode and decode are inverse operations')]
    public function test_encode_decode_round_trip(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encoding then decoding returns the
        // original PHP value

        // ----------------------------------------------------------------
        // setup your test

        $original = [
            'string' => 'hello',
            'int' => 42,
            'float' => 3.14,
            'bool' => true,
            'null' => null,
            'array' => [1, 2, 3],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $encoded = $this->json->encode($original);
        $decoded = $this->json->decode($encoded, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($original, $decoded);
    }

    /**
     * Test decoding succeeds within depth limit.
     */
    #[TestDox('decodes successfully within custom depth limit')]
    public function test_decode_succeeds_within_depth(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() successfully decodes
        // JSON when the nesting is within the specified depth

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->decode($json, true, 5);

        // ----------------------------------------------------------------
        // test the results

        /** @var array<string, array<string, array<string, string>>> $result */
        $this->assertSame(
            'value',
            $result['level1']['level2']['level3'],
        );
    }

    /**
     * Test decode with custom depth.
     */
    #[TestDox('respects custom depth parameter in decode')]
    public function test_decode_custom_depth(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() respects the depth
        // parameter and throws when exceeded

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // perform the change

        // depth of 2 should fail for this 3-level structure
        $this->expectException(\JsonException::class);
        $this->json->decode($json, true, 2);
    }

    // ================================================================
    //
    // Additional Validate Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test validating empty string.
     */
    #[TestDox('returns error for empty string')]
    public function test_validate_empty_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() rejects an empty string
        // as invalid JSON

        // ----------------------------------------------------------------
        // setup your test

        $input = '';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    /**
     * Test validating whitespace-only string.
     */
    #[TestDox('returns error for whitespace-only string')]
    public function test_validate_whitespace_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() rejects a whitespace-
        // only string as invalid JSON

        // ----------------------------------------------------------------
        // setup your test

        $input = "   \n\t  ";

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEmpty($result);
    }

    /**
     * Test validating various invalid JSON formats.
     */
    #[DataProvider('getInvalidJsonProvider')]
    #[TestDox('returns errors for various invalid JSON formats')]
    public function test_validate_various_invalid_formats(string $json): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() correctly identifies
        // various types of malformed JSON

        // ----------------------------------------------------------------
        // perform the change and test results

        $result = $this->json->validate($json);
        $this->assertNotEmpty(
            $result,
            "Expected {$json} to be invalid but it was accepted",
        );
        $this->assertCount(2, $result);
    }

    /**
     * Data provider for various invalid JSON formats.
     *
     * @return array<string, list<string>>
     */
    public static function getInvalidJsonProvider(): array
    {
        return [
            'missing closing brace' => ['{"unclosed brace'],
            'trailing comma'        => ['[1, 2, 3,]'],
            'unquoted key'          => ['{key: "value"}'],
            'unquoted string value' => ['{"key": value}'],
            'incomplete boolean'    => ['tru'],
            'incomplete null'       => ['nul'],
            'multiple values'       => ['{"a": 1} {"b": 2}'],
            'wrong format'          => ['<xml>not json</xml>'],
            'invalid escape sequence' => ['{"escaped": "bad\\n\'}'],
        ];
    }

    /**
     * Test validating valid JSON with whitespace.
     */
    #[TestDox('accepts valid JSON with surrounding whitespace')]
    public function test_validate_json_with_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() accepts valid JSON even
        // when surrounded by whitespace (which json_decode allows)

        // ----------------------------------------------------------------
        // setup your test

        $json = '   {"a": 1}   ';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEmpty($result);
    }

    /**
     * Test validate accepts JSON within depth limit.
     */
    #[TestDox('accepts JSON within custom depth limit')]
    public function test_validate_accepts_json_within_depth(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() accepts JSON when the
        // nesting is within the specified depth limit

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($json, 5);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEmpty($result);
    }

    /**
     * Test validate rejects JSON exceeding depth limit.
     */
    #[TestDox('rejects JSON exceeding custom depth limit')]
    public function test_validate_rejects_json_exceeding_depth(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() rejects JSON when the
        // nesting exceeds the specified depth limit

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($json, 2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEmpty($result);
    }

    /**
     * Test validating various valid JSON primitives.
     */
    #[DataProvider('getValidJsonPrimitivesProvider')]
    #[TestDox('accepts valid JSON primitives')]
    public function test_validate_accepts_valid_primitives(
        string $json,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() accepts various valid
        // JSON primitives as root elements

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEmpty(
            $result,
            "Expected {$json} to be valid but it was rejected",
        );
    }

    /**
     * Data provider for valid JSON primitives.
     *
     * @return array<string, list<string>>
     */
    public static function getValidJsonPrimitivesProvider(): array
    {
        return [
            'array'         => ['[1, 2, 3]'],
            'string'        => ['"hello"'],
            'integer'       => ['42'],
            'negative int'  => ['-17'],
            'float'         => ['3.14'],
            'boolean true'  => ['true'],
            'boolean false' => ['false'],
            'null'          => ['null'],
            'empty array'   => ['[]'],
            'empty object'  => ['{}'],
        ];
    }

    /**
     * Test validate returns meaningful error code and message.
     */
    #[TestDox('returns meaningful error code and message')]
    public function test_validate_returns_meaningful_error_details(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validate() returns a non-zero
        // error code and a non-empty error message when given
        // invalid JSON

        // ----------------------------------------------------------------
        // setup your test

        $invalidJson = '{"a":1';

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->validate($invalidJson);

        // ----------------------------------------------------------------
        // test the results

        /** @var array{0: int, 1: string} $result */
        $this->assertNotSame(0, $result[0]);
        $this->assertNotEmpty($result[1]);
    }

    // ================================================================
    //
    // Additional Encode Tests
    //
    // ----------------------------------------------------------------

    /**
     * Test encoding boolean as root value.
     */
    #[TestDox('can encode boolean true as root')]
    public function test_encode_boolean_true_root(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles boolean true as
        // the root value

        // ----------------------------------------------------------------
        // setup your test

        $input = true;

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('true', $result);
    }

    #[TestDox('can encode boolean false as root')]
    public function test_encode_boolean_false_root(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles boolean false as
        // the root value

        // ----------------------------------------------------------------
        // setup your test

        $input = false;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('false', $actualResult);
    }

    /**
     * Test encoding integer as root value.
     */
    #[TestDox('can encode integer as root')]
    public function test_encode_integer_root(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles an integer as the
        // root value

        // ----------------------------------------------------------------
        // setup your test

        $input = 42;

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('42', $result);
    }

    /**
     * Test encoding float as root value.
     */
    #[TestDox('can encode float as root')]
    public function test_encode_float_root(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles a float as the
        // root value

        // ----------------------------------------------------------------
        // setup your test

        $input = 3.14159;

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString('3.14', $result);
    }

    /**
     * Test encoding string as root value.
     */
    #[TestDox('can encode string as root')]
    public function test_encode_string_root(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles a string as the
        // root value

        // ----------------------------------------------------------------
        // setup your test

        $input = 'hello world';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('"hello world"', $actualResult);
    }

    /**
     * Test encoding with JSON_UNESCAPED_SLASHES flag.
     */
    #[TestDox('can encode with JSON_UNESCAPED_SLASHES')]
    public function test_encode_unescaped_slashes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() respects the
        // JSON_UNESCAPED_SLASHES flag

        // ----------------------------------------------------------------
        // setup your test

        $input = ['url' => 'https://example.com'];

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input, JSON_UNESCAPED_SLASHES);

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringNotContainsString('\\/', $result);
        $this->assertStringContainsString('//', $result);
    }

    /**
     * Test encoding with JSON_UNESCAPED_UNICODE flag.
     */
    #[TestDox('can encode with JSON_UNESCAPED_UNICODE')]
    public function test_encode_unescaped_unicode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() respects the
        // JSON_UNESCAPED_UNICODE flag to output unicode characters
        // directly instead of escaped sequences

        // ----------------------------------------------------------------
        // setup your test

        $input = ['greeting' => '你好'];

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input, JSON_UNESCAPED_UNICODE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString('你好', $result);
    }

    /**
     * Test encoding with JSON_PRETTY_PRINT and JSON_INDENT.
     */
    #[TestDox('can encode with pretty print')]
    public function test_encode_pretty_print(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() produces nicely formatted
        // output when JSON_PRETTY_PRINT is used

        // ----------------------------------------------------------------
        // setup your test

        $input = ['a' => 1, 'b' => [2, 3]];

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input, JSON_PRETTY_PRINT);

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString("\n", $result);
        $this->assertStringContainsString('  ', $result); // indentation
    }

    /**
     * Test encoding nested stdClass objects.
     */
    #[TestDox('can encode nested stdClass objects')]
    public function test_encode_nested_stdclass(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles nested stdClass
        // objects correctly

        // ----------------------------------------------------------------
        // setup your test

        $inner = new \stdClass();
        $inner->value = 'deep';

        $outer = new \stdClass();
        $outer->nested = $inner;

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($outer);

        // ----------------------------------------------------------------
        // test the results

        $decoded = $this->json->decode($result, false);
        $this->assertInstanceOf(\stdClass::class, $decoded);
        $this->assertInstanceOf(\stdClass::class, $decoded->nested);
        $this->assertSame('deep', $decoded->nested->value);
    }

    /**
     * Test encoding a resource.
     */
    #[TestDox('throws exception when encoding a resource')]
    public function test_encode_resource_throws_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() throws a \JsonException when
        // attempting to encode a PHP resource, as resources cannot be
        // converted to JSON

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(\JsonException::class);
        $this->json->encode($resource);
    }

    /**
     * Test encoding PHP_INT_MAX without precision loss.
     */
    #[TestDox('can encode PHP_INT_MAX without precision loss')]
    public function test_encode_php_int_max(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() handles PHP_INT_MAX
        // correctly, preserving full precision through an
        // encode/decode round-trip

        // ----------------------------------------------------------------
        // setup your test

        $input = PHP_INT_MAX;

        // ----------------------------------------------------------------
        // perform the change

        $result = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $decoded = $this->json->decode($result);
        $this->assertSame(PHP_INT_MAX, $decoded);
    }
}
