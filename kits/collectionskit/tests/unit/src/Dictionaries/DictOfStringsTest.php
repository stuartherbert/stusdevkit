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
use StusDevKit\CollectionsKit\Dictionaries\DictOfStrings;

#[TestDox('DictOfStrings')]
class DictOfStringsTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Dictionaries namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(DictOfStrings::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Dictionaries',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(DictOfStrings::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsDict')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(DictOfStrings::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict::class,
            $parent->getName(),
        );
    }

    #[TestDox('uses the StringTransformations trait')]
    public function test_uses_StringTransformations_trait(): void
    {
        $traits = \class_uses(DictOfStrings::class);
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
    // DictOfStrings declares no methods of its own — everything comes
    // from CollectionAsDict (via AccessibleCollection) or from the
    // StringTransformations trait. PHP reflection reports trait
    // methods as declared by the using class, so the "own" method set
    // here is exactly the trait's public API.
    //
    // ----------------------------------------------------------------

    #[TestDox('declares only trait public methods as its own')]
    public function test_declares_only_trait_public_methods(): void
    {
        $reflection = new \ReflectionClass(DictOfStrings::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === DictOfStrings::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['applyLtrim', 'applyRtrim', 'applyTrim'], $ownMethods);
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty dict')]
    public function test_can_instantiate_empty_dict(): void
    {
        // this test proves that we can create a new, empty
        // DictOfStrings

        $unit = new DictOfStrings();

        $this->assertInstanceOf(DictOfStrings::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // this test proves that we can create a DictOfStrings and
        // seed it with an initial associative array of strings

        $expectedData = [
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ];

        $unit = new DictOfStrings($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        // this test proves that when constructed with an associative
        // array, the string keys are preserved

        $expectedData = [
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ];

        $unit = new DictOfStrings($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame(
            ['host', 'port', 'name'],
            array_keys($actualData),
        );
    }

    #[TestDox('::__construct() accepts integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        // this test proves that DictOfStrings can also be constructed
        // with integer keys

        $expectedData = [
            10 => 'alpha',
            20 => 'bravo',
            30 => 'charlie',
        ];

        $unit = new DictOfStrings($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() stores a value with a string key')]
    public function test_set_stores_value_with_string_key(): void
    {
        // this test proves that set() stores a string value at the
        // given string key

        $unit = new DictOfStrings();

        $unit->set(key: 'host', value: 'localhost');

        $this->assertSame(['host' => 'localhost'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        // this test proves that set() stores a string value at the
        // given integer key

        $unit = new DictOfStrings();

        $unit->set(key: 42, value: 'alpha');

        $this->assertSame([42 => 'alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() overwrites existing value at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        // this test proves that calling set() with an existing key
        // overwrites the previous value

        $unit = new DictOfStrings(['host' => 'localhost']);

        $unit->set(key: 'host', value: '127.0.0.1');

        $this->assertSame(['host' => '127.0.0.1'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() adds to existing data')]
    public function test_set_adds_to_existing_data(): void
    {
        // this test proves that set() adds a new key-value pair
        // alongside data passed into the constructor

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $unit->set(key: 'name', value: 'mydb');

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    #[TestDox('->set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        // this test proves that set() returns the same collection
        // instance for fluent method chaining

        $unit = new DictOfStrings();

        $result = $unit->set(key: 'host', value: 'localhost');

        $this->assertSame($unit, $result);
    }

    #[TestDox('->set() supports fluent chaining')]
    public function test_set_supports_fluent_chaining(): void
    {
        // this test proves that set() calls can be chained together
        // fluently to build up the dict

        $unit = new DictOfStrings();

        $unit->set(key: 'host', value: 'localhost')
            ->set(key: 'port', value: '3306')
            ->set(key: 'name', value: 'mydb');

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_string_key(): void
    {
        // this test proves that has() returns true when the dict
        // contains the given string key

        $unit = new DictOfStrings(['host' => 'localhost']);

        $actualResult = $unit->has('host');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns true for existing key with empty string value')]
    public function test_has_returns_true_for_key_with_empty_string_value(): void
    {
        // this test proves that has() returns true when the dict
        // contains a key whose value is an empty string — has()
        // checks for key existence, not value emptiness

        $unit = new DictOfStrings(['name' => '']);

        $actualResult = $unit->has('name');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        // this test proves that has() returns false when the dict
        // does not contain the given key

        $unit = new DictOfStrings(['host' => 'localhost']);

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_dict(): void
    {
        // this test proves that has() returns false when the dict
        // is empty

        $unit = new DictOfStrings();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns true for key added via set()')]
    public function test_has_returns_true_for_key_added_via_set(): void
    {
        // this test proves that has() detects keys that were added
        // via the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');

        $actualResult = $unit->has('host');

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGet() returns value for existing key')]
    public function test_maybe_get_returns_value_for_existing_key(): void
    {
        // this test proves that maybeGet() returns the string stored
        // at the given key when it exists

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $actualResult = $unit->maybeGet('host');

        $this->assertSame('localhost', $actualResult);
    }

    #[TestDox('->maybeGet() returns empty string without converting to null')]
    public function test_maybe_get_returns_empty_string_value(): void
    {
        // this test proves that maybeGet() correctly returns an
        // empty string value, not null, when the key exists and
        // its value is an empty string

        $unit = new DictOfStrings(['name' => '']);

        $actualResult = $unit->maybeGet('name');

        $this->assertSame('', $actualResult);
        $this->assertNotSame(null, $actualResult);
    }

    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // this test proves that maybeGet() returns null when the
        // given key does not exist in the dict

        $unit = new DictOfStrings(['host' => 'localhost']);

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_dict(): void
    {
        // this test proves that maybeGet() returns null when the
        // dict is empty

        $unit = new DictOfStrings();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns value added via set()')]
    public function test_maybe_get_returns_value_added_via_set(): void
    {
        // this test proves that maybeGet() retrieves values that
        // were stored using the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');

        $actualResult = $unit->maybeGet('host');

        $this->assertSame('localhost', $actualResult);
    }

    #[TestDox('->maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        // this test proves that maybeGet() returns the most recent
        // value after a key has been overwritten with set()

        $unit = new DictOfStrings(['host' => 'localhost']);
        $unit->set(key: 'host', value: '127.0.0.1');

        $actualResult = $unit->maybeGet('host');

        $this->assertSame('127.0.0.1', $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns value for existing key')]
    public function test_get_returns_value_for_existing_key(): void
    {
        // this test proves that get() returns the string stored at
        // the given key when it exists

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $actualResult = $unit->get('port');

        $this->assertSame('3306', $actualResult);
    }

    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the dict

        $unit = new DictOfStrings(['host' => 'localhost']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfStrings does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('->get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_dict(): void
    {
        // this test proves that get() throws a RuntimeException
        // when the dict is empty

        $unit = new DictOfStrings();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfStrings does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('->get() returns value added via set()')]
    public function test_get_returns_value_added_via_set(): void
    {
        // this test proves that get() retrieves values that were
        // stored using the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');

        $actualResult = $unit->get('host');

        $this->assertSame('localhost', $actualResult);
    }

    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        // this test proves that the RuntimeException thrown by
        // get() includes the missing key in its message

        $unit = new DictOfStrings();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfStrings does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_dict(): void
    {
        // this test proves that toArray() returns an empty array
        // when the dict contains no data

        $unit = new DictOfStrings();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        // this test proves that toArray() returns all the strings
        // stored in the dict, preserving keys

        $expectedData = [
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ];
        $unit = new DictOfStrings($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() returns data added via set()')]
    public function test_to_array_returns_data_added_via_set(): void
    {
        // this test proves that toArray() includes data that was
        // added using the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');
        $unit->set(key: 'port', value: '3306');

        $actualResult = $unit->toArray();

        $this->assertSame(
            ['host' => 'localhost', 'port' => '3306'],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_dict(): void
    {
        // this test proves that count() returns 0 when the dict
        // contains no data

        $unit = new DictOfStrings();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        // this test proves that count() returns the correct number
        // of strings stored in the dict

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // this test proves that the dict works with PHP's built-in
        // count() function via the Countable interface

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() reflects items added via set()')]
    public function test_count_reflects_items_added_via_set(): void
    {
        // this test proves that count() correctly reflects items
        // added via the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');
        $unit->set(key: 'port', value: '3306');

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('->count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        // this test proves that overwriting an existing key via
        // set() does not increase the count

        $unit = new DictOfStrings(['host' => 'localhost']);

        $unit->set(key: 'host', value: '127.0.0.1');

        $this->assertCount(1, $unit);
    }

    // ================================================================
    //
    // IteratorAggregate interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIterator() returns an ArrayIterator')]
    public function test_get_iterator_returns_array_iterator(): void
    {
        // this test proves that getIterator() returns an
        // ArrayIterator instance

        $unit = new DictOfStrings(['host' => 'localhost']);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // this test proves that the dict can be used in a foreach
        // loop via the IteratorAggregate interface

        $expectedData = [
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ];
        $unit = new DictOfStrings($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_dict_produces_no_iterations(): void
    {
        // this test proves that iterating over an empty dict does
        // not execute the loop body

        $unit = new DictOfStrings();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        // this test proves that iterating over a dict preserves
        // the string keys

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(
            ['host', 'port', 'name'],
            $actualKeys,
        );
    }

    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        // this test proves that iterating over a dict includes
        // items that were added via the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');
        $unit->set(key: 'port', value: '3306');
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            ['host' => 'localhost', 'port' => '3306'],
            $actualData,
        );
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('->merge() can merge an array into the dict')]
    public function test_merge_can_merge_array(): void
    {
        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the dict

        $unit = new DictOfStrings(['host' => 'localhost']);

        $result = $unit->merge([
            'port' => '3306',
            'name' => 'mydb',
        ]);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another DictOfStrings')]
    public function test_merge_can_merge_dict_of_strings(): void
    {
        // this test proves that merge() can accept another
        // DictOfStrings and merge its contents

        $unit = new DictOfStrings(['host' => 'localhost']);
        $other = new DictOfStrings([
            'port' => '3306',
            'name' => 'mydb',
        ]);

        $result = $unit->merge($other);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
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

    #[TestDox('->mergeArray() adds array items to the dict')]
    public function test_merge_array_adds_items(): void
    {
        // this test proves that mergeArray() adds the given array's
        // key-value pairs to the dict

        $unit = new DictOfStrings(['host' => 'localhost']);

        $result = $unit->mergeArray([
            'port' => '3306',
            'name' => 'mydb',
        ]);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty dict sets the data')]
    public function test_merge_array_into_empty_dict(): void
    {
        // this test proves that mergeArray() works correctly when
        // the dict is initially empty

        $unit = new DictOfStrings();

        $unit->mergeArray([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $this->assertSame(
            ['host' => 'localhost', 'port' => '3306'],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // this test proves that merging an empty array does not
        // alter the dict's existing data

        $expectedData = ['host' => 'localhost', 'port' => '3306'];
        $unit = new DictOfStrings($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        // this test proves that when merging an array with matching
        // string keys, the merged values overwrite the originals

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $unit->mergeArray([
            'port' => '5432',
            'name' => 'mydb',
        ]);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '5432',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // this test proves that mergeArray() returns the same dict
        // instance for fluent method chaining

        $unit = new DictOfStrings(['host' => 'localhost']);

        $result = $unit->mergeArray(['port' => '3306']);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another dict into this one')]
    public function test_merge_self_merges_dict(): void
    {
        // this test proves that mergeSelf() adds the contents of
        // another DictOfStrings into this dict

        $unit = new DictOfStrings(['host' => 'localhost']);
        $other = new DictOfStrings([
            'port' => '3306',
            'name' => 'mydb',
        ]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // this test proves that the dict being merged from is not
        // modified by the merge operation

        $unit = new DictOfStrings(['host' => 'localhost']);
        $other = new DictOfStrings(['port' => '3306']);

        $unit->mergeSelf($other);

        $this->assertSame(['port' => '3306'], $other->toArray());
    }

    #[TestDox('->mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // this test proves that merging an empty dict does not
        // alter the existing data

        $expectedData = ['host' => 'localhost', 'port' => '3306'];
        $unit = new DictOfStrings($expectedData);
        $other = new DictOfStrings();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        // this test proves that when merging a dict with matching
        // keys, the merged values overwrite the originals

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);
        $other = new DictOfStrings([
            'port' => '5432',
            'name' => 'mydb',
        ]);

        $unit->mergeSelf($other);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '5432',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first string')]
    public function test_maybe_first_returns_first_string(): void
    {
        // this test proves that maybeFirst() returns the value of
        // the first key in the dict

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame('localhost', $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_dict(): void
    {
        // this test proves that maybeFirst() returns null when the
        // dict is empty, rather than throwing an exception

        $unit = new DictOfStrings();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeFirst() returns the first string added via set()')]
    public function test_maybe_first_returns_first_string_added_via_set(): void
    {
        // this test proves that maybeFirst() returns the first
        // string that was added via the set() method

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');
        $unit->set(key: 'port', value: '3306');

        $actualResult = $unit->maybeFirst();

        $this->assertSame('localhost', $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('->first() returns the first string')]
    public function test_first_returns_first_string(): void
    {
        // this test proves that first() returns the value of the
        // first key in the dict when it is not empty

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $actualResult = $unit->first();

        $this->assertSame('localhost', $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_dict(): void
    {
        // this test proves that first() throws a RuntimeException
        // when the dict is empty

        $unit = new DictOfStrings();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfStrings is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last string')]
    public function test_maybe_last_returns_last_string(): void
    {
        // this test proves that maybeLast() returns the value of
        // the last key in the dict

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $actualResult = $unit->maybeLast();

        $this->assertSame('3306', $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_dict(): void
    {
        // this test proves that maybeLast() returns null when the
        // dict is empty, rather than throwing an exception

        $unit = new DictOfStrings();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeLast() returns the last string added via set()')]
    public function test_maybe_last_returns_last_string_added_via_set(): void
    {
        // this test proves that maybeLast() returns the most
        // recently added string via set()

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');
        $unit->set(key: 'port', value: '3306');

        $actualResult = $unit->maybeLast();

        $this->assertSame('3306', $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('->last() returns the last string')]
    public function test_last_returns_last_string(): void
    {
        // this test proves that last() returns the value of the
        // last key in the dict when it is not empty

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $actualResult = $unit->last();

        $this->assertSame('3306', $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_dict(): void
    {
        // this test proves that last() throws a RuntimeException
        // when the dict is empty

        $unit = new DictOfStrings();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfStrings is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new DictOfStrings with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // this test proves that copy() returns a new DictOfStrings
        // instance containing the same data as the original

        $expectedData = [
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ];
        $unit = new DictOfStrings($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfStrings::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // this test proves that modifying the copied dict does not
        // affect the original dict's data

        $originalData = ['host' => 'localhost', 'port' => '3306'];
        $unit = new DictOfStrings($originalData);

        $copy = $unit->copy();
        $copy->set(key: 'name', value: 'mydb');

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $copy->toArray(),
        );
    }

    #[TestDox('->copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        // this test proves that copying an empty dict returns a
        // new, empty DictOfStrings instance

        $unit = new DictOfStrings();

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfStrings::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('->empty() returns true for empty dict')]
    public function test_empty_returns_true_for_empty_dict(): void
    {
        // this test proves that empty() returns true when the dict
        // has no data

        $unit = new DictOfStrings();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_dict(): void
    {
        // this test proves that empty() returns false when the dict
        // contains data

        $unit = new DictOfStrings(['host' => 'localhost']);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    #[TestDox('->empty() returns false after set()')]
    public function test_empty_returns_false_after_set(): void
    {
        // this test proves that empty() returns false after a
        // string has been added via set()

        $unit = new DictOfStrings();
        $unit->set(key: 'host', value: 'localhost');

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns "DictOfStrings"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // this test proves that getCollectionTypeAsString() returns
        // "DictOfStrings" (just the class name without namespace)

        $unit = new DictOfStrings();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('DictOfStrings', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('Dict with one string: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // this test proves that for a dict with exactly one string,
        // both first() and last() return that same value

        $unit = new DictOfStrings(['only' => 'value']);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame('value', $first);
        $this->assertSame('value', $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() and merge methods support fluent chaining together')]
    public function test_set_and_merge_support_chaining(): void
    {
        // this test proves that set() and merge methods can be
        // chained together fluently

        $unit = new DictOfStrings();
        $other = new DictOfStrings(['name' => 'mydb']);

        $unit->set(key: 'host', value: 'localhost')
            ->mergeArray(['port' => '3306'])
            ->mergeSelf($other);

        $this->assertSame(
            [
                'host' => 'localhost',
                'port' => '3306',
                'name' => 'mydb',
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() and ->maybeGet() return same value for existing key')]
    public function test_get_and_maybe_get_return_same_value(): void
    {
        // this test proves that get() and maybeGet() return the
        // same string value when the key exists

        $unit = new DictOfStrings([
            'host' => 'localhost',
            'port' => '3306',
        ]);

        $getResult = $unit->get('host');
        $maybeGetResult = $unit->maybeGet('host');

        $this->assertSame('localhost', $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // applyTrim()
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyTrim() removes whitespace from strings in the dict')]
    public function test_apply_trim_removes_whitespace_from_strings(): void
    {
        // this test proves that applyTrim() uses PHP's trim()
        // function to remove whitespace from all strings in the
        // dict

        $expectedTrimmed = [
            'host' => 'localhost',
            'port' => '3306',
            'name' => 'mydb',
        ];
        $unit = new DictOfStrings([
            'host' => '  localhost  ',
            'port' => '  3306  ',
            'name' => '  mydb  ',
        ]);

        $unit->applyTrim();

        $this->assertSame($expectedTrimmed, $unit->toArray());
    }

    #[TestDox('->applyTrim() on dict with no spaces leaves strings unchanged')]
    public function test_apply_trim_unchanged_when_no_spaces(): void
    {
        // this test proves that applyTrim() does not alter strings
        // that don't have leading or trailing whitespace

        $expectedData = [
            'host' => 'localhost',
            'port' => '3306',
        ];
        $unit = new DictOfStrings($expectedData);

        $unit->applyTrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->applyTrim() handles empty dict')]
    public function test_apply_trim_on_empty_dict(): void
    {
        // this test proves that applyTrim() works correctly on
        // empty dicts

        $unit = new DictOfStrings();

        $unit->applyTrim();

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('->applyTrim() handles strings with newlines and tabs')]
    public function test_apply_trim_removes_newlines_and_tabs(): void
    {
        // this test proves that applyTrim() removes newline and tab
        // characters

        $unit = new DictOfStrings([
            'a' => "\nalpha",
            'b' => "bravo\t",
            'c' => "\ncharlie\n",
        ]);

        $unit->applyTrim();

        $this->assertSame(
            ['a' => 'alpha', 'b' => 'bravo', 'c' => 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyTrim() handles empty strings')]
    public function test_apply_trim_preserves_empty_strings(): void
    {
        // this test proves that applyTrim() correctly handles empty
        // strings

        $expectedData = [
            'a' => '',
            'b' => 'alpha',
            'c' => '',
        ];
        $unit = new DictOfStrings($expectedData);

        $unit->applyTrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->applyTrim() can be chained with other methods')]
    public function test_apply_trim_supports_method_chaining(): void
    {
        // this test proves that applyTrim() returns $this for
        // fluent method chaining

        $unit = new DictOfStrings([
            'a' => '  alpha  ',
            'b' => '  bravo  ',
        ]);

        $result = $unit->applyTrim();

        $this->assertSame($unit, $result);
    }

    #[TestDox('->applyTrim() can be used fluently with set()')]
    public function test_apply_trim_with_set(): void
    {
        // this test proves that applyTrim() works correctly with
        // strings added dynamically via set()

        $unit = new DictOfStrings(['a' => '  alpha  ']);

        $unit->set(key: 'b', value: '  bravo  ')->applyTrim();

        $this->assertSame(
            ['a' => 'alpha', 'b' => 'bravo'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyTrim() with custom characters strips only those characters')]
    public function test_apply_trim_with_custom_characters(): void
    {
        // this test proves that when a custom $characters parameter
        // is provided, applyTrim() only strips those specified
        // characters from the strings

        $unit = new DictOfStrings([
            'a' => '/path/',
            'b' => '//double//',
            'c' => '/single',
        ]);

        $unit->applyTrim(characters: '/');

        $this->assertSame(
            ['a' => 'path', 'b' => 'double', 'c' => 'single'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyTrim() with custom characters does not strip whitespace')]
    public function test_apply_trim_with_custom_characters_preserves_whitespace(): void
    {
        // this test proves that when custom characters are provided,
        // default whitespace is not stripped — only the specified
        // characters are removed

        $unit = new DictOfStrings([
            'a' => '/ path /',
            'b' => '/ hello /',
        ]);

        $unit->applyTrim(characters: '/');

        $this->assertSame(
            ['a' => ' path ', 'b' => ' hello '],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyTrim() with custom characters handles empty dict')]
    public function test_apply_trim_with_custom_characters_on_empty_dict(): void
    {
        // this test proves that applyTrim() with custom characters
        // works correctly on an empty dict without error

        $unit = new DictOfStrings();

        $unit->applyTrim(characters: '/');

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('->applyTrim() with custom characters returns $this for chaining')]
    public function test_apply_trim_with_custom_characters_returns_this(): void
    {
        // this test proves that applyTrim() returns $this for
        // fluent method chaining when custom characters are
        // provided

        $unit = new DictOfStrings([
            'a' => '/path/',
            'b' => '/other/',
        ]);

        $result = $unit->applyTrim(characters: '/');

        $this->assertSame($unit, $result);
    }

    #[TestDox('->applyTrim() preserves dict keys')]
    public function test_apply_trim_preserves_keys(): void
    {
        // this test proves that applyTrim() preserves the original
        // string keys in the dict

        $unit = new DictOfStrings([
            'host' => '  localhost  ',
            'port' => '  3306  ',
        ]);

        $unit->applyTrim();

        $this->assertSame(
            ['host', 'port'],
            array_keys($unit->toArray()),
        );
    }

    // ================================================================
    //
    // applyLtrim()
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyLtrim() removes leading whitespace from strings')]
    public function test_apply_ltrim_removes_leading_whitespace(): void
    {
        // this test proves that applyLtrim() removes leading
        // whitespace from all strings in the dict, while preserving
        // trailing whitespace

        $unit = new DictOfStrings([
            'a' => '  alpha  ',
            'b' => '  bravo  ',
            'c' => '  charlie  ',
        ]);

        $unit->applyLtrim();

        $this->assertSame(
            [
                'a' => 'alpha  ',
                'b' => 'bravo  ',
                'c' => 'charlie  ',
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyLtrim() preserves trailing whitespace')]
    public function test_apply_ltrim_preserves_trailing_whitespace(): void
    {
        // this test proves that applyLtrim() only removes leading
        // whitespace and does not affect trailing whitespace

        $unit = new DictOfStrings([
            'a' => 'alpha  ',
            'b' => 'bravo  ',
        ]);

        $unit->applyLtrim();

        $this->assertSame(
            ['a' => 'alpha  ', 'b' => 'bravo  '],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyLtrim() on dict with no leading spaces leaves strings unchanged')]
    public function test_apply_ltrim_unchanged_when_no_leading_spaces(): void
    {
        // this test proves that applyLtrim() does not alter strings
        // that don't have leading whitespace

        $expectedData = ['a' => 'alpha', 'b' => 'bravo'];
        $unit = new DictOfStrings($expectedData);

        $unit->applyLtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->applyLtrim() handles empty dict')]
    public function test_apply_ltrim_on_empty_dict(): void
    {
        // this test proves that applyLtrim() works correctly on
        // empty dicts

        $unit = new DictOfStrings();

        $unit->applyLtrim();

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('->applyLtrim() handles strings with leading newlines and tabs')]
    public function test_apply_ltrim_removes_leading_newlines_and_tabs(): void
    {
        // this test proves that applyLtrim() removes leading
        // newline and tab characters

        $unit = new DictOfStrings([
            'a' => "\nalpha",
            'b' => "\tbravo",
            'c' => "\n\tcharlie",
        ]);

        $unit->applyLtrim();

        $this->assertSame(
            ['a' => 'alpha', 'b' => 'bravo', 'c' => 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyLtrim() handles empty strings')]
    public function test_apply_ltrim_preserves_empty_strings(): void
    {
        // this test proves that applyLtrim() correctly handles
        // empty strings

        $expectedData = ['a' => '', 'b' => 'alpha', 'c' => ''];
        $unit = new DictOfStrings($expectedData);

        $unit->applyLtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->applyLtrim() returns $this for method chaining')]
    public function test_apply_ltrim_supports_method_chaining(): void
    {
        // this test proves that applyLtrim() returns $this for
        // fluent method chaining

        $unit = new DictOfStrings([
            'a' => '  alpha  ',
            'b' => '  bravo  ',
        ]);

        $result = $unit->applyLtrim();

        $this->assertSame($unit, $result);
    }

    #[TestDox('->applyLtrim() can be used fluently with set()')]
    public function test_apply_ltrim_with_set(): void
    {
        // this test proves that applyLtrim() works correctly with
        // strings added dynamically via set()

        $unit = new DictOfStrings(['a' => '  alpha  ']);

        $unit->set(key: 'b', value: '  bravo  ')->applyLtrim();

        $this->assertSame(
            ['a' => 'alpha  ', 'b' => 'bravo  '],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyLtrim() with custom characters strips only those characters from the left')]
    public function test_apply_ltrim_with_custom_characters(): void
    {
        // this test proves that when a custom $characters parameter
        // is provided, applyLtrim() only strips those specified
        // characters from the left side of the strings

        $unit = new DictOfStrings([
            'a' => '/path/',
            'b' => '//double//',
            'c' => '/single',
        ]);

        $unit->applyLtrim(characters: '/');

        $this->assertSame(
            ['a' => 'path/', 'b' => 'double//', 'c' => 'single'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyLtrim() with custom characters does not strip whitespace')]
    public function test_apply_ltrim_with_custom_characters_preserves_whitespace(): void
    {
        // this test proves that when custom characters are provided,
        // default whitespace is not stripped — only the specified
        // characters are removed from the left

        $unit = new DictOfStrings([
            'a' => '/ path /',
            'b' => '/ hello /',
        ]);

        $unit->applyLtrim(characters: '/');

        $this->assertSame(
            ['a' => ' path /', 'b' => ' hello /'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyLtrim() with custom characters handles empty dict')]
    public function test_apply_ltrim_with_custom_characters_on_empty_dict(): void
    {
        // this test proves that applyLtrim() with custom characters
        // works correctly on an empty dict without error

        $unit = new DictOfStrings();

        $unit->applyLtrim(characters: '/');

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('->applyLtrim() with custom characters returns $this for chaining')]
    public function test_apply_ltrim_with_custom_characters_returns_this(): void
    {
        // this test proves that applyLtrim() returns $this for
        // fluent method chaining when custom characters are
        // provided

        $unit = new DictOfStrings([
            'a' => '/path/',
            'b' => '/other/',
        ]);

        $result = $unit->applyLtrim(characters: '/');

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // applyRtrim()
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyRtrim() removes trailing whitespace from strings')]
    public function test_apply_rtrim_removes_trailing_whitespace(): void
    {
        // this test proves that applyRtrim() removes trailing
        // whitespace from all strings in the dict, while preserving
        // leading whitespace

        $unit = new DictOfStrings([
            'a' => '  alpha  ',
            'b' => '  bravo  ',
            'c' => '  charlie  ',
        ]);

        $unit->applyRtrim();

        $this->assertSame(
            [
                'a' => '  alpha',
                'b' => '  bravo',
                'c' => '  charlie',
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyRtrim() preserves leading whitespace')]
    public function test_apply_rtrim_preserves_leading_whitespace(): void
    {
        // this test proves that applyRtrim() only removes trailing
        // whitespace and does not affect leading whitespace

        $unit = new DictOfStrings([
            'a' => '  alpha',
            'b' => '  bravo',
        ]);

        $unit->applyRtrim();

        $this->assertSame(
            ['a' => '  alpha', 'b' => '  bravo'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyRtrim() on dict with no trailing spaces leaves strings unchanged')]
    public function test_apply_rtrim_unchanged_when_no_trailing_spaces(): void
    {
        // this test proves that applyRtrim() does not alter strings
        // that don't have trailing whitespace

        $expectedData = ['a' => 'alpha', 'b' => 'bravo'];
        $unit = new DictOfStrings($expectedData);

        $unit->applyRtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->applyRtrim() handles empty dict')]
    public function test_apply_rtrim_on_empty_dict(): void
    {
        // this test proves that applyRtrim() works correctly on
        // empty dicts

        $unit = new DictOfStrings();

        $unit->applyRtrim();

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('->applyRtrim() handles strings with trailing newlines and tabs')]
    public function test_apply_rtrim_removes_trailing_newlines_and_tabs(): void
    {
        // this test proves that applyRtrim() removes trailing
        // newline and tab characters

        $unit = new DictOfStrings([
            'a' => "alpha\n",
            'b' => "bravo\t",
            'c' => "charlie\n\t",
        ]);

        $unit->applyRtrim();

        $this->assertSame(
            ['a' => 'alpha', 'b' => 'bravo', 'c' => 'charlie'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyRtrim() handles empty strings')]
    public function test_apply_rtrim_preserves_empty_strings(): void
    {
        // this test proves that applyRtrim() correctly handles
        // empty strings

        $expectedData = ['a' => '', 'b' => 'alpha', 'c' => ''];
        $unit = new DictOfStrings($expectedData);

        $unit->applyRtrim();

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->applyRtrim() returns $this for method chaining')]
    public function test_apply_rtrim_supports_method_chaining(): void
    {
        // this test proves that applyRtrim() returns $this for
        // fluent method chaining

        $unit = new DictOfStrings([
            'a' => '  alpha  ',
            'b' => '  bravo  ',
        ]);

        $result = $unit->applyRtrim();

        $this->assertSame($unit, $result);
    }

    #[TestDox('->applyRtrim() can be used fluently with set()')]
    public function test_apply_rtrim_with_set(): void
    {
        // this test proves that applyRtrim() works correctly with
        // strings added dynamically via set()

        $unit = new DictOfStrings(['a' => '  alpha  ']);

        $unit->set(key: 'b', value: '  bravo  ')->applyRtrim();

        $this->assertSame(
            ['a' => '  alpha', 'b' => '  bravo'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyRtrim() with custom characters strips only those characters from the right')]
    public function test_apply_rtrim_with_custom_characters(): void
    {
        // this test proves that when a custom $characters parameter
        // is provided, applyRtrim() only strips those specified
        // characters from the right side of the strings

        $unit = new DictOfStrings([
            'a' => '/path/',
            'b' => '//double//',
            'c' => 'single/',
        ]);

        $unit->applyRtrim(characters: '/');

        $this->assertSame(
            ['a' => '/path', 'b' => '//double', 'c' => 'single'],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyRtrim() with custom characters does not strip whitespace')]
    public function test_apply_rtrim_with_custom_characters_preserves_whitespace(): void
    {
        // this test proves that when custom characters are provided,
        // default whitespace is not stripped — only the specified
        // characters are removed from the right

        $unit = new DictOfStrings([
            'a' => '/ path /',
            'b' => '/ hello /',
        ]);

        $unit->applyRtrim(characters: '/');

        $this->assertSame(
            ['a' => '/ path ', 'b' => '/ hello '],
            $unit->toArray(),
        );
    }

    #[TestDox('->applyRtrim() with custom characters handles empty dict')]
    public function test_apply_rtrim_with_custom_characters_on_empty_dict(): void
    {
        // this test proves that applyRtrim() with custom characters
        // works correctly on an empty dict without error

        $unit = new DictOfStrings();

        $unit->applyRtrim(characters: '/');

        $this->assertSame([], $unit->toArray());
        $this->assertCount(0, $unit);
    }

    #[TestDox('->applyRtrim() with custom characters returns $this for chaining')]
    public function test_apply_rtrim_with_custom_characters_returns_this(): void
    {
        // this test proves that applyRtrim() returns $this for
        // fluent method chaining when custom characters are
        // provided

        $unit = new DictOfStrings([
            'a' => '/path/',
            'b' => '/other/',
        ]);

        $result = $unit->applyRtrim(characters: '/');

        $this->assertSame($unit, $result);
    }
}
