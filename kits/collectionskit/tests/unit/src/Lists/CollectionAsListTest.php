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
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\CollectionsKit\Lists\CollectionAsList;

#[TestDox('CollectionAsList')]
class CollectionAsListTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // CollectionAsList

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsList::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a CollectionAsList
        // and seed it with an initial array of data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsList($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('Constructor preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when constructed with a list-style
        // array, the keys remain sequential integers

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionAsList($expectedData);
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

    #[TestDox('add() appends a value to the list')]
    public function test_add_appends_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a value to the end
        // of the list with a sequential integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() appends multiple values in order')]
    public function test_add_appends_multiple_values_in_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() multiple times appends
        // each value in the order they were added

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo', 'charlie'], $unit->toArray());
    }

    #[TestDox('add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a value after any
        // data that was passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo', 'charlie'], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    #[TestDox('add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() returns the same collection
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add('alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() calls can be chained together
        // fluently to build up the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha')
            ->add('bravo')
            ->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo', 'charlie'], $unit->toArray());
    }

    #[TestDox('add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that values added via add() always
        // receive sequential integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    #[TestDox('add() can add duplicate values')]
    public function test_add_can_add_duplicate_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() allows duplicate values
        // in the list (unlike a set)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');
        $unit->add('alpha');
        $unit->add('alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'alpha', 'alpha'], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    #[TestDox('add() rejects null values')]
    public function test_add_rejects_null_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() throws a
        // NullValueNotAllowed exception when given a null value

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(null);
    }

    #[TestDox('add() can add values of different types')]
    public function test_add_can_add_mixed_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store values of different
        // types in the same list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('a string');
        $unit->add(42);
        $unit->add(3.14);
        $unit->add(true);
        $unit->add(['nested' => 'array']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['a string', 42, 3.14, true, ['nested' => 'array']],
            $unit->toArray(),
        );
        $this->assertCount(5, $unit);
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns empty array for empty list')]
    public function test_to_array_returns_empty_array_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the list contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

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
        // stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionAsList($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() includes data that was
        // added using the add() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo'], $actualResult);
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('count() returns 0 for empty list')]
    public function test_count_returns_zero_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the
        // list contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the correct number
        // of items stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

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

        // this test proves that the list works with PHP's built-in
        // count() function via the Countable interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = count($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() correctly reflects items
        // added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

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

    #[TestDox('getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIterator() returns an
        // ArrayIterator instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo']);

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

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionAsList($expectedData);
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

        // this test proves that iterating over an empty list
        // does not execute the loop body

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
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

        // this test proves that iterating over a list produces
        // sequential integer keys starting from 0

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);
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

        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo'], $actualData);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo']);
        $toMerge = ['charlie', 'delta'];

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another CollectionAsList')]
    public function test_merge_can_merge_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // CollectionAsList and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo']);
        $other = new CollectionAsList(['charlie', 'delta']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeArray()
    //
    // ----------------------------------------------------------------

    #[TestDox('mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() appends the given
        // array's contents to the list's data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha']);
        $toMerge = ['bravo', 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() works correctly when
        // the list is initially empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $toMerge = ['alpha', 'bravo'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    #[TestDox('mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty array does not
        // alter the list's existing data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo'];
        $unit = new CollectionAsList($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() returns the same
        // list instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray(['bravo']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() appends the contents
        // of another CollectionAsList into this list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha']);
        $other = new CollectionAsList(['bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the list being merged from is not
        // modified by the merge operation

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha']);
        $other = new CollectionAsList(['bravo']);
        $expectedOtherData = ['bravo'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    #[TestDox('mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty list does not
        // alter the existing data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo'];
        $unit = new CollectionAsList($expectedData);
        $other = new CollectionAsList();

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

    #[TestDox('maybeFirst() returns the first item')]
    public function test_maybe_first_returns_first_item(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first item
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // list is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first item added via add()')]
    public function test_maybe_first_returns_first_item_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first item
        // that was added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

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

        // this test proves that first() returns the first item in
        // the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the list is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsList is empty');

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

        // this test proves that maybeLast() returns the last item
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // list is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last item added via add()')]
    public function test_maybe_last_returns_last_item_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most recently
        // added item via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

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

        // this test proves that last() returns the last item in
        // the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the list is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsList is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new CollectionAsList with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new CollectionAsList
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionAsList($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsList::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifying the copied list does not
        // affect the original list's data

        // ----------------------------------------------------------------
        // setup your test

        $originalData = ['alpha', 'bravo'];
        $unit = new CollectionAsList($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $copy->toArray(),
        );
    }

    #[TestDox('copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty list returns a new,
        // empty CollectionAsList instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsList::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // list has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('empty() returns false for non-empty list')]
    public function test_empty_returns_false_for_non_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // list contains data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false after an item
        // has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $unit->add('alpha');

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
        // "CollectionAsList" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionAsList', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    #[TestDox('List with one item: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a list with exactly one item,
        // both first() and last() return that same item

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList(['only-item']);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('only-item', $first);
        $this->assertSame('only-item', $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() and merge methods can be
        // chained together fluently

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionAsList();
        $other = new CollectionAsList(['delta']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha')
            ->mergeArray(['bravo', 'charlie'])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
    }
}
