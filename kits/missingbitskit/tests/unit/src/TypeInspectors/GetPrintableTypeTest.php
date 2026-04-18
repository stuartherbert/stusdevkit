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
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInvokable;
use StusDevKit\MissingBitsKit\TypeInspectors\GetPrintableType;

#[TestDox(GetPrintableType::class)]
class GetPrintableTypeTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() returns a new instance')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetPrintableType class can be
        // instantiated - even though all its public API is static,
        // it should still be constructible

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetPrintableType();

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that scalar values produce their
        // gettype() name, optionally followed by `<value>` when
        // FLAG_SCALAR_VALUE is set. Booleans format as
        // `<true>`/`<false>` rather than `<1>`/`<>` so the output
        // is readable.

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, $options);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object value reduces to the
        // string 'object' when the caller has not asked for
        // classname detail

        // ----------------------------------------------------------------
        // setup your test

        $input = new stdClass();
        $expected = 'object';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_NONE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns object<ClassName> when FLAG_CLASSNAME is set')]
    public function test_from_returns_object_with_classname(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a plain object reports its class
        // name inside an `object<...>` wrapper when the caller
        // has asked for classname detail

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleClass();
        $expected = 'object<StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors\\SampleClass>';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CLASSNAME);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns object<ClassName> for an invokable object with defaults')]
    public function test_from_returns_object_for_invokable_with_defaults(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object defining __invoke() is
        // still reported as an object (with classname) rather
        // than as a callable - the object dispatch runs before
        // the callable dispatch

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleInvokable();
        $expected = 'object<StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors\\SampleInvokable>';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_DEFAULTS);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a Closure is always routed to
        // the callable formatter - its closure-ness is not gated
        // by FLAG_CLASSNAME - so without FLAG_CALLABLE_DETAILS,
        // it emits the bare 'callable' token

        // ----------------------------------------------------------------
        // setup your test

        $input = fn(): int => 1;
        $expected = 'callable';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_NONE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<Closure>" for a Closure when FLAG_CALLABLE_DETAILS alone is set')]
    public function test_from_returns_closure_detail_without_classname(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that FLAG_CALLABLE_DETAILS alone is
        // enough to reveal that the value is a Closure - the
        // caller does not also have to pass FLAG_CLASSNAME. This
        // guards against the earlier bug where the closure-aware
        // branch sat behind the classname gate.

        // ----------------------------------------------------------------
        // setup your test

        $input = fn(): int => 1;
        $expected = 'callable<Closure>';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CALLABLE_DETAILS);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<Closure>" for a Closure with defaults')]
    public function test_from_returns_callable_closure_detail_for_closure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a Closure with the default flag
        // set is rendered as `callable<Closure>` - the class name
        // is hidden behind the 'Closure' literal because all
        // closures share the same anonymous class

        // ----------------------------------------------------------------
        // setup your test

        $input = fn(): int => 1;
        $expected = 'callable<Closure>';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_DEFAULTS);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string naming a callable
        // function produces just 'callable' when no details were
        // requested

        // ----------------------------------------------------------------
        // setup your test

        $input = 'strlen';
        $expected = 'callable';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_NONE);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<function>" for a callable string with FLAG_CALLABLE_DETAILS')]
    public function test_from_returns_callable_with_function_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when details are requested, a
        // callable string is rendered with the function name
        // inside `callable<...>`

        // ----------------------------------------------------------------
        // setup your test

        $input = 'strlen';
        $expected = 'callable<strlen>';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CALLABLE_DETAILS);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "callable<Class::method>" for a [Class, method] callable array with FLAG_CALLABLE_DETAILS')]
    public function test_from_returns_callable_with_static_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a `[ClassName, 'method']` array
        // callable renders as `callable<ClassName::method>` when
        // details are requested

        // ----------------------------------------------------------------
        // setup your test

        $input = [DateTimeImmutable::class, 'createFromFormat'];
        $expected = 'callable<DateTimeImmutable::createFromFormat>';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($input, GetPrintableType::FLAG_CALLABLE_DETAILS);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that null falls through to the
        // catch-all branch and is normalised to the lowercase
        // 'null' token (PHP's own keyword spelling), not
        // gettype()'s 'NULL'

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'null';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "array" for a non-callable array input')]
    public function test_from_returns_array_output_for_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a plain (non-callable) array
        // falls through to the catch-all gettype() branch and
        // produces 'array'

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from([1, 2, 3]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns "resource" for a resource input')]
    public function test_from_returns_resource_output_for_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an open PHP resource falls
        // through to the catch-all gettype() branch and produces
        // 'resource'

        // ----------------------------------------------------------------
        // setup your test

        $handle = tmpfile();
        $this->assertNotFalse($handle);

        $expected = 'resource';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($handle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);

        // ----------------------------------------------------------------
        // tidy up

        fclose($handle);
    }

    #[TestDox('::from() collapses a closed resource back to "resource"')]
    public function test_from_collapses_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a closed resource - which
        // gettype() reports as 'resource (closed)' - is
        // normalised back to the clean 'resource' token, so
        // callers do not see the unusable parenthesised form

        // ----------------------------------------------------------------
        // setup your test

        $handle = tmpfile();
        $this->assertNotFalse($handle);
        fclose($handle);

        $expected = 'resource';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetPrintableType::from($handle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
