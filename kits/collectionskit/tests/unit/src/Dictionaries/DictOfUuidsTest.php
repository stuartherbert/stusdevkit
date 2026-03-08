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
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\DictOfObjects;
use StusDevKit\CollectionsKit\Dictionaries\DictOfUuids;

#[TestDox('DictOfUuids')]
class DictOfUuidsTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty dict')]
    public function test_can_instantiate_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // DictOfUuids

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfUuids::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends DictOfObjects')]
    public function test_extends_dict_of_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that DictOfUuids is a subclass of
        // DictOfObjects

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfObjects::class, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a DictOfUuids
        // and seed it with an initial associative array of UUIDs

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $expectedData = [
            'user' => $uuid1,
            'org' => $uuid2,
            'team' => $uuid3,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfUuids($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('Constructor preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when constructed with an associative
        // array, the string keys are preserved

        // ----------------------------------------------------------------
        // setup your test

        $expectedData = [
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
            'team' => Uuid::uuid4(),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DictOfUuids($expectedData);
        $actualData = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user', 'org', 'team'],
            array_keys($actualData),
        );
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    #[TestDox('add() stores a UUID with a string key')]
    public function test_add_stores_uuid_with_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() stores a UuidInterface at
        // the given string key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();
        $uuid = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(key: 'user', input: $uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $unit->get('user'));
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() overwrites existing UUID at same key')]
    public function test_add_overwrites_existing_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() with an existing key
        // overwrites the previous UUID

        // ----------------------------------------------------------------
        // setup your test

        $original = Uuid::uuid4();
        $replacement = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $original]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(key: 'user', input: $replacement);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $unit->get('user'));
        $this->assertNotSame($original, $unit->get('user'));
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() adds to existing data')]
    public function test_add_adds_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() adds a new key-value pair
        // alongside data passed into the constructor

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(key: 'team', input: $uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
            ],
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

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add(key: 'user', input: Uuid::uuid4());

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
        // together fluently to build up the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(key: 'user', input: $uuid1)
            ->add(key: 'org', input: $uuid2)
            ->add(key: 'team', input: $uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    #[TestDox('set() stores a UUID with a string key')]
    public function test_set_stores_uuid_with_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the inherited set() method also
        // works for storing UUIDs

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();
        $uuid = Uuid::uuid4();

        // ----------------------------------------------------------------
        // perform the change

        $unit->set(key: 'user', value: $uuid);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $unit->get('user'));
        $this->assertCount(1, $unit);
    }

    #[TestDox('set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that set() returns the same collection
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->set(key: 'user', value: Uuid::uuid4());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns true when the dict
        // contains the given string key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the dict
        // does not contain the given key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the dict
        // is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('has() returns true for key added via add()')]
    public function test_has_returns_true_for_key_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() detects keys that were added
        // via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: Uuid::uuid4());

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeGet() returns UUID for existing key')]
    public function test_maybe_get_returns_uuid_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the UUID stored
        // at the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actualResult);
    }

    #[TestDox('maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // given key does not exist in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns UUID added via add()')]
    public function test_maybe_get_returns_uuid_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() retrieves UUIDs that
        // were stored using the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actualResult);
    }

    #[TestDox('maybeGet() returns the overwritten UUID after add()')]
    public function test_maybe_get_returns_overwritten_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the most recent
        // UUID after a key has been overwritten with add()

        // ----------------------------------------------------------------
        // setup your test

        $original = Uuid::uuid4();
        $replacement = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $original]);
        $unit->add(key: 'user', input: $replacement);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('get() returns UUID for existing key')]
    public function test_get_returns_uuid_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() returns the UUID stored at
        // the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('org');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
    }

    #[TestDox('get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfUuids does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfUuids does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('get() returns UUID added via add()')]
    public function test_get_returns_uuid_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() retrieves UUIDs that were
        // stored using the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $actualResult);
    }

    #[TestDox('get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the RuntimeException thrown by
        // get() includes the missing key in its message

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfUuids does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the dict contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

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

        // this test proves that toArray() returns all the UUIDs
        // stored in the dict, preserving keys

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [
            'user' => $uuid1,
            'org' => $uuid2,
        ];
        $unit = new DictOfUuids($expectedData);

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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid1);
        $unit->add(key: 'org', input: $uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user' => $uuid1, 'org' => $uuid2],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the dict
        // contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the correct number
        // of UUIDs stored in the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
            'team' => Uuid::uuid4(),
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

        // this test proves that the dict works with PHP's built-in
        // count() function via the Countable interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
            'team' => Uuid::uuid4(),
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

        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: Uuid::uuid4());
        $unit->add(key: 'org', input: Uuid::uuid4());

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that overwriting an existing key via
        // add() does not increase the count

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(key: 'user', input: Uuid::uuid4());

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
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

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the dict can be used in a foreach
        // loop via the IteratorAggregate interface

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $expectedData = [
            'user' => $uuid1,
            'org' => $uuid2,
            'team' => $uuid3,
        ];
        $unit = new DictOfUuids($expectedData);
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

    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_dict_produces_no_iterations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over an empty dict does
        // not execute the loop body

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();
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

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a dict preserves
        // the string keys

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
            'team' => Uuid::uuid4(),
        ]);
        $actualKeys = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['user', 'org', 'team'], $actualKeys);
    }

    #[TestDox('Iteration includes items added via add()')]
    public function test_iteration_includes_items_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over a dict includes
        // items that were added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid1);
        $unit->add(key: 'org', input: $uuid2);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user' => $uuid1, 'org' => $uuid2],
            $actualData,
        );
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('merge() can merge an array into the dict')]
    public function test_merge_can_merge_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the dict

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([
            'org' => $uuid2,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another DictOfUuids')]
    public function test_merge_can_merge_dict_of_uuids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // DictOfUuids and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid1]);
        $other = new DictOfUuids([
            'org' => $uuid2,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
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

    #[TestDox('mergeArray() adds array items to the dict')]
    public function test_merge_array_adds_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() adds the given array's
        // key-value pairs to the dict

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid1]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray([
            'org' => $uuid2,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeArray() into empty dict sets the data')]
    public function test_merge_array_into_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() works correctly when
        // the dict is initially empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray(['user' => $uuid1, 'org' => $uuid2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user' => $uuid1, 'org' => $uuid2],
            $unit->toArray(),
        );
    }

    #[TestDox('mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty array does not
        // alter the dict's existing data

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = ['user' => $uuid1, 'org' => $uuid2];
        $unit = new DictOfUuids($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when merging an array with matching
        // string keys, the merged UUIDs overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $original = Uuid::uuid4();
        $replacement = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $original,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray([
            'org' => $replacement,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $replacement,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() returns the same dict
        // instance for fluent method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray(['org' => Uuid::uuid4()]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('mergeSelf() merges another dict into this one')]
    public function test_merge_self_merges_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() adds the contents
        // of another DictOfUuids into this dict

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid1]);
        $other = new DictOfUuids([
            'org' => $uuid2,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the dict being merged from is not
        // modified by the merge operation

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid1]);
        $other = new DictOfUuids(['org' => $uuid2]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['org' => $uuid2], $other->toArray());
    }

    #[TestDox('mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty dict does not
        // alter the existing data

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = ['user' => $uuid1, 'org' => $uuid2];
        $unit = new DictOfUuids($expectedData);
        $other = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when merging a dict with matching
        // keys, the merged UUIDs overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $original = Uuid::uuid4();
        $replacement = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $original,
        ]);
        $other = new DictOfUuids([
            'org' => $replacement,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $replacement,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeFirst() returns the first UUID')]
    public function test_maybe_first_returns_first_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the UUID at
        // the first key in the dict

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $actualResult);
    }

    #[TestDox('maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // dict is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeFirst() returns the first UUID added via add()')]
    public function test_maybe_first_returns_first_uuid_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the first
        // UUID that was added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid1);
        $unit->add(key: 'org', input: $uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first UUID')]
    public function test_first_returns_first_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the UUID at the
        // first key in the dict when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid1, $actualResult);
    }

    #[TestDox('first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfUuids is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last UUID')]
    public function test_maybe_last_returns_last_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the UUID at
        // the last key in the dict

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
    }

    #[TestDox('maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // dict is empty, rather than throwing an exception

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeLast() returns the last UUID added via add()')]
    public function test_maybe_last_returns_last_uuid_added_via_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the most
        // recently added UUID via add()

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid1);
        $unit->add(key: 'org', input: $uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last UUID')]
    public function test_last_returns_last_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the UUID at the
        // last key in the dict when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid2, $actualResult);
    }

    #[TestDox('last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the dict is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfUuids is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new DictOfUuids with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new DictOfUuids
        // instance containing the same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [
            'user' => $uuid1,
            'org' => $uuid2,
        ];
        $unit = new DictOfUuids($expectedData);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfUuids::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('copy() returns independent instance (adding to copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that adding to the copied dict does not
        // affect the original dict's key set

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->add(key: 'team', input: $uuid3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertCount(3, $copy);
        $this->assertFalse($unit->has('team'));
    }

    #[TestDox('copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty dict returns a
        // new, empty DictOfUuids instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfUuids::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('empty() returns true for empty dict')]
    public function test_empty_returns_true_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // dict has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // dict contains data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids(['user' => Uuid::uuid4()]);

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
        // UUID has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: Uuid::uuid4());

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

    #[TestDox('getCollectionTypeAsString() returns "DictOfUuids"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "DictOfUuids" (just the class name without namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('DictOfUuids', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('Dict with one UUID: first() and last() return the same UUID')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for a dict with exactly one UUID,
        // both first() and last() return that same UUID

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids(['only' => $uuid]);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $first);
        $this->assertSame($uuid, $last);
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

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids();
        $other = new DictOfUuids(['team' => $uuid3]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(key: 'user', input: $uuid1)
            ->mergeArray(['org' => $uuid2])
            ->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => $uuid1,
                'org' => $uuid2,
                'team' => $uuid3,
            ],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('get() and maybeGet() return same UUID for existing key')]
    public function test_get_and_maybe_get_return_same_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() and maybeGet() return the
        // same UUID instance when the key exists

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid]);

        // ----------------------------------------------------------------
        // perform the change

        $getResult = $unit->get('user');
        $maybeGetResult = $unit->maybeGet('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // UUID-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('Preserves UUID identity (same instance, not a copy)')]
    public function test_preserves_uuid_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that UUIDs stored in the dict are the
        // same instances (not cloned copies)

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid]);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->get('user');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($uuid, $retrieved);
    }

    #[TestDox('All stored values implement UuidInterface')]
    public function test_all_stored_values_are_uuids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that all values retrieved from the
        // dict implement UuidInterface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
            'team' => Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $value) {
            $this->assertInstanceOf(UuidInterface::class, $value);
        }
    }

    #[TestDox('Each UUID has a unique string representation')]
    public function test_each_uuid_has_unique_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each UUID stored in the dict has
        // a unique string representation

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
            'team' => Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $strings = [];
        foreach ($unit as $value) {
            $strings[] = (string) $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, array_unique($strings));
    }

    // ================================================================
    //
    // toArrayOfStrings()
    //
    // ----------------------------------------------------------------

    #[TestDox('toArrayOfStrings() returns empty array for empty dict')]
    public function test_to_array_of_strings_returns_empty_for_empty_dict(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() returns an
        // empty array when the dict contains no UUIDs

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);
    }

    #[TestDox('toArrayOfStrings() returns UUID string representations')]
    public function test_to_array_of_strings_returns_string_representations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() returns each
        // UUID converted to its string representation

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
            'team' => $uuid3,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => (string) $uuid1,
                'org' => (string) $uuid2,
                'team' => (string) $uuid3,
            ],
            $actualResult,
        );
    }

    #[TestDox('toArrayOfStrings() preserves string keys')]
    public function test_to_array_of_strings_preserves_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() preserves the
        // original string keys from the dict

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user', 'org'],
            array_keys($actualResult),
        );
    }

    #[TestDox('toArrayOfStrings() returns valid UUID strings')]
    public function test_to_array_of_strings_returns_valid_uuid_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each string returned by
        // toArrayOfStrings() is a valid UUID string

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DictOfUuids([
            'user' => Uuid::uuid4(),
            'org' => Uuid::uuid4(),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        foreach ($actualResult as $uuidString) {
            $this->assertIsString($uuidString);
            $this->assertTrue(Uuid::isValid($uuidString));
        }
    }

    #[TestDox('toArrayOfStrings() includes items added via add()')]
    public function test_to_array_of_strings_includes_items_from_add(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArrayOfStrings() includes items
        // that were added via the add() method

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids();
        $unit->add(key: 'user', input: $uuid1);
        $unit->add(key: 'org', input: $uuid2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user' => (string) $uuid1,
                'org' => (string) $uuid2,
            ],
            $actualResult,
        );
    }

    #[TestDox('toArrayOfStrings() values match UUID toString()')]
    public function test_to_array_of_strings_matches_to_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each value in the array returned
        // by toArrayOfStrings() matches calling toString() on the
        // corresponding UUID object

        // ----------------------------------------------------------------
        // setup your test

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new DictOfUuids([
            'user' => $uuid1,
            'org' => $uuid2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $stringResult = $unit->toArrayOfStrings();
        $objectResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        foreach ($objectResult as $key => $uuid) {
            $this->assertSame(
                $uuid->toString(),
                $stringResult[$key],
            );
        }
    }

    #[TestDox('copy() shares UUID references with original')]
    public function test_copy_shares_uuid_references(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() creates a shallow copy —
        // the copied dict contains references to the same UUID
        // instances, not new objects

        // ----------------------------------------------------------------
        // setup your test

        $uuid = Uuid::uuid4();
        $unit = new DictOfUuids(['user' => $uuid]);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            $unit->get('user'),
            $copy->get('user'),
        );
    }
}
