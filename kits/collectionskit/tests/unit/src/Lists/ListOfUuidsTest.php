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
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use StusDevKit\CollectionsKit\Lists\ListOfUuids;

#[TestDox('ListOfUuids')]
class ListOfUuidsTest extends TestCase
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
        // ListOfUuids

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfUuids::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Can instantiate with initial UUIDs')]
    public function test_can_instantiate_with_initial_uuids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new ListOfUuids
        // and seed it with an initial array of UuidInterface objects

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedUuids = [$uuid1, $uuid2];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfUuids($expectedUuids);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($expectedUuids, $unit->toArray());
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

        $uuids = [
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfUuids($uuids);
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

    #[TestDox('add() appends a UUID to the list')]
    public function test_add_appends_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a UUID to the end
        // of the list with a sequential integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();
        $uuid = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() appends multiple UUIDs in order')]
    public function test_add_appends_multiple_uuids_in_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() multiple times
        // appends each UUID in the order they were added

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($uuid1);
        $unit->add($uuid2);
        $unit->add($uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid1, $uuid2, $uuid3], $unit->toArray());
    }

    #[TestDox('add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a UUID after any
        // data that was passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid1, $uuid2, $uuid3], $unit->toArray());
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

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add(Uuid::uuid4());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() calls can be chained
        // together fluently to build up the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($uuid1)
            ->add($uuid2)
            ->add($uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid1, $uuid2, $uuid3], $unit->toArray());
    }

    #[TestDox('add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that UUIDs added via add() always
        // receive sequential integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        // ----------------------------------------------------------------
        // test the results

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    #[TestDox('add() can add the same UUID instance twice')]
    public function test_add_can_add_same_uuid_twice(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() allows the same UUID
        // instance to appear multiple times in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();
        $uuid = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($uuid);
        $unit->add($uuid);
        $unit->add($uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid, $uuid, $uuid], $unit->toArray());
        $this->assertCount(3, $unit);
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

        $unit = new ListOfUuids();

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

        // this test proves that toArray() returns all the UUIDs
        // stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);

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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid1, $uuid2], $actualResult);
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

        // this test proves that count() returns 0 when the list
        // contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();

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
        // of UUIDs stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
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

        // this test proves that the list works with PHP's built-in
        // count() function via the Countable interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

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

        $unit = new ListOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

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

        $unit = new ListOfUuids([Uuid::uuid4()]);

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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);
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

        $unit = new ListOfUuids();
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

        // this test proves that iterating over a ListOfUuids
        // produces sequential integer keys starting from 0

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid1, $uuid2], $actualData);
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $uuid4 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([$uuid3, $uuid4]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3, $uuid4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another ListOfUuids')]
    public function test_merge_can_merge_list_of_uuids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // ListOfUuids and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $uuid4 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);
        $other = new ListOfUuids([$uuid3, $uuid4]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3, $uuid4],
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([$uuid2, $uuid3]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3],
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid1, $uuid2], $unit->toArray());
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);

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

        // this test proves that mergeArray() returns the same list
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([Uuid::uuid4()]);

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
        // of another ListOfUuids into this list

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1]);
        $other = new ListOfUuids([$uuid2, $uuid3]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3],
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1]);
        $other = new ListOfUuids([$uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$uuid2], $other->toArray());
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);
        $other = new ListOfUuids();

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

    #[TestDox('maybeFirst() returns the first UUID')]
    public function test_maybe_first_returns_first_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // UUID in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $actualResult);
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

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first UUID added via add()')]
    public function test_maybe_first_returns_first_uuid_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // UUID that was added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first UUID')]
    public function test_first_returns_first_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the first UUID
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $actualResult);
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

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfUuids is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last UUID')]
    public function test_maybe_last_returns_last_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the last
        // UUID in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
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

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last UUID added via add()')]
    public function test_maybe_last_returns_last_uuid_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added UUID via add()

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last UUID')]
    public function test_last_returns_last_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the last UUID
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
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

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfUuids is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new ListOfUuids with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new ListOfUuids
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfUuids::class, $copy);
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $originalData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->add($uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [$uuid1, $uuid2, $uuid3],
            $copy->toArray(),
        );
    }

    #[TestDox('copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty list returns a
        // new, empty ListOfUuids instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfUuids::class, $copy);
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

        $unit = new ListOfUuids();

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

        $unit = new ListOfUuids([Uuid::uuid4()]);

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

        // this test proves that empty() returns false after a
        // UUID has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();
        $unit->add(Uuid::uuid4());

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

    #[TestDox('getCollectionTypeAsString() returns "ListOfUuids"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "ListOfUuids" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('ListOfUuids', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    #[TestDox('List with one UUID: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a list with exactly one
        // UUID, both first() and last() return that UUID

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid]);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $first);
        $this->assertSame($uuid, $last);
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $uuid4 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $other = new ListOfUuids([$uuid4]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($uuid1)
            ->mergeArray([$uuid2, $uuid3])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3, $uuid4],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // UUID-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('All stored values are UuidInterface instances')]
    public function test_all_stored_values_are_uuid_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that all values retrieved from the
        // list implement UuidInterface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $value) {
            $this->assertInstanceOf(UuidInterface::class, $value);
        }
    }

    #[TestDox('Stored UUIDs preserve their identity')]
    public function test_stored_uuids_preserve_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that UUIDs stored in the list retain
        // their identity — the same instance is returned, not a
        // clone

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $retrieved);
        $this->assertSame(
            (string) $uuid1,
            (string) $retrieved,
        );
    }

    #[TestDox('Each UUID has a unique string representation')]
    public function test_each_uuid_has_unique_string_representation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that distinct UUIDs in the list have
        // distinct string representations

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $strings = [];
        foreach ($unit as $uuid) {
            $strings[] = (string) $uuid;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, array_unique($strings));
    }

    // ================================================================
    //
    // toArrayOfStrings()
    //
    // ----------------------------------------------------------------

    #[TestDox('toArrayOfStrings() returns empty array for empty list')]
    public function test_to_array_of_strings_returns_empty_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() returns an
        // empty array when the list is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);
    }

    #[TestDox('toArrayOfStrings() returns string representations of all UUIDs')]
    public function test_to_array_of_strings_returns_string_representations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() returns the
        // string representation of each UUID in the list

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2, $uuid3]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                (string) $uuid1,
                (string) $uuid2,
                (string) $uuid3,
            ],
            $actualResult,
        );
    }

    #[TestDox('toArrayOfStrings() returns sequential integer keys')]
    public function test_to_array_of_strings_returns_sequential_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() returns a
        // list with sequential integer keys starting from 0

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([0, 1], array_keys($actualResult));
    }

    #[TestDox('toArrayOfStrings() returns valid UUID strings')]
    public function test_to_array_of_strings_returns_valid_uuid_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that every string returned by
        // toArrayOfStrings() is a valid UUID string that can be
        // parsed back into a UUID

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $uuidString) {
            $this->assertIsString($uuidString);
            $this->assertTrue(Uuid::isValid($uuidString));
        }
    }

    #[TestDox('toArrayOfStrings() includes UUIDs added via add()')]
    public function test_to_array_of_strings_includes_added_uuids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() includes UUIDs
        // that were added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            $actualResult,
        );
    }
}
