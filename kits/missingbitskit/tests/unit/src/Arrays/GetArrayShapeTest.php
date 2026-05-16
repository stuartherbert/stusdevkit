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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Arrays;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\MissingBitsKit\Arrays\ArrayShape;
use StusDevKit\MissingBitsKit\Arrays\GetArrayShape;

#[TestDox(GetArrayShape::class)]
class GetArrayShapeTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Arrays namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract. Callers
        // import GetArrayShape by its FQN, so moving it is a
        // breaking change that must go through a major version
        // bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Arrays';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(GetArrayShape::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // GetArrayShape is a concrete class - not an interface or
        // a trait. Callers reach it through `GetArrayShape::from()`,
        // so a silent switch to either would break every call
        // site. Pin the class-ness so a refactor cannot demote it
        // by accident.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(GetArrayShape::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait     = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('exposes only ::from() as its public method')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // GetArrayShape's published surface is a single static
        // method. Pin the method set by enumeration - any
        // addition fails with a diff that names the new method,
        // rather than a cryptic count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['from'];
        $reflection = new ReflectionClass(GetArrayShape::class);

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
    // from() method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() is declared')]
    public function test_declares_a_from_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the single entry point to this class is `from()`.
        // Renaming it is a breaking change for every caller.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(GetArrayShape::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('from');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::from() is public static')]
    public function test_from_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // GetArrayShape is a stateless utility; its single method
        // is called without an instance, and must be reachable
        // from outside the class. A silent visibility downgrade
        // would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(GetArrayShape::class))
            ->getMethod('from');

        // ----------------------------------------------------------------
        // perform the change

        $actualPublic = $method->isPublic();
        $actualStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualPublic);
        $this->assertTrue($actualStatic);
    }

    #[TestDox('::from() takes exactly one parameter typed as array')]
    public function test_from_takes_a_single_array_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the contract accepts a single PHP array and nothing
        // else. Widening to `iterable` would change the semantics
        // (a Generator has no array keys to inspect), so the
        // runtime type is pinned to `array`.

        // ----------------------------------------------------------------
        // setup your test

        $expectedCount = 1;
        $expectedType = 'array';
        $method = (new ReflectionClass(GetArrayShape::class))
            ->getMethod('from');

        // ----------------------------------------------------------------
        // perform the change

        $parameters = $method->getParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount($expectedCount, $parameters);
        $type = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame($expectedType, $type->getName());
    }

    #[TestDox('::from() returns an ArrayShape')]
    public function test_from_returns_an_ArrayShape(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is part of the contract. Callers
        // depend on it for type-narrowing in `match` blocks and
        // pattern-matching. Pin it so a silent widening (e.g. to
        // `mixed`) shows up here.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::class;
        $method = (new ReflectionClass(GetArrayShape::class))
            ->getMethod('from');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame($expected, $returnType->getName());
    }

    // ================================================================
    //
    // Behaviour: LIST cases
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns LIST for an empty array')]
    public function test_from_returns_list_for_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array is technically both list and map. We
        // pick LIST so callers do not need a third "neither"
        // case, matching PHP's own `array_is_list([])` returning
        // true.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::LIST;
        $input = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns LIST for a zero-indexed sequential array')]
    public function test_from_returns_list_for_zero_indexed_sequential_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the canonical list shape: literal `[a, b, c]`. Every
        // key is an int, keys are 0..n-1, no gaps. This is what
        // `array_is_list()` also calls a list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::LIST;
        $input = ['a', 'b', 'c'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns LIST for an int-keyed array with gaps')]
    public function test_from_returns_list_for_sparse_int_keyed_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the post-`array_filter` shape: int keys with a gap
        // where an element was removed. `array_is_list()` would
        // reject this, but GetArrayShape calls it a list because
        // the caller's intent is still "a sequence of values".

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::LIST;
        $input = [0 => 'a', 2 => 'c'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns LIST for an int-keyed array that does not start at zero')]
    public function test_from_returns_list_for_non_zero_start_int_keyed_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // another `array_is_list()` rejection that GetArrayShape
        // accepts: an int-keyed array starting at 5. The keys
        // are still positions, not identities, so the array is
        // still a list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::LIST;
        $input = [5 => 'a', 6 => 'b'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns LIST when numeric-string keys are coerced to ints by PHP')]
    public function test_from_returns_list_for_numeric_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP coerces numeric-string array keys to int at
        // write time, so `['10' => 'x']` is indistinguishable
        // from `[10 => 'x']` once stored. Both report as LIST.
        // This is documented as a "Here Be Dragons" item on
        // GetArrayShape - pinned here so a regression that
        // tried to "fix" it would fail this test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::LIST;
        $input = ['10' => 'x', '2' => 'y'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour: MAP cases
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns MAP for an all-string-keyed array')]
    public function test_from_returns_map_for_all_string_keyed_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the canonical map shape: every key is a string. The
        // keys ARE the identity, so the array is unambiguously
        // a map.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::MAP;
        $input = ['name' => 'alice', 'age' => '30'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns MAP when at least one key is a string')]
    public function test_from_returns_map_for_mixed_int_and_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a single non-numeric string key flips the whole array
        // into map territory. The int keys are now peers of the
        // string key, not list positions.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::MAP;
        $input = ['name' => 'alice', 10 => 'a', 2 => 'b'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns MAP when a numeric-looking string key is NOT coerced by PHP')]
    public function test_from_returns_map_for_non_coercible_numeric_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP only coerces a string key to int when the string is
        // a canonical decimal integer (the same form `(string)$int`
        // would produce). A leading zero, a decimal point, a plus
        // sign, or surrounding whitespace all disqualify the
        // string from coercion, so PHP keeps it as a string at
        // storage time.
        //
        // GetArrayShape classifies by the runtime key type, not by
        // the source-code appearance of the literal. A future
        // "improvement" that special-cased numeric-looking
        // strings would silently flip these into LIST and break
        // the docblock's contract - pin the MAP outcome here so
        // such a change fails this test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::MAP;
        $input = ['01' => 'a', '1.5' => 'b'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns MAP for a single-entry string-keyed array')]
    public function test_from_returns_map_for_single_string_keyed_entry(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the smallest map: a single key/value pair. Pin that a
        // string key alone is enough to trigger map-shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ArrayShape::MAP;
        $input = ['only' => 'one'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayShape::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
