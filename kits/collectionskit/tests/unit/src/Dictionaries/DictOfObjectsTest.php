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
use ArrayObject;
use DateTime;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict;
use StusDevKit\CollectionsKit\Dictionaries\DictOfObjects;

#[TestDox('DictOfObjects')]
class DictOfObjectsTest extends TestCase
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
        // DictOfObjects

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfObjects::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends CollectionAsDict')]
    public function test_extends_collection_as_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that DictOfObjects is a subclass of
        // CollectionAsDict

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsDict::class, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a DictOfObjects
        // and seed it with an initial associative array of objects

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $expectedData = [
            'first' => $obj1,
            'second' => $obj2,
            'third' => $obj3,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfObjects($expectedData);

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
            'alpha' => new stdClass(),
            'beta' => new stdClass(),
            'gamma' => new stdClass(),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfObjects($expectedData);
        $actualData = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'beta', 'gamma'],
            array_keys($actualData),
        );
    }

    #[TestDox('Can instantiate with integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that DictOfObjects can also be
        // constructed with integer keys

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [
            10 => $obj1,
            20 => $obj2,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfObjects($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('Can hold mixed object types')]
    public function test_can_hold_mixed_object_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that DictOfObjects can hold objects of
        // different classes in the same collection

        // ----------------------------------------------------------------
        // setup your test

        $stdObj = new stdClass();
        $dateTime = new DateTime();
        $arrayObj = new ArrayObject();
        $anonymous = new class {
            public string $name = 'test';
        };

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfObjects([
            'std' => $stdObj,
            'date' => $dateTime,
            'arr' => $arrayObj,
            'anon' => $anonymous,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(4, $unit);
        $this->assertSame($stdObj, $unit->get('std'));
        $this->assertSame($dateTime, $unit->get('date'));
        $this->assertSame($arrayObj, $unit->get('arr'));
        $this->assertSame($anonymous, $unit->get('anon'));
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    #[TestDox('set() stores an object with a string key')]
    public function test_set_stores_object_with_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() stores an object at the
        // given string key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects();
        $obj = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'item', value: $obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['item' => $obj], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() stores an object with an integer key')]
    public function test_set_stores_object_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() stores an object at the
        // given integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects();
        $obj = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 42, value: $obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([42 => $obj], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() overwrites existing object at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling set() with an existing key
        // overwrites the previous object

        // ----------------------------------------------------------------
        // setup your test

        $original = new stdClass();
        $replacement = new stdClass();
        $unit = new DictOfObjects(['item' => $original]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'item', value: $replacement);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $unit->get('item'));
        $this->assertNotSame($original, $unit->get('item'));
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'third', value: $obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->set(key: 'item', value: new stdClass());

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

        $unit = new DictOfObjects();
        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'first', value: $obj1)
            ->set(key: 'second', value: $obj2)
            ->set(key: 'third', value: $obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
            ],
            $unit->toArray(),
        );
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

        $unit = new DictOfObjects(['item' => new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('item');

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

        $unit = new DictOfObjects(['item' => new stdClass()]);

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

        $unit = new DictOfObjects();

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

        $unit = new DictOfObjects();
        $unit->set(key: 'item', value: new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeGet() returns object for existing key')]
    public function test_maybe_get_returns_object_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the object
        // stored at the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects(['item' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
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

        $unit = new DictOfObjects(['item' => new stdClass()]);

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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns object added via set()')]
    public function test_maybe_get_returns_object_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() retrieves objects that
        // were stored using the set() method

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects();
        $unit->set(key: 'item', value: $obj);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
    }

    #[TestDox('maybeGet() returns object with integer key')]
    public function test_maybe_get_returns_object_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() works correctly with
        // integer keys

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects([42 => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
    }

    #[TestDox('maybeGet() returns the overwritten object after set()')]
    public function test_maybe_get_returns_overwritten_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the most recent
        // object after a key has been overwritten with set()

        // ----------------------------------------------------------------
        // setup your test

        $original = new stdClass();
        $replacement = new stdClass();
        $unit = new DictOfObjects(['item' => $original]);
        $unit->set(key: 'item', value: $replacement);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('get() returns object for existing key')]
    public function test_get_returns_object_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() returns the object stored at
        // the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('second');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj2, $actualResult);
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

        $unit = new DictOfObjects(['item' => new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfObjects does not contain missing',
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfObjects does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('get() returns object added via set()')]
    public function test_get_returns_object_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() retrieves objects that were
        // stored using the set() method

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects();
        $unit->set(key: 'item', value: $obj);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
    }

    #[TestDox('get() returns object with integer key')]
    public function test_get_returns_object_with_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() works correctly with
        // integer keys

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects([42 => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfObjects does not contain my-special-key',
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

        $unit = new DictOfObjects();

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

        // this test proves that toArray() returns all the objects
        // stored in the dict, preserving keys

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [
            'first' => $obj1,
            'second' => $obj2,
        ];
        $unit = new DictOfObjects($expectedData);

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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects();
        $unit->set(key: 'first', value: $obj1);
        $unit->set(key: 'second', value: $obj2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['first' => $obj1, 'second' => $obj2],
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

        $unit = new DictOfObjects();

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
        // of objects stored in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects([
            'first' => new stdClass(),
            'second' => new stdClass(),
            'third' => new stdClass(),
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

        $unit = new DictOfObjects([
            'first' => new stdClass(),
            'second' => new stdClass(),
            'third' => new stdClass(),
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

        $unit = new DictOfObjects();
        $unit->set(key: 'first', value: new stdClass());
        $unit->set(key: 'second', value: new stdClass());

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

        $unit = new DictOfObjects(['item' => new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'item', value: new stdClass());

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

        $unit = new DictOfObjects(['item' => new stdClass()]);

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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $expectedData = [
            'first' => $obj1,
            'second' => $obj2,
            'third' => $obj3,
        ];
        $unit = new DictOfObjects($expectedData);
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

        $unit = new DictOfObjects();
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

        $unit = new DictOfObjects([
            'alpha' => new stdClass(),
            'beta' => new stdClass(),
            'gamma' => new stdClass(),
        ]);
        $actualKeys = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'beta', 'gamma'], $actualKeys);
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects();
        $unit->set(key: 'first', value: $obj1);
        $unit->set(key: 'second', value: $obj2);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['first' => $obj1, 'second' => $obj2],
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects(['first' => $obj1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([
            'second' => $obj2,
            'third' => $obj3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another DictOfObjects')]
    public function test_merge_can_merge_dict_of_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // DictOfObjects and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects(['first' => $obj1]);
        $other = new DictOfObjects([
            'second' => $obj2,
            'third' => $obj3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects(['first' => $obj1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([
            'second' => $obj2,
            'third' => $obj3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray(['first' => $obj1, 'second' => $obj2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['first' => $obj1, 'second' => $obj2],
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = ['first' => $obj1, 'second' => $obj2];
        $unit = new DictOfObjects($expectedData);

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
        // string keys, the merged objects overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $original = new stdClass();
        $replacement = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $original,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([
            'second' => $replacement,
            'third' => $obj3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $replacement,
                'third' => $obj3,
            ],
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

        $unit = new DictOfObjects(['first' => new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray(['second' => new stdClass()]);

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
        // of another DictOfObjects into this dict

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects(['first' => $obj1]);
        $other = new DictOfObjects([
            'second' => $obj2,
            'third' => $obj3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects(['first' => $obj1]);
        $other = new DictOfObjects(['second' => $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['second' => $obj2], $other->toArray());
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = ['first' => $obj1, 'second' => $obj2];
        $unit = new DictOfObjects($expectedData);
        $other = new DictOfObjects();

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
        // keys, the merged objects overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $original = new stdClass();
        $replacement = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $original,
        ]);
        $other = new DictOfObjects([
            'second' => $replacement,
            'third' => $obj3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $replacement,
                'third' => $obj3,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeFirst() returns the first object')]
    public function test_maybe_first_returns_first_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the object at
        // the first key in the dict

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj1, $actualResult);
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first object added via set()')]
    public function test_maybe_first_returns_first_object_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // object that was added via the set() method

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects();
        $unit->set(key: 'first', value: $obj1);
        $unit->set(key: 'second', value: $obj2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first object')]
    public function test_first_returns_first_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the object at the
        // first key in the dict when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj1, $actualResult);
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfObjects is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last object')]
    public function test_maybe_last_returns_last_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the object at
        // the last key in the dict

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj2, $actualResult);
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last object added via set()')]
    public function test_maybe_last_returns_last_object_added_via_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added object via set()

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects();
        $unit->set(key: 'first', value: $obj1);
        $unit->set(key: 'second', value: $obj2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last object')]
    public function test_last_returns_last_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the object at the
        // last key in the dict when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj2, $actualResult);
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

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfObjects is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new DictOfObjects with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new DictOfObjects
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [
            'first' => $obj1,
            'second' => $obj2,
        ];
        $unit = new DictOfObjects($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfObjects::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('copy() returns independent instance (adding to copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that adding to the copied dict does not
        // affect the original dict's key set

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects([
            'first' => $obj1,
            'second' => $obj2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->set(key: 'third', value: $obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertCount(3, $copy);
        $this->assertFalse($unit->has('third'));
    }

    #[TestDox('copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty dict returns a
        // new, empty DictOfObjects instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfObjects::class, $copy);
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

        $unit = new DictOfObjects();

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

        $unit = new DictOfObjects(['item' => new stdClass()]);

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

        // this test proves that empty() returns false after an
        // object has been added via set()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects();
        $unit->set(key: 'item', value: new stdClass());

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

    #[TestDox('getCollectionTypeAsString() returns "DictOfObjects"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "DictOfObjects" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('DictOfObjects', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('Dict with one object: first() and last() return the same object')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a dict with exactly one object,
        // both first() and last() return that same object

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects(['only' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $first);
        $this->assertSame($obj, $last);
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

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new DictOfObjects();
        $other = new DictOfObjects(['third' => $obj3]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'first', value: $obj1)
            ->mergeArray(['second' => $obj2])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'first' => $obj1,
                'second' => $obj2,
                'third' => $obj3,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('get() and maybeGet() return same object for existing key')]
    public function test_get_and_maybe_get_return_same_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() and maybeGet() return the
        // same object instance when the key exists

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new DictOfObjects(['item' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $getResult = $unit->get('item');
        $maybeGetResult = $unit->maybeGet('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // Object-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('Preserves object identity (same instance, not a copy)')]
    public function test_preserves_object_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that objects stored in the dict are
        // the same instances (not cloned copies)

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $obj->name = 'original';
        $unit = new DictOfObjects(['item' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->get('item');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $retrieved);
        $this->assertSame('original', $retrieved->name);
    }

    #[TestDox('Mutations to retrieved object are visible through the dict')]
    public function test_mutations_visible_through_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that because objects are stored by
        // reference, mutations to a retrieved object are visible
        // when the object is retrieved again

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $obj->value = 'before';
        $unit = new DictOfObjects(['item' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->get('item');
        $retrieved->value = 'after';

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('after', $unit->get('item')->value);
    }

    #[TestDox('All stored values are objects')]
    public function test_all_stored_values_are_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that all values retrieved from the
        // dict are objects

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfObjects([
            'std' => new stdClass(),
            'date' => new DateTime(),
            'arr' => new ArrayObject(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $value) {
            $this->assertIsObject($value);
        }
    }

    #[TestDox('copy() shares object references with original')]
    public function test_copy_shares_object_references(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() creates a shallow copy —
        // the copied dict contains references to the same object
        // instances, not clones

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $obj->value = 'shared';
        $unit = new DictOfObjects(['item' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->get('item')->value = 'mutated';

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'mutated',
            $unit->get('item')->value,
        );
    }

    #[TestDox('mergeSelf() shares object references')]
    public function test_merge_self_shares_object_references(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() transfers the same
        // object references, not clones

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $obj->value = 'original';
        $unit = new DictOfObjects();
        $other = new DictOfObjects(['item' => $obj]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $unit->get('item'));
    }
}
