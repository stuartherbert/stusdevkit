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
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox('CollectionOfAnything')]
class CollectionOfAnythingTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty collection')]
    public function test_can_instantiate_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // CollectionOfAnything

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionOfAnything::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a CollectionOfAnything
        // and seed it with an initial array of data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('Can instantiate with associative array')]
    public function test_can_instantiate_with_associative_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a CollectionOfAnything
        // with string keys (associative array)

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns empty array for empty collection')]
    public function test_to_array_returns_empty_array_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the collection contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

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
        // stored in the collection

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

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('count() returns 0 for empty collection')]
    public function test_count_returns_zero_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the
        // collection contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('count() returns number of items in collection')]
    public function test_count_returns_number_of_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the correct number
        // of items stored in the collection

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

    #[TestDox('count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the collection works with PHP's
        // built-in count() function via the Countable interface

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

        $unit = new CollectionOfAnything(['alpha', 'bravo']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Collection can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the collection can be used in a
        // foreach loop via the IteratorAggregate interface

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionOfAnything($expectedData);
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

    #[TestDox('Iterating empty collection produces no iterations')]
    public function test_iterating_empty_collection_produces_no_iterations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over an empty collection
        // does not execute the loop body

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();
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

        // this test proves that iterating preserves the associative
        // keys of the underlying data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];
        $unit = new CollectionOfAnything($expectedData);
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

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('merge() can merge an array into the collection')]
    public function test_merge_can_merge_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the collection

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo']);
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

    #[TestDox('merge() can merge another collection')]
    public function test_merge_can_merge_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // CollectionOfAnything and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo']);
        $other = new CollectionOfAnything(['charlie', 'delta']);

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

    #[TestDox('mergeArray() adds array items to the collection')]
    public function test_merge_array_adds_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() appends the given
        // array's contents to the collection's data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);
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

    #[TestDox('mergeArray() into empty collection sets the data')]
    public function test_merge_array_into_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() works correctly when
        // the collection is initially empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();
        $toMerge = ['alpha', 'bravo'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray($toMerge);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    #[TestDox('mergeArray() with empty array leaves collection unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty array does not
        // alter the collection's existing data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo'];
        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('mergeArray() with associative keys overwrites matching keys')]
    public function test_merge_array_overwrites_matching_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when merging associative arrays,
        // matching string keys are overwritten by the merged data
        // (standard PHP spread operator behavior)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything([
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

        // this test proves that mergeArray() returns the same
        // collection instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);

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

    #[TestDox('mergeSelf() merges another collection into this one')]
    public function test_merge_self_merges_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() appends the contents
        // of another CollectionOfAnything into this collection

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);
        $other = new CollectionOfAnything(['bravo', 'charlie']);

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

    #[TestDox('mergeSelf() does not modify the source collection')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the collection being merged from
        // is not modified by the merge operation

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);
        $other = new CollectionOfAnything(['bravo']);
        $expectedOtherData = ['bravo'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    #[TestDox('mergeSelf() with empty source leaves collection unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty collection does
        // not alter the existing data

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo'];
        $unit = new CollectionOfAnything($expectedData);
        $other = new CollectionOfAnything();

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
        // in the collection when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('maybeFirst() returns null for empty collection')]
    public function test_maybe_first_returns_null_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // collection is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns first item from associative array')]
    public function test_maybe_first_returns_first_from_associative(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() correctly returns the
        // value associated with the first key in an associative
        // collection

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything([
            'x' => 'alpha',
            'y' => 'bravo',
        ]);

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
        // the collection when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('first() throws RuntimeException for empty collection')]
    public function test_first_throws_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the collection is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionOfAnything is empty');

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
        // in the collection when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('maybeLast() returns null for empty collection')]
    public function test_maybe_last_returns_null_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // collection is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns last item from associative array')]
    public function test_maybe_last_returns_last_from_associative(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() correctly returns the
        // value associated with the last key in an associative
        // collection

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything([
            'x' => 'alpha',
            'y' => 'bravo',
        ]);

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
        // the collection when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha', 'bravo', 'charlie']);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('last() throws RuntimeException for empty collection')]
    public function test_last_throws_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the collection is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionOfAnything is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new instance with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new collection
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionOfAnything::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifying the copied collection
        // does not affect the original collection's data

        // ----------------------------------------------------------------
        // setup your test

        $originalData = ['alpha', 'bravo'];
        $unit = new CollectionOfAnything($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->mergeArray(['charlie']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $copy->toArray(),
        );
    }

    #[TestDox('copy() of empty collection returns empty collection')]
    public function test_copy_of_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty collection
        // returns a new, empty collection instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('empty() returns true for empty collection')]
    public function test_empty_returns_true_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // collection has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('empty() returns false for non-empty collection')]
    public function test_empty_returns_false_for_non_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // collection contains data

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
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('getCollectionTypeAsString() returns the class basename')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // just the class name without the namespace prefix

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionOfAnything', $actualResult);
    }

    // ================================================================
    //
    // Single-item collections
    //
    // ----------------------------------------------------------------

    #[TestDox('Collection with one item: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a collection with exactly one
        // item, both first() and last() return that same item

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['only-item']);

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
    // Mixed type data
    //
    // ----------------------------------------------------------------

    #[TestDox('Collection can hold mixed types')]
    public function test_can_hold_mixed_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CollectionOfAnything can store
        // values of different types in the same collection

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'a string',
            42,
            3.14,
            true,
            ['nested' => 'array'],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new CollectionOfAnything($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
        $this->assertCount(5, $unit);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('Merge methods support fluent chaining')]
    public function test_merge_methods_support_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge methods return the collection
        // instance, enabling fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);
        $other = new CollectionOfAnything(['delta']);

        // ----------------------------------------------------------------
        // perform the change

        $unit
            ->mergeArray(['bravo', 'charlie'])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // Null value rejection
    //
    // ----------------------------------------------------------------

    #[TestDox('Constructor rejects array containing null')]
    public function test_constructor_rejects_null_in_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the constructor throws a
        // NullValueNotAllowed exception when the initial data
        // array contains a null value

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        new CollectionOfAnything(['alpha', null, 'bravo']);
    }

    #[TestDox('mergeArray() rejects array containing null')]
    public function test_merge_array_rejects_null_in_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() throws a
        // NullValueNotAllowed exception when the input array
        // contains a null value, and does not modify the
        // existing collection

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CollectionOfAnything(['alpha']);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(NullValueNotAllowedException::class);

        $unit->mergeArray(['bravo', null]);
    }
}
