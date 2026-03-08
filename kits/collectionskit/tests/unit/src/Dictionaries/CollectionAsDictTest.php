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
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox('CollectionAsDict')]
class CollectionAsDictTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty dictionary')]
    public function test_can_instantiate_empty_dictionary(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // CollectionAsDict

        // ----------------------------------------------------------------
        // dictup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsDict::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a CollectionAsDict
        // and seed it with an initial associative array of data

        // ----------------------------------------------------------------
        // dictup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsDict($expectedData);

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
        // dictup your test

        $expectedData = [
            'x' => 'alpha',
            'y' => 'bravo',
            'z' => 'charlie',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsDict($expectedData);
        $actualData = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['x', 'y', 'z'], array_keys($actualData));
    }

    #[TestDox('Can instantiate with integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CollectionAsDict can also be
        // constructed with integer keys

        // ----------------------------------------------------------------
        // dictup your test

        $expectedData = [
            10 => 'alpha',
            20 => 'bravo',
            30 => 'charlie',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsDict($expectedData);

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

        // this test proves that set() stores a value at the given
        // string key

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'name', value: 'alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['name' => 'alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() stores a value at the given
        // integer key

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 42, value: 'alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([42 => 'alpha'], $unit->toArray());
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
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'name', value: 'bravo');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['name' => 'bravo'], $unit->toArray());
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
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'third', value: 'charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
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
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->set(key: 'name', value: 'alpha');

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
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'first', value: 'alpha')
            ->set(key: 'second', value: 'bravo')
            ->set(key: 'third', value: 'charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('set() can store values of different types')]
    public function test_set_can_store_mixed_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() can store values of different
        // types in the same dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'string', value: 'hello');
        $unit->set(key: 'int', value: 42);
        $unit->set(key: 'float', value: 3.14);
        $unit->set(key: 'bool', value: true);
        $unit->set(key: 'array', value: ['nested' => 'data']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'string' => 'hello',
                'int' => 42,
                'float' => 3.14,
                'bool' => true,
                'array' => ['nested' => 'data'],
            ],
            $unit->toArray(),
        );
        $this->assertCount(5, $unit);
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
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('name');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('has() returns true for existing integer key')]
    public function test_has_returns_true_for_existing_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns true when the dict
        // contains the given integer key

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([42 => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has(42);

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
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the dict
        // is empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

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
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('name');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('Constructor rejects null values')]
    public function test_constructor_rejects_null_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the constructor throws a
        // NullValueNotAllowed exception when the initial data
        // contains a null value

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        new CollectionAsDict(['name' => null]);
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

        // this test proves that maybeGet() returns the value stored
        // at the given key when it exists

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('first');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // given key does not exist in the dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // dict is empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

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
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('name');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('maybeGet() returns value with integer key')]
    public function test_maybe_get_returns_value_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() works correctly with
        // integer keys

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([42 => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }


    #[TestDox('maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the most recent
        // value after a key has been overwritten with set()

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);
        $unit->set(key: 'name', value: 'bravo');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('name');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('bravo', $actualResult);
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

        // this test proves that get() returns the value stored at
        // the given key when it exists

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('second');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('bravo', $actualResult);
    }

    #[TestDox('get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'CollectionAsDict does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'CollectionAsDict does not contain anything',
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
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('name');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('get() returns value with integer key')]
    public function test_get_returns_value_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() works correctly with
        // integer keys

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([42 => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the RuntimeException thrown by
        // get() includes the missing key in its message

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'CollectionAsDict does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the dict contains no data

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

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

        // this test proves that toArray() returns all the data
        // stored in the dict, preserving keys

        // ----------------------------------------------------------------
        // dictup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];
        $unit = new CollectionAsDict($expectedData);

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
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['first' => 'alpha', 'second' => 'bravo'],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the dict
        // contains no data

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

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
        // of items stored in the dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
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
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
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
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

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
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'name', value: 'bravo');

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
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the dict can be used in a foreach
        // loop via the IteratorAggregate interface

        // ----------------------------------------------------------------
        // dictup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];
        $unit = new CollectionAsDict($expectedData);
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
    public function test_iterating_empty_set_produces_no_iterations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over an empty dict does
        // not execute the loop body

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();
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
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);
        $actualKeys = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['first', 'second', 'third'], $actualKeys);
    }

    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a dict includes
        // items that were added via the set() method

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['first' => 'alpha', 'second' => 'bravo'],
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
        // dictup your test

        $unit = new CollectionAsDict(['first' => 'alpha']);
        $toMerge = ['second' => 'bravo', 'third' => 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another CollectionAsDict')]
    public function test_merge_can_merge_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // CollectionAsDict and merge its contents

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['first' => 'alpha']);
        $other = new CollectionAsDict([
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
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
        // dictup your test

        $unit = new CollectionAsDict(['first' => 'alpha']);
        $toMerge = ['second' => 'bravo', 'third' => 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeArray() into empty dict dicts the data')]
    public function test_merge_array_into_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() works correctly when
        // the dict is initially empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();
        $toMerge = ['first' => 'alpha', 'second' => 'bravo'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['first' => 'alpha', 'second' => 'bravo'],
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
        // dictup your test

        $expectedData = ['first' => 'alpha', 'second' => 'bravo'];
        $unit = new CollectionAsDict($expectedData);

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
        // dictup your test

        $unit = new CollectionAsDict([
            'name' => 'alpha',
            'value' => 100,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray(['value' => 200, 'extra' => 'new']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['name' => 'alpha', 'value' => 200, 'extra' => 'new'],
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
        // dictup your test

        $unit = new CollectionAsDict(['first' => 'alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray(['second' => 'bravo']);

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
    public function test_merge_self_merges_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() adds the contents
        // of another CollectionAsDict into this dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['first' => 'alpha']);
        $other = new CollectionAsDict([
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
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
        // dictup your test

        $unit = new CollectionAsDict(['first' => 'alpha']);
        $other = new CollectionAsDict(['second' => 'bravo']);
        $expectedOtherData = ['second' => 'bravo'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    #[TestDox('mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty dict does not
        // alter the existing data

        // ----------------------------------------------------------------
        // dictup your test

        $expectedData = ['first' => 'alpha', 'second' => 'bravo'];
        $unit = new CollectionAsDict($expectedData);
        $other = new CollectionAsDict();

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
        // dictup your test

        $unit = new CollectionAsDict([
            'name' => 'alpha',
            'value' => 100,
        ]);
        $other = new CollectionAsDict([
            'value' => 200,
            'extra' => 'new',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['name' => 'alpha', 'value' => 200, 'extra' => 'new'],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeFirst() returns the first item')]
    public function test_maybe_first_returns_first_item(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the value of
        // the first key in the dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // dict is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first item added via set()')]
    public function test_maybe_first_returns_first_item_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // item that was added via the set() method

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first item')]
    public function test_first_returns_first_item(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the value of the
        // first key in the dict when it is not empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsDict is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last item')]
    public function test_maybe_last_returns_last_item(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the value of
        // the last key in the dict

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // dict is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last item added via set()')]
    public function test_maybe_last_returns_last_item_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added item via set()

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('bravo', $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last item')]
    public function test_last_returns_last_item(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the value of the
        // last key in the dict when it is not empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsDict is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new CollectionAsDict with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new CollectionAsDict
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // dictup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];
        $unit = new CollectionAsDict($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsDict::class, $copy);
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
        // dictup your test

        $originalData = ['first' => 'alpha', 'second' => 'bravo'];
        $unit = new CollectionAsDict($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->set(key: 'third', value: 'charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $copy->toArray(),
        );
    }

    #[TestDox('copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty dict returns a new,
        // empty CollectionAsDict instance

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsDict::class, $copy);
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
    public function test_empty_returns_true_for_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // dict has no data

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // dict contains data

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['name' => 'alpha']);

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

        // this test proves that empty() returns false after an item
        // has been added via set()

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

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

    #[TestDox('getCollectionTypeAsString() returns the class basename')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "CollectionAsDict" (just the class name without namespace)

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionAsDict', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('dict with one item: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a dict with exactly one item,
        // both first() and last() return that same item

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict(['only' => 'item']);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('item', $first);
        $this->assertSame('item', $last);
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
        // dictup your test

        $unit = new CollectionAsDict();
        $other = new CollectionAsDict(['fourth' => 'delta']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'first', value: 'alpha')
            ->mergeArray([
                'second' => 'bravo',
                'third' => 'charlie',
            ])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
                'fourth' => 'delta',
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
        // same value when the key exists and is not null

        // ----------------------------------------------------------------
        // dictup your test

        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $getResult = $unit->get('first');
        $maybeGetResult = $unit->maybeGet('first');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }
}
