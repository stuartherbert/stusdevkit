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

namespace StusDevKit\ValidationKit\Tests\Unit\Coercions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Coercions\CoerceToBoolean;

#[TestDox('CoerceToBoolean')]
class CoerceToBooleanTest extends TestCase
{
    // ================================================================
    //
    // Coercions to true
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function provideTruthyValues(): array
    {
        return [
            'string "true"'     => ['true'],
            'string "TRUE"'     => ['TRUE'],
            'string "True"'     => ['True'],
            'string "1"'        => ['1'],
            'string "yes"'      => ['yes'],
            'string "YES"'      => ['YES'],
            'int 1'             => [1],
            'int 42'            => [42],
            'float 1.0'         => [1.0],
            'float 0.5'         => [0.5],
        ];
    }

    #[DataProvider('provideTruthyValues')]
    #[TestDox('coerces truthy values to true')]
    public function test_coerces_truthy_to_true(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToBoolean converts
        // truthy strings and non-zero numbers to true

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToBoolean();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // Coercions to false
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function provideFalsyValues(): array
    {
        return [
            'string "false"'    => ['false'],
            'string "FALSE"'    => ['FALSE'],
            'string "False"'    => ['False'],
            'string "0"'        => ['0'],
            'string "no"'       => ['no'],
            'string "NO"'       => ['NO'],
            'empty string'      => [''],
            'int 0'             => [0],
            'float 0.0'         => [0.0],
        ];
    }

    #[DataProvider('provideFalsyValues')]
    #[TestDox('coerces falsy values to false')]
    public function test_coerces_falsy_to_false(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToBoolean converts
        // falsy strings and zero numbers to false

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToBoolean();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // Non-Coercible Values
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function provideNonCoercibleValues(): array
    {
        return [
            'unrecognised string'   => ['maybe'],
            'null'                  => [null],
            'array'                 => [['a']],
        ];
    }

    #[DataProvider('provideNonCoercibleValues')]
    #[TestDox('returns non-coercible value unchanged')]
    public function test_returns_non_coercible_unchanged(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToBoolean returns
        // values unchanged when they cannot be recognised
        // as boolean

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToBoolean();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    // ================================================================
    //
    // Custom String Mappings
    //
    // ----------------------------------------------------------------

    #[TestDox('custom strings replace built-in defaults')]
    public function test_custom_strings_replace_defaults(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that custom string mappings
        // replace the built-in defaults entirely, so only
        // the custom strings are recognised

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToBoolean(
            strings: ['on' => true, 'off' => false],
        );

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        // custom strings work
        $this->assertTrue($unit->coerce('on'));
        $this->assertFalse($unit->coerce('off'));

        // built-in defaults are no longer recognised
        $this->assertSame('true', $unit->coerce('true'));
        $this->assertSame('false', $unit->coerce('false'));
    }

    #[TestDox('custom strings are case-insensitive')]
    public function test_custom_strings_are_case_insensitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that custom string mappings are
        // matched case-insensitively, because the input is
        // lowercased before lookup

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToBoolean(
            strings: ['on' => true, 'off' => false],
        );

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->coerce('ON'));
        $this->assertTrue($unit->coerce('On'));
        $this->assertFalse($unit->coerce('OFF'));
        $this->assertFalse($unit->coerce('Off'));
    }

    #[TestDox('DEFAULTS can be merged with custom strings')]
    public function test_defaults_can_be_merged_with_custom(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that users can keep the built-in
        // defaults and add custom strings by merging the
        // DEFAULTS constant with their own array

        // ----------------------------------------------------------------
        // setup your test

        /** @var array<string, bool> $strings */
        $strings = [
            ...CoerceToBoolean::DEFAULT_STRINGS,
            ...['on' => true, 'off' => false]
        ];
        $unit = new CoerceToBoolean(strings: $strings);

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        // custom strings work
        $this->assertTrue($unit->coerce('on'));
        $this->assertFalse($unit->coerce('off'));

        // built-in defaults still work
        $this->assertTrue($unit->coerce('true'));
        $this->assertFalse($unit->coerce('false'));
    }
}
