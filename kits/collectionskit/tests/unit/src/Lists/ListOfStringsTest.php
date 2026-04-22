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
use StusDevKit\CollectionsKit\Lists\ListOfStrings;

#[TestDox('ListOfStrings')]
class ListOfStringsTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // namespace is a long-term contract — renames break every
        // `use` statement in every caller

        $reflection = new \ReflectionClass(ListOfStrings::class);

        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ListOfStrings must remain a class (not an interface or
        // trait) so callers can instantiate it with `new`

        $reflection = new \ReflectionClass(ListOfStrings::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsList')]
    public function test_extends_CollectionAsList(): void
    {
        // ListOfStrings builds on the shared list behaviour of
        // CollectionAsList — breaking this breaks every caller that
        // type-hints against the parent

        $reflection = new \ReflectionClass(ListOfStrings::class);
        $parent = $reflection->getParentClass();

        // correctness! getParentClass() returns false when no parent
        // exists — fail loudly rather than silently skip the assertion
        $this->assertNotFalse($parent);

        $this->assertSame(
            \StusDevKit\CollectionsKit\Lists\CollectionAsList::class,
            $parent->getName(),
        );
    }

    #[TestDox('uses the StringTransformations trait')]
    public function test_uses_StringTransformations_trait(): void
    {
        // StringTransformations adds applyTrim/applyLtrim/applyRtrim/
        // etc. — pinning it here catches accidental trait removal

        $traits = \class_uses(ListOfStrings::class);

        $this->assertContains(
            \StusDevKit\CollectionsKit\Traits\StringTransformations::class,
            $traits,
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------
    //
    // ListOfStrings is a thin type specialisation: it inherits its
    // method shape from CollectionAsList<string> and picks up the
    // StringTransformations trait. Shape is pinned on the parent
    // class and the trait; ListOfStrings itself declares no methods
    // beyond what the trait contributes.
    //
    // ----------------------------------------------------------------

    #[TestDox('declares only the StringTransformations trait methods as its own public methods')]
    public function test_declares_only_trait_public_methods(): void
    {
        // ListOfStrings is a thin class: it adds no public API of its
        // own beyond what the StringTransformations trait contributes.
        //
        // PHP reflection reports trait-provided methods as declared by
        // the using class, so the "own methods" set is exactly the
        // trait's public API — pinning this set here catches both
        // accidental new methods on the class and accidental trait
        // changes.

        $reflection = new \ReflectionClass(ListOfStrings::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ListOfStrings::class) {
                $ownMethods[] = $m->getName();
            }
        }
        \sort($ownMethods);

        $this->assertSame(
            ['applyLtrim', 'applyRtrim', 'applyTrim'],
            $ownMethods,
        );
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    /**
     * we can create a new, empty ListOfStrings.
     */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        $unit = new ListOfStrings();

        $this->assertInstanceOf(ListOfStrings::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * we can create a new ListOfStrings and seed it with an initial array of
     * strings.
     */
    #[TestDox('::__construct() accepts initial strings')]
    public function test_can_instantiate_with_initial_strings(): void
    {
        $expectedStrings = [
            'hello, world',
            'goodbye for now',
        ];

        $unit = new ListOfStrings($expectedStrings);

        $this->assertCount(2, $unit);
        $this->assertSame($expectedStrings, $unit->toArray());
    }

    /**
     * when constructed with a list-style array, the keys remain sequential
     * integers.
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $expectedStrings = ['alpha', 'bravo', 'charlie'];

        $unit = new ListOfStrings($expectedStrings);
        $actualData = $unit->toArray();

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * add() appends a string to the end of the list with a sequential integer
     * key.
     */
    #[TestDox('->add() appends a string to the list')]
    public function test_add_appends_string(): void
    {
        $unit = new ListOfStrings();

        $unit->add('alpha');

        $this->assertSame(['alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * calling add() multiple times appends each string in the order they were
     * added.
     */
    #[TestDox('->add() appends multiple strings in order')]
    public function test_add_appends_multiple_strings_in_order(): void
    {
        $unit = new ListOfStrings();

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    /**
     * add() appends a string after any data that was passed into the
     * constructor.
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo']);

        $unit->add('charlie');

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    /**
     * add() returns the same collection instance for fluent method chaining.
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new ListOfStrings();

        $result = $unit->add('alpha');

        $this->assertSame($unit, $result);
    }

    /**
     * add() calls can be chained together fluently to build up the list.
     */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new ListOfStrings();

        $unit->add('alpha')
            ->add('bravo')
            ->add('charlie');

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    /**
     * strings added via add() always receive sequential integer keys.
     */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new ListOfStrings();

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /**
     * add() allows duplicate strings in the list (unlike a set).
     */
    #[TestDox('->add() can add duplicate strings')]
    public function test_add_can_add_duplicate_strings(): void
    {
        $unit = new ListOfStrings();

        $unit->add('alpha');
        $unit->add('alpha');
        $unit->add('alpha');

        $this->assertSame(
            ['alpha', 'alpha', 'alpha'],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    /**
     * add() can store empty strings in the list.
     */
    #[TestDox('->add() can add empty strings')]
    public function test_add_can_add_empty_strings(): void
    {
        $unit = new ListOfStrings();

        $unit->add('');

        $this->assertSame([''], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function provideStringVariants(): array
    {
        return [
            'simple string' => ['hello world'],
            'empty string' => [''],
            'string with spaces' => ['  spaces  '],
            'string with newlines' => ["line1\nline2"],
            'string with tabs' => ["col1\tcol2"],
            'unicode string' => ['héllo wörld'],
            'string with special chars' => ['<html>&amp;</html>'],
            'numeric string' => ['12345'],
            'string with null bytes' => ["null\0byte"],
        ];
    }

    /**
     * add() correctly stores strings containing various special characters and
     * formats.
     */
    #[TestDox('->add() accepts various string formats')]
    #[DataProvider('provideStringVariants')]
    public function test_add_accepts_various_string_formats(
        string $input,
    ): void {
        $unit = new ListOfStrings();

        $unit->add($input);

        $this->assertSame([$input], $unit->toArray());
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    /**
     * toArray() returns an empty array when the list contains no data.
     */
    #[TestDox('->toArray() returns empty array for empty list')]
    public function test_to_array_returns_empty_array_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /**
     * toArray() returns all the strings stored in the list.
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    /**
     * toArray() includes data that was added using the add() method.
     */
    #[TestDox('->toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        $unit = new ListOfStrings();
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
     * count() returns 0 when the list contains no data.
     */
    #[TestDox('->count() returns 0 for empty list')]
    public function test_count_returns_zero_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /**
     * count() returns the correct number of strings stored in the list.
     */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    /**
     * the list works with PHP's built-in count() function via the Countable
     * interface.
     */
    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    /**
     * count() correctly reflects items added via the add() method.
     */
    #[TestDox('->count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        $unit = new ListOfStrings();
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
     * getIterator() returns an ArrayIterator instance.
     */
    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo']);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    /**
     * the list can be used in a foreach loop via the IteratorAggregate
     * interface.
     */
    #[TestDox('List can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * iterating over an empty list does not execute the loop body.
     */
    #[TestDox('Iterating empty list produces no iterations')]
    public function test_iterating_empty_list_produces_no_iterations(): void
    {
        $unit = new ListOfStrings();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * iterating over a ListOfStrings produces sequential integer keys starting
     * from 0.
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame([0, 1, 2], $actualKeys);
    }

    /**
     * iterating over a list includes items that were added via the add()
     * method.
     */
    #[TestDox('Iteration includes items added via add()')]
    public function test_iteration_includes_items_added_via_add(): void
    {
        $unit = new ListOfStrings();
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
     * merge() can accept a plain PHP array and merge its contents into the
     * list.
     */
    #[TestDox('->merge() can merge an array into the list')]
    public function test_merge_can_merge_array(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo']);
        $toMerge = ['charlie', 'delta'];

        $result = $unit->merge($toMerge);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * merge() can accept another ListOfStrings and merge its contents.
     */
    #[TestDox('->merge() can merge another ListOfStrings')]
    public function test_merge_can_merge_list_of_strings(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo']);
        $other = new ListOfStrings(['charlie', 'delta']);

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
     * mergeArray() appends the given array's contents to the list's data.
     */
    #[TestDox('->mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        $unit = new ListOfStrings(['alpha']);
        $toMerge = ['bravo', 'charlie'];

        $result = $unit->mergeArray($toMerge);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * mergeArray() works correctly when the list is initially empty.
     */
    #[TestDox('->mergeArray() into empty list sets the data')]
    public function test_merge_array_into_empty_list(): void
    {
        $unit = new ListOfStrings();
        $toMerge = ['alpha', 'bravo'];

        $unit->mergeArray($toMerge);

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    /**
     * merging an empty array does not alter the list's existing data.
     */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = ['alpha', 'bravo'];
        $unit = new ListOfStrings($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * mergeArray() returns the same list instance for fluent method chaining.
     */
    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        $unit = new ListOfStrings(['alpha']);

        $result = $unit->mergeArray(['bravo']);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * mergeSelf() appends the contents of another ListOfStrings into this
     * list.
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $unit = new ListOfStrings(['alpha']);
        $other = new ListOfStrings(['bravo', 'charlie']);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * the list being merged from is not modified by the merge operation.
     */
    #[TestDox('->mergeSelf() does not modify the source list')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new ListOfStrings(['alpha']);
        $other = new ListOfStrings(['bravo']);
        $expectedOtherData = ['bravo'];

        $unit->mergeSelf($other);

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    /**
     * merging an empty list does not alter the existing data.
     */
    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = ['alpha', 'bravo'];
        $unit = new ListOfStrings($expectedData);
        $other = new ListOfStrings();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    /**
     * maybeFirst() returns the first string in the list when it is not empty.
     */
    #[TestDox('->maybeFirst() returns the first string')]
    public function test_maybe_first_returns_first_string(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->maybeFirst();

        $this->assertSame('alpha', $actualResult);
    }

    /**
     * maybeFirst() returns null when the list is empty, rather than throwing
     * an exception.
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * maybeFirst() returns the first string that was added via the add()
     * method.
     */
    #[TestDox('->maybeFirst() returns the first string added via add()')]
    public function test_maybe_first_returns_first_string_added_via_add(): void
    {
        $unit = new ListOfStrings();
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
     * first() returns the first string in the list when it is not empty.
     */
    #[TestDox('->first() returns the first string')]
    public function test_first_returns_first_string(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->first();

        $this->assertSame('alpha', $actualResult);
    }

    /**
     * first() throws a RuntimeException when the list is empty.
     */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfStrings is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * maybeLast() returns the last string in the list when it is not empty.
     */
    #[TestDox('->maybeLast() returns the last string')]
    public function test_maybe_last_returns_last_string(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->maybeLast();

        $this->assertSame('charlie', $actualResult);
    }

    /**
     * maybeLast() returns null when the list is empty, rather than throwing an
     * exception.
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /**
     * maybeLast() returns the most recently added string via add().
     */
    #[TestDox('->maybeLast() returns the last string added via add()')]
    public function test_maybe_last_returns_last_string_added_via_add(): void
    {
        $unit = new ListOfStrings();
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
     * last() returns the last string in the list when it is not empty.
     */
    #[TestDox('->last() returns the last string')]
    public function test_last_returns_last_string(): void
    {
        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->last();

        $this->assertSame('charlie', $actualResult);
    }

    /**
     * last() throws a RuntimeException when the list is empty.
     */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfStrings is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * copy() returns a new ListOfStrings instance containing the same data as
     * the original.
     */
    #[TestDox('->copy() returns a new ListOfStrings with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfStrings::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    /**
     * modifying the copied list does not affect the original list's data.
     */
    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $originalData = ['alpha', 'bravo'];
        $unit = new ListOfStrings($originalData);

        $copy = $unit->copy();
        $copy->add('charlie');

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $copy->toArray(),
        );
    }

    /**
     * copying an empty list returns a new, empty ListOfStrings instance.
     */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new ListOfStrings();

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfStrings::class, $copy);
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
     * empty() returns true when the list has no data.
     */
    #[TestDox('->empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        $unit = new ListOfStrings();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    /**
     * empty() returns false when the list contains data.
     */
    #[TestDox('->empty() returns false for non-empty list')]
    public function test_empty_returns_false_for_non_empty_list(): void
    {
        $unit = new ListOfStrings(['alpha']);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /**
     * empty() returns false after a string has been added via add().
     */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new ListOfStrings();
        $unit->add('alpha');

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // applyTrim()
    //
    // ----------------------------------------------------------------

    /**
     * applyTrim() uses PHP's trim() function to remove whitespace from all
     * strings in the list.
     */
    #[TestDox('->applyTrim() removes whitespace from strings in the list')]
    public function test_apply_trim_removes_whitespace_from_strings(): void
    {
        $expectedData = ['  alpha  ', '  bravo  ', '  charlie  '];
        $expectedTrimmed = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $unit->applyTrim();

        $this->assertSame($expectedTrimmed, $unit->toArray());
    }

    /**
     * applyTrim() does not alter strings that don't have leading or trailing
     * whitespace.
     */
    #[TestDox('->applyTrim() on list with no spaces leaves strings unchanged')]
    public function test_apply_trim_unchanged_when_no_spaces(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $unit->applyTrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * applyTrim() works correctly on empty lists.
     */
    #[TestDox('->applyTrim() handles empty list')]
    public function test_apply_trim_on_empty_list(): void
    {
        $unit = new ListOfStrings();

        $unit->applyTrim();

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    /**
     * applyTrim() removes newline and tab characters.
     */
    #[TestDox('->applyTrim() handles strings with newlines and tabs')]
    public function test_apply_trim_removes_newlines_and_tabs(): void
    {
        $expectedData = ["
alpha", "bravo	", "charlie

"];
        $expectedTrimmed = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $unit->applyTrim();

        $this->assertSame($expectedTrimmed, $unit->toArray());
    }

    /**
     * applyTrim() correctly handles empty strings.
     */
    #[TestDox('->applyTrim() handles empty strings')]
    public function test_apply_trim_preserves_empty_strings(): void
    {
        $expectedData = ['', 'alpha', '', 'bravo', ''];
        $unit = new ListOfStrings($expectedData);

        $unit->applyTrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * applyTrim() returns $this for fluent method chaining.
     */
    #[TestDox('->applyTrim() can be chained with other methods')]
    public function test_apply_trim_supports_method_chaining(): void
    {
        $unit = new ListOfStrings(['  alpha  ', '  bravo  ']);

        $result = $unit->applyTrim();

        $this->assertSame($unit, $result);
    }

    /**
     * applyTrim() works correctly with strings added dynamically via add().
     */
    #[TestDox('->applyTrim() can be used fluently with add()')]
    public function test_apply_trim_with_add(): void
    {
        $unit = new ListOfStrings(['  alpha  ']);

        $unit->add('  bravo  ')->applyTrim();

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    /**
     * when a custom $characters parameter is provided, applyTrim() only strips
     * those specified characters from the strings.
     */
    #[TestDox('->applyTrim() with custom characters strips only those characters')]
    public function test_apply_trim_with_custom_characters(): void
    {
        $unit = new ListOfStrings(['/path/', '//double//', '/single']);

        $unit->applyTrim(characters: '/');

        $this->assertSame(
            ['path', 'double', 'single'],
            $unit->toArray(),
        );
    }

    /**
     * when custom characters are provided, default whitespace is not stripped
     * — only the specified characters are removed.
     */
    #[TestDox('->applyTrim() with custom characters does not strip whitespace')]
    public function test_apply_trim_with_custom_characters_preserves_whitespace(): void
    {
        $unit = new ListOfStrings(['/ path /', '/ hello /']);

        $unit->applyTrim(characters: '/');

        $this->assertSame(
            [' path ', ' hello '],
            $unit->toArray(),
        );
    }

    /**
     * applyTrim() with custom characters works correctly on an empty list
     * without error.
     */
    #[TestDox('->applyTrim() with custom characters handles empty list')]
    public function test_apply_trim_with_custom_characters_on_empty_list(): void
    {
        $unit = new ListOfStrings();

        $unit->applyTrim(characters: '/');

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    /**
     * applyTrim() returns $this for fluent method chaining when custom
     * characters are provided.
     */
    #[TestDox('->applyTrim() with custom characters returns $this for chaining')]
    public function test_apply_trim_with_custom_characters_returns_this(): void
    {
        $unit = new ListOfStrings(['/path/', '/other/']);

        $result = $unit->applyTrim(characters: '/');

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // applyLtrim()
    //
    // ----------------------------------------------------------------

    /**
     * applyLtrim() removes leading whitespace from all strings in the list,
     * while preserving trailing whitespace.
     */
    #[TestDox('->applyLtrim() removes leading whitespace from strings')]
    public function test_apply_ltrim_removes_leading_whitespace(): void
    {
        $unit = new ListOfStrings(['  alpha  ', '  bravo  ', '  charlie  ']);

        $unit->applyLtrim();

        $this->assertSame(
            ['alpha  ', 'bravo  ', 'charlie  '],
            $unit->toArray(),
        );
    }

    /**
     * applyLtrim() only removes leading whitespace and does not affect
     * trailing whitespace.
     */
    #[TestDox('->applyLtrim() preserves trailing whitespace')]
    public function test_apply_ltrim_preserves_trailing_whitespace(): void
    {
        $unit = new ListOfStrings(['alpha  ', 'bravo  ']);

        $unit->applyLtrim();

        $this->assertSame(
            ['alpha  ', 'bravo  '],
            $unit->toArray(),
        );
    }

    /**
     * applyLtrim() does not alter strings that don't have leading whitespace.
     */
    #[TestDox('->applyLtrim() on list with no leading spaces leaves strings unchanged')]
    public function test_apply_ltrim_unchanged_when_no_leading_spaces(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $unit->applyLtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * applyLtrim() works correctly on empty lists.
     */
    #[TestDox('->applyLtrim() handles empty list')]
    public function test_apply_ltrim_on_empty_list(): void
    {
        $unit = new ListOfStrings();

        $unit->applyLtrim();

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    /**
     * applyLtrim() removes leading newline and tab characters.
     */
    #[TestDox('->applyLtrim() handles strings with leading newlines and tabs')]
    public function test_apply_ltrim_removes_leading_newlines_and_tabs(): void
    {
        $unit = new ListOfStrings([
            "\nalpha",
            "\tbravo",
            "\n\tcharlie",
        ]);

        $unit->applyLtrim();

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    /**
     * applyLtrim() correctly handles empty strings.
     */
    #[TestDox('->applyLtrim() handles empty strings')]
    public function test_apply_ltrim_preserves_empty_strings(): void
    {
        $expectedData = ['', 'alpha', '', 'bravo', ''];
        $unit = new ListOfStrings($expectedData);

        $unit->applyLtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * applyLtrim() returns $this for fluent method chaining.
     */
    #[TestDox('->applyLtrim() returns $this for method chaining')]
    public function test_apply_ltrim_supports_method_chaining(): void
    {
        $unit = new ListOfStrings(['  alpha  ', '  bravo  ']);

        $result = $unit->applyLtrim();

        $this->assertSame($unit, $result);
    }

    /**
     * applyLtrim() works correctly with strings added dynamically via add().
     */
    #[TestDox('->applyLtrim() can be used fluently with add()')]
    public function test_apply_ltrim_with_add(): void
    {
        $unit = new ListOfStrings(['  alpha  ']);

        $unit->add('  bravo  ')->applyLtrim();

        $this->assertSame(
            ['alpha  ', 'bravo  '],
            $unit->toArray(),
        );
    }

    /**
     * when a custom $characters parameter is provided, applyLtrim() only
     * strips those specified characters from the left side of the strings.
     */
    #[TestDox('->applyLtrim() with custom characters strips only those characters from the left')]
    public function test_apply_ltrim_with_custom_characters(): void
    {
        $unit = new ListOfStrings(['/path/', '//double//', '/single']);

        $unit->applyLtrim(characters: '/');

        $this->assertSame(
            ['path/', 'double//', 'single'],
            $unit->toArray(),
        );
    }

    /**
     * when custom characters are provided, default whitespace is not stripped
     * — only the specified characters are removed from the left.
     */
    #[TestDox('->applyLtrim() with custom characters does not strip whitespace')]
    public function test_apply_ltrim_with_custom_characters_preserves_whitespace(): void
    {
        $unit = new ListOfStrings(['/ path /', '/ hello /']);

        $unit->applyLtrim(characters: '/');

        $this->assertSame(
            [' path /', ' hello /'],
            $unit->toArray(),
        );
    }

    /**
     * applyLtrim() with custom characters works correctly on an empty list
     * without error.
     */
    #[TestDox('->applyLtrim() with custom characters handles empty list')]
    public function test_apply_ltrim_with_custom_characters_on_empty_list(): void
    {
        $unit = new ListOfStrings();

        $unit->applyLtrim(characters: '/');

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    /**
     * applyLtrim() returns $this for fluent method chaining when custom
     * characters are provided.
     */
    #[TestDox('->applyLtrim() with custom characters returns $this for chaining')]
    public function test_apply_ltrim_with_custom_characters_returns_this(): void
    {
        $unit = new ListOfStrings(['/path/', '/other/']);

        $result = $unit->applyLtrim(characters: '/');

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // applyRtrim()
    //
    // ----------------------------------------------------------------

    /**
     * applyRtrim() removes trailing whitespace from all strings in the list,
     * while preserving leading whitespace.
     */
    #[TestDox('->applyRtrim() removes trailing whitespace from strings')]
    public function test_apply_rtrim_removes_trailing_whitespace(): void
    {
        $unit = new ListOfStrings(['  alpha  ', '  bravo  ', '  charlie  ']);

        $unit->applyRtrim();

        $this->assertSame(
            ['  alpha', '  bravo', '  charlie'],
            $unit->toArray(),
        );
    }

    /**
     * applyRtrim() only removes trailing whitespace and does not affect
     * leading whitespace.
     */
    #[TestDox('->applyRtrim() preserves leading whitespace')]
    public function test_apply_rtrim_preserves_leading_whitespace(): void
    {
        $unit = new ListOfStrings(['  alpha', '  bravo']);

        $unit->applyRtrim();

        $this->assertSame(
            ['  alpha', '  bravo'],
            $unit->toArray(),
        );
    }

    /**
     * applyRtrim() does not alter strings that don't have trailing whitespace.
     */
    #[TestDox('->applyRtrim() on list with no trailing spaces leaves strings unchanged')]
    public function test_apply_rtrim_unchanged_when_no_trailing_spaces(): void
    {
        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        $unit->applyRtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * applyRtrim() works correctly on empty lists.
     */
    #[TestDox('->applyRtrim() handles empty list')]
    public function test_apply_rtrim_on_empty_list(): void
    {
        $unit = new ListOfStrings();

        $unit->applyRtrim();

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    /**
     * applyRtrim() removes trailing newline and tab characters.
     */
    #[TestDox('->applyRtrim() handles strings with trailing newlines and tabs')]
    public function test_apply_rtrim_removes_trailing_newlines_and_tabs(): void
    {
        $unit = new ListOfStrings([
            "alpha\n",
            "bravo\t",
            "charlie\n\t",
        ]);

        $unit->applyRtrim();

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    /**
     * applyRtrim() correctly handles empty strings.
     */
    #[TestDox('->applyRtrim() handles empty strings')]
    public function test_apply_rtrim_preserves_empty_strings(): void
    {
        $expectedData = ['', 'alpha', '', 'bravo', ''];
        $unit = new ListOfStrings($expectedData);

        $unit->applyRtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * applyRtrim() returns $this for fluent method chaining.
     */
    #[TestDox('->applyRtrim() returns $this for method chaining')]
    public function test_apply_rtrim_supports_method_chaining(): void
    {
        $unit = new ListOfStrings(['  alpha  ', '  bravo  ']);

        $result = $unit->applyRtrim();

        $this->assertSame($unit, $result);
    }

    /**
     * applyRtrim() works correctly with strings added dynamically via add().
     */
    #[TestDox('->applyRtrim() can be used fluently with add()')]
    public function test_apply_rtrim_with_add(): void
    {
        $unit = new ListOfStrings(['  alpha  ']);

        $unit->add('  bravo  ')->applyRtrim();

        $this->assertSame(
            ['  alpha', '  bravo'],
            $unit->toArray(),
        );
    }

    /**
     * when a custom $characters parameter is provided, applyRtrim() only
     * strips those specified characters from the right side of the strings.
     */
    #[TestDox('->applyRtrim() with custom characters strips only those characters from the right')]
    public function test_apply_rtrim_with_custom_characters(): void
    {
        $unit = new ListOfStrings(['/path/', '//double//', 'single/']);

        $unit->applyRtrim(characters: '/');

        $this->assertSame(
            ['/path', '//double', 'single'],
            $unit->toArray(),
        );
    }

    /**
     * when custom characters are provided, default whitespace is not stripped
     * — only the specified characters are removed from the right.
     */
    #[TestDox('->applyRtrim() with custom characters does not strip whitespace')]
    public function test_apply_rtrim_with_custom_characters_preserves_whitespace(): void
    {
        $unit = new ListOfStrings(['/ path /', '/ hello /']);

        $unit->applyRtrim(characters: '/');

        $this->assertSame(
            ['/ path ', '/ hello '],
            $unit->toArray(),
        );
    }

    /**
     * applyRtrim() with custom characters works correctly on an empty list
     * without error.
     */
    #[TestDox('->applyRtrim() with custom characters handles empty list')]
    public function test_apply_rtrim_with_custom_characters_on_empty_list(): void
    {
        $unit = new ListOfStrings();

        $unit->applyRtrim(characters: '/');

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    /**
     * applyRtrim() returns $this for fluent method chaining when custom
     * characters are provided.
     */
    #[TestDox('->applyRtrim() with custom characters returns $this for chaining')]
    public function test_apply_rtrim_with_custom_characters_returns_this(): void
    {
        $unit = new ListOfStrings(['/path/', '/other/']);

        $result = $unit->applyRtrim(characters: '/');

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // -----------------------------------------------------------------------

    /**
     * getCollectionTypeAsString() returns "ListOfStrings" (just the class name
     * without namespace).
     */
    #[TestDox('->getCollectionTypeAsString() returns "ListOfStrings"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new ListOfStrings();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('ListOfStrings', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // -----------------------------------------------------
    //
    // ----------------------------------------------------------------

    /**
     * for a list with exactly one string, both first() and last() return that
     * same string.
     */
    #[TestDox('List with one string: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new ListOfStrings(['only-item']);

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
     * add() and merge methods can be chained together fluently.
     */
    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        $unit = new ListOfStrings();
        $other = new ListOfStrings(['delta']);

        $unit->add('alpha')
            ->mergeArray(['bravo', 'charlie'])
            ->mergeSelf($other);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
    }
}
