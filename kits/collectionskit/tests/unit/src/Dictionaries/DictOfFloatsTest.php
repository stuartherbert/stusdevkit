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
use StusDevKit\CollectionsKit\Dictionaries\DictOfFloats;
use StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers;

#[TestDox('DictOfFloats')]
class DictOfFloatsTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty dict')]
    public function test_can_instantiate_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // DictOfFloats

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfFloats::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends DictOfNumbers')]
    public function test_extends_dict_of_numbers(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that DictOfFloats is a subclass of
        // DictOfNumbers

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfNumbers::class, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a DictOfFloats
        // and seed it with an initial associative array of floats

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfFloats($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('Constructor preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when constructed with an associative
        // array, the string keys are preserved

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfFloats($expectedData);
        $actualData = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['price', 'tax', 'total'],
            array_keys($actualData),
        );
    }

    #[TestDox('Can instantiate with integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that DictOfFloats can also be
        // constructed with integer keys

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            10 => 1.99,
            20 => 2.50,
            30 => 3.75,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfFloats($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    #[TestDox('set() stores a value with a string key')]
    public function test_set_stores_value_with_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() stores a float value at
        // the given string key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'price', value: 1.99);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['price' => 1.99], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() stores a float value at
        // the given integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 42, value: 3.14);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([42 => 3.14], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() overwrites existing value at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling set() with an existing key
        // overwrites the previous value

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'price', value: 2.50);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['price' => 2.50], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() adds to existing data')]
    public function test_set_adds_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() adds a new key-value pair
        // alongside data passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'total', value: 2.14);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    #[TestDox('set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() returns the same collection
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->set(key: 'price', value: 1.99);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('set() supports fluent chaining')]
    public function test_set_supports_fluent_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() calls can be chained
        // together fluently to build up the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'price', value: 1.99)
            ->set(key: 'tax', value: 0.15)
            ->set(key: 'total', value: 2.14);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $unit->toArray(),
        );
    }

    /**
     * @return array<string, array{0: float}>
     */
    public static function provideFloatVariants(): array
    {
        return [
            'positive float' => [1.99],
            'negative float' => [-3.14],
            'zero' => [0.0],
            'very small positive' => [1.0e-10],
            'very large positive' => [1.0e+15],
            'very small negative' => [-1.0e-10],
            'very large negative' => [-1.0e+15],
            'one third' => [1.0 / 3.0],
            'pi approximation' => [3.14159265358979],
            'euler number' => [2.71828182845905],
        ];
    }

    #[TestDox('set() accepts various float values')]
    #[DataProvider('provideFloatVariants')]
    public function test_set_accepts_various_float_values(
        float $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() correctly stores floats
        // of various magnitudes and formats

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'value', value: $input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame($input, $unit->get('value'));
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns true when the dict
        // contains the given string key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('has() returns true for key with zero value')]
    public function test_has_returns_true_for_key_with_zero_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns true when the dict
        // contains a key whose value is 0.0 — has() checks for
        // key existence, not truthiness

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['offset' => 0.0]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('offset');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the dict
        // does not contain the given key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the dict
        // is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('has() returns true for key added via set()')]
    public function test_has_returns_true_for_key_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() detects keys that were added
        // via the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeGet() returns value for existing key')]
    public function test_maybe_get_returns_value_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the float
        // stored at the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // given key does not exist in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns value added via set()')]
    public function test_maybe_get_returns_value_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() retrieves values that
        // were stored using the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('maybeGet() returns value with integer key')]
    public function test_maybe_get_returns_value_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() works correctly with
        // integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([42 => 3.14]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14, $actualResult);
    }

    #[TestDox('maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the most recent
        // value after a key has been overwritten with set()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);
        $unit->set(key: 'price', value: 2.50);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2.50, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('get() returns value for existing key')]
    public function test_get_returns_value_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() returns the float stored at
        // the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('tax');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0.15, $actualResult);
    }

    #[TestDox('get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfFloats does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfFloats does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('get() returns value added via set()')]
    public function test_get_returns_value_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() retrieves values that were
        // stored using the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('get() returns value with integer key')]
    public function test_get_returns_value_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() works correctly with
        // integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([42 => 3.14]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14, $actualResult);
    }

    #[TestDox('get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the RuntimeException thrown by
        // get() includes the missing key in its message

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfFloats does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the dict contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);
    }

    #[TestDox('toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns all the floats
        // stored in the dict, preserving keys

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ];
        $unit = new DictOfFloats($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('toArray() returns data added via set()')]
    public function test_to_array_returns_data_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() includes data that was
        // added using the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);
        $unit->set(key: 'tax', value: 0.15);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['price' => 1.99, 'tax' => 0.15],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the dict
        // contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the correct number
        // of floats stored in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the dict works with PHP's built-in
        // count() function via the Countable interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = count($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('count() reflects items added via set()')]
    public function test_count_reflects_items_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() correctly reflects items
        // added via the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);
        $unit->set(key: 'tax', value: 0.15);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that overwriting an existing key via
        // set() does not increase the count

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'price', value: 2.50);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    #[TestDox('getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIterator() returns an
        // ArrayIterator instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the dict can be used in a foreach
        // loop via the IteratorAggregate interface

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ];
        $unit = new DictOfFloats($expectedData);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_dict_produces_no_iterations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over an empty dict does
        // not execute the loop body

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $iterationCount = 0;

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            $iterationCount++;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a dict preserves
        // the string keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ]);
        $actualKeys = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['price', 'tax', 'total'], $actualKeys);
    }

    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a dict includes
        // items that were added via the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);
        $unit->set(key: 'tax', value: 0.15);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['price' => 1.99, 'tax' => 0.15],
            $actualData,
        );
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('merge() can merge an array into the dict')]
    public function test_merge_can_merge_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another DictOfFloats')]
    public function test_merge_can_merge_dict_of_floats(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // DictOfFloats and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);
        $other = new DictOfFloats([
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
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

    #[TestDox('mergeArray() adds array items to the dict')]
    public function test_merge_array_adds_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() adds the given array's
        // key-value pairs to the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeArray() into empty dict sets the data')]
    public function test_merge_array_into_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() works correctly when
        // the dict is initially empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray(['price' => 1.99, 'tax' => 0.15]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['price' => 1.99, 'tax' => 0.15],
            $unit->toArray(),
        );
    }

    #[TestDox('mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty array does not
        // alter the dict's existing data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['price' => 1.99, 'tax' => 0.15];
        $unit = new DictOfFloats($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when merging an array with matching
        // string keys, the merged values overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray(['tax' => 0.20, 'total' => 2.19]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['price' => 1.99, 'tax' => 0.20, 'total' => 2.19],
            $unit->toArray(),
        );
    }

    #[TestDox('mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() returns the same dict
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray(['tax' => 0.15]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('mergeSelf() merges another dict into this one')]
    public function test_merge_self_merges_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() adds the contents
        // of another DictOfFloats into this dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);
        $other = new DictOfFloats([
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the dict being merged from is not
        // modified by the merge operation

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);
        $other = new DictOfFloats(['tax' => 0.15]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['tax' => 0.15], $other->toArray());
    }

    #[TestDox('mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty dict does not
        // alter the existing data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['price' => 1.99, 'tax' => 0.15];
        $unit = new DictOfFloats($expectedData);
        $other = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when merging a dict with matching
        // keys, the merged values overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);
        $other = new DictOfFloats([
            'tax' => 0.20,
            'total' => 2.19,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['price' => 1.99, 'tax' => 0.20, 'total' => 2.19],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeFirst() returns the first float')]
    public function test_maybe_first_returns_first_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the value of
        // the first key in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // dict is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first float added via set()')]
    public function test_maybe_first_returns_first_float_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // float that was added via the set() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);
        $unit->set(key: 'tax', value: 0.15);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first float')]
    public function test_first_returns_first_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the value of the
        // first key in the dict when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $actualResult);
    }

    #[TestDox('first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfFloats is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last float')]
    public function test_maybe_last_returns_last_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the value of
        // the last key in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0.15, $actualResult);
    }

    #[TestDox('maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // dict is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last float added via set()')]
    public function test_maybe_last_returns_last_float_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added float via set()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);
        $unit->set(key: 'tax', value: 0.15);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0.15, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last float')]
    public function test_last_returns_last_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the value of the
        // last key in the dict when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0.15, $actualResult);
    }

    #[TestDox('last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfFloats is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new DictOfFloats with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new DictOfFloats
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ];
        $unit = new DictOfFloats($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfFloats::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifying the copied dict does not
        // affect the original dict's data

        // ----------------------------------------------------------------
        // setup your test

        $originalData = ['price' => 1.99, 'tax' => 0.15];
        $unit = new DictOfFloats($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->set(key: 'total', value: 2.14);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $copy->toArray(),
        );
    }

    #[TestDox('copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty dict returns a
        // new, empty DictOfFloats instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfFloats::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('empty() returns true for empty dict')]
    public function test_empty_returns_true_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // dict has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // dict contains data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['price' => 1.99]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('empty() returns false after set()')]
    public function test_empty_returns_false_after_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false after a
        // float has been added via set()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $unit->set(key: 'price', value: 1.99);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('getCollectionTypeAsString() returns "DictOfFloats"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "DictOfFloats" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('DictOfFloats', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('Dict with one float: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a dict with exactly one float,
        // both first() and last() return that same value

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats(['only' => 3.14]);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14, $first);
        $this->assertSame(3.14, $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('set() and merge methods support fluent chaining together')]
    public function test_set_and_merge_support_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() and merge methods can be
        // chained together fluently

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats();
        $other = new DictOfFloats(['total' => 2.14]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'price', value: 1.99)
            ->mergeArray(['tax' => 0.15])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'price' => 1.99,
                'tax' => 0.15,
                'total' => 2.14,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('get() and maybeGet() return same value for existing key')]
    public function test_get_and_maybe_get_return_same_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() and maybeGet() return the
        // same float value when the key exists

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $getResult = $unit->get('price');
        $maybeGetResult = $unit->maybeGet('price');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1.99, $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // Float-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('All stored values are floats')]
    public function test_all_stored_values_are_floats(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that all values retrieved from the
        // dict are floats

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'price' => 1.99,
            'tax' => 0.15,
            'total' => 2.14,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $value) {
            $this->assertIsFloat($value);
        }
    }

    #[TestDox('Handles negative floats correctly')]
    public function test_handles_negative_floats(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that negative float values are stored
        // and retrieved correctly

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfFloats([
            'loss' => -100.50,
            'adjustment' => -0.01,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        // nothing to do — values were set in the constructor

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(-100.50, $unit->get('loss'));
        $this->assertSame(-0.01, $unit->get('adjustment'));
    }

    #[TestDox('Preserves float precision')]
    public function test_preserves_float_precision(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that float values are stored and
        // retrieved without loss of precision

        // ----------------------------------------------------------------
        // setup your test

        $pi = 3.14159265358979;
        $euler = 2.71828182845905;
        $unit = new DictOfFloats([
            'pi' => $pi,
            'euler' => $euler,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        // nothing to do — values were set in the constructor

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($pi, $unit->get('pi'));
        $this->assertSame($euler, $unit->get('euler'));
    }
}
