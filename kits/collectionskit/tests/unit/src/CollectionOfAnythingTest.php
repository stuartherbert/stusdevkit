<?php

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
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Arrays\Arrayable;

#[TestDox('CollectionOfAnything')]
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
        // this test locks down the namespace so that the class
        // stays consistent with the PSR-4 layout published in
        // composer.json

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        $this->assertSame(
            'StusDevKit\\CollectionsKit',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // this test proves that CollectionOfAnything is a
        // concrete class (not an interface or trait), so
        // callers can both extend it and instantiate it
        // directly

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('is not declared abstract')]
    public function test_is_not_abstract(): void
    {
        // this test proves that CollectionOfAnything can be
        // instantiated directly — it acts as both a base
        // class for CollectionsKit collections and a
        // usable collection type in its own right

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('implements Arrayable')]
    public function test_implements_Arrayable(): void
    {
        // this test locks in the Arrayable contract so that
        // callers can rely on toArray() across every
        // CollectionsKit collection

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        $this->assertTrue($reflection->implementsInterface(Arrayable::class));
    }

    #[TestDox('implements Countable')]
    public function test_implements_Countable(): void
    {
        // this test locks in the Countable contract so that
        // callers can use PHP's count() on any collection

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        $this->assertTrue($reflection->implementsInterface(Countable::class));
    }

    #[TestDox('implements IteratorAggregate')]
    public function test_implements_IteratorAggregate(): void
    {
        // this test locks in the IteratorAggregate contract
        // so that callers can use foreach on any collection

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        $this->assertTrue(
            $reflection->implementsInterface(IteratorAggregate::class),
        );
    }

    #[TestDox('declares the expected set of own public methods')]
    public function test_declares_own_method_set(): void
    {
        // this test pins the published public API surface of
        // CollectionOfAnything. Any new public method (or a
        // removed one) should be a deliberate, reviewed
        // change — this test will name the offender when the
        // set drifts

        $reflection = new ReflectionClass(CollectionOfAnything::class);

        // our return value
        $ownMethods = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() === CollectionOfAnything::class) {
                $ownMethods[] = $method->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame(
            [
                '__construct',
                'count',
                'empty',
                'getCollectionTypeAsString',
                'getIterator',
                'toArray',
            ],
            $ownMethods,
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() signature: __construct(array $data = [])')]
    public function test_construct_signature(): void
    {
        // this test locks in the constructor's public shape:
        // a single array parameter, optional, defaulting to
        // an empty array

        $method = new ReflectionMethod(CollectionOfAnything::class, '__construct');

        $this->assertTrue($method->isPublic());
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['data'], $paramNames);

        $param = $method->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);
        $this->assertSame('array', $paramType->getName());
        $this->assertTrue($param->isDefaultValueAvailable());
        $this->assertSame([], $param->getDefaultValue());
    }

    #[TestDox('::toArray() signature: toArray(): array')]
    public function test_toArray_signature(): void
    {
        // this test locks in the toArray() shape required
        // by the Arrayable contract

        $method = new ReflectionMethod(CollectionOfAnything::class, 'toArray');

        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::count() signature: count(): int')]
    public function test_count_signature(): void
    {
        // this test locks in the count() shape required by
        // the Countable contract

        $method = new ReflectionMethod(CollectionOfAnything::class, 'count');

        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('int', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::getIterator() signature: getIterator(): ArrayIterator')]
    public function test_getIterator_signature(): void
    {
        // this test locks in the getIterator() shape
        // required by the IteratorAggregate contract

        $method = new ReflectionMethod(CollectionOfAnything::class, 'getIterator');

        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame(ArrayIterator::class, $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::empty() signature: empty(): bool')]
    public function test_empty_signature(): void
    {
        // this test locks in the empty() shape: a no-arg
        // predicate returning a bool

        $method = new ReflectionMethod(CollectionOfAnything::class, 'empty');

        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::getCollectionTypeAsString() signature: getCollectionTypeAsString(): string')]
    public function test_getCollectionTypeAsString_signature(): void
    {
        // this test locks in the shape of the helper used
        // by the null-value validator to build its error
        // messages

        $method = new ReflectionMethod(
            CollectionOfAnything::class,
            'getCollectionTypeAsString',
        );

        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
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
        // this test proves that calling the constructor with
        // no arguments produces a usable, empty collection

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        $this->assertInstanceOf(CollectionOfAnything::class, $unit);
        $this->assertSame([], $unit->toArray());
    }

    #[TestDox('::__construct() seeds the collection from an indexed array')]
    public function test_construct_seeds_from_indexed_array(): void
    {
        // this test proves that the constructor takes the
        // supplied indexed array as the collection's
        // initial contents, preserving order

        $expectedData = ['alpha', 'bravo', 'charlie'];

        $unit = new CollectionOfAnything($expectedData);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() seeds the collection from an associative array')]
    public function test_construct_seeds_from_associative_array(): void
    {
        // this test proves that the constructor accepts
        // string-keyed arrays and stores them as-is

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];

        $unit = new CollectionOfAnything($expectedData);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() rejects an array containing a null value')]
    public function test_construct_rejects_null_in_array(): void
    {
        // this test proves that the constructor rejects any
        // array that contains a null value. Null storage is
        // prohibited by the RejectNullArrayValues validator
        // so that maybe* accessors can safely use null as a
        // sentinel for "not present"

        $this->expectException(NullValueNotAllowedException::class);

        new CollectionOfAnything(['alpha', null, 'bravo']); // @phpstan-ignore argument.type
    }

    #[TestDox('::__construct() rejects a single null-only array')]
    public function test_construct_rejects_single_null(): void
    {
        // this test proves that even a one-element array
        // containing only null is rejected — the validator
        // is not fooled by lone offenders

        $this->expectException(NullValueNotAllowedException::class);

        new CollectionOfAnything([null]); // @phpstan-ignore argument.type
    }

    // ================================================================
    //
    // ::toArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns an empty array for an empty collection')]
    public function test_toArray_returns_empty_array_for_empty_collection(): void
    {
        // this test proves that an empty collection's
        // toArray() is an empty PHP array — not null, not a
        // placeholder

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the stored data for an indexed collection')]
    public function test_toArray_returns_stored_indexed_data(): void
    {
        // this test proves that toArray() round-trips the
        // data supplied to the constructor for an indexed
        // array

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionOfAnything($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() preserves string keys for an associative collection')]
    public function test_toArray_preserves_string_keys(): void
    {
        // this test proves that toArray() returns string
        // keys intact — the collection is not an auto-
        // reindexing list

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];
        $unit = new CollectionOfAnything($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() preserves mixed value types')]
    public function test_toArray_preserves_mixed_value_types(): void
    {
        // this test proves that CollectionOfAnything lives
        // up to its name — it stores strings, ints, floats,
        // bools, arrays and objects side-by-side and hands
        // them back unchanged

        $expectedData = [
            'a string',
            42,
            3.14,
            true,
            ['nested' => 'array'],
            new \stdClass(),
        ];

        $unit = new CollectionOfAnything($expectedData);

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
        // this test proves that count() returns the literal
        // int 0 for an empty collection

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns the number of stored items')]
    public function test_count_returns_number_of_stored_items(): void
    {
        // this test proves that count() reflects the size
        // of the seeded data

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() is used by PHP\'s count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // this test proves that the Countable interface
        // wiring is correct: PHP's count() function dispatches
        // to our count() method

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        $actualResult = count($unit);

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
        // this test proves that the iterator produced for a
        // collection is an ArrayIterator — the concrete type
        // we promise in our return declaration

        $unit = new CollectionOfAnything(['alpha', 'bravo']);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('->getIterator() yields values in insertion order')]
    public function test_getIterator_yields_values_in_insertion_order(): void
    {
        // this test proves that iteration walks the stored
        // data in the same order the caller supplied it —
        // no silent reordering

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionOfAnything($expectedData);

        $actualData = [];
        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('->getIterator() yields no items for an empty collection')]
    public function test_getIterator_yields_nothing_for_empty_collection(): void
    {
        // this test proves that iterating an empty
        // collection does not enter the loop body at all

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        // our return value
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('->getIterator() preserves string keys during iteration')]
    public function test_getIterator_preserves_string_keys(): void
    {
        // this test proves that foreach over the collection
        // yields the original associative keys, not
        // auto-generated integers

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];
        $unit = new CollectionOfAnything($expectedData);

        $actualData = [];
        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

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
        // this test proves that empty() reports true when
        // the collection has no data

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for a non-empty collection')]
    public function test_empty_returns_false_for_non_empty_collection(): void
    {
        // this test proves that empty() reports false when
        // at least one item has been stored

        $unit = new CollectionOfAnything(['alpha']);

        $actualResult = $unit->empty();

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
        // this test proves that the method returns just the
        // class name without the namespace prefix — the form
        // used in the null-value validator's error messages

        /** @var CollectionOfAnything<int, string> $unit */
        $unit = new CollectionOfAnything();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('CollectionOfAnything', $actualResult);
    }

    #[TestDox('->getCollectionTypeAsString() resolves via late-static binding on subclasses')]
    public function test_getCollectionTypeAsString_uses_late_static_binding(): void
    {
        // this test proves that the method uses static::class
        // rather than self::class. On an anonymous subclass
        // PHP composes the runtime class name as
        // "CollectionOfAnything@anonymous...", which the
        // basename helper returns verbatim. The telltale
        // "@anonymous" marker only appears when late-static
        // binding is in effect — if self::class had been
        // used, we would get the plain parent basename
        // instead

        $unit = new class extends CollectionOfAnything {
        };

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertStringContainsString('@anonymous', $actualResult);
    }
}
