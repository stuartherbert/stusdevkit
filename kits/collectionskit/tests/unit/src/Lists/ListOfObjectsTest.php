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

namespace StusDevKit\CollectionsKit\Tests\Unit\Lists;

use ArrayIterator;
use ArrayObject;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use StusDevKit\CollectionsKit\Lists\ListOfObjects;

#[TestDox('ListOfObjects')]
class ListOfObjectsTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // ListOfObjects

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfObjects::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('::__construct() accepts initial objects')]
    public function test_can_instantiate_with_initial_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new ListOfObjects
        // and seed it with an initial array of objects

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedObjects = [$obj1, $obj2];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfObjects($expectedObjects);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($expectedObjects, $unit->toArray());
    }

    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when constructed with a list-style
        // array, the keys remain sequential integers

        // ----------------------------------------------------------------
        // setup your test

        $objects = [
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfObjects($objects);
        $actualData = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    #[TestDox('->add() appends an object to the list')]
    public function test_add_appends_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends an object to the
        // end of the list with a sequential integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->add() appends multiple objects in order')]
    public function test_add_appends_multiple_objects_in_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() multiple times
        // appends each object in the order they were added

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj1);
        $unit->add($obj2);
        $unit->add($obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj1, $obj2, $obj3], $unit->toArray());
    }

    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends an object after any
        // data that was passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj1, $obj2, $obj3], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() returns the same collection
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add(new stdClass());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() calls can be chained
        // together fluently to build up the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj1)
            ->add($obj2)
            ->add($obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj1, $obj2, $obj3], $unit->toArray());
    }

    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that objects added via add() always
        // receive sequential integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(new stdClass());
        $unit->add(new stdClass());
        $unit->add(new stdClass());

        // ----------------------------------------------------------------
        // test the results

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    #[TestDox('->add() can add the same object instance twice')]
    public function test_add_can_add_same_object_twice(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() allows the same object
        // instance to appear multiple times in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj);
        $unit->add($obj);
        $unit->add($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj, $obj, $obj], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    #[TestDox('->add() accepts a stdClass')]
    public function test_add_accepts_stdclass(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store a stdClass object

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj = new stdClass();
        $obj->name = 'test';

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame($obj, $unit->first());
        $this->assertSame('test', $unit->first()->name);
    }

    #[TestDox('->add() accepts a DateTime')]
    public function test_add_accepts_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store a DateTime object

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $date = new DateTime('2026-01-15');

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($date);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertInstanceOf(DateTime::class, $unit->first());
        $this->assertSame($date, $unit->first());
    }

    #[TestDox('->add() accepts an anonymous class instance')]
    public function test_add_accepts_anonymous_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store an anonymous class
        // instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj = new class {
            public function greet(): string
            {
                return 'hello';
            }
        };

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame('hello', $unit->first()->greet()); // @phpstan-ignore method.notFound
    }

    #[TestDox('->add() accepts an ArrayObject')]
    public function test_add_accepts_array_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store an ArrayObject
        // instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $obj = new ArrayObject(['alpha', 'bravo']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertInstanceOf(ArrayObject::class, $unit->first());
    }

    /**
     * @return array<string, array{0: object}>
     */
    public static function provideObjectVariants(): array
    {
        return [
            'stdClass' => [new stdClass()],
            'DateTime' => [new DateTime()],
            'ArrayObject' => [new ArrayObject()],
            'anonymous class' => [
                new class {
                    public string $name = 'test';
                },
            ],
        ];
    }

    #[TestDox('->add() accepts various object types')]
    #[DataProvider('provideObjectVariants')]
    public function test_add_accepts_various_object_types(
        object $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() correctly stores objects of
        // various types

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame($input, $unit->first());
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns empty array for empty list')]
    public function test_to_array_returns_empty_array_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the list contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns all the objects
        // stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [$obj1, $obj2];
        $unit = new ListOfObjects($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() includes data that was
        // added using the add() method

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects();
        $unit->add($obj1);
        $unit->add($obj2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj1, $obj2], $actualResult);
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for empty list')]
    public function test_count_returns_zero_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the list
        // contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the correct number
        // of objects stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the list works with PHP's built-in
        // count() function via the Countable interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = count($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() correctly reflects items
        // added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $unit->add(new stdClass());
        $unit->add(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2, $actualResult);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIterator() returns an
        // ArrayIterator instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('List can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the list can be used in a foreach
        // loop via the IteratorAggregate interface

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [$obj1, $obj2];
        $unit = new ListOfObjects($expectedData);
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

    #[TestDox('Iterating empty list produces no iterations')]
    public function test_iterating_empty_list_produces_no_iterations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over an empty list does
        // not execute the loop body

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
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

    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a ListOfObjects
        // produces sequential integer keys starting from 0

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ]);
        $actualKeys = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([0, 1, 2], $actualKeys);
    }

    #[TestDox('Iteration includes items added via add()')]
    public function test_iteration_includes_items_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a list includes
        // items that were added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects();
        $unit->add($obj1);
        $unit->add($obj2);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj1, $obj2], $actualData);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('->merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the list

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $obj4 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([$obj3, $obj4]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$obj1, $obj2, $obj3, $obj4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another ListOfObjects')]
    public function test_merge_can_merge_list_of_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // ListOfObjects and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $obj4 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);
        $other = new ListOfObjects([$obj3, $obj4]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$obj1, $obj2, $obj3, $obj4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeArray()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() appends the given
        // array's contents to the list's data

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new ListOfObjects([$obj1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([$obj2, $obj3]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$obj1, $obj2, $obj3],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() works correctly when
        // the list is initially empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj1, $obj2], $unit->toArray());
    }

    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty array does not
        // alter the list's existing data

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [$obj1, $obj2];
        $unit = new ListOfObjects($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() returns the same list
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([new stdClass()]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() appends the contents
        // of another ListOfObjects into this list

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $unit = new ListOfObjects([$obj1]);
        $other = new ListOfObjects([$obj2, $obj3]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$obj1, $obj2, $obj3],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the list being merged from is not
        // modified by the merge operation

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects([$obj1]);
        $other = new ListOfObjects([$obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$obj2], $other->toArray());
    }

    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty list does not
        // alter the existing data

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [$obj1, $obj2];
        $unit = new ListOfObjects($expectedData);
        $other = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first object')]
    public function test_maybe_first_returns_first_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // object in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj1, $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // list is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeFirst() returns the first object added via add()')]
    public function test_maybe_first_returns_first_object_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // object that was added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects();
        $unit->add($obj1);
        $unit->add($obj2);

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

    #[TestDox('->first() returns the first object')]
    public function test_first_returns_first_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the first object
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj1, $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the list is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfObjects is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last object')]
    public function test_maybe_last_returns_last_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the last
        // object in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj2, $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // list is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeLast() returns the last object added via add()')]
    public function test_maybe_last_returns_last_object_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added object via add()

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects();
        $unit->add($obj1);
        $unit->add($obj2);

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

    #[TestDox('->last() returns the last object')]
    public function test_last_returns_last_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the last object
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj2, $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the list is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfObjects is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new ListOfObjects with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new ListOfObjects
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $expectedData = [$obj1, $obj2];
        $unit = new ListOfObjects($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfObjects::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifying the copied list does not
        // affect the original list's data

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $originalData = [$obj1, $obj2];
        $unit = new ListOfObjects($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->add($obj3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [$obj1, $obj2, $obj3],
            $copy->toArray(),
        );
    }

    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty list returns a
        // new, empty ListOfObjects instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfObjects::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('->empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // list has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty list')]
    public function test_empty_returns_false_for_non_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // list contains data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([new stdClass()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false after an
        // object has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();
        $unit->add(new stdClass());

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

    #[TestDox('->getCollectionTypeAsString() returns "ListOfObjects"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "ListOfObjects" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('ListOfObjects', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    #[TestDox('List with one object: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a list with exactly one
        // object, both first() and last() return that object

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $unit = new ListOfObjects([$obj]);

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

    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() and merge methods can be
        // chained together fluently

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj2 = new stdClass();
        $obj3 = new stdClass();
        $obj4 = new stdClass();
        $unit = new ListOfObjects();
        $other = new ListOfObjects([$obj4]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($obj1)
            ->mergeArray([$obj2, $obj3])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$obj1, $obj2, $obj3, $obj4],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // Object-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('Stored objects preserve their identity')]
    public function test_stored_objects_preserve_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that objects stored in the list retain
        // their identity — the same instance is returned, not a
        // clone

        // ----------------------------------------------------------------
        // setup your test

        $obj1 = new stdClass();
        $obj1->name = 'alpha';
        $obj2 = new stdClass();
        $obj2->name = 'bravo';
        $unit = new ListOfObjects([$obj1, $obj2]);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj1, $retrieved);
        $this->assertSame('alpha', $retrieved->name);
    }

    #[TestDox('Mutating a stored object is reflected when retrieved')]
    public function test_mutating_stored_object_is_reflected(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that because objects are stored by
        // reference, mutations to the original object are visible
        // when the object is retrieved from the list

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();
        $obj->value = 'before';
        $unit = new ListOfObjects([$obj]);

        // ----------------------------------------------------------------
        // perform the change

        $obj->value = 'after';

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('after', $unit->first()->value); // @phpstan-ignore property.notFound
    }

    #[TestDox('Can store objects of different classes')]
    public function test_can_store_objects_of_different_classes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the list can hold objects of
        // different classes in the same collection

        // ----------------------------------------------------------------
        // setup your test

        $stdObj = new stdClass();
        $dateTime = new DateTime();
        $arrayObj = new ArrayObject();

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfObjects([$stdObj, $dateTime, $arrayObj]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertInstanceOf(stdClass::class, $unit->toArray()[0]);
        $this->assertInstanceOf(DateTime::class, $unit->toArray()[1]);
        $this->assertInstanceOf(
            ArrayObject::class,
            $unit->toArray()[2],
        );
    }

    #[TestDox('All stored values are objects')]
    public function test_all_stored_values_are_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that all values retrieved from the
        // list are objects

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfObjects([
            new stdClass(),
            new DateTime(),
            new ArrayObject(),
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
}
