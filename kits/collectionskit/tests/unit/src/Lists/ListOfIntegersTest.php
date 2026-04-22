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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Lists\ListOfIntegers;
use StusDevKit\CollectionsKit\Lists\ListOfNumbers;

#[TestDox('ListOfIntegers')]
class ListOfIntegersTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ListOfIntegers::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(ListOfIntegers::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends ListOfNumbers')]
    public function test_extends_ListOfNumbers(): void
    {
        $reflection = new \ReflectionClass(ListOfIntegers::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Lists\ListOfNumbers::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------
    //
    // ListOfIntegers is a thin type specialisation: it inherits its
    // entire method shape from ListOfNumbers and declares no
    // additional public methods of its own.
    //
    // ----------------------------------------------------------------

    #[TestDox('declares no public methods of its own beyond inherited methods')]
    public function test_declares_no_own_public_methods(): void
    {
        $reflection = new \ReflectionClass(ListOfIntegers::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ListOfIntegers::class) {
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

    /** we can create a new, empty ListOfIntegers */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $this->assertInstanceOf(ListOfIntegers::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * we can create a new ListOfIntegers and seed it with an initial array of
     * integers
     */
    #[TestDox('::__construct() accepts initial integers')]
    public function test_can_instantiate_with_initial_integers(): void
    {
        $expectedIntegers = [10, 20, 30];

        $unit = new ListOfIntegers($expectedIntegers);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedIntegers, $unit->toArray());
    }

    /**
     * when constructed with a list-style array, the keys remain sequential
     * integers
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $expectedIntegers = [10, 20, 30];

        $unit = new ListOfIntegers($expectedIntegers);
        $actualData = $unit->toArray();

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * add() appends an integer to the end of the list with a sequential
     * integer key
     */
    #[TestDox('->add() appends an integer to the list')]
    public function test_add_appends_integer(): void
    {
        $unit = new ListOfIntegers();

        $unit->add(42);

        $this->assertSame([42], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * calling add() multiple times appends each integer in the order they were
     * added
     */
    #[TestDox('->add() appends multiple integers in order')]
    public function test_add_appends_multiple_integers_in_order(): void
    {
        $unit = new ListOfIntegers();

        $unit->add(1);
        $unit->add(2);
        $unit->add(3);

        $this->assertSame([1, 2, 3], $unit->toArray());
    }

    /**
     * add() appends an integer after any data that was passed into the
     * constructor
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $unit = new ListOfIntegers([10, 20]);

        $unit->add(30);

        $this->assertSame([10, 20, 30], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * add() returns the same collection instance for fluent method chaining
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new ListOfIntegers();

        $result = $unit->add(42);

        $this->assertSame($unit, $result);
    }

    /** add() calls can be chained together fluently to build up the list */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new ListOfIntegers();

        $unit->add(1)
            ->add(2)
            ->add(3);

        $this->assertSame([1, 2, 3], $unit->toArray());
    }

    /** integers added via add() always receive sequential integer keys */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new ListOfIntegers();

        $unit->add(10);
        $unit->add(20);
        $unit->add(30);

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /** add() allows duplicate integer values in the list (unlike a set) */
    #[TestDox('->add() can add duplicate integers')]
    public function test_add_can_add_duplicate_integers(): void
    {
        $unit = new ListOfIntegers();

        $unit->add(42);
        $unit->add(42);
        $unit->add(42);

        $this->assertSame([42, 42, 42], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /** add() correctly stores zero */
    #[TestDox('->add() can add zero')]
    public function test_add_can_add_zero(): void
    {
        $unit = new ListOfIntegers();

        $unit->add(0);

        $this->assertSame([0], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * @return array<string, array{0: int}>
     */
    public static function provideIntegerVariants(): array
    {
        return [
            'positive integer' => [42],
            'negative integer' => [-42],
            'zero' => [0],
            'one' => [1],
            'negative one' => [-1],
            'large positive' => [PHP_INT_MAX],
            'large negative' => [PHP_INT_MIN],
            'power of two' => [1024],
            'hex-friendly value' => [255],
        ];
    }

    /** add() correctly stores integers of various magnitudes */
    #[TestDox('->add() accepts various integer formats')]
    #[DataProvider('provideIntegerVariants')]
    public function test_add_accepts_various_integer_formats(
        int $input,
    ): void {
        $unit = new ListOfIntegers();

        $unit->add($input);

        $this->assertSame([$input], $unit->toArray());
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    /** toArray() returns an empty array when the list contains no data */
    #[TestDox('->toArray() returns empty array for empty list')]
    public function test_to_array_returns_empty_array_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /** toArray() returns all the integers stored in the list */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = [10, 20, 30];
        $unit = new ListOfIntegers($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    /** toArray() includes data that was added using the add() method */
    #[TestDox('->toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        $unit = new ListOfIntegers();
        $unit->add(10);
        $unit->add(20);

        $actualResult = $unit->toArray();

        $this->assertSame([10, 20], $actualResult);
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    /** count() returns 0 when the list contains no data */
    #[TestDox('->count() returns 0 for empty list')]
    public function test_count_returns_zero_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /** count() returns the correct number of integers stored in the list */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    /**
     * the list works with PHP's built-in count() function via the Countable
     * interface
     */
    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    /** count() correctly reflects items added via the add() method */
    #[TestDox('->count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        $unit = new ListOfIntegers();
        $unit->add(10);
        $unit->add(20);

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    /** getIterator() returns an ArrayIterator instance */
    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        $unit = new ListOfIntegers([10, 20]);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    /**
     * the list can be used in a foreach loop via the IteratorAggregate
     * interface
     */
    #[TestDox('List can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $expectedData = [10, 20, 30];
        $unit = new ListOfIntegers($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    /** iterating over an empty list does not execute the loop body */
    #[TestDox('Iterating empty list produces no iterations')]
    public function test_iterating_empty_list_produces_no_iterations(): void
    {
        $unit = new ListOfIntegers();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * iterating over a ListOfIntegers produces sequential integer keys
     * starting from 0
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame([0, 1, 2], $actualKeys);
    }

    /**
     * iterating over a list includes items that were added via the add()
     * method
     */
    #[TestDox('Iteration includes items added via add()')]
    public function test_iteration_includes_items_added_via_add(): void
    {
        $unit = new ListOfIntegers();
        $unit->add(10);
        $unit->add(20);
        $actualData = [];

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        $this->assertSame([10, 20], $actualData);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    /**
     * merge() can accept a plain PHP array and merge its contents into the
     * list
     */
    #[TestDox('->merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        $unit = new ListOfIntegers([1, 2]);
        $toMerge = [3, 4];

        $result = $unit->merge($toMerge);

        $this->assertSame([1, 2, 3, 4], $unit->toArray());
        $this->assertSame($unit, $result);
    }

    /** merge() can accept another ListOfIntegers and merge its contents */
    #[TestDox('->merge() can merge another ListOfIntegers')]
    public function test_merge_can_merge_list_of_integers(): void
    {
        $unit = new ListOfIntegers([1, 2]);
        $other = new ListOfIntegers([3, 4]);

        $result = $unit->merge($other);

        $this->assertSame([1, 2, 3, 4], $unit->toArray());
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeArray()
    //
    // ----------------------------------------------------------------

    /** mergeArray() appends the given array's contents to the list's data */
    #[TestDox('->mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        $unit = new ListOfIntegers([10]);
        $toMerge = [20, 30];

        $result = $unit->mergeArray($toMerge);

        $this->assertSame(
            [10, 20, 30],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /** mergeArray() works correctly when the list is initially empty */
    #[TestDox('->mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        $unit = new ListOfIntegers();
        $toMerge = [10, 20];

        $unit->mergeArray($toMerge);

        $this->assertSame([10, 20], $unit->toArray());
    }

    /** merging an empty array does not alter the list's existing data */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = [10, 20];
        $unit = new ListOfIntegers($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * mergeArray() returns the same list instance for fluent method chaining
     */
    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        $unit = new ListOfIntegers([10]);

        $result = $unit->mergeArray([20]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * mergeSelf() appends the contents of another ListOfIntegers into this
     * list
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $unit = new ListOfIntegers([10]);
        $other = new ListOfIntegers([20, 30]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [10, 20, 30],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /** the list being merged from is not modified by the merge operation */
    #[TestDox('->mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new ListOfIntegers([10]);
        $other = new ListOfIntegers([20]);
        $expectedOtherData = [20];

        $unit->mergeSelf($other);

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    /** merging an empty list does not alter the existing data */
    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = [10, 20];
        $unit = new ListOfIntegers($expectedData);
        $other = new ListOfIntegers();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    /**
     * maybeFirst() returns the first integer in the list when it is not empty
     */
    #[TestDox('->maybeFirst() returns the first integer')]
    public function test_maybe_first_returns_first_integer(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(10, $actualResult);
    }

    /**
     * maybeFirst() returns null when the list is empty, rather than throwing
     * an exception
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * maybeFirst() returns the first integer that was added via the add()
     * method
     */
    #[TestDox('->maybeFirst() returns the first integer added via add()')]
    public function test_maybe_first_returns_first_integer_added_via_add(): void
    {
        $unit = new ListOfIntegers();
        $unit->add(10);
        $unit->add(20);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(10, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    /** first() returns the first integer in the list when it is not empty */
    #[TestDox('->first() returns the first integer')]
    public function test_first_returns_first_integer(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);

        $actualResult = $unit->first();

        $this->assertSame(10, $actualResult);
    }

    /** first() throws a RuntimeException when the list is empty */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfIntegers is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * maybeLast() returns the last integer in the list when it is not empty
     */
    #[TestDox('->maybeLast() returns the last integer')]
    public function test_maybe_last_returns_last_integer(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);

        $actualResult = $unit->maybeLast();

        $this->assertSame(30, $actualResult);
    }

    /**
     * maybeLast() returns null when the list is empty, rather than throwing an
     * exception
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /** maybeLast() returns the most recently added integer via add() */
    #[TestDox('->maybeLast() returns the last integer added via add()')]
    public function test_maybe_last_returns_last_integer_added_via_add(): void
    {
        $unit = new ListOfIntegers();
        $unit->add(10);
        $unit->add(20);

        $actualResult = $unit->maybeLast();

        $this->assertSame(20, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    /** last() returns the last integer in the list when it is not empty */
    #[TestDox('->last() returns the last integer')]
    public function test_last_returns_last_integer(): void
    {
        $unit = new ListOfIntegers([10, 20, 30]);

        $actualResult = $unit->last();

        $this->assertSame(30, $actualResult);
    }

    /** last() throws a RuntimeException when the list is empty */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfIntegers is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * copy() returns a new ListOfIntegers instance containing the same data as
     * the original
     */
    #[TestDox('->copy() returns a new ListOfIntegers with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = [10, 20, 30];
        $unit = new ListOfIntegers($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfIntegers::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    /** modifying the copied list does not affect the original list's data */
    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $originalData = [10, 20];
        $unit = new ListOfIntegers($originalData);

        $copy = $unit->copy();
        $copy->add(30);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [10, 20, 30],
            $copy->toArray(),
        );
    }

    /** copying an empty list returns a new, empty ListOfIntegers instance */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfIntegers::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    /** empty() returns true when the list has no data */
    #[TestDox('->empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        $unit = new ListOfIntegers();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    /** empty() returns false when the list contains data */
    #[TestDox('->empty() returns false for non-empty list')]
    public function test_empty_returns_false_for_non_empty_list(): void
    {
        $unit = new ListOfIntegers([42]);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /** empty() returns false after an integer has been added via add() */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new ListOfIntegers();
        $unit->add(42);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    /**
     * getCollectionTypeAsString() returns "ListOfIntegers" (just the class
     * name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns "ListOfIntegers"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new ListOfIntegers();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('ListOfIntegers', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    /**
     * for a list with exactly one integer, both first() and last() return that
     * same value
     */
    #[TestDox('List with one integer: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new ListOfIntegers([42]);

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

    /** add() and merge methods can be chained together fluently */
    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        $unit = new ListOfIntegers();
        $other = new ListOfIntegers([40]);

        $unit->add(10)
            ->mergeArray([20, 30])
            ->mergeSelf($other);

        $this->assertSame(
            [10, 20, 30, 40],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // Integer-specific behaviour
    //
    // ----------------------------------------------------------------

    /** all values retrieved from the list are int type */
    #[TestDox('All stored values are integers')]
    public function test_all_stored_values_are_integers(): void
    {
        $unit = new ListOfIntegers([1, 0, -1, 100]);

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertIsInt($value);
        }
    }

    /** negative integer values are stored and retrieved correctly */
    #[TestDox('Handles negative integers correctly')]
    public function test_handles_negative_integers(): void
    {
        $unit = new ListOfIntegers([-1, -2, -3]);

        $actualResult = $unit->toArray();

        $this->assertSame([-1, -2, -3], $actualResult);
    }

    /** PHP_INT_MAX and PHP_INT_MIN are stored and retrieved correctly */
    #[TestDox('Handles boundary values correctly')]
    public function test_handles_boundary_values(): void
    {
        $unit = new ListOfIntegers([PHP_INT_MIN, 0, PHP_INT_MAX]);

        $actualResult = $unit->toArray();

        $this->assertSame(
            [PHP_INT_MIN, 0, PHP_INT_MAX],
            $actualResult,
        );
    }
}
