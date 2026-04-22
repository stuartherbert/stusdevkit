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
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\ValidationKit\Coercions\CoerceToBoolean;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;

#[TestDox('CoerceToBoolean')]
class CoerceToBooleanTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Coercions namespace')]
    public function test_lives_in_coercions_namespace(): void
    {
        $reflection = new ReflectionClass(CoerceToBoolean::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Coercions',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(CoerceToBoolean::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isEnum());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('implements ValueCoercion')]
    public function test_implements_value_coercion(): void
    {
        $reflection = new ReflectionClass(CoerceToBoolean::class);
        $this->assertContains(
            ValueCoercion::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('publishes exactly [__construct, coerce] as its own public methods')]
    public function test_publishes_expected_public_methods(): void
    {
        $reflection = new ReflectionClass(CoerceToBoolean::class);

        // collect only methods declared on CoerceToBoolean itself,
        // so inherited PHPUnit/TestCase methods don't leak in
        $ownPublicMethods = array_values(array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            static fn (ReflectionMethod $method): bool =>
                $method->getDeclaringClass()->getName()
                === CoerceToBoolean::class,
        ));

        $methodNames = array_map(
            static fn (ReflectionMethod $method): string => $method->getName(),
            $ownPublicMethods,
        );
        sort($methodNames);

        $this->assertSame(['__construct', 'coerce'], $methodNames);
    }

    #[TestDox('pins DEFAULT_STRINGS to the documented lookup table')]
    public function test_default_strings_constant_value(): void
    {
        $this->assertSame(
            [
                'true'  => true,
                '1'     => true,
                'yes'   => true,
                'false' => false,
                '0'     => false,
                'no'    => false,
                ''      => false,
            ],
            CoerceToBoolean::DEFAULT_STRINGS,
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts an optional array of string-to-bool mappings')]
    public function test_construct_signature(): void
    {
        $reflection = new ReflectionMethod(
            CoerceToBoolean::class,
            '__construct',
        );

        $parameters = $reflection->getParameters();
        $this->assertCount(1, $parameters);

        $strings = $parameters[0];
        $this->assertSame('strings', $strings->getName());
        $this->assertSame('array', (string) $strings->getType());
        $this->assertTrue($strings->isDefaultValueAvailable());
    }

    #[TestDox('->coerce() accepts mixed and returns mixed')]
    public function test_coerce_signature(): void
    {
        $reflection = new ReflectionMethod(
            CoerceToBoolean::class,
            'coerce',
        );

        $parameters = $reflection->getParameters();
        $this->assertCount(1, $parameters);

        $data = $parameters[0];
        $this->assertSame('data', $data->getName());
        $this->assertSame('mixed', (string) $data->getType());

        $this->assertSame('mixed', (string) $reflection->getReturnType());
    }

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
    #[TestDox('->coerce() coerces truthy values to true')]
    public function test_coerces_truthy_to_true(
        mixed $inputValue,
    ): void {
        /** CoerceToBoolean converts truthy strings and non-zero numbers to true. */

        $unit = new CoerceToBoolean();

        $actualResult = $unit->coerce($inputValue);

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
    #[TestDox('->coerce() coerces falsy values to false')]
    public function test_coerces_falsy_to_false(
        mixed $inputValue,
    ): void {
        /** CoerceToBoolean converts falsy strings and zero numbers to false. */

        $unit = new CoerceToBoolean();

        $actualResult = $unit->coerce($inputValue);

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
    #[TestDox('->coerce() returns non-coercible value unchanged')]
    public function test_returns_non_coercible_unchanged(
        mixed $inputValue,
    ): void {
        /**
         * CoerceToBoolean returns values unchanged when they cannot be
         * recognised as boolean.
         */

        $unit = new CoerceToBoolean();

        $actualResult = $unit->coerce($inputValue);

        $this->assertSame($inputValue, $actualResult);
    }

    // ================================================================
    //
    // Custom String Mappings
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() honours custom strings that replace built-in defaults')]
    public function test_custom_strings_replace_defaults(): void
    {
        /**
         * Custom string mappings replace the built-in defaults entirely,
         * so only the custom strings are recognised.
         */

        $unit = new CoerceToBoolean(
            strings: ['on' => true, 'off' => false],
        );

        // custom strings work
        $this->assertTrue($unit->coerce('on'));
        $this->assertFalse($unit->coerce('off'));

        // built-in defaults are no longer recognised
        $this->assertSame('true', $unit->coerce('true'));
        $this->assertSame('false', $unit->coerce('false'));
    }

    #[TestDox('->coerce() matches custom strings case-insensitively')]
    public function test_custom_strings_are_case_insensitive(): void
    {
        /**
         * Custom string mappings are matched case-insensitively, because
         * the input is lowercased before lookup.
         */

        $unit = new CoerceToBoolean(
            strings: ['on' => true, 'off' => false],
        );

        $this->assertTrue($unit->coerce('ON'));
        $this->assertTrue($unit->coerce('On'));
        $this->assertFalse($unit->coerce('OFF'));
        $this->assertFalse($unit->coerce('Off'));
    }

    #[TestDox('->coerce() works with DEFAULT_STRINGS merged with custom strings')]
    public function test_defaults_can_be_merged_with_custom(): void
    {
        /**
         * Users can keep the built-in defaults and add custom strings by
         * merging the DEFAULTS constant with their own array.
         */

        /** @var array<string, bool> $strings */
        $strings = [
            ...CoerceToBoolean::DEFAULT_STRINGS,
            ...['on' => true, 'off' => false]
        ];
        $unit = new CoerceToBoolean(strings: $strings);

        // custom strings work
        $this->assertTrue($unit->coerce('on'));
        $this->assertFalse($unit->coerce('off'));

        // built-in defaults still work
        $this->assertTrue($unit->coerce('true'));
        $this->assertFalse($unit->coerce('false'));
    }
}
