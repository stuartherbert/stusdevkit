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
use Ramsey\Uuid\UuidInterface;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;

#[TestDox('Validate::uuid()')]
class ValidateUuidTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts a UuidInterface instance')]
    public function test_accepts_uuid_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::uuid()->parse()
        // accepts a UuidInterface instance and returns
        // it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::uuid();
        $uuid = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideNonUuidInterfaceValues(): array
    {
        return [
            'string' => [ '550e8400-e29b-41d4-a716-446655440000' ],
            'int'    => [ 42 ],
            'float'  => [ 3.14 ],
            'bool'   => [ true ],
            'array'  => [ ['a'] ],
            'null'   => [ null ],
        ];
    }

    #[DataProvider('provideNonUuidInterfaceValues')]
    #[TestDox('rejects non-UuidInterface values')]
    public function test_rejects_non_uuid_interface_values(
        mixed $inputValue,
    ): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::uuid() rejects
        // non-UuidInterface values with an invalid_uuid
        // error

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::uuid();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issues = $result->error()->issues()->jsonSerialize();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_uuid',
            $issues[0]['type'],
        );
        $this->assertStringStartsWith(
            'Expected UuidInterface, received ',
            $issues[0]['message'],
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerce() converts a UUID string to UuidInterface')]
    public function test_coerce_converts_uuid_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts a valid
        // UUID string into a UuidInterface instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::uuid()->coerce();

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

    #[TestDox('coerce() converts a dashless UUID string to UuidInterface')]
    public function test_coerce_converts_dashless_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts a dashless
        // UUID string into a UuidInterface instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::uuid()->coerce();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(
            '550e8400e29b41d4a716446655440000',
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

    #[TestDox('coerce() passes through a UuidInterface unchanged')]
    public function test_coerce_passes_through_uuid_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() leaves a
        // UuidInterface instance unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::uuid()->coerce();
        $uuid = Uuid::fromString(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('coerce() still rejects non-UUID strings')]
    public function test_coerce_rejects_non_uuid_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() does not magically
        // make an invalid string into a valid UUID

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::uuid()->coerce();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-a-uuid');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issues = $result->error()->issues()->jsonSerialize();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_uuid',
            $issues[0]['type'],
        );
        $this->assertStringStartsWith(
            'Expected UuidInterface, received ',
            $issues[0]['message'],
        );

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

        $unit = Validate::uuid()
            ->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(
            Uuid::fromString(
                '550e8400-e29b-41d4-a716-446655440000',
            ),
        );

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
}
