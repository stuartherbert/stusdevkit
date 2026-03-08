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
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // ListOfStrings

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfStrings::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Can instantiate with initial strings')]
    public function test_can_instantiate_with_initial_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new ListOfStrings
        // and seed it with an initial array of strings

        // ----------------------------------------------------------------
        // setup your test

        $expectedStrings = [
            'hello, world',
            'goodbye for now',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfStrings($expectedStrings);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($expectedStrings, $unit->toArray());
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

        $expectedStrings = ['alpha', 'bravo', 'charlie'];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfStrings($expectedStrings);
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

    #[TestDox('add() appends a string to the list')]
    public function test_add_appends_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a string to the end
        // of the list with a sequential integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() appends multiple strings in order')]
    public function test_add_appends_multiple_strings_in_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() multiple times
        // appends each string in the order they were added

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');
        $unit->add('bravo');
        $unit->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a string after any
        // data that was passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
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

        $unit = new ListOfStrings();

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

        // this test proves that add() calls can be chained
        // together fluently to build up the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha')
            ->add('bravo')
            ->add('charlie');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that strings added via add() always
        // receive sequential integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

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

    #[TestDox('add() can add duplicate strings')]
    public function test_add_can_add_duplicate_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() allows duplicate strings
        // in the list (unlike a set)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('alpha');
        $unit->add('alpha');
        $unit->add('alpha');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'alpha', 'alpha'],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    #[TestDox('add() can add empty strings')]
    public function test_add_can_add_empty_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store empty strings
        // in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('');

        // ----------------------------------------------------------------
        // test the results

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

    #[TestDox('add() accepts various string formats')]
    #[DataProvider('provideStringVariants')]
    public function test_add_accepts_various_string_formats(
        string $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() correctly stores strings
        // containing various special characters and formats

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$input], $unit->toArray());
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

        $unit = new ListOfStrings();

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

        // this test proves that toArray() returns all the strings
        // stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

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

        $unit = new ListOfStrings();
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

        // this test proves that count() returns 0 when the list
        // contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

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
        // of strings stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

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

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

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

        $unit = new ListOfStrings();
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

        $unit = new ListOfStrings(['alpha', 'bravo']);

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
        $unit = new ListOfStrings($expectedData);
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

        $unit = new ListOfStrings();
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

        // this test proves that iterating over a ListOfStrings
        // produces sequential integer keys starting from 0

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);
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

        $unit = new ListOfStrings();
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

        $unit = new ListOfStrings(['alpha', 'bravo']);
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

    #[TestDox('merge() can merge another ListOfStrings')]
    public function test_merge_can_merge_list_of_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // ListOfStrings and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo']);
        $other = new ListOfStrings(['charlie', 'delta']);

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

        $unit = new ListOfStrings(['alpha']);
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

        $unit = new ListOfStrings();
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
        $unit = new ListOfStrings($expectedData);

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

        $unit = new ListOfStrings(['alpha']);

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
        // of another ListOfStrings into this list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha']);
        $other = new ListOfStrings(['bravo', 'charlie']);

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

        $unit = new ListOfStrings(['alpha']);
        $other = new ListOfStrings(['bravo']);
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
        $unit = new ListOfStrings($expectedData);
        $other = new ListOfStrings();

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

    #[TestDox('maybeFirst() returns the first string')]
    public function test_maybe_first_returns_first_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // string in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

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

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first string added via add()')]
    public function test_maybe_first_returns_first_string_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // string that was added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();
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

    #[TestDox('first() returns the first string')]
    public function test_first_returns_first_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the first string
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

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

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfStrings is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last string')]
    public function test_maybe_last_returns_last_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the last string
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

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

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last string added via add()')]
    public function test_maybe_last_returns_last_string_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added string via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();
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

    #[TestDox('last() returns the last string')]
    public function test_last_returns_last_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the last string in
        // the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha', 'bravo', 'charlie']);

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

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfStrings is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new ListOfStrings with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new ListOfStrings
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfStrings::class, $copy);
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
        $unit = new ListOfStrings($originalData);

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

        // this test proves that copying an empty list returns a
        // new, empty ListOfStrings instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

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

    #[TestDox('empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // list has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

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

        $unit = new ListOfStrings(['alpha']);

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
        // string has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();
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
    // applyTrim()
    //
    // ----------------------------------------------------------------

    #[TestDox('applyTrim() removes whitespace from strings in the list')]
    public function test_apply_trim_removes_whitespace_from_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() uses PHP's trim()
        // function to remove whitespace from all strings in the list

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['  alpha  ', '  bravo  ', '  charlie  '];
        $expectedTrimmed = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedTrimmed, $unit->toArray());
    }

    #[TestDox('applyTrim() on list with no spaces leaves strings unchanged')]
    public function test_apply_trim_unchanged_when_no_spaces(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() does not alter strings
        // that don't have leading or trailing whitespace

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('applyTrim() handles empty list')]
    public function test_apply_trim_on_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() works correctly on empty
        // lists

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('applyTrim() handles strings with newlines and tabs')]
    public function test_apply_trim_removes_newlines_and_tabs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() removes newline and tab
        // characters

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ["
alpha", "bravo	", "charlie

"];
        $expectedTrimmed = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedTrimmed, $unit->toArray());
    }

    #[TestDox('applyTrim() handles empty strings')]
    public function test_apply_trim_preserves_empty_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() correctly handles empty
        // strings

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['', 'alpha', '', 'bravo', ''];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('applyTrim() can be chained with other methods')]
    public function test_apply_trim_supports_method_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() returns $this for fluent
        // method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ', '  bravo  ']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('applyTrim() can be used fluently with add()')]
    public function test_apply_trim_with_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() works correctly with
        // strings added dynamically via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('  bravo  ')->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    #[TestDox('applyTrim() with custom characters strips only those characters')]
    public function test_apply_trim_with_custom_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a custom $characters parameter
        // is provided, applyTrim() only strips those specified
        // characters from the strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/path/', '//double//', '/single']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['path', 'double', 'single'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyTrim() with custom characters does not strip whitespace')]
    public function test_apply_trim_with_custom_characters_preserves_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when custom characters are provided,
        // default whitespace is not stripped — only the specified
        // characters are removed

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/ path /', '/ hello /']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [' path ', ' hello '],
            $unit->toArray(),
        );
    }

    #[TestDox('applyTrim() with custom characters handles empty list')]
    public function test_apply_trim_with_custom_characters_on_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() with custom characters
        // works correctly on an empty list without error

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('applyTrim() with custom characters returns $this for chaining')]
    public function test_apply_trim_with_custom_characters_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() returns $this for fluent
        // method chaining when custom characters are provided

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/path/', '/other/']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->applyTrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // applyLtrim()
    //
    // ----------------------------------------------------------------

    #[TestDox('applyLtrim() removes leading whitespace from strings')]
    public function test_apply_ltrim_removes_leading_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() removes leading
        // whitespace from all strings in the list, while preserving
        // trailing whitespace

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ', '  bravo  ', '  charlie  ']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha  ', 'bravo  ', 'charlie  '],
            $unit->toArray(),
        );
    }

    #[TestDox('applyLtrim() preserves trailing whitespace')]
    public function test_apply_ltrim_preserves_trailing_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() only removes leading
        // whitespace and does not affect trailing whitespace

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['alpha  ', 'bravo  ']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha  ', 'bravo  '],
            $unit->toArray(),
        );
    }

    #[TestDox('applyLtrim() on list with no leading spaces leaves strings unchanged')]
    public function test_apply_ltrim_unchanged_when_no_leading_spaces(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() does not alter strings
        // that don't have leading whitespace

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('applyLtrim() handles empty list')]
    public function test_apply_ltrim_on_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() works correctly on
        // empty lists

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('applyLtrim() handles strings with leading newlines and tabs')]
    public function test_apply_ltrim_removes_leading_newlines_and_tabs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() removes leading newline
        // and tab characters

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings([
            "\nalpha",
            "\tbravo",
            "\n\tcharlie",
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyLtrim() handles empty strings')]
    public function test_apply_ltrim_preserves_empty_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() correctly handles empty
        // strings

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['', 'alpha', '', 'bravo', ''];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('applyLtrim() returns $this for method chaining')]
    public function test_apply_ltrim_supports_method_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() returns $this for
        // fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ', '  bravo  ']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('applyLtrim() can be used fluently with add()')]
    public function test_apply_ltrim_with_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() works correctly with
        // strings added dynamically via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('  bravo  ')->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha  ', 'bravo  '],
            $unit->toArray(),
        );
    }

    #[TestDox('applyLtrim() with custom characters strips only those characters from the left')]
    public function test_apply_ltrim_with_custom_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a custom $characters parameter
        // is provided, applyLtrim() only strips those specified
        // characters from the left side of the strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/path/', '//double//', '/single']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['path/', 'double//', 'single'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyLtrim() with custom characters does not strip whitespace')]
    public function test_apply_ltrim_with_custom_characters_preserves_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when custom characters are provided,
        // default whitespace is not stripped — only the specified
        // characters are removed from the left

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/ path /', '/ hello /']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [' path /', ' hello /'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyLtrim() with custom characters handles empty list')]
    public function test_apply_ltrim_with_custom_characters_on_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() with custom characters
        // works correctly on an empty list without error

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('applyLtrim() with custom characters returns $this for chaining')]
    public function test_apply_ltrim_with_custom_characters_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyLtrim() returns $this for
        // fluent method chaining when custom characters are provided

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/path/', '/other/']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->applyLtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // applyRtrim()
    //
    // ----------------------------------------------------------------

    #[TestDox('applyRtrim() removes trailing whitespace from strings')]
    public function test_apply_rtrim_removes_trailing_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() removes trailing
        // whitespace from all strings in the list, while preserving
        // leading whitespace

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ', '  bravo  ', '  charlie  ']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['  alpha', '  bravo', '  charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyRtrim() preserves leading whitespace')]
    public function test_apply_rtrim_preserves_leading_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() only removes trailing
        // whitespace and does not affect leading whitespace

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha', '  bravo']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['  alpha', '  bravo'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyRtrim() on list with no trailing spaces leaves strings unchanged')]
    public function test_apply_rtrim_unchanged_when_no_trailing_spaces(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() does not alter strings
        // that don't have trailing whitespace

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('applyRtrim() handles empty list')]
    public function test_apply_rtrim_on_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() works correctly on
        // empty lists

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('applyRtrim() handles strings with trailing newlines and tabs')]
    public function test_apply_rtrim_removes_trailing_newlines_and_tabs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() removes trailing
        // newline and tab characters

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings([
            "alpha\n",
            "bravo\t",
            "charlie\n\t",
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyRtrim() handles empty strings')]
    public function test_apply_rtrim_preserves_empty_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() correctly handles empty
        // strings

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = ['', 'alpha', '', 'bravo', ''];
        $unit = new ListOfStrings($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('applyRtrim() returns $this for method chaining')]
    public function test_apply_rtrim_supports_method_chaining(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() returns $this for
        // fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ', '  bravo  ']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('applyRtrim() can be used fluently with add()')]
    public function test_apply_rtrim_with_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() works correctly with
        // strings added dynamically via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  alpha  ']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('  bravo  ')->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['  alpha', '  bravo'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyRtrim() with custom characters strips only those characters from the right')]
    public function test_apply_rtrim_with_custom_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a custom $characters parameter
        // is provided, applyRtrim() only strips those specified
        // characters from the right side of the strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/path/', '//double//', 'single/']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['/path', '//double', 'single'],
            $unit->toArray(),
        );
    }

    #[TestDox('applyRtrim() with custom characters does not strip whitespace')]
    public function test_apply_rtrim_with_custom_characters_preserves_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when custom characters are provided,
        // default whitespace is not stripped — only the specified
        // characters are removed from the right

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/ path /', '/ hello /']);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['/ path ', '/ hello '],
            $unit->toArray(),
        );
    }

    #[TestDox('applyRtrim() with custom characters handles empty list')]
    public function test_apply_rtrim_with_custom_characters_on_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() with custom characters
        // works correctly on an empty list without error

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('applyRtrim() with custom characters returns $this for chaining')]
    public function test_apply_rtrim_with_custom_characters_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyRtrim() returns $this for
        // fluent method chaining when custom characters are provided

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['/path/', '/other/']);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->applyRtrim(characters: '/');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // -----------------------------------------------------------------------

    #[TestDox('getCollectionTypeAsString() returns "ListOfStrings"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "ListOfStrings" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('ListOfStrings', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // -----------------------------------------------------
    //
    // ----------------------------------------------------------------

    #[TestDox('List with one string: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a list with exactly one string,
        // both first() and last() return that same string

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['only-item']);

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

        $unit = new ListOfStrings();
        $other = new ListOfStrings(['delta']);

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
