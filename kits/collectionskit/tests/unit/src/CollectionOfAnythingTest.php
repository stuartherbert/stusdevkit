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

namespace StusDevKit\CollectionsKit\Tests\Unit;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use stdClass;
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Arrays\Arrayable;

#[TestDox(CollectionOfAnything::class)]
class CollectionOfAnythingTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks down the namespace so that the class
        // stays consistent with the PSR-4 layout published in
        // composer.json

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            CollectionOfAnything::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CollectionOfAnything is a
        // concrete class (not an interface or trait), so
        // callers can both extend it and instantiate it
        // directly

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('is not declared abstract')]
    public function test_is_not_abstract(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CollectionOfAnything can be
        // instantiated directly — it acts as both a base
        // class for CollectionsKit collections and a
        // usable collection type in its own right

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // ----------------------------------------------------------------
        // perform the change

        $isAbstract = $reflection->isAbstract();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isAbstract);
    }

    #[TestDox('implements Arrayable')]
    public function test_implements_Arrayable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the Arrayable contract so that
        // callers can rely on toArray() across every
        // CollectionsKit collection

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // ----------------------------------------------------------------
        // perform the change

        $implementsArrayable = $reflection->implementsInterface(Arrayable::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($implementsArrayable);
    }

    #[TestDox('implements Countable')]
    public function test_implements_Countable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the Countable contract so that
        // callers can use PHP's count() on any collection

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // ----------------------------------------------------------------
        // perform the change

        $implementsCountable = $reflection->implementsInterface(Countable::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($implementsCountable);
    }

    #[TestDox('implements IteratorAggregate')]
    public function test_implements_IteratorAggregate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the IteratorAggregate contract
        // so that callers can use foreach on any collection

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // ----------------------------------------------------------------
        // perform the change

        $implementsIteratorAggregate = $reflection->implementsInterface(
            IteratorAggregate::class,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($implementsIteratorAggregate);
    }

    #[TestDox('declares the expected set of own public methods')]
    public function test_declares_own_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test pins the published public API surface of
        // CollectionOfAnything. Any new public method (or a
        // removed one) should be a deliberate, reviewed
        // change — this test will name the offender when the
        // set drifts

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            '__construct',
            'count',
            'empty',
            'getCollectionTypeAsString',
            'getIterator',
            'toArray',
        ];
        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // ----------------------------------------------------------------
        // perform the change

        // our return value
        $ownMethods = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() === CollectionOfAnything::class) {
                $ownMethods[] = $method->getName();
            }
        }
        sort($ownMethods);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() signature: __construct(array $data = [])')]
    public function test_construct_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the constructor's public shape:
        // a single array parameter, optional, defaulting to
        // an empty array

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionOfAnything::class, '__construct');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $param = $method->getParameters()[0];
        $paramType = $param->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertSame(['data'], $paramNames);
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);
        $this->assertSame('array', $paramType->getName());
        $this->assertTrue($param->isDefaultValueAvailable());
        $this->assertSame([], $param->getDefaultValue());
    }

    #[TestDox('::toArray() signature: toArray(): array')]
    public function test_toArray_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the toArray() shape required
        // by the Arrayable contract

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionOfAnything::class, 'toArray');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::count() signature: count(): int')]
    public function test_count_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the count() shape required by
        // the Countable contract

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionOfAnything::class, 'count');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('int', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::getIterator() signature: getIterator(): ArrayIterator')]
    public function test_getIterator_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the getIterator() shape
        // required by the IteratorAggregate contract

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionOfAnything::class, 'getIterator');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame(ArrayIterator::class, $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::empty() signature: empty(): bool')]
    public function test_empty_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the empty() shape: a no-arg
        // predicate returning a bool

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionOfAnything::class, 'empty');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::getCollectionTypeAsString() signature: getCollectionTypeAsString(): string')]
    public function test_getCollectionTypeAsString_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test locks in the shape of the helper used
        // by the null-value validator to build its error
        // messages

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            CollectionOfAnything::class,
            'getCollectionTypeAsString',
        );

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty collection when called with no arguments')]
    public function test_construct_creates_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling the constructor with
        // no arguments produces a usable, empty collection

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionOfAnything::class, $unit);
        $this->assertSame([], $unit->toArray());
    }

    #[TestDox('::__construct() seeds the collection from an indexed array')]
    public function test_construct_seeds_from_indexed_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the constructor takes the
        // supplied indexed array as the collection's
        // initial contents, preserving order

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() seeds the collection from an associative array')]
    public function test_construct_seeds_from_associative_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the constructor accepts
        // string-keyed arrays and stores them as-is

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() rejects an array containing a null value')]
    public function test_construct_rejects_null_in_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the constructor rejects any
        // array that contains a null value. Null storage is
        // prohibited by the RejectNullArrayValues validator
        // so that maybe* accessors can safely use null as a
        // sentinel for "not present"

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        new CollectionOfAnything(['alpha', null, 'bravo']); // @phpstan-ignore argument.type

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('::__construct() rejects a single null-only array')]
    public function test_construct_rejects_single_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that even a one-element array
        // containing only null is rejected — the validator
        // is not fooled by lone offenders

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        new CollectionOfAnything([null]); // @phpstan-ignore argument.type

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    // ================================================================
    //
    // ::toArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns an empty array for an empty collection')]
    public function test_toArray_returns_empty_array_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an empty collection's
        // toArray() is an empty PHP array — not null, not a
        // placeholder

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the stored data for an indexed collection')]
    public function test_toArray_returns_stored_indexed_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() round-trips the
        // data supplied to the constructor for an indexed
        // array

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() preserves string keys for an associative collection')]
    public function test_toArray_preserves_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns string
        // keys intact — the collection is not an auto-
        // reindexing list

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];
        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() preserves mixed value types')]
    public function test_toArray_preserves_mixed_value_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CollectionOfAnything lives
        // up to its name — it stores strings, ints, floats,
        // bools, arrays and objects side-by-side and hands
        // them back unchanged

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'a string',
            42,
            3.14,
            true,
            ['nested' => 'array'],
            new stdClass(),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // ::count() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for an empty collection')]
    public function test_count_returns_zero_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the literal
        // int 0 for an empty collection

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns the number of stored items')]
    public function test_count_returns_number_of_stored_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() reflects the size
        // of the seeded data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() is used by PHP\'s count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the Countable interface
        // wiring is correct: PHP's count() function dispatches
        // to our count() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = count($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    // ================================================================
    //
    // ::getIterator() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_getIterator_returns_array_iterator(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the iterator produced for a
        // collection is an ArrayIterator — the concrete type
        // we promise in our return declaration

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('->getIterator() yields values in insertion order')]
    public function test_getIterator_yields_values_in_insertion_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iteration walks the stored
        // data in the same order the caller supplied it —
        // no silent reordering

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualData = [];
        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('->getIterator() yields no items for an empty collection')]
    public function test_getIterator_yields_nothing_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating an empty
        // collection does not enter the loop body at all

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        // our return value
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('->getIterator() preserves string keys during iteration')]
    public function test_getIterator_preserves_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that foreach over the collection
        // yields the original associative keys, not
        // auto-generated integers

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];
        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualData = [];
        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualData);
    }

    // ================================================================
    //
    // ::empty() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->empty() returns true for an empty collection')]
    public function test_empty_returns_true_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() reports true when
        // the collection has no data

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for a non-empty collection')]
    public function test_empty_returns_false_for_non_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() reports false when
        // at least one item has been stored

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // ::getCollectionTypeAsString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns the unqualified class name')]
    public function test_getCollectionTypeAsString_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the method returns just the
        // class name without the namespace prefix — the form
        // used in the null-value validator's error messages

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionOfAnything', $actualResult);
    }

    #[TestDox('->getCollectionTypeAsString() resolves via late-static binding on subclasses')]
    public function test_getCollectionTypeAsString_uses_late_static_binding(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the method uses static::class
        // rather than self::class. On an anonymous subclass
        // PHP composes the runtime class name as
        // "CollectionOfAnything@anonymous...", which the
        // basename helper returns verbatim. The telltale
        // "@anonymous" marker only appears when late-static
        // binding is in effect — if self::class had been
        // used, we would get the plain parent basename
        // instead

        // ----------------------------------------------------------------
        // setup your test

        $unit = new class extends CollectionOfAnything {
        };

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString('@anonymous', $actualResult);
    }
}
