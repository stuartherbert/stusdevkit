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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Builtins;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullSchema;

#[TestDox('NullSchema')]
class NullSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // every schema declares its namespace explicitly so
        // Validate::null() resolves to the advertised class
        $reflection = new \ReflectionClass(NullSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        // NullSchema inherits the parse pipeline from
        // BaseSchema; this test locks that relationship down
        // so refactors cannot silently break it
        $reflection = new \ReflectionClass(NullSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseSchema::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts an optional typeCheckError callable')]
    public function test_construct_parameter_names(): void
    {
        // the single construction parameter must stay named
        // typeCheckError so callers can override the default
        // error factory by name
        $method = new \ReflectionMethod(NullSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['typeCheckError'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() returns null when given null')]
    public function test_parse_accepts_null(): void
    {
        // null is the one and only accepted input; parse()
        // must echo it back verbatim
        $unit = new NullSchema();

        $actualResult = $unit->parse(null);

        $this->assertNull($actualResult);
    }

    /**
     * Every non-null value must be rejected. The data
     * provider enumerates one representative example per
     * PHP scalar/compound type so the set, not the count,
     * is documented.
     *
     * @return array<string, array{mixed}>
     */
    public static function nonNullValuesProvider(): array
    {
        return [
            'empty string'  => [''],
            'string'        => ['hello'],
            'zero int'      => [0],
            'positive int'  => [42],
            'zero float'    => [0.0],
            'bool false'    => [false],
            'bool true'     => [true],
            'empty array'   => [[]],
            'list'          => [[1, 2, 3]],
            'stdClass'      => [new \stdClass()],
        ];
    }

    #[DataProvider('nonNullValuesProvider')]
    #[TestDox('->parse() throws ValidationException when given a non-null $_dataName')]
    public function test_parse_rejects_non_null(mixed $input): void
    {
        // anything other than null is out of scope for the
        // null schema — parse() must signal that refusal
        // with a ValidationException
        $unit = new NullSchema();

        $this->expectException(ValidationException::class);

        $unit->parse($input);
    }

    #[TestDox('->safeParse() succeeds on null')]
    public function test_safeParse_succeeds_on_null(): void
    {
        // safeParse() is the non-throwing counterpart;
        // confirm the happy path returns an ok result
        $unit = new NullSchema();

        $actualResult = $unit->safeParse(null);

        $this->assertTrue($actualResult->succeeded());
        $this->assertNull($actualResult->data());
    }

    #[TestDox('->safeParse() fails on a non-null value')]
    public function test_safeParse_fails_on_non_null(): void
    {
        // safeParse() must report failure without throwing
        // when the input cannot pass the type check
        $unit = new NullSchema();

        $actualResult = $unit->safeParse('not null');

        $this->assertFalse($actualResult->succeeded());
    }
}
