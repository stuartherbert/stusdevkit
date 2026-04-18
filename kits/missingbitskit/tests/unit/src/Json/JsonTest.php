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
#[TestDox(Json::class)]
class JsonTest extends TestCase
{
    private Json $json;

    protected function setUp(): void
    {
        $this->json = new Json();
    }

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

    #[TestDox('is a concrete class')]
    public function test_is_a_concrete_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // callers `new Json()` to get a wrapper. Making the class
        // abstract, an interface, or a trait is a breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Json::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($reflection->isAbstract());
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only encode(), decode() and validate() as public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the class exists to wrap three PHP builtins. Pin the method
        // set by enumeration - any addition fails with a diff that
        // names the new method, rather than a cryptic count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['decode', 'encode', 'validate'];
        $reflection = new ReflectionClass(Json::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // the class constants are part of the published surface -
        // callers reference them as `Json::DEFAULT_*` to pass the
        // library's own defaults through. Pin the set so any
        // addition or rename is caught.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'DEFAULT_DECODE_FLAGS',
            'DEFAULT_DEPTH',
            'DEFAULT_ENCODE_FLAGS',
            'DEFAULT_VALIDATE_FLAGS',
        ];
        $reflection = new ReflectionClass(Json::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_keys($reflection->getConstants());
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // the default flag / depth values are part of the contract.
        // A silent change (e.g. switching DEFAULT_DEPTH from 512 to
        // 256) changes the behaviour of every caller that relied on
        // the library's default and so must be pinned.

        // ----------------------------------------------------------------
        // perform the change

        $actual = constant(Json::class . '::' . $constant);

        // ----------------------------------------------------------------
        // test the results

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

    #[TestDox('->encode() declares $input, $flags and $depth as parameters in that order')]
    public function test_encode_declares_expected_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['input', 'flags', 'depth'];
        $method = (new ReflectionClass(Json::class))->getMethod('encode');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // encode() - happy path
    //
    // ----------------------------------------------------------------

    #[TestDox('->encode() encodes an associative array as a JSON object')]
    public function test_encodes_an_associative_array_as_a_json_object(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'string' => 'value',
            'int'    => 123,
            'bool'   => true,
            'null'   => null,
            'array'  => [1, 2, 3],
        ];
        $expected = '{"string":"value","int":123,"bool":true,"null":null,"array":[1,2,3]}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() encodes an empty array as []')]
    public function test_encodes_an_empty_array_as_a_json_array(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = [];
        $expected = '[]';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() encodes an empty stdClass as {}')]
    public function test_encodes_an_empty_stdclass_as_a_json_object(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = new stdClass();
        $expected = '{}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() encodes null as the root value')]
    public function test_encodes_null_as_the_root_value(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = 'null';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() encodes boolean true as the root value')]
    public function test_encodes_boolean_true_as_the_root_value(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode(true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('true', $actual);
    }

    #[TestDox('->encode() encodes boolean false as the root value')]
    public function test_encodes_boolean_false_as_the_root_value(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode(false);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('false', $actual);
    }

    #[TestDox('->encode() encodes an integer as the root value')]
    public function test_encodes_an_integer_as_the_root_value(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('42', $actual);
    }

    #[TestDox('->encode() encodes a string as the root value')]
    public function test_encodes_a_string_as_the_root_value(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode('hello world');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('"hello world"', $actual);
    }

    #[TestDox('->encode() encodes PHP_INT_MAX without precision loss')]
    public function test_encodes_php_int_max_without_precision_loss(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's json_encode() writes integers as decimal digits, and
        // json_decode() reads them back as ints. A round-trip through
        // the wrapper must preserve PHP_INT_MAX exactly, because that
        // is the largest value a PHP int can hold - anything lost
        // here is a silent data-loss bug.

        // ----------------------------------------------------------------
        // setup your test

        $input = PHP_INT_MAX;

        // ----------------------------------------------------------------
        // perform the change

        $encoded = $this->json->encode($input);
        $decoded = $this->json->decode($encoded);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(PHP_INT_MAX, $decoded);
    }

    #[TestDox('->encode() escapes special characters in strings and round-trips cleanly')]
    public function test_escapes_special_characters_in_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // encode() must produce JSON that decodes back to the same
        // PHP string - escape sequences for `"`, `\`, newline and
        // tab are the non-obvious ones.

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'quotes'    => 'He said "Hello"',
            'backslash' => 'C:\\Users',
            'newline'   => "line1\nline2",
            'tab'       => "col1\tcol2",
        ];

        // ----------------------------------------------------------------
        // perform the change

        $encoded = $this->json->encode($input);
        $decoded = $this->json->decode($encoded, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($input, $decoded);
    }

    #[TestDox('->encode() preserves unicode characters through a round-trip')]
    public function test_preserves_unicode_characters_through_a_round_trip(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'emoji'   => '👋',
            'chinese' => '你好',
            'russian' => 'Привет',
            'arabic'  => 'مرحبا',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $encoded = $this->json->encode($input);
        $decoded = $this->json->decode($encoded, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($input, $decoded);
    }

    #[TestDox('->encode() encodes deeply nested arrays')]
    public function test_encodes_deeply_nested_arrays(): void
    {
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
        $expected = '{"level1":{"level2":{"level3":{"value":"deep"}}}}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() encodes nested stdClass objects')]
    public function test_encodes_nested_stdclass_objects(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $inner = new stdClass();
        $inner->value = 'deep';

        $outer = new stdClass();
        $outer->nested = $inner;

        $expected = '{"nested":{"value":"deep"}}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($outer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // encode() - flags
    //
    // ----------------------------------------------------------------

    #[TestDox('->encode() respects JSON_PRETTY_PRINT')]
    public function test_encode_respects_json_pretty_print(): void
    {
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

        $actual = $this->json->encode($input, JSON_PRETTY_PRINT);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() respects JSON_UNESCAPED_SLASHES')]
    public function test_encode_respects_json_unescaped_slashes(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = ['url' => 'https://example.com'];
        $expected = '{"url":"https://example.com"}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input, JSON_UNESCAPED_SLASHES);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() respects JSON_UNESCAPED_UNICODE')]
    public function test_encode_respects_json_unescaped_unicode(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = ['greeting' => '你好'];
        $expected = '{"greeting":"你好"}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input, JSON_UNESCAPED_UNICODE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() accepts combined flags via bitwise OR')]
    public function test_encode_accepts_combined_flags_via_bitwise_or(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // callers commonly OR flags together (e.g. "unescaped
        // slashes + unescaped unicode" for URLs with i18n text).
        // The wrapper must not drop or reinterpret any bit of the
        // composed mask.

        // ----------------------------------------------------------------
        // setup your test

        $input = [
            'url'      => 'https://example.com/path',
            'greeting' => '你好',
        ];
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $expected = '{"url":"https://example.com/path","greeting":"你好"}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input, $flags);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->encode() accepts JSON_THROW_ON_ERROR from the caller as a no-op')]
    public function test_encode_accepts_json_throw_on_error_as_a_no_op(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // encode() always ORs JSON_THROW_ON_ERROR into the mask. If
        // the caller passes it in explicitly the bitwise OR must be
        // idempotent - a double-set must not silently change any
        // other behaviour.

        // ----------------------------------------------------------------
        // setup your test

        $input = ['key' => 'value'];
        $expected = '{"key":"value"}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->encode($input, JSON_THROW_ON_ERROR);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // encode() - error conditions
    //
    // ----------------------------------------------------------------

    #[TestDox('->encode() throws JsonException on a circular reference')]
    public function test_encode_throws_on_a_circular_reference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // json_encode() returns false (and sets an error) on a
        // circular reference. The wrapper forces JSON_THROW_ON_ERROR,
        // so the caller must see a JsonException instead of a
        // silently false-valued return.

        // ----------------------------------------------------------------
        // setup your test

        $input = [];
        $input['self'] = &$input;

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->encode($input);
    }

    #[TestDox('->encode() throws JsonException when depth is exceeded')]
    public function test_encode_throws_when_depth_is_exceeded(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        // a 3-level structure at depth=2 must fail
        $input = ['level1' => ['level2' => ['level3' => 'value']]];

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->encode(input: $input, flags: 0, depth: 2);
    }

    #[TestDox('->encode() throws JsonException when given a resource')]
    public function test_encode_throws_when_given_a_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // resources have no JSON representation. json_encode() would
        // normally return false; the wrapper must throw instead.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->encode($resource);
    }

    // ================================================================
    //
    // decode() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('->decode() declares $input, $associative, $depth and $flags as parameters in that order')]
    public function test_decode_declares_expected_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['input', 'associative', 'depth', 'flags'];
        $method = (new ReflectionClass(Json::class))->getMethod('decode');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // decode() - happy path
    //
    // ----------------------------------------------------------------

    #[TestDox('->decode() decodes a JSON object as stdClass by default')]
    public function test_decodes_a_json_object_as_stdclass_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `associative` defaults to null, which json_decode()
        // interprets as "return stdClass". Pin the default so any
        // future change to the wrapper that flips this is caught.

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"name":"John","age":30}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(stdClass::class, $actual);
        $this->assertSame('John', $actual->name);
        $this->assertSame(30, $actual->age);
    }

    #[TestDox('->decode() decodes a JSON object as an associative array when associative is true')]
    public function test_decodes_a_json_object_as_an_associative_array(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $json = '{"key":"value","number":123}';
        $expected = ['key' => 'value', 'number' => 123];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode($json, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->decode() decodes a JSON array')]
    public function test_decodes_a_json_array(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $json = '[1, 2, 3, "four", true]';
        $expected = [1, 2, 3, 'four', true];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode($json, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->decode() decodes root-level JSON string primitive')]
    public function test_decodes_root_level_string_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('"hello world"');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello world', $actual);
    }

    #[TestDox('->decode() decodes root-level JSON integer primitive')]
    public function test_decodes_root_level_integer_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('42');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('->decode() decodes root-level JSON negative integer primitive')]
    public function test_decodes_root_level_negative_integer_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('-17');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(-17, $actual);
    }

    #[TestDox('->decode() decodes root-level JSON float primitive')]
    public function test_decodes_root_level_float_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('3.14159');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14159, $actual);
    }

    #[TestDox('->decode() decodes root-level JSON boolean true primitive')]
    public function test_decodes_root_level_boolean_true_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('true');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->decode() decodes root-level JSON boolean false primitive')]
    public function test_decodes_root_level_boolean_false_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('false');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->decode() decodes root-level JSON null primitive')]
    public function test_decodes_root_level_null_primitive(): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode('null');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    // ================================================================
    //
    // decode() - flags
    //
    // ----------------------------------------------------------------

    #[TestDox('->decode() respects JSON_BIGINT_AS_STRING')]
    public function test_decode_respects_json_bigint_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a 20-digit integer exceeds PHP_INT_MAX on a 64-bit
        // platform. JSON_BIGINT_AS_STRING tells json_decode() to
        // return the number as a string rather than coercing to a
        // float (which would lose precision). The wrapper must pass
        // this flag through unchanged.

        // ----------------------------------------------------------------
        // setup your test

        $json = '{"big":99999999999999999999}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode(
            input: $json,
            associative: true,
            flags: JSON_BIGINT_AS_STRING,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['big' => '99999999999999999999'], $actual);
    }

    #[TestDox('->decode() succeeds when nesting is within the custom depth limit')]
    public function test_decode_succeeds_within_custom_depth_limit(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $json = '{"level1":{"level2":{"level3":"value"}}}';
        $expected = ['level1' => ['level2' => ['level3' => 'value']]];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->decode(
            input: $json,
            associative: true,
            depth: 5,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // decode() - error conditions
    //
    // ----------------------------------------------------------------

    #[TestDox('->decode() throws JsonException on syntactically invalid JSON')]
    public function test_decode_throws_on_syntactically_invalid_json(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $invalidJson = '{"key": "unclosed quote}';

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->decode($invalidJson);
    }

    #[TestDox('->decode() throws JsonException on an empty string')]
    public function test_decode_throws_on_an_empty_string(): void
    {
        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->decode('');
    }

    #[TestDox('->decode() throws JsonException on a whitespace-only string')]
    public function test_decode_throws_on_a_whitespace_only_string(): void
    {
        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->decode("   \n\t  ");
    }

    #[TestDox('->decode() throws JsonException when depth is exceeded')]
    public function test_decode_throws_when_depth_is_exceeded(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        // a 3-level structure at depth=2 must fail
        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(JsonException::class);

        // ----------------------------------------------------------------
        // perform the change

        $this->json->decode(input: $json, associative: true, depth: 2);
    }

    // ================================================================
    //
    // encode() + decode() round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('->encode() and ->decode() are inverse operations')]
    public function test_encode_and_decode_are_inverse_operations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the two operations are contracts on each other: any value
        // encode() produces must be something decode() can read back
        // to the original PHP value, for every supported input.

        // ----------------------------------------------------------------
        // setup your test

        $original = [
            'string' => 'hello',
            'int'    => 42,
            'float'  => 3.14,
            'bool'   => true,
            'null'   => null,
            'array'  => [1, 2, 3],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $encoded = $this->json->encode($original);
        $decoded = $this->json->decode($encoded, true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($original, $decoded);
    }

    // ================================================================
    //
    // validate() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('->validate() declares $input, $depth and $flags as parameters in that order')]
    public function test_validate_declares_expected_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['input', 'depth', 'flags'];
        $method = (new ReflectionClass(Json::class))->getMethod('validate');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // validate() - happy path
    //
    // ----------------------------------------------------------------

    #[TestDox('->validate() returns an empty array for valid JSON')]
    public function test_validate_returns_an_empty_array_for_valid_json(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $json = '{"a":1}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    #[DataProvider('getValidJsonPrimitivesProvider')]
    #[TestDox('->validate() accepts valid JSON primitive $json')]
    public function test_validate_accepts_valid_json_primitive(
        string $json,
    ): void {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
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

    #[TestDox('->validate() accepts valid JSON with surrounding whitespace')]
    public function test_validate_accepts_valid_json_with_surrounding_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // json_validate() follows json_decode() - both accept leading
        // and trailing whitespace around an otherwise valid JSON
        // document. Pin this so a future restriction to a "strict"
        // mode is an intentional, documented change.

        // ----------------------------------------------------------------
        // setup your test

        $json = '   {"a": 1}   ';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    #[TestDox('->validate() accepts JSON within the custom depth limit')]
    public function test_validate_accepts_json_within_the_custom_depth_limit(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($json, 5);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    // ================================================================
    //
    // validate() - error conditions
    //
    // ----------------------------------------------------------------

    #[TestDox('->validate() returns a JsonValidationError for invalid JSON')]
    public function test_validate_returns_error_details_for_invalid_json(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the documented shape on failure is a list of
        // `JsonValidationError`. Pin that the list carries at
        // least one error, that each entry is a
        // `JsonValidationError`, and that the first error's
        // `getCode()` is non-zero / `getMessage()` is non-empty -
        // so a silent change to the error surface is caught.

        // ----------------------------------------------------------------
        // setup your test

        $invalidJson = '{"a":1'; // missing closing brace

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($invalidJson);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame([], $actual);
        $this->assertContainsOnlyInstancesOf(
            JsonValidationError::class,
            $actual,
        );
        $this->assertNotSame(0, $actual[0]->getCode());
        $this->assertNotSame('', $actual[0]->getMessage());
    }

    #[DataProvider('getInvalidJsonProvider')]
    #[TestDox('->validate() rejects invalid JSON: $json')]
    public function test_validate_rejects_invalid_json(string $json): void
    {
        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($json);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame([], $actual);
        $this->assertContainsOnlyInstancesOf(
            JsonValidationError::class,
            $actual,
        );
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

    #[TestDox('->validate() rejects JSON exceeding the custom depth limit')]
    public function test_validate_rejects_json_exceeding_the_custom_depth_limit(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        // a 3-level structure at depth=2 must be rejected
        $json = '{"level1":{"level2":{"level3":"value"}}}';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $this->json->validate($json, 2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame([], $actual);
        $this->assertContainsOnlyInstancesOf(
            JsonValidationError::class,
            $actual,
        );
    }
}
