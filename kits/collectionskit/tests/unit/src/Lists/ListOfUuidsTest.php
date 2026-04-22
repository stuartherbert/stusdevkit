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
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // pinning the namespace catches accidental moves that would
        // silently break every `use` statement in the wider codebase

        $reflection = new \ReflectionClass(ListOfUuids::class);

        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ListOfUuids must remain a class (not an interface or trait)
        // so callers can instantiate it with `new`

        $reflection = new \ReflectionClass(ListOfUuids::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsList')]
    public function test_extends_CollectionAsList(): void
    {
        // ListOfUuids builds on the shared list behaviour of
        // CollectionAsList — breaking this breaks every caller that
        // type-hints against the parent

        $reflection = new \ReflectionClass(ListOfUuids::class);
        $parent = $reflection->getParentClass();

        // correctness! getParentClass() returns false when no parent
        // exists — fail loudly rather than silently skip the assertion
        $this->assertNotFalse($parent);

        $this->assertSame(
            \StusDevKit\CollectionsKit\Lists\CollectionAsList::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------
    //
    // ListOfUuids is a type specialisation of CollectionAsList<UuidInterface>
    // that adds a single extractor: toArrayOfStrings(). Shape of the
    // inherited surface is pinned on the parent class; this section
    // pins the additional public methods declared by ListOfUuids itself.
    //
    // ----------------------------------------------------------------

    #[TestDox('declares toArrayOfStrings() as its only own public method')]
    public function test_declares_only_own_public_methods(): void
    {
        // ListOfUuids adds toArrayOfStrings() on top of the parent's
        // public API. Pinning this set here catches accidental new
        // methods on the class as well as accidental removal of
        // toArrayOfStrings().

        $reflection = new \ReflectionClass(ListOfUuids::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ListOfUuids::class) {
                $ownMethods[] = $m->getName();
            }
        }
        \sort($ownMethods);

        $this->assertSame(
            ['toArrayOfStrings'],
            $ownMethods,
        );
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that we can create a new, empty
     * ListOfUuids
     */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        // nothing to do

        $unit = new ListOfUuids();

        $this->assertInstanceOf(ListOfUuids::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * this test proves that we can create a new ListOfUuids
     * and seed it with an initial array of UuidInterface objects
     */
    #[TestDox('::__construct() accepts initial UUIDs')]
    public function test_can_instantiate_with_initial_uuids(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedUuids = [$uuid1, $uuid2];

        $unit = new ListOfUuids($expectedUuids);

        $this->assertCount(2, $unit);
        $this->assertSame($expectedUuids, $unit->toArray());
    }

    /**
     * this test proves that when constructed with a list-style
     * array, the keys remain sequential integers
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $uuids = [
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ];

        $unit = new ListOfUuids($uuids);
        $actualData = $unit->toArray();

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that add() appends a UUID to the end
     * of the list with a sequential integer key
     */
    #[TestDox('->add() appends a UUID to the list')]
    public function test_add_appends_uuid(): void
    {
        $unit = new ListOfUuids();
        $uuid = Uuid::uuid4();

        $unit->add($uuid);

        $this->assertSame([$uuid], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that calling add() multiple times
     * appends each UUID in the order they were added
     */
    #[TestDox('->add() appends multiple UUIDs in order')]
    public function test_add_appends_multiple_uuids_in_order(): void
    {
        $unit = new ListOfUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();

        $unit->add($uuid1);
        $unit->add($uuid2);
        $unit->add($uuid3);

        $this->assertSame([$uuid1, $uuid2, $uuid3], $unit->toArray());
    }

    /**
     * this test proves that add() appends a UUID after any
     * data that was passed into the constructor
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $unit->add($uuid3);

        $this->assertSame([$uuid1, $uuid2, $uuid3], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() returns the same collection
     * instance for fluent method chaining
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new ListOfUuids();

        $result = $unit->add(Uuid::uuid4());

        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that add() calls can be chained
     * together fluently to build up the list
     */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new ListOfUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();

        $unit->add($uuid1)
            ->add($uuid2)
            ->add($uuid3);

        $this->assertSame([$uuid1, $uuid2, $uuid3], $unit->toArray());
    }

    /**
     * this test proves that UUIDs added via add() always
     * receive sequential integer keys
     */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new ListOfUuids();

        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /**
     * this test proves that add() allows the same UUID
     * instance to appear multiple times in the list
     */
    #[TestDox('->add() can add the same UUID instance twice')]
    public function test_add_can_add_same_uuid_twice(): void
    {
        $unit = new ListOfUuids();
        $uuid = Uuid::uuid4();

        $unit->add($uuid);
        $unit->add($uuid);
        $unit->add($uuid);

        $this->assertSame([$uuid, $uuid, $uuid], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that toArray() returns an empty array
     * when the list contains no data
     */
    #[TestDox('->toArray() returns empty array for empty list')]
    public function test_to_array_returns_empty_array_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /**
     * this test proves that toArray() returns all the UUIDs
     * stored in the list
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    /**
     * this test proves that toArray() includes data that was
     * added using the add() method
     */
    #[TestDox('->toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->toArray();

        $this->assertSame([$uuid1, $uuid2], $actualResult);
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that count() returns 0 when the list
     * contains no data
     */
    #[TestDox('->count() returns 0 for empty list')]
    public function test_count_returns_zero_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /**
     * this test proves that count() returns the correct number
     * of UUIDs stored in the list
     */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    /**
     * this test proves that the list works with PHP's built-in
     * count() function via the Countable interface
     */
    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    /**
     * this test proves that count() correctly reflects items
     * added via the add() method
     */
    #[TestDox('->count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        $unit = new ListOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that getIterator() returns an
     * ArrayIterator instance
     */
    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        $unit = new ListOfUuids([Uuid::uuid4()]);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    /**
     * this test proves that the list can be used in a foreach
     * loop via the IteratorAggregate interface
     */
    #[TestDox('List can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * this test proves that iterating over an empty list does
     * not execute the loop body
     */
    #[TestDox('Iterating empty list produces no iterations')]
    public function test_iterating_empty_list_produces_no_iterations(): void
    {
        $unit = new ListOfUuids();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * this test proves that iterating over a ListOfUuids
     * produces sequential integer keys starting from 0
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame([0, 1, 2], $actualKeys);
    }

    /**
     * this test proves that iterating over a list includes
     * items that were added via the add() method
     */
    #[TestDox('Iteration includes items added via add()')]
    public function test_iteration_includes_items_added_via_add(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $actualData = [];

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        $this->assertSame([$uuid1, $uuid2], $actualData);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that merge() can accept a plain PHP
     * array and merge its contents into the list
     */
    #[TestDox('->merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $uuid4 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $result = $unit->merge([$uuid3, $uuid4]);

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3, $uuid4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that merge() can accept another
     * ListOfUuids and merge its contents
     */
    #[TestDox('->merge() can merge another ListOfUuids')]
    public function test_merge_can_merge_list_of_uuids(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $uuid4 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);
        $other = new ListOfUuids([$uuid3, $uuid4]);

        $result = $unit->merge($other);

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

    /**
     * this test proves that mergeArray() appends the given
     * array's contents to the list's data
     */
    #[TestDox('->mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1]);

        $result = $unit->mergeArray([$uuid2, $uuid3]);

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that mergeArray() works correctly when
     * the list is initially empty
     */
    #[TestDox('->mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();

        $unit->mergeArray([$uuid1, $uuid2]);

        $this->assertSame([$uuid1, $uuid2], $unit->toArray());
    }

    /**
     * this test proves that merging an empty array does not
     * alter the list's existing data
     */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * this test proves that mergeArray() returns the same list
     * instance for fluent method chaining
     */
    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        $unit = new ListOfUuids([Uuid::uuid4()]);

        $result = $unit->mergeArray([Uuid::uuid4()]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that mergeSelf() appends the contents
     * of another ListOfUuids into this list
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1]);
        $other = new ListOfUuids([$uuid2, $uuid3]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [$uuid1, $uuid2, $uuid3],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that the list being merged from is not
     * modified by the merge operation
     */
    #[TestDox('->mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1]);
        $other = new ListOfUuids([$uuid2]);

        $unit->mergeSelf($other);

        $this->assertSame([$uuid2], $other->toArray());
    }

    /**
     * this test proves that merging an empty list does not
     * alter the existing data
     */
    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);
        $other = new ListOfUuids();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeFirst() returns the first
     * UUID in the list when it is not empty
     */
    #[TestDox('->maybeFirst() returns the first UUID')]
    public function test_maybe_first_returns_first_uuid(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame($uuid1, $actualResult);
    }

    /**
     * this test proves that maybeFirst() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeFirst() returns the first
     * UUID that was added via the add() method
     */
    #[TestDox('->maybeFirst() returns the first UUID added via add()')]
    public function test_maybe_first_returns_first_uuid_added_via_add(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->maybeFirst();

        $this->assertSame($uuid1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that first() returns the first UUID
     * in the list when it is not empty
     */
    #[TestDox('->first() returns the first UUID')]
    public function test_first_returns_first_uuid(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $actualResult = $unit->first();

        $this->assertSame($uuid1, $actualResult);
    }

    /**
     * this test proves that first() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfUuids is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeLast() returns the last
     * UUID in the list when it is not empty
     */
    #[TestDox('->maybeLast() returns the last UUID')]
    public function test_maybe_last_returns_last_uuid(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $actualResult = $unit->maybeLast();

        $this->assertSame($uuid2, $actualResult);
    }

    /**
     * this test proves that maybeLast() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeLast() returns the most
     * recently added UUID via add()
     */
    #[TestDox('->maybeLast() returns the last UUID added via add()')]
    public function test_maybe_last_returns_last_uuid_added_via_add(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->maybeLast();

        $this->assertSame($uuid2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that last() returns the last UUID
     * in the list when it is not empty
     */
    #[TestDox('->last() returns the last UUID')]
    public function test_last_returns_last_uuid(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $actualResult = $unit->last();

        $this->assertSame($uuid2, $actualResult);
    }

    /**
     * this test proves that last() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfUuids is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that copy() returns a new ListOfUuids
     * instance containing the same data as the original
     */
    #[TestDox('->copy() returns a new ListOfUuids with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfUuids::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    /**
     * this test proves that modifying the copied list does not
     * affect the original list's data
     */
    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $originalData = [$uuid1, $uuid2];
        $unit = new ListOfUuids($originalData);

        $copy = $unit->copy();
        $copy->add($uuid3);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [$uuid1, $uuid2, $uuid3],
            $copy->toArray(),
        );
    }

    /**
     * this test proves that copying an empty list returns a
     * new, empty ListOfUuids instance
     */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new ListOfUuids();

        $copy = $unit->copy();

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

    /**
     * this test proves that empty() returns true when the
     * list has no data
     */
    #[TestDox('->empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that empty() returns false when the
     * list contains data
     */
    #[TestDox('->empty() returns false for non-empty list')]
    public function test_empty_returns_false_for_non_empty_list(): void
    {
        $unit = new ListOfUuids([Uuid::uuid4()]);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that empty() returns false after a
     * UUID has been added via add()
     */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new ListOfUuids();
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that getCollectionTypeAsString() returns
     * "ListOfUuids" (just the class name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns "ListOfUuids"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('ListOfUuids', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that for a list with exactly one
     * UUID, both first() and last() return that UUID
     */
    #[TestDox('List with one UUID: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $uuid = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid]);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame($uuid, $first);
        $this->assertSame($uuid, $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that add() and merge methods can be
     * chained together fluently
     */
    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $uuid4 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $other = new ListOfUuids([$uuid4]);

        $unit->add($uuid1)
            ->mergeArray([$uuid2, $uuid3])
            ->mergeSelf($other);

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

    /**
     * this test proves that all values retrieved from the
     * list implement UuidInterface
     */
    #[TestDox('All stored values are UuidInterface instances')]
    public function test_all_stored_values_are_uuid_interface(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertInstanceOf(UuidInterface::class, $value);
        }
    }

    /**
     * this test proves that UUIDs stored in the list retain
     * their identity — the same instance is returned, not a
     * clone
     */
    #[TestDox('Stored UUIDs preserve their identity')]
    public function test_stored_uuids_preserve_identity(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2]);

        $retrieved = $unit->first();

        $this->assertSame($uuid1, $retrieved);
        $this->assertSame(
            (string) $uuid1,
            (string) $retrieved,
        );
    }

    /**
     * this test proves that distinct UUIDs in the list have
     * distinct string representations
     */
    #[TestDox('Each UUID has a unique string representation')]
    public function test_each_uuid_has_unique_string_representation(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        $strings = [];
        foreach ($unit as $uuid) {
            $strings[] = (string) $uuid;
        }

        $this->assertCount(3, array_unique($strings));
    }

    // ================================================================
    //
    // toArrayOfStrings()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that toArrayOfStrings() returns an
     * empty array when the list is empty
     */
    #[TestDox('->toArrayOfStrings() returns empty array for empty list')]
    public function test_to_array_of_strings_returns_empty_for_empty_list(): void
    {
        $unit = new ListOfUuids();

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame([], $actualResult);
    }

    /**
     * this test proves that toArrayOfStrings() returns the
     * string representation of each UUID in the list
     */
    #[TestDox('->toArrayOfStrings() returns string representations of all UUIDs')]
    public function test_to_array_of_strings_returns_string_representations(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new ListOfUuids([$uuid1, $uuid2, $uuid3]);

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame(
            [
                (string) $uuid1,
                (string) $uuid2,
                (string) $uuid3,
            ],
            $actualResult,
        );
    }

    /**
     * this test proves that toArrayOfStrings() returns a
     * list with sequential integer keys starting from 0
     */
    #[TestDox('->toArrayOfStrings() returns sequential integer keys')]
    public function test_to_array_of_strings_returns_sequential_keys(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame([0, 1], array_keys($actualResult));
    }

    /**
     * this test proves that every string returned by
     * toArrayOfStrings() is a valid UUID string that can be
     * parsed back into a UUID
     */
    #[TestDox('->toArrayOfStrings() returns valid UUID strings')]
    public function test_to_array_of_strings_returns_valid_uuid_strings(): void
    {
        $unit = new ListOfUuids([
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
        ]);

        $actualResult = $unit->toArrayOfStrings();

        foreach ($actualResult as $uuidString) {
            $this->assertIsString($uuidString);
            $this->assertTrue(Uuid::isValid($uuidString));
        }
    }

    /**
     * this test proves that toArrayOfStrings() includes UUIDs
     * that were added via the add() method
     */
    #[TestDox('->toArrayOfStrings() includes UUIDs added via add()')]
    public function test_to_array_of_strings_includes_added_uuids(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new ListOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            $actualResult,
        );
    }
}
