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
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Lists namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ListOfCallables::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Lists',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(ListOfCallables::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsList')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(ListOfCallables::class);
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
        $reflection = new \ReflectionClass(ListOfCallables::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ListOfCallables::class) {
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
     * ListOfCallables
     */
    #[TestDox('::__construct() creates an empty list')]
    public function test_can_instantiate_empty_list(): void
    {
        // nothing to do

        $unit = new ListOfCallables();

        $this->assertInstanceOf(ListOfCallables::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * this test proves that we can create a new ListOfCallables
     * and seed it with an initial array of callables
     */
    #[TestDox('::__construct() accepts initial callables')]
    public function test_can_instantiate_with_initial_callables(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedCallables = [$fn1, $fn2];

        $unit = new ListOfCallables($expectedCallables);

        $this->assertCount(2, $unit);
        $this->assertSame($expectedCallables, $unit->toArray());
    }

    /**
     * this test proves that when constructed with a list-style
     * array, the keys remain sequential integers
     */
    #[TestDox('::__construct() preserves sequential integer keys')]
    public function test_constructor_preserves_sequential_integer_keys(): void
    {
        $callables = [
            fn() => 'alpha',
            fn() => 'bravo',
            fn() => 'charlie',
        ];

        $unit = new ListOfCallables($callables);
        $actualData = $unit->toArray();

        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that add() appends a callable to the
     * end of the list with a sequential integer key
     */
    #[TestDox('->add() appends a callable to the list')]
    public function test_add_appends_callable(): void
    {
        $unit = new ListOfCallables();
        $fn = fn() => 'hello';

        $unit->add($fn);

        $this->assertSame([$fn], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that calling add() multiple times
     * appends each callable in the order they were added
     */
    #[TestDox('->add() appends multiple callables in order')]
    public function test_add_appends_multiple_callables_in_order(): void
    {
        $unit = new ListOfCallables();
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;

        $unit->add($fn1);
        $unit->add($fn2);
        $unit->add($fn3);

        $this->assertSame([$fn1, $fn2, $fn3], $unit->toArray());
    }

    /**
     * this test proves that add() appends a callable after any
     * data that was passed into the constructor
     */
    #[TestDox('->add() appends to existing data')]
    public function test_add_appends_to_existing_data(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $fn3 = fn() => 'charlie';
        $unit = new ListOfCallables([$fn1, $fn2]);

        $unit->add($fn3);

        $this->assertSame([$fn1, $fn2, $fn3], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() returns the same collection
     * instance for fluent method chaining
     */
    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new ListOfCallables();

        $result = $unit->add(fn() => 'hello');

        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that add() calls can be chained
     * together fluently to build up the list
     */
    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new ListOfCallables();
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;

        $unit->add($fn1)
            ->add($fn2)
            ->add($fn3);

        $this->assertSame([$fn1, $fn2, $fn3], $unit->toArray());
    }

    /**
     * this test proves that callables added via add() always
     * receive sequential integer keys
     */
    #[TestDox('->add() maintains sequential integer keys')]
    public function test_add_maintains_sequential_integer_keys(): void
    {
        $unit = new ListOfCallables();

        $unit->add(fn() => 1);
        $unit->add(fn() => 2);
        $unit->add(fn() => 3);

        $actualData = $unit->toArray();
        $this->assertSame([0, 1, 2], array_keys($actualData));
    }

    /**
     * this test proves that add() allows the same callable
     * instance to appear multiple times in the list
     */
    #[TestDox('->add() can add the same callable instance twice')]
    public function test_add_can_add_same_callable_twice(): void
    {
        $unit = new ListOfCallables();
        $fn = fn() => 'hello';

        $unit->add($fn);
        $unit->add($fn);
        $unit->add($fn);

        $this->assertSame([$fn, $fn, $fn], $unit->toArray());
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that add() can store a Closure
     */
    #[TestDox('->add() accepts a closure')]
    public function test_add_accepts_closure(): void
    {
        $unit = new ListOfCallables();
        $fn = function (string $name): string {
            return 'hello ' . $name;
        };

        $unit->add($fn);

        $this->assertCount(1, $unit);
        $this->assertInstanceOf(Closure::class, $unit->first());
    }

    /**
     * this test proves that add() can store a callable
     * specified as a function name string
     */
    #[TestDox('->add() accepts a named function string')]
    public function test_add_accepts_named_function_string(): void
    {
        $unit = new ListOfCallables();

        $unit->add('strtolower');

        $this->assertCount(1, $unit);
        $this->assertSame('strtolower', $unit->first());
    }

    /**
     * this test proves that add() can store a callable
     * specified as a [class, method] static method array
     */
    #[TestDox('->add() accepts a static method array')]
    public function test_add_accepts_static_method_array(): void
    {
        $unit = new ListOfCallables();
        $callable = [self::class, 'provideCallableVariants'];

        $unit->add($callable);

        $this->assertCount(1, $unit);
        $this->assertSame($callable, $unit->first());
    }

    /**
     * this test proves that add() can store a callable
     * specified as an [$object, method] instance method array
     */
    #[TestDox('->add() accepts an instance method array')]
    public function test_add_accepts_instance_method_array(): void
    {
        $unit = new ListOfCallables();
        $callable = [$this, 'test_add_accepts_instance_method_array'];

        $unit->add($callable);

        $this->assertCount(1, $unit);
        $this->assertSame($callable, $unit->first());
    }

    /**
     * this test proves that add() can store an invokable
     * object (one with an __invoke method)
     */
    #[TestDox('->add() accepts an invokable object')]
    public function test_add_accepts_invokable_object(): void
    {
        $unit = new ListOfCallables();
        $invokable = new class {
            public function __invoke(): string
            {
                return 'invoked';
            }
        };

        $unit->add($invokable);

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

    /**
     * this test proves that add() correctly stores callables
     * in various formats
     */
    #[TestDox('->add() accepts various callable formats')]
    #[DataProvider('provideCallableVariants')]
    public function test_add_accepts_various_callable_formats(
        callable $input,
    ): void {
        $unit = new ListOfCallables();

        $unit->add($input);

        $this->assertCount(1, $unit);
        $this->assertTrue(is_callable($unit->first()));
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
        $unit = new ListOfCallables();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /**
     * this test proves that toArray() returns all the callables
     * stored in the list
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);

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
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);

        $actualResult = $unit->toArray();

        $this->assertSame([$fn1, $fn2], $actualResult);
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
        $unit = new ListOfCallables();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /**
     * this test proves that count() returns the correct number
     * of callables stored in the list
     */
    #[TestDox('->count() returns number of items in list')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new ListOfCallables([
            fn() => 1,
            fn() => 2,
            fn() => 3,
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
        $unit = new ListOfCallables([
            fn() => 1,
            fn() => 2,
            fn() => 3,
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
        $unit = new ListOfCallables();
        $unit->add(fn() => 1);
        $unit->add(fn() => 2);

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
        $unit = new ListOfCallables([fn() => 1]);

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
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);
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
        $unit = new ListOfCallables();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * this test proves that iterating over a ListOfCallables
     * produces sequential integer keys starting from 0
     */
    #[TestDox('Iteration produces sequential integer keys')]
    public function test_iteration_produces_sequential_integer_keys(): void
    {
        $unit = new ListOfCallables([
            fn() => 1,
            fn() => 2,
            fn() => 3,
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
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);
        $actualData = [];

        foreach ($unit as $value) {
            $actualData[] = $value;
        }

        $this->assertSame([$fn1, $fn2], $actualData);
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
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $fn4 = fn() => 4;
        $unit = new ListOfCallables([$fn1, $fn2]);

        $result = $unit->merge([$fn3, $fn4]);

        $this->assertSame(
            [$fn1, $fn2, $fn3, $fn4],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that merge() can accept another
     * ListOfCallables and merge its contents
     */
    #[TestDox('->merge() can merge another ListOfCallables')]
    public function test_merge_can_merge_list_of_callables(): void
    {
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $fn4 = fn() => 4;
        $unit = new ListOfCallables([$fn1, $fn2]);
        $other = new ListOfCallables([$fn3, $fn4]);

        $result = $unit->merge($other);

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

    /**
     * this test proves that mergeArray() appends the given
     * array's contents to the list's data
     */
    #[TestDox('->mergeArray() adds array items to the list')]
    public function test_merge_array_adds_items(): void
    {
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $unit = new ListOfCallables([$fn1]);

        $result = $unit->mergeArray([$fn2, $fn3]);

        $this->assertSame(
            [$fn1, $fn2, $fn3],
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
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $unit = new ListOfCallables();

        $unit->mergeArray([$fn1, $fn2]);

        $this->assertSame([$fn1, $fn2], $unit->toArray());
    }

    /**
     * this test proves that merging an empty array does not
     * alter the list's existing data
     */
    #[TestDox('->mergeArray() with empty array leaves list unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);

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
        $unit = new ListOfCallables([fn() => 1]);

        $result = $unit->mergeArray([fn() => 2]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that mergeSelf() appends the contents
     * of another ListOfCallables into this list
     */
    #[TestDox('->mergeSelf() merges another list into this one')]
    public function test_merge_self_merges_list(): void
    {
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $unit = new ListOfCallables([$fn1]);
        $other = new ListOfCallables([$fn2, $fn3]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [$fn1, $fn2, $fn3],
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
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $unit = new ListOfCallables([$fn1]);
        $other = new ListOfCallables([$fn2]);

        $unit->mergeSelf($other);

        $this->assertSame([$fn2], $other->toArray());
    }

    /**
     * this test proves that merging an empty list does not
     * alter the existing data
     */
    #[TestDox('->mergeSelf() with empty source leaves list unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);
        $other = new ListOfCallables();

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
     * callable in the list when it is not empty
     */
    #[TestDox('->maybeFirst() returns the first callable')]
    public function test_maybe_first_returns_first_callable(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame($fn1, $actualResult);
    }

    /**
     * this test proves that maybeFirst() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeFirst() returns null for empty list')]
    public function test_maybe_first_returns_null_for_empty_list(): void
    {
        $unit = new ListOfCallables();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeFirst() returns the first
     * callable that was added via the add() method
     */
    #[TestDox('->maybeFirst() returns the first callable added via add()')]
    public function test_maybe_first_returns_first_callable_added_via_add(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);

        $actualResult = $unit->maybeFirst();

        $this->assertSame($fn1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that first() returns the first callable
     * in the list when it is not empty
     */
    #[TestDox('->first() returns the first callable')]
    public function test_first_returns_first_callable(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        $actualResult = $unit->first();

        $this->assertSame($fn1, $actualResult);
    }

    /**
     * this test proves that first() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->first() throws RuntimeException for empty list')]
    public function test_first_throws_for_empty_list(): void
    {
        $unit = new ListOfCallables();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfCallables is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeLast() returns the last
     * callable in the list when it is not empty
     */
    #[TestDox('->maybeLast() returns the last callable')]
    public function test_maybe_last_returns_last_callable(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        $actualResult = $unit->maybeLast();

        $this->assertSame($fn2, $actualResult);
    }

    /**
     * this test proves that maybeLast() returns null when the
     * list is empty, rather than throwing an exception
     */
    #[TestDox('->maybeLast() returns null for empty list')]
    public function test_maybe_last_returns_null_for_empty_list(): void
    {
        $unit = new ListOfCallables();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeLast() returns the most
     * recently added callable via add()
     */
    #[TestDox('->maybeLast() returns the last callable added via add()')]
    public function test_maybe_last_returns_last_callable_added_via_add(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables();
        $unit->add($fn1);
        $unit->add($fn2);

        $actualResult = $unit->maybeLast();

        $this->assertSame($fn2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that last() returns the last callable
     * in the list when it is not empty
     */
    #[TestDox('->last() returns the last callable')]
    public function test_last_returns_last_callable(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $unit = new ListOfCallables([$fn1, $fn2]);

        $actualResult = $unit->last();

        $this->assertSame($fn2, $actualResult);
    }

    /**
     * this test proves that last() throws a RuntimeException
     * when the list is empty
     */
    #[TestDox('->last() throws RuntimeException for empty list')]
    public function test_last_throws_for_empty_list(): void
    {
        $unit = new ListOfCallables();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ListOfCallables is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that copy() returns a new ListOfCallables
     * instance containing the same data as the original
     */
    #[TestDox('->copy() returns a new ListOfCallables with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $expectedData = [$fn1, $fn2];
        $unit = new ListOfCallables($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(ListOfCallables::class, $copy);
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
        $fn1 = fn() => 'alpha';
        $fn2 = fn() => 'bravo';
        $fn3 = fn() => 'charlie';
        $originalData = [$fn1, $fn2];
        $unit = new ListOfCallables($originalData);

        $copy = $unit->copy();
        $copy->add($fn3);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [$fn1, $fn2, $fn3],
            $copy->toArray(),
        );
    }

    /**
     * this test proves that copying an empty list returns a
     * new, empty ListOfCallables instance
     */
    #[TestDox('->copy() of empty list returns empty list')]
    public function test_copy_of_empty_list(): void
    {
        $unit = new ListOfCallables();

        $copy = $unit->copy();

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

    /**
     * this test proves that empty() returns true when the
     * list has no data
     */
    #[TestDox('->empty() returns true for empty list')]
    public function test_empty_returns_true_for_empty_list(): void
    {
        $unit = new ListOfCallables();

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
        $unit = new ListOfCallables([fn() => 'hello']);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that empty() returns false after a
     * callable has been added via add()
     */
    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        $unit = new ListOfCallables();
        $unit->add(fn() => 'hello');

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
     * "ListOfCallables" (just the class name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns "ListOfCallables"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new ListOfCallables();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('ListOfCallables', $actualResult);
    }

    // ================================================================
    //
    // Single-item lists
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that for a list with exactly one
     * callable, both first() and last() return that callable
     */
    #[TestDox('List with one callable: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $fn = fn() => 'only';
        $unit = new ListOfCallables([$fn]);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame($fn, $first);
        $this->assertSame($fn, $last);
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
        $fn1 = fn() => 1;
        $fn2 = fn() => 2;
        $fn3 = fn() => 3;
        $fn4 = fn() => 4;
        $unit = new ListOfCallables();
        $other = new ListOfCallables([$fn4]);

        $unit->add($fn1)
            ->mergeArray([$fn2, $fn3])
            ->mergeSelf($other);

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

    /**
     * this test proves that callables stored in the list
     * remain invokable after retrieval
     */
    #[TestDox('Stored callables can be invoked')]
    public function test_stored_callables_can_be_invoked(): void
    {
        $unit = new ListOfCallables([
            fn(int $x) => $x * 2,
            fn(int $x) => $x + 10,
        ]);

        $doubler = $unit->first();
        $adder = $unit->last();

        $this->assertSame(10, $doubler(5));
        $this->assertSame(15, $adder(5));
    }

    /**
     * this test proves that closures that capture variables
     * retain their captured state after storage and retrieval
     */
    #[TestDox('Stored callables preserve their closures')]
    public function test_stored_callables_preserve_closures(): void
    {
        $multiplier = 3;
        $fn = fn(int $x) => $x * $multiplier;
        $unit = new ListOfCallables([$fn]);

        $retrieved = $unit->first();

        $this->assertSame(15, $retrieved(5));
    }

    /**
     * this test proves that all values retrieved from the
     * list are callable
     */
    #[TestDox('All stored values are callable')]
    public function test_all_stored_values_are_callable(): void
    {
        $unit = new ListOfCallables([
            fn() => 'closure',
            'strtolower',
            [self::class, 'provideCallableVariants'],
        ]);

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertTrue(is_callable($value));
        }
    }
}
