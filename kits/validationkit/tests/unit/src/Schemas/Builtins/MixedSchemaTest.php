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
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\MixedSchema;

#[TestDox('MixedSchema')]
class MixedSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // Validate::mixed() must resolve to this namespace;
        // locking it down catches accidental moves
        $reflection = new \ReflectionClass(MixedSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        // MixedSchema is still a real schema — pipeline
        // methods like withCustomConstraint() come from
        // BaseSchema
        $reflection = new \ReflectionClass(MixedSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseSchema::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * MixedSchema is the "accept anything" schema; the
     * data provider documents the breadth of types it must
     * wave through unchanged.
     *
     * @return array<string, array{mixed}>
     */
    public static function anyValueProvider(): array
    {
        return [
            'null'         => [null],
            'empty string' => [''],
            'string'       => ['hello'],
            'zero int'     => [0],
            'positive int' => [42],
            'zero float'   => [0.0],
            'float'        => [3.14],
            'bool false'   => [false],
            'bool true'    => [true],
            'empty array'  => [[]],
            'list'         => [[1, 2, 3]],
            'assoc array'  => [['key' => 'value']],
            'stdClass'     => [new \stdClass()],
        ];
    }

    #[DataProvider('anyValueProvider')]
    #[TestDox('->parse() accepts a $_dataName and returns it unchanged')]
    public function test_parse_accepts_anything(mixed $input): void
    {
        // MixedSchema performs no type check or constraint
        // check of its own — every input must come back
        // identical (using loose equality to cope with
        // object identity for stdClass) and, for scalars,
        // must be strictly equal
        $unit = new MixedSchema();

        $actualResult = $unit->parse($input);

        // assertEquals handles stdClass value-equality; for
        // scalars this is equivalent to strict comparison
        $this->assertEquals($input, $actualResult);
    }

    #[TestDox('->safeParse() succeeds on any value')]
    public function test_safeParse_always_succeeds(): void
    {
        // there is no failure mode intrinsic to MixedSchema,
        // so the result type must always be "ok"
        $unit = new MixedSchema();

        $actualResult = $unit->safeParse('anything');

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('anything', $actualResult->data());
    }
}
