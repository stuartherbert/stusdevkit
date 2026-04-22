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

namespace StusDevKit\MissingBitsKit\Tests\Unit\TypeInspectors;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInvokable;
use StusDevKit\MissingBitsKit\TypeInspectors\GetPrintableType;

#[TestDox(GetPrintableType::class)]
class GetPrintableTypeTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\TypeInspectors namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new ReflectionClass(GetPrintableType::class);
        $this->assertSame(
            'StusDevKit\\MissingBitsKit\\TypeInspectors',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(GetPrintableType::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('exposes only ::from() as a public method')]
    public function test_exposes_only_from(): void
    {
        $reflection = new ReflectionClass(GetPrintableType::class);
        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === GetPrintableType::class) {
                $methodNames[] = $m->getName();
            }
        }
        sort($methodNames);
        $this->assertSame(['from'], $methodNames);
    }

    #[TestDox('publishes FLAG_NONE, FLAG_CLASSNAME, FLAG_CALLABLE_DETAILS, FLAG_SCALAR_VALUE, and FLAG_DEFAULTS as public constants')]
    public function test_publishes_expected_flag_constants(): void
    {
        $reflection = new ReflectionClass(GetPrintableType::class);
        $constants = array_keys($reflection->getConstants());
        sort($constants);
        $expected = [
            'FLAG_CALLABLE_DETAILS',
            'FLAG_CLASSNAME',
            'FLAG_DEFAULTS',
            'FLAG_NONE',
            'FLAG_SCALAR_VALUE',
        ];
        $this->assertSame($expected, $constants);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() is declared public static')]
    public function test_from_is_public_static(): void
    {
        $method = new ReflectionMethod(GetPrintableType::class, 'from');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    #[TestDox('::from() parameter names in order')]
    public function test_from_parameter_names(): void
    {
        $method = new ReflectionMethod(GetPrintableType::class, 'from');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['item', 'options'], $paramNames);
    }

    #[TestDox('::from() returns string')]
    public function test_from_return_type(): void
    {
        $method = new ReflectionMethod(GetPrintableType::class, 'from');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() returns a new instance')]
    public function test_can_instantiate(): void
    {
        /**
         * the GetPrintableType class can be instantiated - even though all its
         * public API is static, it should still be constructible
         */
        $unit = new GetPrintableType();

        $this->assertInstanceOf(GetPrintableType::class, $unit);
    }

    // ================================================================
    //
    // from() - scalars
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{bool|int|float|string,int,string}>
     */
    public static function scalarProvider(): array
    {
        return [
            'int, FLAG_NONE' => [42, GetPrintableType::FLAG_NONE, 'int'],
            'int, FLAG_SCALAR_VALUE' => [42, GetPrintableType::FLAG_SCALAR_VALUE, 'int<42>'],
            'int, defaults' => [42, GetPrintableType::FLAG_DEFAULTS, 'int<42>'],

            'float, FLAG_NONE' => [1.5, GetPrintableType::FLAG_NONE, 'float'],
            'float, FLAG_SCALAR_VALUE' => [1.5, GetPrintableType::FLAG_SCALAR_VALUE, 'float<1.5>'],

            'true, FLAG_NONE' => [true, GetPrintableType::FLAG_NONE, 'bool'],
            'true, FLAG_SCALAR_VALUE' => [true, GetPrintableType::FLAG_SCALAR_VALUE, 'bool<true>'],

            'false, FLAG_NONE' => [false, GetPrintableType::FLAG_NONE, 'bool'],
            'false, FLAG_SCALAR_VALUE' => [false, GetPrintableType::FLAG_SCALAR_VALUE, 'bool<false>'],

            'plain string, FLAG_NONE' => ['hello', GetPrintableType::FLAG_NONE, 'string'],
            'plain string, FLAG_SCALAR_VALUE' => ['hello', GetPrintableType::FLAG_SCALAR_VALUE, 'string<hello>'],
        ];
    }

    #[TestDox('::from() returns the expected descriptor for a scalar value')]
    #[DataProvider('scalarProvider')]
    public function test_from_returns_expected_for_scalar(
        bool|int|float|string $input,
        int $options,
        string $expected,
    ): void {
        /**
         * scalar values produce their gettype() name, optionally followed by
         * `<value>` when FLAG_SCALAR_VALUE is set. Booleans format as
         * `<true>`/`<false>` rather than `<1>`/`<>` so the output is readable.
         */
        $actual = GetPrintableType::from($input, $options);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - objects
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns just "object" for a plain object when FLAG_CLASSNAME is not set')]
    public function test_from_returns_plain_object_without_classname_flag(): void
    {
        /**
         * an object value reduces to the string 'object' when the caller has
         * not asked for classname detail
         */
        $input = new stdClass();
        $expected = 'object';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_NONE);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns object<ClassName> when FLAG_CLASSNAME is set')]
    public function test_from_returns_object_with_classname(): void
    {
        /**
         * a plain object reports its class name inside an `object<...>` wrapper
         * when the caller has asked for classname detail
         */
        $input = new SampleClass();
        $expected = 'object<StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors\\SampleClass>';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CLASSNAME);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns object<ClassName> for an invokable object with defaults')]
    public function test_from_returns_object_for_invokable_with_defaults(): void
    {
        /**
         * an object defining __invoke() is still reported as an object (with
         * classname) rather than as a callable - the object dispatch runs
         * before the callable dispatch
         */
        $input = new SampleInvokable();
        $expected = 'object<StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors\\SampleInvokable>';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_DEFAULTS);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - Closures
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns "callable" for a Closure when FLAG_CALLABLE_DETAILS is not set')]
    public function test_from_returns_plain_callable_for_closure_without_details(): void
    {
        /**
         * a Closure is always routed to the callable formatter - its closure-
         * ness is not gated by FLAG_CLASSNAME - so without
         * FLAG_CALLABLE_DETAILS, it emits the bare 'callable' token
         */
        $input = fn(): int => 1;
        $expected = 'callable';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_NONE);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<Closure>" for a Closure when FLAG_CALLABLE_DETAILS alone is set')]
    public function test_from_returns_closure_detail_without_classname(): void
    {
        /**
         * FLAG_CALLABLE_DETAILS alone is enough to reveal that the value is a
         * Closure - the caller does not also have to pass FLAG_CLASSNAME. This
         * guards against the earlier bug where the closure-aware branch sat
         * behind the classname gate.
         */
        $input = fn(): int => 1;
        $expected = 'callable<Closure>';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CALLABLE_DETAILS);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<Closure>" for a Closure with defaults')]
    public function test_from_returns_callable_closure_detail_for_closure(): void
    {
        /**
         * a Closure with the default flag set is rendered as
         * `callable<Closure>` - the class name is hidden behind the 'Closure'
         * literal because all closures share the same anonymous class
         */
        $input = fn(): int => 1;
        $expected = 'callable<Closure>';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_DEFAULTS);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - callable non-object inputs
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns "callable" for a callable string without FLAG_CALLABLE_DETAILS')]
    public function test_from_returns_plain_callable_for_callable_string(): void
    {
        /**
         * a string naming a callable function produces just 'callable' when no
         * details were requested
         */
        $input = 'strlen';
        $expected = 'callable';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_NONE);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<function>" for a callable string with FLAG_CALLABLE_DETAILS')]
    public function test_from_returns_callable_with_function_name(): void
    {
        /**
         * when details are requested, a callable string is rendered with the
         * function name inside `callable<...>`
         */
        $input = 'strlen';
        $expected = 'callable<strlen>';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CALLABLE_DETAILS);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<Class::method>" for a [Class, method] callable array with FLAG_CALLABLE_DETAILS')]
    public function test_from_returns_callable_with_static_method(): void
    {
        /**
         * a `[ClassName, 'method']` array callable renders as
         * `callable<ClassName::method>` when details are requested
         */
        $input = [DateTimeImmutable::class, 'createFromFormat'];
        $expected = 'callable<DateTimeImmutable::createFromFormat>';

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CALLABLE_DETAILS);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - catch-all (types with no specific formatter)
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns "null" for a null input')]
    public function test_from_returns_null_output_for_null(): void
    {
        /**
         * null falls through to the catch-all branch and is normalised to the
         * lowercase 'null' token (PHP's own keyword spelling), not gettype()'s
         * 'NULL'
         */
        $expected = 'null';

        $actual = GetPrintableType::from(null);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "array" for a non-callable array input')]
    public function test_from_returns_array_output_for_array(): void
    {
        /**
         * a plain (non-callable) array falls through to the catch-all gettype()
         * branch and produces 'array'
         */
        $expected = 'array';

        $actual = GetPrintableType::from([1, 2, 3]);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "resource" for a resource input')]
    public function test_from_returns_resource_output_for_resource(): void
    {
        /**
         * an open PHP resource falls through to the catch-all gettype() branch
         * and produces 'resource'
         */
        $handle = tmpfile();
        $this->assertNotFalse($handle);

        $expected = 'resource';

        $actual = GetPrintableType::from($handle);

        $this->assertSame($expected, $actual);

        fclose($handle);
    }

    #[TestDox('::from() collapses a closed resource back to "resource"')]
    public function test_from_collapses_closed_resource(): void
    {
        /**
         * a closed resource - which gettype() reports as 'resource (closed)' -
         * is normalised back to the clean 'resource' token, so callers do not
         * see the unusable parenthesised form
         */
        $handle = tmpfile();
        $this->assertNotFalse($handle);
        fclose($handle);

        $expected = 'resource';

        $actual = GetPrintableType::from($handle);

        $this->assertSame($expected, $actual);
    }
}
