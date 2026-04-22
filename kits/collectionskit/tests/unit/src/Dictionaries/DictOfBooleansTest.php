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

namespace StusDevKit\CollectionsKit\Tests\Unit\Dictionaries;

use ArrayIterator;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans;

#[TestDox('DictOfBooleans')]
class DictOfBooleansTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Dictionaries namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(DictOfBooleans::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Dictionaries',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(DictOfBooleans::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsDict')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(DictOfBooleans::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares isTrue and isFalse as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(DictOfBooleans::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === DictOfBooleans::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['isFalse', 'isTrue'], $ownMethods);
    }

    #[TestDox('::isTrue() signature: isTrue(mixed $name): bool')]
    public function test_isTrue_signature(): void
    {
        $method = new \ReflectionMethod(DictOfBooleans::class, 'isTrue');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['name'], $paramNames);
    }

    #[TestDox('::isFalse() signature: isFalse(mixed $name): bool')]
    public function test_isFalse_signature(): void
    {
        $method = new \ReflectionMethod(DictOfBooleans::class, 'isFalse');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['name'], $paramNames);
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that we can create a new, empty
     * DictOfBooleans
     */
    #[TestDox('::__construct() creates an empty dict')]
    public function test_can_instantiate_empty_dict(): void
    {
        // nothing to do

        $unit = new DictOfBooleans();

        $this->assertInstanceOf(DictOfBooleans::class, $unit);
        $this->assertCount(0, $unit);
    }

    /**
     * this test proves that we can create a DictOfBooleans
     * and seed it with an initial associative array of flags
     */
    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        $expectedData = [
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ];

        $unit = new DictOfBooleans($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * this test proves that when constructed with an associative
     * array, the string keys are preserved
     */
    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        $expectedData = [
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ];

        $unit = new DictOfBooleans($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame(
            ['verbose', 'debug', 'dry_run'],
            array_keys($actualData),
        );
    }

    /**
     * this test proves that DictOfBooleans can also be
     * constructed with integer keys
     */
    #[TestDox('::__construct() accepts integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        $expectedData = [
            10 => true,
            20 => false,
            30 => true,
        ];

        $unit = new DictOfBooleans($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that set() stores a boolean value at
     * the given string key
     */
    #[TestDox('->set() stores a value with a string key')]
    public function test_set_stores_value_with_string_key(): void
    {
        $unit = new DictOfBooleans();

        $unit->set(key: 'verbose', value: true);

        $this->assertSame(['verbose' => true], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that set() stores a boolean value at
     * the given integer key
     */
    #[TestDox('->set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        $unit = new DictOfBooleans();

        $unit->set(key: 42, value: true);

        $this->assertSame([42 => true], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that calling set() with an existing key
     * overwrites the previous value
     */
    #[TestDox('->set() overwrites existing value at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $unit->set(key: 'verbose', value: false);

        $this->assertSame(['verbose' => false], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    /**
     * this test proves that set() adds a new key-value pair
     * alongside data passed into the constructor
     */
    #[TestDox('->set() adds to existing data')]
    public function test_set_adds_to_existing_data(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $unit->set(key: 'dry_run', value: true);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    /**
     * this test proves that set() returns the same collection
     * instance for fluent method chaining
     */
    #[TestDox('->set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        $unit = new DictOfBooleans();

        $result = $unit->set(key: 'verbose', value: true);

        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that set() calls can be chained
     * together fluently to build up the dict
     */
    #[TestDox('->set() supports fluent chaining')]
    public function test_set_supports_fluent_chaining(): void
    {
        $unit = new DictOfBooleans();

        $unit->set(key: 'verbose', value: true)
            ->set(key: 'debug', value: false)
            ->set(key: 'dry_run', value: true);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that has() returns true when the dict
     * contains the given string key
     */
    #[TestDox('->has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_string_key(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->has('verbose');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that has() returns true when the dict
     * contains a key whose value is false — has() checks for
     * key existence, not truthiness
     */
    #[TestDox('->has() returns true for existing key with false value')]
    public function test_has_returns_true_for_key_with_false_value(): void
    {
        $unit = new DictOfBooleans(['debug' => false]);

        $actualResult = $unit->has('debug');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that has() returns false when the dict
     * does not contain the given key
     */
    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that has() returns false when the dict
     * is empty
     */
    #[TestDox('->has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that has() detects keys that were added
     * via the set() method
     */
    #[TestDox('->has() returns true for key added via set()')]
    public function test_has_returns_true_for_key_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);

        $actualResult = $unit->has('verbose');

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeGet() returns the boolean
     * stored at the given key when it exists
     */
    #[TestDox('->maybeGet() returns value for existing key')]
    public function test_maybe_get_returns_value_for_existing_key(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $actualResult = $unit->maybeGet('verbose');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that maybeGet() correctly returns a
     * boolean false value, not null, when the key exists
     * and its value is false
     */
    #[TestDox('->maybeGet() returns false value without converting to null')]
    public function test_maybe_get_returns_false_value(): void
    {
        $unit = new DictOfBooleans(['debug' => false]);

        $actualResult = $unit->maybeGet('debug');

        $this->assertFalse($actualResult);
        $this->assertNotSame(null, $actualResult);
    }

    /**
     * this test proves that maybeGet() returns null when the
     * given key does not exist in the dict
     */
    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeGet() returns null when the
     * dict is empty
     */
    #[TestDox('->maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeGet() retrieves values that
     * were stored using the set() method
     */
    #[TestDox('->maybeGet() returns value added via set()')]
    public function test_maybe_get_returns_value_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);

        $actualResult = $unit->maybeGet('verbose');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that maybeGet() returns the most recent
     * value after a key has been overwritten with set()
     */
    #[TestDox('->maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);
        $unit->set(key: 'verbose', value: false);

        $actualResult = $unit->maybeGet('verbose');

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that get() returns the boolean stored at
     * the given key when it exists
     */
    #[TestDox('->get() returns value for existing key')]
    public function test_get_returns_value_for_existing_key(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $actualResult = $unit->get('debug');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that get() throws a RuntimeException
     * when the given key does not exist in the dict
     */
    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfBooleans does not contain missing',
        );

        $unit->get('missing');
    }

    /**
     * this test proves that get() throws a RuntimeException
     * when the dict is empty
     */
    #[TestDox('->get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfBooleans does not contain anything',
        );

        $unit->get('anything');
    }

    /**
     * this test proves that get() retrieves values that were
     * stored using the set() method
     */
    #[TestDox('->get() returns value added via set()')]
    public function test_get_returns_value_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);

        $actualResult = $unit->get('verbose');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that the RuntimeException thrown by
     * get() includes the missing key in its message
     */
    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        $unit = new DictOfBooleans();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfBooleans does not contain my-special-flag',
        );

        $unit->get('my-special-flag');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that toArray() returns an empty array
     * when the dict contains no data
     */
    #[TestDox('->toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    /**
     * this test proves that toArray() returns all the flags
     * stored in the dict, preserving keys
     */
    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = [
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ];
        $unit = new DictOfBooleans($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    /**
     * this test proves that toArray() includes data that was
     * added using the set() method
     */
    #[TestDox('->toArray() returns data added via set()')]
    public function test_to_array_returns_data_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);

        $actualResult = $unit->toArray();

        $this->assertSame(
            ['verbose' => true, 'debug' => false],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that count() returns 0 when the dict
     * contains no data
     */
    #[TestDox('->count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    /**
     * this test proves that count() returns the correct number
     * of flags stored in the dict
     */
    #[TestDox('->count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    /**
     * this test proves that the dict works with PHP's built-in
     * count() function via the Countable interface
     */
    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    /**
     * this test proves that count() correctly reflects items
     * added via the set() method
     */
    #[TestDox('->count() reflects items added via set()')]
    public function test_count_reflects_items_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    /**
     * this test proves that overwriting an existing key via
     * set() does not increase the count
     */
    #[TestDox('->count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $unit->set(key: 'verbose', value: false);

        $this->assertCount(1, $unit);
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
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    /**
     * this test proves that the dict can be used in a foreach
     * loop via the IteratorAggregate interface
     */
    #[TestDox('Dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $expectedData = [
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ];
        $unit = new DictOfBooleans($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * this test proves that iterating over an empty dict does
     * not execute the loop body
     */
    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_dict_produces_no_iterations(): void
    {
        $unit = new DictOfBooleans();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    /**
     * this test proves that iterating over a dict preserves
     * the string keys
     */
    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(
            ['verbose', 'debug', 'dry_run'],
            $actualKeys,
        );
    }

    /**
     * this test proves that iterating over a dict includes
     * items that were added via the set() method
     */
    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            ['verbose' => true, 'debug' => false],
            $actualData,
        );
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that merge() can accept a plain PHP
     * array and merge its contents into the dict
     */
    #[TestDox('->merge() can merge an array into the dict')]
    public function test_merge_can_merge_array(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $result = $unit->merge([
            'debug' => false,
            'dry_run' => true,
        ]);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that merge() can accept another
     * DictOfBooleans and merge its contents
     */
    #[TestDox('->merge() can merge another DictOfBooleans')]
    public function test_merge_can_merge_dict_of_booleans(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);
        $other = new DictOfBooleans([
            'debug' => false,
            'dry_run' => true,
        ]);

        $result = $unit->merge($other);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
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
     * this test proves that mergeArray() adds the given array's
     * key-value pairs to the dict
     */
    #[TestDox('->mergeArray() adds array items to the dict')]
    public function test_merge_array_adds_items(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $result = $unit->mergeArray([
            'debug' => false,
            'dry_run' => true,
        ]);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that mergeArray() works correctly when
     * the dict is initially empty
     */
    #[TestDox('->mergeArray() into empty dict sets the data')]
    public function test_merge_array_into_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $unit->mergeArray([
            'verbose' => true,
            'debug' => false,
        ]);

        $this->assertSame(
            ['verbose' => true, 'debug' => false],
            $unit->toArray(),
        );
    }

    /**
     * this test proves that merging an empty array does not
     * alter the dict's existing data
     */
    #[TestDox('->mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = ['verbose' => true, 'debug' => false];
        $unit = new DictOfBooleans($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * this test proves that when merging an array with matching
     * string keys, the merged values overwrite the originals
     */
    #[TestDox('->mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $unit->mergeArray([
            'debug' => true,
            'dry_run' => false,
        ]);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => true,
                'dry_run' => false,
            ],
            $unit->toArray(),
        );
    }

    /**
     * this test proves that mergeArray() returns the same dict
     * instance for fluent method chaining
     */
    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $result = $unit->mergeArray(['debug' => false]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that mergeSelf() adds the contents
     * of another DictOfBooleans into this dict
     */
    #[TestDox('->mergeSelf() merges another dict into this one')]
    public function test_merge_self_merges_dict(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);
        $other = new DictOfBooleans([
            'debug' => false,
            'dry_run' => true,
        ]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    /**
     * this test proves that the dict being merged from is not
     * modified by the merge operation
     */
    #[TestDox('->mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);
        $other = new DictOfBooleans(['debug' => false]);

        $unit->mergeSelf($other);

        $this->assertSame(['debug' => false], $other->toArray());
    }

    /**
     * this test proves that merging an empty dict does not
     * alter the existing data
     */
    #[TestDox('->mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = ['verbose' => true, 'debug' => false];
        $unit = new DictOfBooleans($expectedData);
        $other = new DictOfBooleans();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    /**
     * this test proves that when merging a dict with matching
     * keys, the merged values overwrite the originals
     */
    #[TestDox('->mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);
        $other = new DictOfBooleans([
            'debug' => true,
            'dry_run' => false,
        ]);

        $unit->mergeSelf($other);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => true,
                'dry_run' => false,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeFirst() returns the value of
     * the first key in the dict
     */
    #[TestDox('->maybeFirst() returns the first flag')]
    public function test_maybe_first_returns_first_flag(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $actualResult = $unit->maybeFirst();

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that maybeFirst() returns null when the
     * dict is empty, rather than throwing an exception
     */
    #[TestDox('->maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeFirst() returns the first
     * flag that was added via the set() method
     */
    #[TestDox('->maybeFirst() returns the first flag added via set()')]
    public function test_maybe_first_returns_first_flag_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);

        $actualResult = $unit->maybeFirst();

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that first() returns the value of the
     * first key in the dict when it is not empty
     */
    #[TestDox('->first() returns the first flag')]
    public function test_first_returns_first_flag(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $actualResult = $unit->first();

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that first() throws a RuntimeException
     * when the dict is empty
     */
    #[TestDox('->first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfBooleans is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that maybeLast() returns the value of
     * the last key in the dict
     */
    #[TestDox('->maybeLast() returns the last flag')]
    public function test_maybe_last_returns_last_flag(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $actualResult = $unit->maybeLast();

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that maybeLast() returns null when the
     * dict is empty, rather than throwing an exception
     */
    #[TestDox('->maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    /**
     * this test proves that maybeLast() returns the most
     * recently added flag via set()
     */
    #[TestDox('->maybeLast() returns the last flag added via set()')]
    public function test_maybe_last_returns_last_flag_added_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);

        $actualResult = $unit->maybeLast();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that last() returns the value of the
     * last key in the dict when it is not empty
     */
    #[TestDox('->last() returns the last flag')]
    public function test_last_returns_last_flag(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $actualResult = $unit->last();

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that last() throws a RuntimeException
     * when the dict is empty
     */
    #[TestDox('->last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfBooleans is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that copy() returns a new DictOfBooleans
     * instance containing the same data as the original
     */
    #[TestDox('->copy() returns a new DictOfBooleans with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = [
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ];
        $unit = new DictOfBooleans($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfBooleans::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    /**
     * this test proves that modifying the copied dict does not
     * affect the original dict's data
     */
    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $originalData = ['verbose' => true, 'debug' => false];
        $unit = new DictOfBooleans($originalData);

        $copy = $unit->copy();
        $copy->set(key: 'dry_run', value: true);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $copy->toArray(),
        );
    }

    /**
     * this test proves that copying an empty dict returns a
     * new, empty DictOfBooleans instance
     */
    #[TestDox('->copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfBooleans::class, $copy);
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
     * dict has no data
     */
    #[TestDox('->empty() returns true for empty dict')]
    public function test_empty_returns_true_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that empty() returns false when the
     * dict contains data
     */
    #[TestDox('->empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_dict(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that empty() returns false after a flag
     * has been added via set()
     */
    #[TestDox('->empty() returns false after set()')]
    public function test_empty_returns_false_after_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);

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
     * "DictOfBooleans" (just the class name without namespace)
     */
    #[TestDox('->getCollectionTypeAsString() returns "DictOfBooleans"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('DictOfBooleans', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that for a dict with exactly one flag,
     * both first() and last() return that same value
     */
    #[TestDox('Dict with one flag: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new DictOfBooleans(['only' => true]);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertTrue($first);
        $this->assertTrue($last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that set() and merge methods can be
     * chained together fluently
     */
    #[TestDox('->set() and merge methods support fluent chaining together')]
    public function test_set_and_merge_support_chaining(): void
    {
        /** @var DictOfBooleans<string> $unit */
        $unit = new DictOfBooleans();
        $other = new DictOfBooleans(['dry_run' => true]);

        $unit->set(key: 'verbose', value: true)
            ->mergeArray(['debug' => false])
            ->mergeSelf($other);

        $this->assertSame(
            [
                'verbose' => true,
                'debug' => false,
                'dry_run' => true,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that get() and maybeGet() return the
     * same boolean value when the key exists
     */
    #[TestDox('->get() and ->maybeGet() return same value for existing key')]
    public function test_get_and_maybe_get_return_same_value(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
        ]);

        $getResult = $unit->get('verbose');
        $maybeGetResult = $unit->maybeGet('verbose');

        $this->assertTrue($getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // isTrue()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that isTrue() returns true when the
     * named flag exists and is set to true
     */
    #[TestDox('->isTrue() returns true for flag set to true')]
    public function test_is_true_returns_true_for_true_flag(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->isTrue('verbose');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that isTrue() returns false when the
     * named flag exists and is set to false
     */
    #[TestDox('->isTrue() returns false for flag set to false')]
    public function test_is_true_returns_false_for_false_flag(): void
    {
        $unit = new DictOfBooleans(['debug' => false]);

        $actualResult = $unit->isTrue('debug');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that isTrue() returns false when the
     * named flag does not exist in the dict
     */
    #[TestDox('->isTrue() returns false for non-existent flag')]
    public function test_is_true_returns_false_for_non_existent_flag(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->isTrue('missing');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that isTrue() returns false when the
     * dict is empty
     */
    #[TestDox('->isTrue() returns false for empty dict')]
    public function test_is_true_returns_false_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->isTrue('anything');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that isTrue() returns the correct
     * result for flags that were added via set()
     */
    #[TestDox('->isTrue() reflects value set via set()')]
    public function test_is_true_reflects_value_set_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);

        $verboseResult = $unit->isTrue('verbose');
        $debugResult = $unit->isTrue('debug');

        $this->assertTrue($verboseResult);
        $this->assertFalse($debugResult);
    }

    /**
     * this test proves that isTrue() returns the correct
     * result after a flag has been overwritten via set()
     */
    #[TestDox('->isTrue() reflects overwritten value')]
    public function test_is_true_reflects_overwritten_value(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);
        $unit->set(key: 'verbose', value: false);

        $actualResult = $unit->isTrue('verbose');

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // isFalse()
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that isFalse() returns true when the
     * named flag exists and is set to false
     */
    #[TestDox('->isFalse() returns true for flag set to false')]
    public function test_is_false_returns_true_for_false_flag(): void
    {
        $unit = new DictOfBooleans(['debug' => false]);

        $actualResult = $unit->isFalse('debug');

        $this->assertTrue($actualResult);
    }

    /**
     * this test proves that isFalse() returns false when the
     * named flag exists and is set to true
     */
    #[TestDox('->isFalse() returns false for flag set to true')]
    public function test_is_false_returns_false_for_true_flag(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $actualResult = $unit->isFalse('verbose');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that isFalse() returns false when the
     * named flag does not exist in the dict
     */
    #[TestDox('->isFalse() returns false for non-existent flag')]
    public function test_is_false_returns_false_for_non_existent_flag(): void
    {
        $unit = new DictOfBooleans(['debug' => false]);

        $actualResult = $unit->isFalse('missing');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that isFalse() returns false when the
     * dict is empty
     */
    #[TestDox('->isFalse() returns false for empty dict')]
    public function test_is_false_returns_false_for_empty_dict(): void
    {
        $unit = new DictOfBooleans();

        $actualResult = $unit->isFalse('anything');

        $this->assertFalse($actualResult);
    }

    /**
     * this test proves that isFalse() returns the correct
     * result for flags that were added via set()
     */
    #[TestDox('->isFalse() reflects value set via set()')]
    public function test_is_false_reflects_value_set_via_set(): void
    {
        $unit = new DictOfBooleans();
        $unit->set(key: 'verbose', value: true);
        $unit->set(key: 'debug', value: false);

        $verboseResult = $unit->isFalse('verbose');
        $debugResult = $unit->isFalse('debug');

        $this->assertFalse($verboseResult);
        $this->assertTrue($debugResult);
    }

    /**
     * this test proves that isFalse() returns the correct
     * result after a flag has been overwritten via set()
     */
    #[TestDox('->isFalse() reflects overwritten value')]
    public function test_is_false_reflects_overwritten_value(): void
    {
        $unit = new DictOfBooleans(['debug' => false]);
        $unit->set(key: 'debug', value: true);

        $actualResult = $unit->isFalse('debug');

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // isTrue() and isFalse() consistency
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that for any existing flag, isTrue()
     * and isFalse() always return opposite values
     */
    #[TestDox('->isTrue() and ->isFalse() are opposites for existing flags')]
    public function test_is_true_and_is_false_are_opposites(): void
    {
        $unit = new DictOfBooleans([
            'enabled' => true,
            'disabled' => false,
        ]);

        // nothing to do — we test both methods on each key

        $this->assertTrue($unit->isTrue('enabled'));
        $this->assertFalse($unit->isFalse('enabled'));

        $this->assertFalse($unit->isTrue('disabled'));
        $this->assertTrue($unit->isFalse('disabled'));
    }

    /**
     * this test proves that for a non-existent flag, both
     * isTrue() and isFalse() return false — the flag is
     * neither true nor false, it simply does not exist
     */
    #[TestDox('->isTrue() and ->isFalse() both return false for missing flags')]
    public function test_is_true_and_is_false_both_false_for_missing(): void
    {
        $unit = new DictOfBooleans(['verbose' => true]);

        $isTrueResult = $unit->isTrue('missing');
        $isFalseResult = $unit->isFalse('missing');

        $this->assertFalse($isTrueResult);
        $this->assertFalse($isFalseResult);
    }

    // ================================================================
    //
    // Boolean-specific behaviour
    //
    // ----------------------------------------------------------------

    /**
     * this test proves that all values retrieved from the
     * dict are booleans
     */
    #[TestDox('All stored values are booleans')]
    public function test_all_stored_values_are_booleans(): void
    {
        $unit = new DictOfBooleans([
            'verbose' => true,
            'debug' => false,
            'dry_run' => true,
        ]);

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertIsBool($value);
        }
    }
}
