<?php

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

declare(strict_types=1);

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\UuidSchema;

#[TestDox(UuidSchema::class)]
class UuidSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\Schemas';
        $actual = (new ReflectionClass(UuidSchema::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(UuidSchema::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(UuidSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('implements ValidationSchema via BaseSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(UuidSchema::class);
        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    // ================================================================
    //
    // Shape — own public method
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes coerce() as a public method')]
    public function test_coerce_is_public(): void
    {
        $reflection = new ReflectionClass(UuidSchema::class);
        $this->assertTrue($reflection->hasMethod('coerce'));
        $this->assertTrue(
            $reflection->getMethod('coerce')->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — type checks
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() accepts a UuidInterface instance')]
    public function test_parse_accepts_uuid_instance(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new UuidSchema();
        $uuid = Uuid::uuid7();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actual);
    }

    #[TestDox('->parse() rejects a UUID string without ->coerce()')]
    public function test_parse_rejects_uuid_string_without_coerce(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // without coerce() the schema demands an actual
        // UuidInterface instance — strings must be explicitly
        // opted-in via coerce() to avoid accidentally parsing
        // arbitrary strings as UUIDs.

        // ----------------------------------------------------------------
        // setup your test

        $schema = new UuidSchema();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse('550e8400-e29b-41d4-a716-446655440000');
    }

    #[TestDox('->parse() rejects a non-UUID value')]
    public function test_parse_rejects_non_uuid(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new UuidSchema();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse(42);
    }

    // ================================================================
    //
    // Behaviour — coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() returns a new instance (immutable)')]
    public function test_coerce_returns_new_instance(): void
    {
        $schema = new UuidSchema();
        $clone = $schema->coerce();
        $this->assertNotSame($schema, $clone);
    }

    #[TestDox('->parse() on a coercing schema accepts a UUID string')]
    public function test_parse_accepts_uuid_string_with_coerce(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = (new UuidSchema())->coerce();
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse($uuidString);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UuidInterface::class, $actual);
        $this->assertSame($uuidString, $actual->toString());
    }

    #[TestDox('->parse() on a coercing schema rejects a non-UUID string')]
    public function test_parse_rejects_invalid_string_with_coerce(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = (new UuidSchema())->coerce();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse('not-a-uuid');
    }

    // ================================================================
    //
    // Behaviour — safeParse
    //
    // ----------------------------------------------------------------

    #[TestDox('->safeParse() returns a successful ParseResult for a UuidInterface instance')]
    public function test_safeParse_ok_on_uuid_instance(): void
    {
        $schema = new UuidSchema();
        $uuid = Uuid::uuid7();

        $result = $schema->safeParse($uuid);

        $this->assertTrue($result->succeeded());
        $this->assertSame($uuid, $result->data());
    }

    #[TestDox('->safeParse() returns a failed ParseResult for a non-UUID input')]
    public function test_safeParse_fail_on_non_uuid(): void
    {
        $schema = new UuidSchema();

        $result = $schema->safeParse('not-a-uuid');

        $this->assertTrue($result->failed());
    }
}
