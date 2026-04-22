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

namespace StusDevKit\CollectionsKit\Tests\Unit\Dictionaries;

use ArrayIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict;
use StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers;

#[TestDox('DictOfNumbers')]
class DictOfNumbersTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Dictionaries namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(DictOfNumbers::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Dictionaries',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(DictOfNumbers::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsDict')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(DictOfNumbers::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares no public methods of its own beyond inherited methods')]
    public function test_declares_no_own_public_methods(): void
    {
        $reflection = new \ReflectionClass(DictOfNumbers::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === DictOfNumbers::class) {
                $ownMethods[] = $m->getName();
            }
        }
        $this->assertSame([], $ownMethods);
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty dict')]
    public function test_can_instantiate_empty_dict(): void
    {
        // this test proves that we can create a new, empty
        // DictOfNumbers
        $unit = new DictOfNumbers();

        $this->assertInstanceOf(DictOfNumbers::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends CollectionAsDict')]
    public function test_extends_collection_as_dict(): void
    {
        // this test proves that DictOfNumbers is a subclass of
        // CollectionAsDict
        $unit = new DictOfNumbers();

        $this->assertInstanceOf(CollectionAsDict::class, $unit);
    }

    #[TestDox('::__construct() accepts initial integer data')]
    public function test_can_instantiate_with_initial_integer_data(): void
    {
        // this test proves that we can create a DictOfNumbers
        // and seed it with an initial associative array of integers

        $expectedData = [
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ];

        $unit = new DictOfNumbers($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() accepts initial float data')]
    public function test_can_instantiate_with_initial_float_data(): void
    {
        // this test proves that we can create a DictOfNumbers
        // and seed it with an initial associative array of floats

        $expectedData = [
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ];

        $unit = new DictOfNumbers($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() accepts mixed integer and float data')]
    public function test_can_instantiate_with_mixed_number_data(): void
    {
        // this test proves that DictOfNumbers can hold both
        // integers and floats in the same collection

        $expectedData = [
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
            'tax_rate' => 0.15,
        ];

        $unit = new DictOfNumbers($expectedData);

        $this->assertCount(4, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        // this test proves that when constructed with an associative
        // array, the string keys are preserved

        $expectedData = [
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ];

        $unit = new DictOfNumbers($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame(
            ['count', 'price', 'quantity'],
            array_keys($actualData),
        );
    }

    #[TestDox('::__construct() accepts integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        // this test proves that DictOfNumbers can also be
        // constructed with integer keys

        $expectedData = [
            10 => 100,
            20 => 3.14,
            30 => 42,
        ];

        $unit = new DictOfNumbers($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() stores an integer with a string key')]
    public function test_set_stores_integer_with_string_key(): void
    {
        // this test proves that set() stores an integer value at
        // the given string key

        $unit = new DictOfNumbers();

        $unit->set(key: 'count', value: 42);

        $this->assertSame(['count' => 42], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() stores a float with a string key')]
    public function test_set_stores_float_with_string_key(): void
    {
        // this test proves that set() stores a float value at
        // the given string key

        $unit = new DictOfNumbers();

        $unit->set(key: 'price', value: 1.99);

        $this->assertSame(['price' => 1.99], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        // this test proves that set() stores a numeric value at
        // the given integer key

        $unit = new DictOfNumbers();

        $unit->set(key: 42, value: 100);

        $this->assertSame([42 => 100], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() overwrites existing value at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        // this test proves that calling set() with an existing key
        // overwrites the previous value

        $unit = new DictOfNumbers(['count' => 5]);

        $unit->set(key: 'count', value: 10);

        $this->assertSame(['count' => 10], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() can overwrite an integer with a float')]
    public function test_set_can_overwrite_integer_with_float(): void
    {
        // this test proves that a key originally holding an integer
        // can be overwritten with a float value

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['value' => 5]);

        $unit->set(key: 'value', value: 5.5);

        $this->assertSame(['value' => 5.5], $unit->toArray());
        $this->assertIsFloat($unit->get('value'));
    }

    #[TestDox('->set() can overwrite a float with an integer')]
    public function test_set_can_overwrite_float_with_integer(): void
    {
        // this test proves that a key originally holding a float
        // can be overwritten with an integer value

        $unit = new DictOfNumbers(['value' => 5.5]);

        $unit->set(key: 'value', value: 6);

        $this->assertSame(['value' => 6], $unit->toArray());
        $this->assertIsInt($unit->get('value'));
    }

    #[TestDox('->set() adds to existing data')]
    public function test_set_adds_to_existing_data(): void
    {
        // this test proves that set() adds a new key-value pair
        // alongside data passed into the constructor

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $unit->set(key: 'total', value: 9.95);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'total' => 9.95,
            ],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    #[TestDox('->set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        // this test proves that set() returns the same collection
        // instance for fluent method chaining

        $unit = new DictOfNumbers();

        $result = $unit->set(key: 'count', value: 42);

        $this->assertSame($unit, $result);
    }

    #[TestDox('->set() supports fluent chaining')]
    public function test_set_supports_fluent_chaining(): void
    {
        // this test proves that set() calls can be chained
        // together fluently to build up the dict

        $unit = new DictOfNumbers();

        $unit->set(key: 'count', value: 5)
            ->set(key: 'price', value: 1.99)
            ->set(key: 'total', value: 9.95);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'total' => 9.95,
            ],
            $unit->toArray(),
        );
    }

    /**
     * @return array<string, array{0: int|float}>
     */
    public static function provideNumberVariants(): array
    {
        return [
            'positive integer' => [42],
            'negative integer' => [-99],
            'zero integer' => [0],
            'positive float' => [3.14],
            'negative float' => [-2.71],
            'zero float' => [0.0],
            'large integer' => [PHP_INT_MAX],
            'small integer' => [PHP_INT_MIN],
            'very small float' => [1.0e-10],
            'very large float' => [1.0e+15],
        ];
    }

    #[TestDox('->set() accepts various numeric values')]
    #[DataProvider('provideNumberVariants')]
    public function test_set_accepts_various_numeric_values(
        int|float $input,
    ): void {
        // this test proves that set() correctly stores numbers
        // of various types and magnitudes

        $unit = new DictOfNumbers();

        $unit->set(key: 'value', value: $input);

        $this->assertCount(1, $unit);
        $this->assertSame($input, $unit->get('value'));
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_string_key(): void
    {
        // this test proves that has() returns true when the dict
        // contains the given string key

        $unit = new DictOfNumbers(['count' => 42]);

        $actualResult = $unit->has('count');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns true for key with zero integer value')]
    public function test_has_returns_true_for_key_with_zero_int(): void
    {
        // this test proves that has() returns true when the dict
        // contains a key whose value is 0 — has() checks for
        // key existence, not truthiness

        $unit = new DictOfNumbers(['offset' => 0]);

        $actualResult = $unit->has('offset');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns true for key with zero float value')]
    public function test_has_returns_true_for_key_with_zero_float(): void
    {
        // this test proves that has() returns true when the dict
        // contains a key whose value is 0.0 — has() checks for
        // key existence, not truthiness

        $unit = new DictOfNumbers(['offset' => 0.0]);

        $actualResult = $unit->has('offset');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        // this test proves that has() returns false when the dict
        // does not contain the given key

        $unit = new DictOfNumbers(['count' => 42]);

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_dict(): void
    {
        // this test proves that has() returns false when the dict
        // is empty

        $unit = new DictOfNumbers();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns true for key added via set()')]
    public function test_has_returns_true_for_key_added_via_set(): void
    {
        // this test proves that has() detects keys that were added
        // via the set() method

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 42);

        $actualResult = $unit->has('count');

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGet() returns value for existing key')]
    public function test_maybe_get_returns_value_for_existing_key(): void
    {
        // this test proves that maybeGet() returns the number
        // stored at the given key when it exists

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->maybeGet('count');

        $this->assertSame(5, $actualResult);
    }

    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // this test proves that maybeGet() returns null when the
        // given key does not exist in the dict

        $unit = new DictOfNumbers(['count' => 42]);

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_dict(): void
    {
        // this test proves that maybeGet() returns null when the
        // dict is empty

        $unit = new DictOfNumbers();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns value added via set()')]
    public function test_maybe_get_returns_value_added_via_set(): void
    {
        // this test proves that maybeGet() retrieves values that
        // were stored using the set() method

        $unit = new DictOfNumbers();
        $unit->set(key: 'price', value: 1.99);

        $actualResult = $unit->maybeGet('price');

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('->maybeGet() returns value with integer key')]
    public function test_maybe_get_returns_value_with_integer_key(): void
    {
        // this test proves that maybeGet() works correctly with
        // integer keys

        $unit = new DictOfNumbers([42 => 3.14]);

        $actualResult = $unit->maybeGet(42);

        $this->assertSame(3.14, $actualResult);
    }

    #[TestDox('->maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        // this test proves that maybeGet() returns the most recent
        // value after a key has been overwritten with set()

        $unit = new DictOfNumbers(['count' => 5]);
        $unit->set(key: 'count', value: 10);

        $actualResult = $unit->maybeGet('count');

        $this->assertSame(10, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns integer value for existing key')]
    public function test_get_returns_integer_for_existing_key(): void
    {
        // this test proves that get() returns an integer stored at
        // the given key when it exists

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->get('count');

        $this->assertSame(5, $actualResult);
    }

    #[TestDox('->get() returns float value for existing key')]
    public function test_get_returns_float_for_existing_key(): void
    {
        // this test proves that get() returns a float stored at
        // the given key when it exists

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->get('price');

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the dict

        $unit = new DictOfNumbers(['count' => 42]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfNumbers does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('->get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_dict(): void
    {
        // this test proves that get() throws a RuntimeException
        // when the dict is empty

        $unit = new DictOfNumbers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfNumbers does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('->get() returns value added via set()')]
    public function test_get_returns_value_added_via_set(): void
    {
        // this test proves that get() retrieves values that were
        // stored using the set() method

        /** @var DictOfNumbers<string, int> $unit */
        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 42);

        $actualResult = $unit->get('count');

        $this->assertSame(42, $actualResult);
    }

    #[TestDox('->get() returns value with integer key')]
    public function test_get_returns_value_with_integer_key(): void
    {
        // this test proves that get() works correctly with
        // integer keys

        $unit = new DictOfNumbers([42 => 100]);

        $actualResult = $unit->get(42);

        $this->assertSame(100, $actualResult);
    }

    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        // this test proves that the RuntimeException thrown by
        // get() includes the missing key in its message

        $unit = new DictOfNumbers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfNumbers does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_dict(): void
    {
        // this test proves that toArray() returns an empty array
        // when the dict contains no data

        $unit = new DictOfNumbers();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        // this test proves that toArray() returns all the numbers
        // stored in the dict, preserving keys and types

        $expectedData = [
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ];
        $unit = new DictOfNumbers($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() returns data added via set()')]
    public function test_to_array_returns_data_added_via_set(): void
    {
        // this test proves that toArray() includes data that was
        // added using the set() method

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 5);
        $unit->set(key: 'price', value: 1.99);

        $actualResult = $unit->toArray();

        $this->assertSame(
            ['count' => 5, 'price' => 1.99],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_dict(): void
    {
        // this test proves that count() returns 0 when the dict
        // contains no data

        $unit = new DictOfNumbers();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        // this test proves that count() returns the correct number
        // of items stored in the dict

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // this test proves that the dict works with PHP's built-in
        // count() function via the Countable interface

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() reflects items added via set()')]
    public function test_count_reflects_items_added_via_set(): void
    {
        // this test proves that count() correctly reflects items
        // added via the set() method

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 5);
        $unit->set(key: 'price', value: 1.99);

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('->count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        // this test proves that overwriting an existing key via
        // set() does not increase the count

        $unit = new DictOfNumbers(['count' => 5]);

        $unit->set(key: 'count', value: 10);

        $this->assertCount(1, $unit);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        // this test proves that getIterator() returns an
        // ArrayIterator instance

        $unit = new DictOfNumbers(['count' => 42]);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // this test proves that the dict can be used in a foreach
        // loop via the IteratorAggregate interface

        $expectedData = [
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ];
        $unit = new DictOfNumbers($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_dict_produces_no_iterations(): void
    {
        // this test proves that iterating over an empty dict does
        // not execute the loop body

        $unit = new DictOfNumbers();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        // this test proves that iterating over a dict preserves
        // the string keys

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(
            ['count', 'price', 'quantity'],
            $actualKeys,
        );
    }

    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        // this test proves that iterating over a dict includes
        // items that were added via the set() method

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 5);
        $unit->set(key: 'price', value: 1.99);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            ['count' => 5, 'price' => 1.99],
            $actualData,
        );
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('->merge() can merge an array into the dict')]
    public function test_merge_can_merge_array(): void
    {
        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the dict

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['count' => 5]);

        $result = $unit->merge([
            'price' => 1.99,
            'quantity' => 10,
        ]);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'quantity' => 10,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another DictOfNumbers')]
    public function test_merge_can_merge_dict_of_numbers(): void
    {
        // this test proves that merge() can accept another
        // DictOfNumbers and merge its contents

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['count' => 5]);
        /** @var DictOfNumbers<string, int|float> $other */
        $other = new DictOfNumbers([
            'price' => 1.99,
            'quantity' => 10,
        ]);

        $result = $unit->merge($other);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'quantity' => 10,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeArray()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeArray() adds array items to the dict')]
    public function test_merge_array_adds_items(): void
    {
        // this test proves that mergeArray() adds the given array's
        // key-value pairs to the dict

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['count' => 5]);

        $result = $unit->mergeArray([
            'price' => 1.99,
            'quantity' => 10,
        ]);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'quantity' => 10,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty dict sets the data')]
    public function test_merge_array_into_empty_dict(): void
    {
        // this test proves that mergeArray() works correctly when
        // the dict is initially empty

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers();

        $unit->mergeArray(['count' => 5, 'price' => 1.99]);

        $this->assertSame(
            ['count' => 5, 'price' => 1.99],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // this test proves that merging an empty array does not
        // alter the dict's existing data

        $expectedData = ['count' => 5, 'price' => 1.99];
        $unit = new DictOfNumbers($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        // this test proves that when merging an array with matching
        // string keys, the merged values overwrite the originals

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $unit->mergeArray(['price' => 2.50, 'total' => 12.50]);

        $this->assertSame(
            ['count' => 5, 'price' => 2.50, 'total' => 12.50],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // this test proves that mergeArray() returns the same dict
        // instance for fluent method chaining

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['count' => 5]);

        $result = $unit->mergeArray(['price' => 1.99]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another dict into this one')]
    public function test_merge_self_merges_dict(): void
    {
        // this test proves that mergeSelf() adds the contents
        // of another DictOfNumbers into this dict

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['count' => 5]);
        /** @var DictOfNumbers<string, int|float> $other */
        $other = new DictOfNumbers([
            'price' => 1.99,
            'quantity' => 10,
        ]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'quantity' => 10,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // this test proves that the dict being merged from is not
        // modified by the merge operation

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers(['count' => 5]);
        /** @var DictOfNumbers<string, int|float> $other */
        $other = new DictOfNumbers(['price' => 1.99]);

        $unit->mergeSelf($other);

        $this->assertSame(['price' => 1.99], $other->toArray());
    }

    #[TestDox('->mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // this test proves that merging an empty dict does not
        // alter the existing data

        $expectedData = ['count' => 5, 'price' => 1.99];
        $unit = new DictOfNumbers($expectedData);
        $other = new DictOfNumbers();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        // this test proves that when merging a dict with matching
        // keys, the merged values overwrite the originals

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);
        /** @var DictOfNumbers<string, int|float> $other */
        $other = new DictOfNumbers([
            'price' => 2.50,
            'total' => 12.50,
        ]);

        $unit->mergeSelf($other);

        $this->assertSame(
            ['count' => 5, 'price' => 2.50, 'total' => 12.50],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first number')]
    public function test_maybe_first_returns_first_number(): void
    {
        // this test proves that maybeFirst() returns the value of
        // the first key in the dict

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(5, $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_dict(): void
    {
        // this test proves that maybeFirst() returns null when the
        // dict is empty, rather than throwing an exception

        $unit = new DictOfNumbers();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeFirst() returns the first number added via set()')]
    public function test_maybe_first_returns_first_number_added_via_set(): void
    {
        // this test proves that maybeFirst() returns the first
        // number that was added via the set() method

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 5);
        $unit->set(key: 'price', value: 1.99);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(5, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('->first() returns the first number')]
    public function test_first_returns_first_number(): void
    {
        // this test proves that first() returns the value of the
        // first key in the dict when it is not empty

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->first();

        $this->assertSame(5, $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_dict(): void
    {
        // this test proves that first() throws a RuntimeException
        // when the dict is empty

        $unit = new DictOfNumbers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfNumbers is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last number')]
    public function test_maybe_last_returns_last_number(): void
    {
        // this test proves that maybeLast() returns the value of
        // the last key in the dict

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->maybeLast();

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_dict(): void
    {
        // this test proves that maybeLast() returns null when the
        // dict is empty, rather than throwing an exception

        $unit = new DictOfNumbers();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeLast() returns the last number added via set()')]
    public function test_maybe_last_returns_last_number_added_via_set(): void
    {
        // this test proves that maybeLast() returns the most
        // recently added number via set()

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 5);
        $unit->set(key: 'price', value: 1.99);

        $actualResult = $unit->maybeLast();

        $this->assertSame(1.99, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('->last() returns the last number')]
    public function test_last_returns_last_number(): void
    {
        // this test proves that last() returns the value of the
        // last key in the dict when it is not empty

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $actualResult = $unit->last();

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_dict(): void
    {
        // this test proves that last() throws a RuntimeException
        // when the dict is empty

        $unit = new DictOfNumbers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfNumbers is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new DictOfNumbers with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // this test proves that copy() returns a new DictOfNumbers
        // instance containing the same data as the original

        $expectedData = [
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ];
        $unit = new DictOfNumbers($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfNumbers::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // this test proves that modifying the copied dict does not
        // affect the original dict's data

        $originalData = ['count' => 5, 'price' => 1.99];
        $unit = new DictOfNumbers($originalData);

        $copy = $unit->copy();
        $copy->set(key: 'total', value: 9.95);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'total' => 9.95,
            ],
            $copy->toArray(),
        );
    }

    #[TestDox('->copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        // this test proves that copying an empty dict returns a
        // new, empty DictOfNumbers instance

        $unit = new DictOfNumbers();

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfNumbers::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('->empty() returns true for empty dict')]
    public function test_empty_returns_true_for_empty_dict(): void
    {
        // this test proves that empty() returns true when the
        // dict has no data

        $unit = new DictOfNumbers();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_dict(): void
    {
        // this test proves that empty() returns false when the
        // dict contains data

        $unit = new DictOfNumbers(['count' => 42]);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    #[TestDox('->empty() returns false after set()')]
    public function test_empty_returns_false_after_set(): void
    {
        // this test proves that empty() returns false after a
        // number has been added via set()

        $unit = new DictOfNumbers();
        $unit->set(key: 'count', value: 42);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns "DictOfNumbers"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // this test proves that getCollectionTypeAsString() returns
        // "DictOfNumbers" (just the class name without namespace)

        $unit = new DictOfNumbers();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('DictOfNumbers', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('Dict with one number: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // this test proves that for a dict with exactly one number,
        // both first() and last() return that same value

        $unit = new DictOfNumbers(['only' => 42]);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame(42, $first);
        $this->assertSame(42, $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() and merge methods support fluent chaining together')]
    public function test_set_and_merge_support_chaining(): void
    {
        // this test proves that set() and merge methods can be
        // chained together fluently

        /** @var DictOfNumbers<string, int|float> $unit */
        $unit = new DictOfNumbers();
        /** @var DictOfNumbers<string, int|float> $other */
        $other = new DictOfNumbers(['total' => 9.95]);

        $unit->set(key: 'count', value: 5)
            ->mergeArray(['price' => 1.99])
            ->mergeSelf($other);

        $this->assertSame(
            [
                'count' => 5,
                'price' => 1.99,
                'total' => 9.95,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() and ->maybeGet() return same value for existing key')]
    public function test_get_and_maybe_get_return_same_value(): void
    {
        // this test proves that get() and maybeGet() return the
        // same number when the key exists

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
        ]);

        $getResult = $unit->get('count');
        $maybeGetResult = $unit->maybeGet('count');

        $this->assertSame(5, $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // Mixed number type behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('Preserves integer and float types in same dict')]
    public function test_preserves_integer_and_float_types(): void
    {
        // this test proves that integers remain integers and
        // floats remain floats when stored together in the
        // same dict

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
            'tax_rate' => 0.15,
        ]);

        // nothing to do — values were set in the constructor

        $this->assertIsInt($unit->get('count'));
        $this->assertIsFloat($unit->get('price'));
        $this->assertIsInt($unit->get('quantity'));
        $this->assertIsFloat($unit->get('tax_rate'));
    }

    #[TestDox('Handles negative numbers of both types')]
    public function test_handles_negative_numbers(): void
    {
        // this test proves that negative integers and negative
        // floats are stored and retrieved correctly

        $unit = new DictOfNumbers([
            'int_loss' => -100,
            'float_loss' => -99.99,
        ]);

        // nothing to do — values were set in the constructor

        $this->assertSame(-100, $unit->get('int_loss'));
        $this->assertSame(-99.99, $unit->get('float_loss'));
        $this->assertIsInt($unit->get('int_loss'));
        $this->assertIsFloat($unit->get('float_loss'));
    }

    #[TestDox('Handles zero values of both types')]
    public function test_handles_zero_values(): void
    {
        // this test proves that both integer zero and float zero
        // are stored and retrieved correctly, and are not confused
        // with null or absent keys

        $unit = new DictOfNumbers([
            'int_zero' => 0,
            'float_zero' => 0.0,
        ]);

        // nothing to do — values were set in the constructor

        $this->assertSame(0, $unit->get('int_zero'));
        $this->assertSame(0.0, $unit->get('float_zero'));
        $this->assertTrue($unit->has('int_zero'));
        $this->assertTrue($unit->has('float_zero'));
    }

    #[TestDox('Handles boundary values')]
    public function test_handles_boundary_values(): void
    {
        // this test proves that extreme numeric values at the
        // boundaries of PHP's numeric range are stored and
        // retrieved without corruption

        $unit = new DictOfNumbers([
            'int_max' => PHP_INT_MAX,
            'int_min' => PHP_INT_MIN,
            'float_max' => PHP_FLOAT_MAX,
            'float_min' => PHP_FLOAT_MIN,
        ]);

        // nothing to do — values were set in the constructor

        $this->assertSame(PHP_INT_MAX, $unit->get('int_max'));
        $this->assertSame(PHP_INT_MIN, $unit->get('int_min'));
        $this->assertSame(PHP_FLOAT_MAX, $unit->get('float_max'));
        $this->assertSame(PHP_FLOAT_MIN, $unit->get('float_min'));
    }

    #[TestDox('Iteration preserves numeric types')]
    public function test_iteration_preserves_numeric_types(): void
    {
        // this test proves that iterating over a dict containing
        // mixed integer and float values preserves each value's
        // original type

        $unit = new DictOfNumbers([
            'count' => 5,
            'price' => 1.99,
            'quantity' => 10,
        ]);
        $types = [];

        foreach ($unit as $key => $value) {
            $types[$key] = get_debug_type($value);
        }

        $this->assertSame('int', $types['count']);
        $this->assertSame('float', $types['price']);
        $this->assertSame('int', $types['quantity']);
    }
}
