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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Json;

use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use stdClass;
use StusDevKit\MissingBitsKit\Json\Json;
use StusDevKit\MissingBitsKit\Json\JsonValidationError;

use const JSON_BIGINT_AS_STRING;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const PHP_INT_MAX;

/**
 * Contract + behaviour tests for the Json wrapper class.
 *
 * The combination reads as "here's what it IS, here's what it DOES":
 * identity and shape pin the published surface, and the behaviour
 * sections exercise encode(), decode() and validate() end-to-end.
 */
class JsonTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Json namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // type-hint against the FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Json';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(Json::class))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('has a private constructor')]
    public function test_has_a_private_constructor(): void
    {
        // the class is a static utility — callers never instantiate it.
        // A public or protected constructor would be a design bug.

        $reflection = new ReflectionClass(Json::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertTrue($constructor->isPrivate());
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only encode(), decode() and validate() as public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // the class exists to wrap three PHP builtins. Pin the method
        // set by enumeration - any addition fails with a diff that
        // names the new method, rather than a cryptic count mismatch.

        $expected = ['decode', 'encode', 'validate'];
        $reflection = new ReflectionClass(Json::class);

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Published class constants
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only the expected public class constants')]
    public function test_exposes_only_the_expected_class_constants(): void
    {
        // the class constants are part of the published surface -
        // callers reference them as `Json::DEFAULT_*` to pass the
        // library's own defaults through. Pin the set so any
        // addition or rename is caught.

        $expected = [
            'DEFAULT_DECODE_FLAGS',
            'DEFAULT_DEPTH',
            'DEFAULT_ENCODE_FLAGS',
            'DEFAULT_VALIDATE_FLAGS',
        ];
        $reflection = new ReflectionClass(Json::class);

        $actual = array_keys($reflection->getConstants());
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    /**
     * @param int $expected
     */
    #[DataProvider('getClassConstantValuesProvider')]
    #[TestDox('$constant has the value $expected')]
    public function test_class_constant_has_expected_value(
        string $constant,
        int $expected,
    ): void {
        // the default flag / depth values are part of the contract.
        // A silent change (e.g. switching DEFAULT_DEPTH from 512 to
        // 256) changes the behaviour of every caller that relied on
        // the library's default and so must be pinned.

        $actual = constant(Json::class . '::' . $constant);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0: string, 1: int}>
     */
    public static function getClassConstantValuesProvider(): array
    {
        return [
            'DEFAULT_DECODE_FLAGS'   => ['DEFAULT_DECODE_FLAGS', 0],
            'DEFAULT_ENCODE_FLAGS'   => ['DEFAULT_ENCODE_FLAGS', 0],
            'DEFAULT_VALIDATE_FLAGS' => ['DEFAULT_VALIDATE_FLAGS', 0],
            'DEFAULT_DEPTH'          => ['DEFAULT_DEPTH', 512],
        ];
    }

    // ================================================================
    //
    // encode() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('::encode() declares $input, $flags and $depth as parameters in that order')]
    public function test_encode_declares_expected_parameters(): void
    {
        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        $expected = ['input', 'flags', 'depth'];
        $method = (new ReflectionClass(Json::class))->getMethod('encode');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // encode() - happy path
    //
    // ----------------------------------------------------------------

    #[TestDox('::encode() encodes an associative array as a JSON object')]
    public function test_encodes_an_associative_array_as_a_json_object(): void
    {
        $input = [
            'string' => 'value',
            'int'    => 123,
            'bool'   => true,
            'null'   => null,
            'array'  => [1, 2, 3],
        ];
        $expected = '{"string":"value","int":123,"bool":true,"null":null,"array":[1,2,3]}';

        $actual = Json::encode($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() encodes an empty array as []')]
    public function test_encodes_an_empty_array_as_a_json_array(): void
    {
        $input = [];
        $expected = '[]';

        $actual = Json::encode($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() encodes an empty stdClass as {}')]
    public function test_encodes_an_empty_stdclass_as_a_json_object(): void
    {
        $input = new stdClass();
        $expected = '{}';

        $actual = Json::encode($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() encodes null as the root value')]
    public function test_encodes_null_as_the_root_value(): void
    {
        $expected = 'null';

        $actual = Json::encode(null);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() encodes boolean true as the root value')]
    public function test_encodes_boolean_true_as_the_root_value(): void
    {
        $actual = Json::encode(true);

        $this->assertSame('true', $actual);
    }

    #[TestDox('::encode() encodes boolean false as the root value')]
    public function test_encodes_boolean_false_as_the_root_value(): void
    {
        $actual = Json::encode(false);

        $this->assertSame('false', $actual);
    }

    #[TestDox('::encode() encodes an integer as the root value')]
    public function test_encodes_an_integer_as_the_root_value(): void
    {
        $actual = Json::encode(42);

        $this->assertSame('42', $actual);
    }

    #[TestDox('::encode() encodes a string as the root value')]
    public function test_encodes_a_string_as_the_root_value(): void
    {
        $actual = Json::encode('hello world');

        $this->assertSame('"hello world"', $actual);
    }

    #[TestDox('::encode() encodes PHP_INT_MAX without precision loss')]
    public function test_encodes_php_int_max_without_precision_loss(): void
    {
        // PHP's json_encode() writes integers as decimal digits, and
        // json_decode() reads them back as ints. A round-trip through
        // the wrapper must preserve PHP_INT_MAX exactly, because that
        // is the largest value a PHP int can hold - anything lost
        // here is a silent data-loss bug.

        $input = PHP_INT_MAX;

        $encoded = Json::encode($input);
        $decoded = Json::decode($encoded);

        $this->assertSame(PHP_INT_MAX, $decoded);
    }

    #[TestDox('::encode() escapes special characters in strings and round-trips cleanly')]
    public function test_escapes_special_characters_in_strings(): void
    {
        // encode() must produce JSON that decodes back to the same
        // PHP string - escape sequences for `"`, `\`, newline and
        // tab are the non-obvious ones.

        $input = [
            'quotes'    => 'He said "Hello"',
            'backslash' => 'C:\\Users',
            'newline'   => "line1\nline2",
            'tab'       => "col1\tcol2",
        ];

        $encoded = Json::encode($input);
        $decoded = Json::decode($encoded, true);

        $this->assertSame($input, $decoded);
    }

    #[TestDox('::encode() preserves unicode characters through a round-trip')]
    public function test_preserves_unicode_characters_through_a_round_trip(): void
    {
        $input = [
            'emoji'   => '👋',
            'chinese' => '你好',
            'russian' => 'Привет',
            'arabic'  => 'مرحبا',
        ];

        $encoded = Json::encode($input);
        $decoded = Json::decode($encoded, true);

        $this->assertSame($input, $decoded);
    }

    #[TestDox('::encode() encodes deeply nested arrays')]
    public function test_encodes_deeply_nested_arrays(): void
    {
        $input = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'value' => 'deep',
                    ],
                ],
            ],
        ];
        $expected = '{"level1":{"level2":{"level3":{"value":"deep"}}}}';

        $actual = Json::encode($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() encodes nested stdClass objects')]
    public function test_encodes_nested_stdclass_objects(): void
    {
        $inner = new stdClass();
        $inner->value = 'deep';

        $outer = new stdClass();
        $outer->nested = $inner;

        $expected = '{"nested":{"value":"deep"}}';

        $actual = Json::encode($outer);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // encode() - flags
    //
    // ----------------------------------------------------------------

    #[TestDox('::encode() respects JSON_PRETTY_PRINT')]
    public function test_encode_respects_json_pretty_print(): void
    {
        $input = ['a', 'b'];
        $expected = <<<'JSON'
            [
                "a",
                "b"
            ]
            JSON;

        $actual = Json::encode($input, JSON_PRETTY_PRINT);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() respects JSON_UNESCAPED_SLASHES')]
    public function test_encode_respects_json_unescaped_slashes(): void
    {
        $input = ['url' => 'https://example.com'];
        $expected = '{"url":"https://example.com"}';

        $actual = Json::encode($input, JSON_UNESCAPED_SLASHES);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() respects JSON_UNESCAPED_UNICODE')]
    public function test_encode_respects_json_unescaped_unicode(): void
    {
        $input = ['greeting' => '你好'];
        $expected = '{"greeting":"你好"}';

        $actual = Json::encode($input, JSON_UNESCAPED_UNICODE);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() accepts combined flags via bitwise OR')]
    public function test_encode_accepts_combined_flags_via_bitwise_or(): void
    {
        // callers commonly OR flags together (e.g. "unescaped
        // slashes + unescaped unicode" for URLs with i18n text).
        // The wrapper must not drop or reinterpret any bit of the
        // composed mask.

        $input = [
            'url'      => 'https://example.com/path',
            'greeting' => '你好',
        ];
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $expected = '{"url":"https://example.com/path","greeting":"你好"}';

        $actual = Json::encode($input, $flags);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::encode() accepts JSON_THROW_ON_ERROR from the caller as a no-op')]
    public function test_encode_accepts_json_throw_on_error_as_a_no_op(): void
    {
        // encode() always ORs JSON_THROW_ON_ERROR into the mask. If
        // the caller passes it in explicitly the bitwise OR must be
        // idempotent - a double-set must not silently change any
        // other behaviour.

        $input = ['key' => 'value'];
        $expected = '{"key":"value"}';

        $actual = Json::encode($input, JSON_THROW_ON_ERROR);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // encode() - error conditions
    //
    // ----------------------------------------------------------------

    #[TestDox('::encode() throws JsonException on a circular reference')]
    public function test_encode_throws_on_a_circular_reference(): void
    {
        // json_encode() returns false (and sets an error) on a
        // circular reference. The wrapper forces JSON_THROW_ON_ERROR,
        // so the caller must see a JsonException instead of a
        // silently false-valued return.

        $input = [];
        $input['self'] = &$input;

        $this->expectException(JsonException::class);

        Json::encode($input);
    }

    #[TestDox('::encode() throws JsonException when depth is exceeded')]
    public function test_encode_throws_when_depth_is_exceeded(): void
    {
        // a 3-level structure at depth=2 must fail
        $input = ['level1' => ['level2' => ['level3' => 'value']]];

        $this->expectException(JsonException::class);

        Json::encode(input: $input, flags: 0, depth: 2);
    }

    #[TestDox('::encode() throws JsonException when given a resource')]
    public function test_encode_throws_when_given_a_resource(): void
    {
        // resources have no JSON representation. json_encode() would
        // normally return false; the wrapper must throw instead.

        $resource = fopen('php://memory', 'r');

        $this->expectException(JsonException::class);

        Json::encode($resource);
    }

    // ================================================================
    //
    // decode() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('::decode() declares $input, $associative, $depth and $flags as parameters in that order')]
    public function test_decode_declares_expected_parameters(): void
    {
        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        $expected = ['input', 'associative', 'depth', 'flags'];
        $method = (new ReflectionClass(Json::class))->getMethod('decode');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // decode() - happy path
    //
    // ----------------------------------------------------------------

    #[TestDox('::decode() decodes a JSON object as stdClass by default')]
    public function test_decodes_a_json_object_as_stdclass_by_default(): void
    {
        // `associative` defaults to null, which json_decode()
        // interprets as "return stdClass". Pin the default so any
        // future change to the wrapper that flips this is caught.

        $json = '{"name":"John","age":30}';

        $actual = Json::decode($json);

        $this->assertInstanceOf(stdClass::class, $actual);
        $this->assertSame('John', $actual->name);
        $this->assertSame(30, $actual->age);
    }

    #[TestDox('::decode() decodes a JSON object as an associative array when associative is true')]
    public function test_decodes_a_json_object_as_an_associative_array(): void
    {
        $json = '{"key":"value","number":123}';
        $expected = ['key' => 'value', 'number' => 123];

        $actual = Json::decode($json, true);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::decode() decodes a JSON array')]
    public function test_decodes_a_json_array(): void
    {
        $json = '[1, 2, 3, "four", true]';
        $expected = [1, 2, 3, 'four', true];

        $actual = Json::decode($json, true);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::decode() decodes root-level JSON string primitive')]
    public function test_decodes_root_level_string_primitive(): void
    {
        $actual = Json::decode('"hello world"');

        $this->assertSame('hello world', $actual);
    }

    #[TestDox('::decode() decodes root-level JSON integer primitive')]
    public function test_decodes_root_level_integer_primitive(): void
    {
        $actual = Json::decode('42');

        $this->assertSame(42, $actual);
    }

    #[TestDox('::decode() decodes root-level JSON negative integer primitive')]
    public function test_decodes_root_level_negative_integer_primitive(): void
    {
        $actual = Json::decode('-17');

        $this->assertSame(-17, $actual);
    }

    #[TestDox('::decode() decodes root-level JSON float primitive')]
    public function test_decodes_root_level_float_primitive(): void
    {
        $actual = Json::decode('3.14159');

        $this->assertSame(3.14159, $actual);
    }

    #[TestDox('::decode() decodes root-level JSON boolean true primitive')]
    public function test_decodes_root_level_boolean_true_primitive(): void
    {
        $actual = Json::decode('true');

        $this->assertTrue($actual);
    }

    #[TestDox('::decode() decodes root-level JSON boolean false primitive')]
    public function test_decodes_root_level_boolean_false_primitive(): void
    {
        $actual = Json::decode('false');

        $this->assertFalse($actual);
    }

    #[TestDox('::decode() decodes root-level JSON null primitive')]
    public function test_decodes_root_level_null_primitive(): void
    {
        $actual = Json::decode('null');

        $this->assertNull($actual);
    }

    // ================================================================
    //
    // decode() - flags
    //
    // ----------------------------------------------------------------

    #[TestDox('::decode() respects JSON_BIGINT_AS_STRING')]
    public function test_decode_respects_json_bigint_as_string(): void
    {
        // a 20-digit integer exceeds PHP_INT_MAX on a 64-bit
        // platform. JSON_BIGINT_AS_STRING tells json_decode() to
        // return the number as a string rather than coercing to a
        // float (which would lose precision). The wrapper must pass
        // this flag through unchanged.

        $json = '{"big":99999999999999999999}';

        $actual = Json::decode(
            input: $json,
            associative: true,
            flags: JSON_BIGINT_AS_STRING,
        );

        $this->assertSame(['big' => '99999999999999999999'], $actual);
    }

    #[TestDox('::decode() succeeds when nesting is within the custom depth limit')]
    public function test_decode_succeeds_within_custom_depth_limit(): void
    {
        $json = '{"level1":{"level2":{"level3":"value"}}}';
        $expected = ['level1' => ['level2' => ['level3' => 'value']]];

        $actual = Json::decode(
            input: $json,
            associative: true,
            depth: 5,
        );

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // decode() - error conditions
    //
    // ----------------------------------------------------------------

    #[TestDox('::decode() throws JsonException on syntactically invalid JSON')]
    public function test_decode_throws_on_syntactically_invalid_json(): void
    {
        $invalidJson = '{"key": "unclosed quote}';

        $this->expectException(JsonException::class);

        Json::decode($invalidJson);
    }

    #[TestDox('::decode() throws JsonException on an empty string')]
    public function test_decode_throws_on_an_empty_string(): void
    {
        $this->expectException(JsonException::class);

        Json::decode('');
    }

    #[TestDox('::decode() throws JsonException on a whitespace-only string')]
    public function test_decode_throws_on_a_whitespace_only_string(): void
    {
        $this->expectException(JsonException::class);

        Json::decode("   \n\t  ");
    }

    #[TestDox('::decode() throws JsonException when depth is exceeded')]
    public function test_decode_throws_when_depth_is_exceeded(): void
    {
        // a 3-level structure at depth=2 must fail
        $json = '{"level1":{"level2":{"level3":"value"}}}';

        $this->expectException(JsonException::class);

        Json::decode(input: $json, associative: true, depth: 2);
    }

    // ================================================================
    //
    // encode() + decode() round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('::encode() and ::decode() are inverse operations')]
    public function test_encode_and_decode_are_inverse_operations(): void
    {
        // the two operations are contracts on each other: any value
        // encode() produces must be something decode() can read back
        // to the original PHP value, for every supported input.

        $original = [
            'string' => 'hello',
            'int'    => 42,
            'float'  => 3.14,
            'bool'   => true,
            'null'   => null,
            'array'  => [1, 2, 3],
        ];

        $encoded = Json::encode($original);
        $decoded = Json::decode($encoded, true);

        $this->assertSame($original, $decoded);
    }

    // ================================================================
    //
    // validate() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('::validate() declares $input, $depth and $flags as parameters in that order')]
    public function test_validate_declares_expected_parameters(): void
    {
        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        $expected = ['input', 'depth', 'flags'];
        $method = (new ReflectionClass(Json::class))->getMethod('validate');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // validate() - happy path
    //
    // ----------------------------------------------------------------

    #[TestDox('::validate() returns null for valid JSON')]
    public function test_validate_returns_null_for_valid_json(): void
    {
        $json = '{"a":1}';

        $actual = Json::validate($json);

        $this->assertNull($actual);
    }

    #[DataProvider('getValidJsonPrimitivesProvider')]
    #[TestDox('::validate() accepts valid JSON primitive $json')]
    public function test_validate_accepts_valid_json_primitive(
        string $json,
    ): void {
        $actual = Json::validate($json);

        $this->assertNull($actual);
    }

    /**
     * @return array<string, array{0: string}>
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

    #[TestDox('::validate() accepts valid JSON with surrounding whitespace')]
    public function test_validate_accepts_valid_json_with_surrounding_whitespace(): void
    {
        // json_validate() follows json_decode() - both accept leading
        // and trailing whitespace around an otherwise valid JSON
        // document. Pin this so a future restriction to a "strict"
        // mode is an intentional, documented change.

        $json = '   {"a": 1}   ';

        $actual = Json::validate($json);

        $this->assertNull($actual);
    }

    #[TestDox('::validate() accepts JSON within the custom depth limit')]
    public function test_validate_accepts_json_within_the_custom_depth_limit(): void
    {
        $json = '{"level1":{"level2":{"level3":"value"}}}';

        $actual = Json::validate($json, 5);

        $this->assertNull($actual);
    }

    // ================================================================
    //
    // validate() - error conditions
    //
    // ----------------------------------------------------------------

    #[TestDox('::validate() returns a JsonValidationError for invalid JSON')]
    public function test_validate_returns_error_details_for_invalid_json(): void
    {
        // the documented shape on failure is a JsonValidationError
        // with non-zero code and non-empty message. Pin all three
        // facts so a silent change to the error surface is caught.

        $invalidJson = '{"a":1'; // missing closing brace

        $actual = Json::validate($invalidJson);

        $this->assertInstanceOf(JsonValidationError::class, $actual);
        $this->assertNotSame(0, $actual->getCode());
        $this->assertNotSame('', $actual->getMessage());
    }

    #[DataProvider('getInvalidJsonProvider')]
    #[TestDox('::validate() rejects invalid JSON: $json')]
    public function test_validate_rejects_invalid_json(string $json): void
    {
        $actual = Json::validate($json);

        $this->assertInstanceOf(JsonValidationError::class, $actual);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function getInvalidJsonProvider(): array
    {
        return [
            'missing closing brace'   => ['{"unclosed brace'],
            'trailing comma'          => ['[1, 2, 3,]'],
            'unquoted key'            => ['{key: "value"}'],
            'unquoted string value'   => ['{"key": value}'],
            'incomplete boolean'      => ['tru'],
            'incomplete null'         => ['nul'],
            'multiple values'         => ['{"a": 1} {"b": 2}'],
            'wrong format'            => ['<xml>not json</xml>'],
            'invalid escape sequence' => ['{"escaped": "bad\\n\'}'],
            'empty string'            => [''],
            'whitespace only'         => ["   \n\t  "],
        ];
    }

    #[TestDox('::validate() rejects JSON exceeding the custom depth limit')]
    public function test_validate_rejects_json_exceeding_the_custom_depth_limit(): void
    {
        // a 3-level structure at depth=2 must be rejected
        $json = '{"level1":{"level2":{"level3":"value"}}}';

        $actual = Json::validate($json, 2);

        $this->assertInstanceOf(JsonValidationError::class, $actual);
    }
}
