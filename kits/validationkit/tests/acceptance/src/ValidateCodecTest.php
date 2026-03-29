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

use BadMethodCallException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\Codec;
use StusDevKit\ValidationKit\Validate;

#[TestDox('Validate::codec()')]
class ValidateCodecTest extends TestCase
{
    // ================================================================
    //
    // Helper: build a UUID codec for tests
    //
    // ----------------------------------------------------------------

    /**
     * @return Codec<string, UuidInterface>
     */
    private static function uuidCodec(): Codec
    {
        return Validate::codec(
            input: Validate::string(),
            output: Validate::uuid(),
            decode: fn(string $s)
                => Uuid::fromString($s),
            encode: fn(UuidInterface $u)
                => $u->toString(),
        );
    }

    // ================================================================
    //
    // Decode (input → output)
    //
    // ----------------------------------------------------------------

    #[TestDox('decode() converts input type to output type')]
    public function test_decode_converts_input_to_output(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() validates the
        // input string, transforms it via the decoder,
        // and returns a UuidInterface

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->decode(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            UuidInterface::class,
            $actualResult,
        );
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $actualResult->toString(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('decode() passes through data that is already the output type')]
    public function test_decode_passes_through_output_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() accepts data
        // that is already the output type and returns it
        // unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();
        $uuid = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->decode($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('decode() rejects invalid input')]
    public function test_decode_rejects_invalid_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode() throws a
        // ValidationException when the input fails the
        // input schema validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->decode(42);

        // ----------------------------------------------------------------
        // test the results

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeDecode() returns success for valid input')]
    public function test_safe_decode_returns_success(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeDecode() returns a
        // successful ParseResult for valid input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeDecode(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertInstanceOf(
            UuidInterface::class,
            $result->data(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeDecode() returns failure for invalid input')]
    public function test_safe_decode_returns_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeDecode() returns a
        // failed ParseResult for invalid input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeDecode(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('parse() is an alias for decode()')]
    public function test_parse_is_alias_for_decode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parse() behaves identically
        // to decode()

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            UuidInterface::class,
            $actualResult,
        );
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $actualResult->toString(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Encode (output → input)
    //
    // ----------------------------------------------------------------

    #[TestDox('encode() converts output type to input type')]
    public function test_encode_converts_output_to_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() validates the
        // output type, transforms it via the encoder, and
        // returns a validated input type

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();
        $uuid = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('encode() rejects non-output type')]
    public function test_encode_rejects_non_output_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() throws a
        // ValidationException when the data is not the
        // output type

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->encode('not-a-uuid-object');

        // ----------------------------------------------------------------
        // test the results

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeEncode() returns success for valid output type')]
    public function test_safe_encode_returns_success(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeEncode() returns a
        // successful ParseResult for valid output data

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();
        $uuid = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeEncode($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $result->data(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeEncode() returns failure for invalid data')]
    public function test_safe_encode_returns_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeEncode() returns a
        // failed ParseResult for invalid data

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeEncode('not-a-uuid-object');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Composability
    //
    // ----------------------------------------------------------------

    #[TestDox('codec works inside an object schema')]
    public function test_codec_works_inside_object_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a codec can be used as a
        // field schema inside Validate::object(), decoding
        // string input to UuidInterface output

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'id' => self::uuidCodec(),
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'test',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            UuidInterface::class,
            $actualResult['id'],
        );
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $actualResult['id']->toString(),
        );
        $this->assertSame('test', $actualResult['name']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('codec inside object reports field path on error')]
    public function test_codec_inside_object_reports_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors from a
        // codec inside an object schema include the correct
        // field path

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'id' => self::uuidCodec(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'id' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issues = $result->error()->issues()->jsonSerialize();
        $this->assertSame(['id'], $issues[0]['path']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('encode() on object schema encodes codec fields')]
    public function test_encode_on_object_encodes_codec_fields(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling encode() on an
        // object schema that contains codec fields runs
        // the encode path (output → input) on each codec,
        // converting native types back to their serialised
        // form

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'id' => self::uuidCodec(),
            'name' => Validate::string(),
        ]);

        $uuid = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode([
            'id' => $uuid,
            'name' => 'test',
        ]);

        // ----------------------------------------------------------------
        // test the results

        // the codec's encode should have converted
        // UuidInterface → string
        $this->assertIsString($actualResult['id']);
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $actualResult['id'],
        );
        $this->assertSame('test', $actualResult['name']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('decode() propagates through arrays of objects')]
    public function test_decode_propagates_through_arrays(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that decode (parse) propagates
        // through array and object schemas, so codec
        // fields inside array elements are decoded from
        // their serialised form to native types

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'items' => Validate::array(
                Validate::object([
                    'id' => self::uuidCodec(),
                    'name' => Validate::string(),
                ]),
            ),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'items' => [
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440000',
                    'name' => 'first',
                ],
                [
                    'id' => '660f9500-f39c-52e5-b827-557766550000',
                    'name' => 'second',
                ],
            ],
        ]);

        // ----------------------------------------------------------------
        // test the results

        /** @var list<array{id: UuidInterface, name: string}> $items */
        $items = $actualResult['items'];

        $this->assertInstanceOf(
            UuidInterface::class,
            $items[0]['id'],
        );
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $items[0]['id']->toString(),
        );
        $this->assertInstanceOf(
            UuidInterface::class,
            $items[1]['id'],
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('encode() propagates through arrays of objects')]
    public function test_encode_propagates_through_arrays(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that encode() propagates through
        // array and object schemas, so codec fields inside
        // array elements are encoded from native types back
        // to their serialised form

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'items' => Validate::array(
                Validate::object([
                    'id' => self::uuidCodec(),
                    'name' => Validate::string(),
                ]),
            ),
        ]);

        $uuid1 = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );
        $uuid2 = Uuid::fromString(
            '660f9500-f39c-52e5-b827-557766550000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode([
            'items' => [
                ['id' => $uuid1, 'name' => 'first'],
                ['id' => $uuid2, 'name' => 'second'],
            ],
        ]);

        // ----------------------------------------------------------------
        // test the results

        /** @var list<array{id: string, name: string}> $items */
        $items = $actualResult['items'];

        $this->assertIsString($items[0]['id']);
        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $items[0]['id'],
        );
        $this->assertIsString($items[1]['id']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Inner Schema Validation
    //
    // ----------------------------------------------------------------

    #[TestDox('input schema constraints apply during decode')]
    public function test_input_schema_constraints_apply(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that constraints on the input
        // schema are enforced during decode

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::codec(
            input: Validate::string()->min(length: 36),
            output: Validate::uuid(),
            decode: fn(string $s)
                => Uuid::fromString($s),
            encode: fn(UuidInterface $u)
                => $u->toString(),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeDecode('short');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('output schema constraints apply during encode')]
    public function test_output_schema_constraints_apply(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that constraints on the output
        // schema are enforced during encode — the output
        // schema validates the value before the encoder
        // runs

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::codec(
            input: Validate::string(),
            output: Validate::uuid()->withRefine(
                function (mixed $data): bool {
                    /** @var UuidInterface $data */
                    return $data->toString()
                        !== '00000000-0000-0000-0000-000000000000';
                },
                'Nil UUID not allowed',
            ),
            decode: fn(string $s)
                => Uuid::fromString($s),
            encode: fn(UuidInterface $u)
                => $u->toString(),
        );
        $nilUuid = Uuid::fromString(
            '00000000-0000-0000-0000-000000000000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeEncode($nilUuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Blocked Methods
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{callable}>
     */
    public static function provideBlockedMethods(): array
    {
        $codec = self::uuidCodec();

        return [
            'transform' => [
                fn() => $codec->withTransform(fn($x) => $x),
            ],
            'refine' => [
                fn() => $codec->withRefine(
                    fn($x) => true,
                    'msg',
                ),
            ],
            'superRefine' => [
                fn() => $codec->withSuperRefine(
                    fn($x, $ctx) => null,
                ),
            ],
            'pipe' => [
                fn() => $codec->withPipe(Validate::string()),
            ],
            'catch' => [
                fn() => $codec->withCatch(null),
            ],
            'default' => [
                fn() => $codec->withDefault(null),
            ],
            'withConstraint' => [
                fn() => $codec->withConstraint(
                    new \StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint(),
                ),
            ],
            'withNormaliser' => [
                fn() => $codec->withNormaliser(
                    new \StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint(),
                ),
            ],
        ];
    }

    #[DataProvider('provideBlockedMethods')]
    #[TestDox('blocked methods throw BadMethodCallException')]
    public function test_blocked_methods_throw(
        callable $blockedCall,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pipeline methods that are
        // not supported on codecs throw a
        // BadMethodCallException

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(BadMethodCallException::class);
        $blockedCall();

        // ----------------------------------------------------------------
        // test the results

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

        // this test proves that withDescription() is allowed on
        // codecs and sets the description metadata

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec()
            ->withDescription('A UUID codec');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A UUID codec', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withMeta() sets the metadata')]
    public function test_with_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withMeta() is allowed on codecs
        // and sets arbitrary metadata

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = self::uuidCodec()
            ->withMeta(['version' => 4]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['version' => 4],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Bi-directional tests
    //
    // ----------------------------------------------------------------

    public function test_example_api_response(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can define a schema
        // with field-level codecs for an HTTP API response,
        // and use encode() on the server side and parse()
        // on the client side with the same schema

        // ----------------------------------------------------------------
        // shorthand

        $uuidCodec = self::uuidCodec();
        $dateTimeCodec = Validate::codec(
            input: Validate::string(),
            output: Validate::dateTime(),
            decode: fn(string $s)
                => new \DateTimeImmutable($s),
            encode: fn(\DateTimeInterface $d)
                => $d->format(\DateTimeInterface::ATOM),
        );

        // ----------------------------------------------------------------
        // setup your test

        $apiSchema = Validate::allOf([
            Validate::object([
                'order_id' => $uuidCodec,
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
                        'confirm_token' => $uuidCodec,
                        'expires_at' => $dateTimeCodec,
                    ]),
                ]),
            ]),
        ]);

        // server-side native data
        $apiServerData = [
            'order_id' => Uuid::fromString(
                '550e8400-e29b-41d4-a716-446655440000',
            ),
            'zero_cost' => [
                'confirm_token' => Uuid::fromString(
                    '660f9500-f39c-52e5-b827-557766550000',
                ),
                'expires_at' => new \DateTimeImmutable(
                    '2026-03-28T12:00:00+00:00',
                ),
            ],
        ];

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        // server: encode native types → serializable array
        $encoded = $apiSchema->encode($apiServerData);

        // simulate the wire: JSON round-trip
        $json = json_encode($encoded);
        $this->assertIsString($json);
        $wire = json_decode($json, associative: true);

        // client: decode serializable array → native types
        $apiClientData = $apiSchema->decode($wire);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($apiServerData, $apiClientData);

        // ----------------------------------------------------------------
        // clean up the database

    }

}
