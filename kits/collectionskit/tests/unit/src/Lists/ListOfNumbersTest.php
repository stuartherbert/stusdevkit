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
use StusDevKit\CollectionsKit\Lists\ListOfNumbers;

#[TestDox('ListOfNumbers')]
class ListOfNumbersTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ListOfNumbers::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(ListOfNumbers::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsList')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(ListOfNumbers::class);
        $parent = $reflection->getParentClass();
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

    #[TestDox('declares no public methods of its own beyond inherited methods')]
    public function test_declares_no_own_public_methods(): void
    {
        $reflection = new \ReflectionClass(ListOfNumbers::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ListOfNumbers::class) {
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

    /**
     * this test proves that we can create a new, empty
     * ListOfNumbers
     */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        // nothing to do
        $unit = new ListOfNumbers();
        $this->assertInstanceOf(ListOfNumbers::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * this test proves that we can create a new ListOfNumbers
     * and seed it with an initial array of integers
     */
    #[TestDox('::__construct() accepts initial integers')]
    public function test_can_instantiate_with_initial_integers(): void
    {
        $expectedNumbers = [1, 2, 3];
        $unit = new ListOfNumbers($expectedNumbers);
        $this->assertCount(3, $unit);
        $this->assertSame($expectedNumbers, $unit->toArray());
    }

    /**
     * this test proves that we can create a new ListOfNumbers
     * and seed it with an initial array of floats
     */
    #[TestDox('::__construct() accepts initial floats')]
    public function test_can_instantiate_with_initial_floats(): void
    {
        $expectedNumbers = [1.1, 2.2, 3.3];
        $unit = new ListOfNumbers($expectedNumbers);
        $this->assertCount(3, $unit);
        $this->assertSame($expectedNumbers, $unit->toArray());
    }

    /**
     * this test proves that we can create a new ListOfNumbers
     * with a mix of integer and float values
     */
    #[TestDox('::__construct() accepts mixed int and float values')]
    public function test_can_instantiate_with_mixed_int_and_float(): void
    {
        $expectedNumbers = [1, 2.5, 3, 4.0];
        $unit = new ListOfNumbers($expectedNumbers);
        $this->assertCount(4, $unit);
        $this->assertSame($expectedNumbers, $unit->toArray());
    }

    /**
     * this test proves that when constructed with a list-style
     * array, the keys remain sequential integers
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $expectedNumbers = [10, 20, 30];
        $unit = new ListOfNumbers($expectedNumbers);
        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that add() appends a number to the end
     * of the list with a sequential integer key
     */
    #[TestDox('->add() appends a number to the list')]
    public function test_add_appends_number(): void
    {
        $unit = new ListOfNumbers();
        $unit->add(42);
        $this->assertSame([42], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that calling add() multiple times
     * appends each number in the order they were added
     */
    #[TestDox('->add() appends multiple numbers in order')]
    public function test_add_appends_multiple_numbers_in_order(): void
    {
        $unit = new ListOfNumbers();
        $unit->add(1);
        $unit->add(2);
        $unit->add(3);
        $this->assertSame([1, 2, 3], $unit->toArray());
    }

    /**
     * this test proves that add() appends a number after any
     * data that was passed into the constructor
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $unit = new ListOfNumbers([10, 20]);
        $unit->add(30);
        $this->assertSame([10, 20, 30], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() returns the same collection
     * instance for fluent method chaining
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new ListOfNumbers();
        $result = $unit->add(42);
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that add() calls can be chained
     * together fluently to build up the list
     */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new ListOfNumbers();
        $unit->add(1)
            ->add(2)
            ->add(3);
        $this->assertSame([1, 2, 3], $unit->toArray());
    }

    /**
     * this test proves that numbers added via add() always
     * receive sequential integer keys
     */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new ListOfNumbers();
        $unit->add(10);
        $unit->add(20);
        $unit->add(30);
        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /**
     * this test proves that add() allows duplicate numbers
     * in the list (unlike a set)
     */
    #[TestDox('->add() can add duplicate numbers')]
    public function test_add_can_add_duplicate_numbers(): void
    {
        $unit = new ListOfNumbers();
        $unit->add(42);
        $unit->add(42);
        $unit->add(42);
        $this->assertSame([42, 42, 42], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() correctly stores zero values
     * (both int and float)
     */
    #[TestDox('->add() can add zero')]
    public function test_add_can_add_zero(): void
    {
        $unit = new ListOfNumbers();
        $unit->add(0);
        $unit->add(0.0);
        $this->assertSame([0, 0.0], $unit->toArray());
        $this->assertCount(2, $unit);
    }

    /**
     * @return array<string, array{0: int|float}>
     */
    public static function provideNumericVariants(): array
    {
        return [
            'positive integer' => [42],
            'negative integer' => [-42],
            'zero integer' => [0],
            'positive float' => [3.14],
            'negative float' => [-3.14],
            'zero float' => [0.0],
            'large integer' => [PHP_INT_MAX],
            'small integer' => [PHP_INT_MIN],
            'large float' => [1.7976931348623e+308],
            'small positive float' => [5e-324],
        ];
    }

    /**
     * this test proves that add() correctly stores numbers
     * of various magnitudes and formats
     */
    #[TestDox('->add() accepts various numeric formats')]
    #[DataProvider('provideNumericVariants')]
    public function test_add_accepts_various_numeric_formats(
        int|float $input,
    ): void {
        $unit = new ListOfNumbers();
        $unit->add($input);
        $this->assertSame([$input], $unit->toArray());
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
        $unit = new ListOfNumbers();
        $actualResult = $unit->toArray();
        $this->assertSame([], $actualResult);
    }

    /**
     * this test proves that toArray() returns all the numbers
     * stored in the list
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = [1, 2.5, 3];
        $unit = new ListOfNumbers($expectedData);
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
        $unit = new ListOfNumbers();
        $unit->add(10);
        $unit->add(20.5);
        $actualResult = $unit->toArray();
        $this->assertSame([10, 20.5], $actualResult);
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
        $unit = new ListOfNumbers();
        $actualResult = $unit->count();
        $this->assertSame(0, $actualResult);
    }

    /**
     * this test proves that count() returns the correct number
     * of numbers stored in the list
     */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new ListOfNumbers([1, 2, 3]);
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
        $unit = new ListOfNumbers([1, 2, 3]);
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
        $unit = new ListOfNumbers();
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

    /**
     * this test proves that getIterator() returns an
     * ArrayIterator instance
     */
    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        $unit = new ListOfNumbers([1, 2]);
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
        $expectedData = [10, 20.5, 30];
        $unit = new ListOfNumbers($expectedData);
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
        $unit = new ListOfNumbers();
        $iterationCount = 0;
        foreach ($unit as $value) {
            $iterationCount++;
        }
        $this->assertSame(0, $iterationCount);
    }

    /**
     * this test proves that iterating over a ListOfNumbers
     * produces sequential integer keys starting from 0
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new ListOfNumbers([10, 20, 30]);
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
        $unit = new ListOfNumbers();
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
     * this test proves that merge() can accept a plain PHP
     * array and merge its contents into the list
     */
    #[TestDox('->merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        $unit = new ListOfNumbers([1, 2]);
        $toMerge = [3, 4];
        $result = $unit->merge($toMerge);
        $this->assertSame([1, 2, 3, 4], $unit->toArray());
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that merge() can accept another
     * ListOfNumbers and merge its contents
     */
    #[TestDox('->merge() can merge another ListOfNumbers')]
    public function test_merge_can_merge_list_of_numbers(): void
    {
        $unit = new ListOfNumbers([1, 2]);
        $other = new ListOfNumbers([3, 4]);
        $result = $unit->merge($other);
        $this->assertSame([1, 2, 3, 4], $unit->toArray());
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
        $unit = new ListOfNumbers([10]);
        $toMerge = [20, 30];
        $result = $unit->mergeArray($toMerge);
        $this->assertSame([10, 20, 30], $unit->toArray());
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that mergeArray() works correctly when
     * the list is initially empty
     */
    #[TestDox('->mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        $unit = new ListOfNumbers();
        $toMerge = [10, 20];
        $unit->mergeArray($toMerge);
        $this->assertSame([10, 20], $unit->toArray());
    }

    /**
     * this test proves that merging an empty array does not
     * alter the list's existing data
     */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = [10, 20];
        $unit = new ListOfNumbers($expectedData);
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
        $unit = new ListOfNumbers([10]);
        $result = $unit->mergeArray([20]);
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that mergeSelf() appends the contents
     * of another ListOfNumbers into this list
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $unit = new ListOfNumbers([10]);
        $other = new ListOfNumbers([20, 30]);
        $result = $unit->mergeSelf($other);
        $this->assertSame([10, 20, 30], $unit->toArray());
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that the list being merged from is not
     * modified by the merge operation
     */
    #[TestDox('->mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new ListOfNumbers([10]);
        $other = new ListOfNumbers([20]);
        $expectedOtherData = [20];
        $unit->mergeSelf($other);
        $this->assertSame($expectedOtherData, $other->toArray());
    }

    /**
     * this test proves that merging an empty list does not
     * alter the existing data
     */
    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = [10, 20];
        $unit = new ListOfNumbers($expectedData);
        $other = new ListOfNumbers();
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
     * number in the list when it is not empty
     */
    #[TestDox('->maybeFirst() returns the first number')]
    public function test_maybe_first_returns_first_number(): void
    {
        $unit = new ListOfNumbers([10, 20, 30]);
        $actualResult = $unit->maybeFirst();
        $this->assertSame(10, $actualResult);
    }

    /**
     * this test proves that maybeFirst() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new ListOfNumbers();
        $actualResult = $unit->maybeFirst();
        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeFirst() returns the first
     * number that was added via the add() method
     */
    #[TestDox('->maybeFirst() returns the first number added via add()')]
    public function test_maybe_first_returns_first_number_added_via_add(): void
    {
        $unit = new ListOfNumbers();
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

    /**
     * this test proves that first() returns the first number
     * in the list when it is not empty
     */
    #[TestDox('->first() returns the first number')]
    public function test_first_returns_first_number(): void
    {
        $unit = new ListOfNumbers([10, 20, 30]);
        $actualResult = $unit->first();
        $this->assertSame(10, $actualResult);
    }

    /**
     * this test proves that first() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new ListOfNumbers();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfNumbers is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeLast() returns the last number
     * in the list when it is not empty
     */
    #[TestDox('->maybeLast() returns the last number')]
    public function test_maybe_last_returns_last_number(): void
    {
        $unit = new ListOfNumbers([10, 20, 30]);
        $actualResult = $unit->maybeLast();
        $this->assertSame(30, $actualResult);
    }

    /**
     * this test proves that maybeLast() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new ListOfNumbers();
        $actualResult = $unit->maybeLast();
        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeLast() returns the most
     * recently added number via add()
     */
    #[TestDox('->maybeLast() returns the last number added via add()')]
    public function test_maybe_last_returns_last_number_added_via_add(): void
    {
        $unit = new ListOfNumbers();
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

    /**
     * this test proves that last() returns the last number in
     * the list when it is not empty
     */
    #[TestDox('->last() returns the last number')]
    public function test_last_returns_last_number(): void
    {
        $unit = new ListOfNumbers([10, 20, 30]);
        $actualResult = $unit->last();
        $this->assertSame(30, $actualResult);
    }

    /**
     * this test proves that last() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new ListOfNumbers();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfNumbers is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that copy() returns a new ListOfNumbers
     * instance containing the same data as the original
     */
    #[TestDox('->copy() returns a new ListOfNumbers with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = [10, 20, 30];
        $unit = new ListOfNumbers($expectedData);
        $copy = $unit->copy();
        $this->assertInstanceOf(ListOfNumbers::class, $copy);
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
        $originalData = [10, 20];
        $unit = new ListOfNumbers($originalData);
        $copy = $unit->copy();
        $copy->add(30);
        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame([10, 20, 30], $copy->toArray());
    }

    /**
     * this test proves that copying an empty list returns a
     * new, empty ListOfNumbers instance
     */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new ListOfNumbers();
        $copy = $unit->copy();
        $this->assertInstanceOf(ListOfNumbers::class, $copy);
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
        $unit = new ListOfNumbers();
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
        $unit = new ListOfNumbers([42]);
        $actualResult = $unit->empty();
        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that empty() returns false after a
     * number has been added via add()
     */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new ListOfNumbers();
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
     * this test proves that getCollectionTypeAsString() returns
     * "ListOfNumbers" (just the class name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns "ListOfNumbers"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new ListOfNumbers();
        $actualResult = $unit->getCollectionTypeAsString();
        $this->assertSame('ListOfNumbers', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that for a list with exactly one number,
     * both first() and last() return that same number
     */
    #[TestDox('List with one number: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new ListOfNumbers([42]);
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

    /**
     * this test proves that add() and merge methods can be
     * chained together fluently
     */
    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        /** @var ListOfNumbers<int> $unit */
        $unit = new ListOfNumbers();
        $other = new ListOfNumbers([40]);
        $unit->add(10)
            ->mergeArray([20, 30])
            ->mergeSelf($other);
        $this->assertSame([10, 20, 30, 40], $unit->toArray());
    }

    // ================================================================
    //
    // Numeric type preservation
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that integer values retain their int
     * type when stored and retrieved from the list
     */
    #[TestDox('Preserves int type for integer values')]
    public function test_preserves_int_type(): void
    {
        $unit = new ListOfNumbers([1, 2, 3]);
        $actualResult = $unit->first();
        $this->assertIsInt($actualResult);
        $this->assertSame(1, $actualResult);
    }

    /**
     * this test proves that float values retain their float
     * type when stored and retrieved from the list
     */
    #[TestDox('Preserves float type for float values')]
    public function test_preserves_float_type(): void
    {
        $unit = new ListOfNumbers([1.5, 2.5, 3.5]);
        $actualResult = $unit->first();
        $this->assertIsFloat($actualResult);
        $this->assertSame(1.5, $actualResult);
    }

    /**
     * this test proves that a list can hold both int and float
     * values simultaneously, preserving each value's type
     */
    #[TestDox('Maintains distinct int and float types in same list')]
    public function test_maintains_distinct_types_in_same_list(): void
    {
        $unit = new ListOfNumbers([1, 2.5, 3, 4.0]);
        $actualResult = $unit->toArray();
        $this->assertIsInt($actualResult[0]);
        $this->assertIsFloat($actualResult[1]);
        $this->assertIsInt($actualResult[2]);
        $this->assertIsFloat($actualResult[3]);
    }

    /**
     * this test proves that negative numbers are stored and
     * retrieved correctly
     */
    #[TestDox('Handles negative numbers correctly')]
    public function test_handles_negative_numbers(): void
    {
        $unit = new ListOfNumbers([-1, -2.5, -3]);
        $actualResult = $unit->toArray();
        $this->assertSame([-1, -2.5, -3], $actualResult);
    }
}
