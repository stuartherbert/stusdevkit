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
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\CollectionsKit\Lists\CollectionAsList;

#[TestDox('CollectionAsList')]
class CollectionAsListTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(CollectionAsList::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(CollectionAsList::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends AccessibleCollection')]
    public function test_extends_AccessibleCollection(): void
    {
        $reflection = new \ReflectionClass(CollectionAsList::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\AccessibleCollection::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------
    //
    // CollectionAsList declares exactly one own public method: add().
    // All other behaviour is inherited and pinned by the parent-class
    // tests (AccessibleCollectionTest, CollectionOfAnythingTest).
    //
    // ----------------------------------------------------------------

    #[TestDox('declares only the add() method of its own')]
    public function test_declares_only_add_method(): void
    {
        $reflection = new \ReflectionClass(CollectionAsList::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === CollectionAsList::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['add'], $ownMethods);
    }

    #[TestDox('::add() is public and not static')]
    public function test_add_is_public_non_static(): void
    {
        $method = new \ReflectionMethod(CollectionAsList::class, 'add');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('::add() declares return type static')]
    public function test_add_declares_return_type(): void
    {
        $method = new \ReflectionMethod(CollectionAsList::class, 'add');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
    }

    #[TestDox('::add() accepts one parameter named $value')]
    public function test_add_parameter_names(): void
    {
        $method = new \ReflectionMethod(CollectionAsList::class, 'add');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['value'], $paramNames);
    }

    #[TestDox('::add() declares $value as mixed')]
    public function test_add_parameter_types(): void
    {
        $method = new \ReflectionMethod(CollectionAsList::class, 'add');
        $param = $method->getParameters()[0];
        $type = $param->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    /**
     * we can create a new, empty
     * CollectionAsList
     */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        $unit = new CollectionAsList();

        $this->assertInstanceOf(CollectionAsList::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * we can create a CollectionAsList
     * and seed it with an initial array of data
     */
    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];

        $unit = new CollectionAsList($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * when constructed with a list-style
     * array, the keys remain sequential integers
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];

        $unit = new CollectionAsList($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * add() appends a value to the end
     * of the list with a sequential integer key
     */
    #[TestDox('->add() appends a value to the list')]
    public function test_add_appends_value(): void
    {
        $unit = new CollectionAsList();

        $unit->add('alpha');

        $this->assertSame(['alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * calling add() multiple times appends
     * each value in the order they were added
     */
    #[TestDox('->add() appends multiple values in order')]
    public function test_add_appends_multiple_values_in_order(): void
    {
        $unit = new CollectionAsList();

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        $this->assertSame(['alpha', 'bravo', 'charlie'], $unit->toArray());
    }

    /**
     * add() appends a value after any
     * data that was passed into the constructor
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo']);

        $unit->add('charlie');

        $this->assertSame(['alpha', 'bravo', 'charlie'], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * add() returns the same collection
     * instance for fluent method chaining
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new CollectionAsList();

        $result = $unit->add('alpha');

        $this->assertSame($unit, $result);
    }

    /**
     * add() calls can be chained together
     * fluently to build up the list
     */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new CollectionAsList();

        $unit->add('alpha')
            ->add('bravo')
            ->add('charlie');

        $this->assertSame(['alpha', 'bravo', 'charlie'], $unit->toArray());
    }

    /**
     * values added via add() always
     * receive sequential integer keys
     */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new CollectionAsList();

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /**
     * add() allows duplicate values
     * in the list (unlike a set)
     */
    #[TestDox('->add() can add duplicate values')]
    public function test_add_can_add_duplicate_values(): void
    {
        $unit = new CollectionAsList();

        $unit->add('alpha');
        $unit->add('alpha');
        $unit->add('alpha');

        $this->assertSame(['alpha', 'alpha', 'alpha'], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * add() throws a
     * NullValueNotAllowed exception when given a null value
     */
    #[TestDox('->add() rejects null values')]
    public function test_add_rejects_null_values(): void
    {
        $unit = new CollectionAsList();

        $this->expectException(NullValueNotAllowedException::class);

        $unit->add(null);
    }

    /**
     * add() can store values of different
     * types in the same list
     */
    #[TestDox('->add() can add values of different types')]
    public function test_add_can_add_mixed_types(): void
    {
        $unit = new CollectionAsList();

        $unit->add('a string');
        $unit->add(42);
        $unit->add(3.14);
        $unit->add(true);
        $unit->add(['nested' => 'array']);

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

    /**
     * toArray() returns an empty array
     * when the list contains no data
     */
    #[TestDox('->toArray() returns empty array for empty list')]
    public function test_to_array_returns_empty_array_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /**
     * toArray() returns all the data
     * stored in the list
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionAsList($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    /**
     * toArray() includes data that was
     * added using the add() method
     */
    #[TestDox('->toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

        $actualResult = $unit->toArray();

        $this->assertSame(['alpha', 'bravo'], $actualResult);
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    /**
     * count() returns 0 when the
     * list contains no data
     */
    #[TestDox('->count() returns 0 for empty list')]
    public function test_count_returns_zero_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /**
     * count() returns the correct number
     * of items stored in the list
     */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    /**
     * the list works with PHP's built-in
     * count() function via the Countable interface
     */
    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    /**
     * count() correctly reflects items
     * added via the add() method
     */
    #[TestDox('->count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    /**
     * getIterator() returns an
     * ArrayIterator instance
     */
    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo']);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    /**
     * the list can be used in a foreach
     * loop via the IteratorAggregate interface
     */
    #[TestDox('List can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionAsList($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * iterating over an empty list
     * does not execute the loop body
     */
    #[TestDox('Iterating empty list produces no iterations')]
    public function test_iterating_empty_list_produces_no_iterations(): void
    {
        $unit = new CollectionAsList();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * iterating over a list produces
     * sequential integer keys starting from 0
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame([0, 1, 2], $actualKeys);
    }

    /**
     * iterating over a list includes
     * items that were added via the add() method
     */
    #[TestDox('Iteration includes items added via add()')]
    public function test_iteration_includes_items_added_via_add(): void
    {
        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');
        $actualData = [];

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        $this->assertSame(['alpha', 'bravo'], $actualData);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    /**
     * merge() can accept a plain PHP
     * array and merge its contents into the list
     */
    #[TestDox('->merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo']);
        $toMerge = ['charlie', 'delta'];

        $result = $unit->merge($toMerge);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * merge() can accept another
     * CollectionAsList and merge its contents
     */
    #[TestDox('->merge() can merge another CollectionAsList')]
    public function test_merge_can_merge_collection(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo']);
        $other = new CollectionAsList(['charlie', 'delta']);

        $result = $unit->merge($other);

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

    /**
     * mergeArray() appends the given
     * array's contents to the list's data
     */
    #[TestDox('->mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        $unit = new CollectionAsList(['alpha']);
        $toMerge = ['bravo', 'charlie'];

        $result = $unit->mergeArray($toMerge);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * mergeArray() works correctly when
     * the list is initially empty
     */
    #[TestDox('->mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        $unit = new CollectionAsList();
        $toMerge = ['alpha', 'bravo'];

        $unit->mergeArray($toMerge);

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    /**
     * merging an empty array does not
     * alter the list's existing data
     */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = ['alpha', 'bravo'];
        $unit = new CollectionAsList($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * mergeArray() returns the same
     * list instance for fluent method chaining
     */
    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        $unit = new CollectionAsList(['alpha']);

        $result = $unit->mergeArray(['bravo']);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * mergeSelf() appends the contents
     * of another CollectionAsList into this list
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $unit = new CollectionAsList(['alpha']);
        $other = new CollectionAsList(['bravo', 'charlie']);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * the list being merged from is not
     * modified by the merge operation
     */
    #[TestDox('->mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new CollectionAsList(['alpha']);
        $other = new CollectionAsList(['bravo']);
        $expectedOtherData = ['bravo'];

        $unit->mergeSelf($other);

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    /**
     * merging an empty list does not
     * alter the existing data
     */
    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = ['alpha', 'bravo'];
        $unit = new CollectionAsList($expectedData);
        $other = new CollectionAsList();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    /**
     * maybeFirst() returns the first item
     * in the list when it is not empty
     */
    #[TestDox('->maybeFirst() returns the first item')]
    public function test_maybe_first_returns_first_item(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->maybeFirst();

        $this->assertSame('alpha', $actualResult);
    }

    /**
     * maybeFirst() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * maybeFirst() returns the first item
     * that was added via the add() method
     */
    #[TestDox('->maybeFirst() returns the first item added via add()')]
    public function test_maybe_first_returns_first_item_added_via_add(): void
    {
        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

        $actualResult = $unit->maybeFirst();

        $this->assertSame('alpha', $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    /**
     * first() returns the first item in
     * the list when it is not empty
     */
    #[TestDox('->first() returns the first item')]
    public function test_first_returns_first_item(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->first();

        $this->assertSame('alpha', $actualResult);
    }

    /**
     * first() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsList is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * maybeLast() returns the last item
     * in the list when it is not empty
     */
    #[TestDox('->maybeLast() returns the last item')]
    public function test_maybe_last_returns_last_item(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->maybeLast();

        $this->assertSame('charlie', $actualResult);
    }

    /**
     * maybeLast() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /**
     * maybeLast() returns the most recently
     * added item via add()
     */
    #[TestDox('->maybeLast() returns the last item added via add()')]
    public function test_maybe_last_returns_last_item_added_via_add(): void
    {
        $unit = new CollectionAsList();
        $unit->add('alpha');
        $unit->add('bravo');

        $actualResult = $unit->maybeLast();

        $this->assertSame('bravo', $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    /**
     * last() returns the last item in
     * the list when it is not empty
     */
    #[TestDox('->last() returns the last item')]
    public function test_last_returns_last_item(): void
    {
        $unit = new CollectionAsList(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->last();

        $this->assertSame('charlie', $actualResult);
    }

    /**
     * last() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsList is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * copy() returns a new CollectionAsList
     * instance containing the same data as the original
     */
    #[TestDox('->copy() returns a new CollectionAsList with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new CollectionAsList($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(CollectionAsList::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    /**
     * modifying the copied list does not
     * affect the original list's data
     */
    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $originalData = ['alpha', 'bravo'];
        $unit = new CollectionAsList($originalData);

        $copy = $unit->copy();
        $copy->add('charlie');

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $copy->toArray(),
        );
    }

    /**
     * copying an empty list returns a new,
     * empty CollectionAsList instance
     */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new CollectionAsList();

        $copy = $unit->copy();

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

    /**
     * empty() returns true when the
     * list has no data
     */
    #[TestDox('->empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        $unit = new CollectionAsList();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    /**
     * empty() returns false when the
     * list contains data
     */
    #[TestDox('->empty() returns false for non-empty list')]
    public function test_empty_returns_false_for_non_empty_list(): void
    {
        $unit = new CollectionAsList(['alpha']);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /**
     * empty() returns false after an item
     * has been added via add()
     */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new CollectionAsList();
        $unit->add('alpha');

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    /**
     * getCollectionTypeAsString() returns
     * "CollectionAsList" (just the class name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns the class basename')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new CollectionAsList();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('CollectionAsList', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    /**
     * for a list with exactly one item,
     * both first() and last() return that same item
     */
    #[TestDox('List with one item: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new CollectionAsList(['only-item']);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame('only-item', $first);
        $this->assertSame('only-item', $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    /**
     * add() and merge methods can be
     * chained together fluently
     */
    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        /** @var CollectionAsList<string> $unit */
        $unit = new CollectionAsList();
        $other = new CollectionAsList(['delta']);

        $unit->add('alpha')
            ->mergeArray(['bravo', 'charlie'])
            ->mergeSelf($other);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
    }
}
