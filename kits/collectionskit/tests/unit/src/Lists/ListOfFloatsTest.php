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
use StusDevKit\CollectionsKit\Lists\ListOfFloats;

#[TestDox('ListOfFloats')]
class ListOfFloatsTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ListOfFloats::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(ListOfFloats::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends ListOfNumbers')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(ListOfFloats::class);
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

    #[TestDox('declares no public methods of its own beyond inherited methods')]
    public function test_declares_no_own_public_methods(): void
    {
        $reflection = new \ReflectionClass(ListOfFloats::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ListOfFloats::class) {
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
     * ListOfFloats
     */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        $unit = new ListOfFloats();

        $this->assertInstanceOf(ListOfFloats::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * this test proves that we can create a new ListOfFloats
     * and seed it with an initial array of floats
     */
    #[TestDox('::__construct() accepts initial floats')]
    public function test_can_instantiate_with_initial_floats(): void
    {
        $expectedFloats = [1.1, 2.2, 3.3];

        $unit = new ListOfFloats($expectedFloats);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedFloats, $unit->toArray());
    }

    /**
     * this test proves that when constructed with a list-style
     * array, the keys remain sequential integers
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $expectedFloats = [1.1, 2.2, 3.3];

        $unit = new ListOfFloats($expectedFloats);
        $actualData = $unit->toArray();

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that add() appends a float to the end
     * of the list with a sequential integer key
     */
    #[TestDox('->add() appends a float to the list')]
    public function test_add_appends_float(): void
    {
        $unit = new ListOfFloats();

        $unit->add(3.14);

        $this->assertSame([3.14], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that calling add() multiple times
     * appends each float in the order they were added
     */
    #[TestDox('->add() appends multiple floats in order')]
    public function test_add_appends_multiple_floats_in_order(): void
    {
        $unit = new ListOfFloats();

        $unit->add(1.1);
        $unit->add(2.2);
        $unit->add(3.3);

        $this->assertSame([1.1, 2.2, 3.3], $unit->toArray());
    }

    /**
     * this test proves that add() appends a float after any
     * data that was passed into the constructor
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $unit = new ListOfFloats([1.1, 2.2]);

        $unit->add(3.3);

        $this->assertSame([1.1, 2.2, 3.3], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() returns the same collection
     * instance for fluent method chaining
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new ListOfFloats();

        $result = $unit->add(3.14);

        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that add() calls can be chained
     * together fluently to build up the list
     */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new ListOfFloats();

        $unit->add(1.1)
            ->add(2.2)
            ->add(3.3);

        $this->assertSame([1.1, 2.2, 3.3], $unit->toArray());
    }

    /**
     * this test proves that floats added via add() always
     * receive sequential integer keys
     */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new ListOfFloats();

        $unit->add(1.1);
        $unit->add(2.2);
        $unit->add(3.3);

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /**
     * this test proves that add() allows duplicate float values
     * in the list (unlike a set)
     */
    #[TestDox('->add() can add duplicate floats')]
    public function test_add_can_add_duplicate_floats(): void
    {
        $unit = new ListOfFloats();

        $unit->add(3.14);
        $unit->add(3.14);
        $unit->add(3.14);

        $this->assertSame([3.14, 3.14, 3.14], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() correctly stores zero
     */
    #[TestDox('->add() can add zero')]
    public function test_add_can_add_zero(): void
    {
        $unit = new ListOfFloats();

        $unit->add(0.0);

        $this->assertSame([0.0], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * @return array<string, array{0: float}>
     */
    public static function provideFloatVariants(): array
    {
        return [
            'positive float' => [3.14],
            'negative float' => [-3.14],
            'zero' => [0.0],
            'very small positive' => [5e-324],
            'very large positive' => [1.7976931348623e+308],
            'very small negative' => [-5e-324],
            'very large negative' => [-1.7976931348623e+308],
            'one third' => [1 / 3],
            'pi approximation' => [M_PI],
            'euler number' => [M_E],
        ];
    }

    /**
     * this test proves that add() correctly stores floats of
     * various magnitudes and special values
     */
    #[TestDox('->add() accepts various float formats')]
    #[DataProvider('provideFloatVariants')]
    public function test_add_accepts_various_float_formats(
        float $input,
    ): void {
        $unit = new ListOfFloats();

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
        $unit = new ListOfFloats();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /**
     * this test proves that toArray() returns all the floats
     * stored in the list
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = [1.1, 2.2, 3.3];
        $unit = new ListOfFloats($expectedData);

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
        $unit = new ListOfFloats();
        $unit->add(1.5);
        $unit->add(2.5);

        $actualResult = $unit->toArray();

        $this->assertSame([1.5, 2.5], $actualResult);
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
        $unit = new ListOfFloats();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /**
     * this test proves that count() returns the correct number
     * of floats stored in the list
     */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);

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
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);

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
        $unit = new ListOfFloats();
        $unit->add(1.1);
        $unit->add(2.2);

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
        $unit = new ListOfFloats([1.1, 2.2]);

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
        $expectedData = [1.1, 2.2, 3.3];
        $unit = new ListOfFloats($expectedData);
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
        $unit = new ListOfFloats();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * this test proves that iterating over a ListOfFloats
     * produces sequential integer keys starting from 0
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);
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
        $unit = new ListOfFloats();
        $unit->add(1.1);
        $unit->add(2.2);
        $actualData = [];

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        $this->assertSame([1.1, 2.2], $actualData);
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
        $unit = new ListOfFloats([1.1, 2.2]);
        $toMerge = [3.3, 4.4];

        $result = $unit->merge($toMerge);

        $this->assertSame(
            [1.1, 2.2, 3.3, 4.4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that merge() can accept another
     * ListOfFloats and merge its contents
     */
    #[TestDox('->merge() can merge another ListOfFloats')]
    public function test_merge_can_merge_list_of_floats(): void
    {
        $unit = new ListOfFloats([1.1, 2.2]);
        $other = new ListOfFloats([3.3, 4.4]);

        $result = $unit->merge($other);

        $this->assertSame(
            [1.1, 2.2, 3.3, 4.4],
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
        $unit = new ListOfFloats([1.1]);
        $toMerge = [2.2, 3.3];

        $result = $unit->mergeArray($toMerge);

        $this->assertSame(
            [1.1, 2.2, 3.3],
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
        $unit = new ListOfFloats();
        $toMerge = [1.1, 2.2];

        $unit->mergeArray($toMerge);

        $this->assertSame([1.1, 2.2], $unit->toArray());
    }

    /**
     * this test proves that merging an empty array does not
     * alter the list's existing data
     */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = [1.1, 2.2];
        $unit = new ListOfFloats($expectedData);

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
        $unit = new ListOfFloats([1.1]);

        $result = $unit->mergeArray([2.2]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that mergeSelf() appends the contents
     * of another ListOfFloats into this list
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $unit = new ListOfFloats([1.1]);
        $other = new ListOfFloats([2.2, 3.3]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [1.1, 2.2, 3.3],
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
        $unit = new ListOfFloats([1.1]);
        $other = new ListOfFloats([2.2]);
        $expectedOtherData = [2.2];

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
        $expectedData = [1.1, 2.2];
        $unit = new ListOfFloats($expectedData);
        $other = new ListOfFloats();

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
     * float in the list when it is not empty
     */
    #[TestDox('->maybeFirst() returns the first float')]
    public function test_maybe_first_returns_first_float(): void
    {
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(1.1, $actualResult);
    }

    /**
     * this test proves that maybeFirst() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new ListOfFloats();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeFirst() returns the first
     * float that was added via the add() method
     */
    #[TestDox('->maybeFirst() returns the first float added via add()')]
    public function test_maybe_first_returns_first_float_added_via_add(): void
    {
        $unit = new ListOfFloats();
        $unit->add(1.1);
        $unit->add(2.2);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(1.1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that first() returns the first float
     * in the list when it is not empty
     */
    #[TestDox('->first() returns the first float')]
    public function test_first_returns_first_float(): void
    {
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);

        $actualResult = $unit->first();

        $this->assertSame(1.1, $actualResult);
    }

    /**
     * this test proves that first() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new ListOfFloats();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfFloats is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeLast() returns the last float
     * in the list when it is not empty
     */
    #[TestDox('->maybeLast() returns the last float')]
    public function test_maybe_last_returns_last_float(): void
    {
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);

        $actualResult = $unit->maybeLast();

        $this->assertSame(3.3, $actualResult);
    }

    /**
     * this test proves that maybeLast() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new ListOfFloats();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeLast() returns the most
     * recently added float via add()
     */
    #[TestDox('->maybeLast() returns the last float added via add()')]
    public function test_maybe_last_returns_last_float_added_via_add(): void
    {
        $unit = new ListOfFloats();
        $unit->add(1.1);
        $unit->add(2.2);

        $actualResult = $unit->maybeLast();

        $this->assertSame(2.2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that last() returns the last float in
     * the list when it is not empty
     */
    #[TestDox('->last() returns the last float')]
    public function test_last_returns_last_float(): void
    {
        $unit = new ListOfFloats([1.1, 2.2, 3.3]);

        $actualResult = $unit->last();

        $this->assertSame(3.3, $actualResult);
    }

    /**
     * this test proves that last() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new ListOfFloats();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfFloats is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that copy() returns a new ListOfFloats
     * instance containing the same data as the original
     */
    #[TestDox('->copy() returns a new ListOfFloats with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = [1.1, 2.2, 3.3];
        $unit = new ListOfFloats($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfFloats::class, $copy);
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
        $originalData = [1.1, 2.2];
        $unit = new ListOfFloats($originalData);

        $copy = $unit->copy();
        $copy->add(3.3);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [1.1, 2.2, 3.3],
            $copy->toArray(),
        );
    }

    /**
     * this test proves that copying an empty list returns a
     * new, empty ListOfFloats instance
     */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new ListOfFloats();

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfFloats::class, $copy);
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
        $unit = new ListOfFloats();

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
        $unit = new ListOfFloats([3.14]);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that empty() returns false after a
     * float has been added via add()
     */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new ListOfFloats();
        $unit->add(3.14);

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
     * "ListOfFloats" (just the class name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns "ListOfFloats"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new ListOfFloats();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('ListOfFloats', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that for a list with exactly one float,
     * both first() and last() return that same float
     */
    #[TestDox('List with one float: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new ListOfFloats([3.14]);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame(3.14, $first);
        $this->assertSame(3.14, $last);
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
        $unit = new ListOfFloats();
        $other = new ListOfFloats([4.4]);

        $unit->add(1.1)
            ->mergeArray([2.2, 3.3])
            ->mergeSelf($other);

        $this->assertSame(
            [1.1, 2.2, 3.3, 4.4],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // Float-specific behaviour
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that all values retrieved from the
     * list are float type
     */
    #[TestDox('All stored values are floats')]
    public function test_all_stored_values_are_floats(): void
    {
        $unit = new ListOfFloats([1.0, 2.5, 0.0, -3.14]);

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertIsFloat($value);
        }
    }

    /**
     * this test proves that negative float values are stored
     * and retrieved correctly
     */
    #[TestDox('Handles negative floats correctly')]
    public function test_handles_negative_floats(): void
    {
        $unit = new ListOfFloats([-1.5, -2.5, -3.5]);

        $actualResult = $unit->toArray();

        $this->assertSame([-1.5, -2.5, -3.5], $actualResult);
    }

    /**
     * this test proves that float precision is preserved
     * through storage and retrieval
     */
    #[TestDox('Preserves float precision')]
    public function test_preserves_float_precision(): void
    {
        $unit = new ListOfFloats([M_PI, M_E, M_SQRT2]);

        $actualResult = $unit->toArray();

        $this->assertSame(M_PI, $actualResult[0]);
        $this->assertSame(M_E, $actualResult[1]);
        $this->assertSame(M_SQRT2, $actualResult[2]);
    }
}
