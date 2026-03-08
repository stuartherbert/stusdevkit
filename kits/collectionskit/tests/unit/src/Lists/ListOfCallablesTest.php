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
use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Lists\ListOfCallables;

#[TestDox('ListOfCallables')]
class ListOfCallablesTest extends TestCase
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
        // ListOfCallables

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfCallables::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Can instantiate with initial callables')]
    public function test_can_instantiate_with_initial_callables(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new ListOfCallables
        // and seed it with an initial array of callables

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedCallables = [$fn1, $fn2];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfCallables($expectedCallables);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($expectedCallables, $unit->toArray());
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

        $callables = [
            fn() => 'alpha',
            fn() => 'bravo',
            fn() => 'charlie',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ListOfCallables($callables);
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

    #[TestDox('add() appends a callable to the list')]
    public function test_add_appends_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a callable to the
        // end of the list with a sequential integer key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $fn = fn() => 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() appends multiple callables in order')]
    public function test_add_appends_multiple_callables_in_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() multiple times
        // appends each callable in the order they were added

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn1);
        $unit->add($fn2);
        $unit->add($fn3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn1, $fn2, $fn3], $unit->toArray());
    }

    #[TestDox('add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() appends a callable after any
        // data that was passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $fn3 = fn() => 'charlie';
        $unit = new ListOfCallables([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn1, $fn2, $fn3], $unit->toArray());
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

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add(fn() => 'hello');

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

        $unit = new ListOfCallables();
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn1)
            ->add($fn2)
            ->add($fn3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn1, $fn2, $fn3], $unit->toArray());
    }

    #[TestDox('add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that callables added via add() always
        // receive sequential integer keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(fn() => 1);
        $unit->add(fn() => 2);
        $unit->add(fn() => 3);

        // ----------------------------------------------------------------
        // test the results

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    #[TestDox('add() can add the same callable instance twice')]
    public function test_add_can_add_same_callable_twice(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() allows the same callable
        // instance to appear multiple times in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $fn = fn() => 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn);
        $unit->add($fn);
        $unit->add($fn);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn, $fn, $fn], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    #[TestDox('add() accepts a closure')]
    public function test_add_accepts_closure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store a Closure

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $fn = function (string $name): string {
            return 'hello ' . $name;
        };

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertInstanceOf(Closure::class, $unit->first());
    }

    #[TestDox('add() accepts a named function string')]
    public function test_add_accepts_named_function_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store a callable
        // specified as a function name string

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add('strtolower');

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame('strtolower', $unit->first());
    }

    #[TestDox('add() accepts a static method array')]
    public function test_add_accepts_static_method_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store a callable
        // specified as a [class, method] static method array

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $callable = [self::class, 'provideCallableVariants'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($callable);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame($callable, $unit->first());
    }

    #[TestDox('add() accepts an instance method array')]
    public function test_add_accepts_instance_method_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store a callable
        // specified as an [$object, method] instance method array

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $callable = [$this, 'test_add_accepts_instance_method_array'];

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($callable);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertSame($callable, $unit->first());
    }

    #[TestDox('add() accepts an invokable object')]
    public function test_add_accepts_invokable_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() can store an invokable
        // object (one with an __invoke method)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $invokable = new class {
            public function __invoke(): string
            {
                return 'invoked';
            }
        };

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($invokable);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $stored = $unit->first();
        $this->assertSame('invoked', $stored());
    }

    /**
     * @return array<string, array{0: callable}>
     */
    public static function provideCallableVariants(): array
    {
        return [
            'closure' => [fn() => 'closure'],
            'arrow function' => [fn(int $x) => $x * 2],
            'named function' => ['strtolower'],
            'static method array' => [[self::class, 'provideCallableVariants']],
        ];
    }

    #[TestDox('add() accepts various callable formats')]
    #[DataProvider('provideCallableVariants')]
    public function test_add_accepts_various_callable_formats(
        callable $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() correctly stores callables
        // in various formats

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertTrue(is_callable($unit->first()));
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

        $unit = new ListOfCallables();

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

        // this test proves that toArray() returns all the callables
        // stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);

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

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn1, $fn2], $actualResult);
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

        $unit = new ListOfCallables();

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
        // of callables stored in the list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables([
            fn() => 1,
            fn() => 2,
            fn() => 3,
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

        $unit = new ListOfCallables([
            fn() => 1,
            fn() => 2,
            fn() => 3,
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

        $unit = new ListOfCallables();
        $unit->add(fn() => 1);
        $unit->add(fn() => 2);

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

        $unit = new ListOfCallables([fn() => 1]);

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

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);
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

        $unit = new ListOfCallables();
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

        // this test proves that iterating over a ListOfCallables
        // produces sequential integer keys starting from 0

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables([
            fn() => 1,
            fn() => 2,
            fn() => 3,
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn1, $fn2], $actualData);
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $fn4 = fn() => 4;
        $unit = new ListOfCallables([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([$fn3, $fn4]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$fn1, $fn2, $fn3, $fn4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another ListOfCallables')]
    public function test_merge_can_merge_list_of_callables(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // ListOfCallables and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $fn4 = fn() => 4;
        $unit = new ListOfCallables([$fn1, $fn2]);
        $other = new ListOfCallables([$fn3, $fn4]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$fn1, $fn2, $fn3, $fn4],
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $unit = new ListOfCallables([$fn1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([$fn2, $fn3]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$fn1, $fn2, $fn3],
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn1, $fn2], $unit->toArray());
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);

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

        $unit = new ListOfCallables([fn() => 1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([fn() => 2]);

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
        // of another ListOfCallables into this list

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $unit = new ListOfCallables([$fn1]);
        $other = new ListOfCallables([$fn2, $fn3]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$fn1, $fn2, $fn3],
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $unit = new ListOfCallables([$fn1]);
        $other = new ListOfCallables([$fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([$fn2], $other->toArray());
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);
        $other = new ListOfCallables();

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

    #[TestDox('maybeFirst() returns the first callable')]
    public function test_maybe_first_returns_first_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // callable in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn1, $actualResult);
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

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first callable added via add()')]
    public function test_maybe_first_returns_first_callable_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // callable that was added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first callable')]
    public function test_first_returns_first_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the first callable
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn1, $actualResult);
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

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfCallables is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last callable')]
    public function test_maybe_last_returns_last_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the last
        // callable in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn2, $actualResult);
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

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last callable added via add()')]
    public function test_maybe_last_returns_last_callable_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added callable via add()

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last callable')]
    public function test_last_returns_last_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the last callable
        // in the list when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn2, $actualResult);
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

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfCallables is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new ListOfCallables with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new ListOfCallables
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfCallables::class, $copy);
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

        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $fn3 = fn() => 'charlie';
        $originalData = [$fn1, $fn2];
        $unit = new ListOfCallables($originalData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->add($fn3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [$fn1, $fn2, $fn3],
            $copy->toArray(),
        );
    }

    #[TestDox('copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty list returns a
        // new, empty ListOfCallables instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListOfCallables::class, $copy);
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

        $unit = new ListOfCallables();

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

        $unit = new ListOfCallables([fn() => 'hello']);

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
        // callable has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();
        $unit->add(fn() => 'hello');

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

    #[TestDox('getCollectionTypeAsString() returns "ListOfCallables"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "ListOfCallables" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('ListOfCallables', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    #[TestDox('List with one callable: first() and last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a list with exactly one
        // callable, both first() and last() return that callable

        // ----------------------------------------------------------------
        // setup your test

        $fn = fn() => 'only';
        $unit = new ListOfCallables([$fn]);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fn, $first);
        $this->assertSame($fn, $last);
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

        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $fn4 = fn() => 4;
        $unit = new ListOfCallables();
        $other = new ListOfCallables([$fn4]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($fn1)
            ->mergeArray([$fn2, $fn3])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [$fn1, $fn2, $fn3, $fn4],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // Callable-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('Stored callables can be invoked')]
    public function test_stored_callables_can_be_invoked(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that callables stored in the list
        // remain invokable after retrieval

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables([
            fn(int $x) => $x * 2,
            fn(int $x) => $x + 10,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $doubler = $unit->first();
        $adder = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $doubler(5));
        $this->assertSame(15, $adder(5));
    }

    #[TestDox('Stored callables preserve their closures')]
    public function test_stored_callables_preserve_closures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that closures that capture variables
        // retain their captured state after storage and retrieval

        // ----------------------------------------------------------------
        // setup your test

        $multiplier = 3;
        $fn = fn(int $x) => $x * $multiplier;
        $unit = new ListOfCallables([$fn]);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(15, $retrieved(5));
    }

    #[TestDox('All stored values are callable')]
    public function test_all_stored_values_are_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that all values retrieved from the
        // list are callable

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfCallables([
            fn() => 'closure',
            'strtolower',
            [self::class, 'provideCallableVariants'],
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $value) {
            $this->assertTrue(is_callable($value));
        }
    }
}
