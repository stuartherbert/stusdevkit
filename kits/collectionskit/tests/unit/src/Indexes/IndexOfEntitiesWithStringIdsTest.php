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

namespace StusDevKit\CollectionsKit\Tests\Unit\Indexes;

use ArrayIterator;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\DictOfObjects;
use StusDevKit\CollectionsKit\Indexes\IndexOfEntitiesWithStringIds;
use StusDevKit\CollectionsKit\Tests\Fixtures\EntityWithStringIdFixture;

#[TestDox('IndexOfEntitiesWithStringIds')]
class IndexOfEntitiesWithStringIdsTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate an empty index')]
    public function test_can_instantiate_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // IndexOfEntitiesWithStringIds

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            IndexOfEntitiesWithStringIds::class,
            $unit,
        );
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends DictOfObjects')]
    public function test_extends_dict_of_objects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that IndexOfEntitiesWithStringIds is a
        // subclass of DictOfObjects

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DictOfObjects::class, $unit);
    }

    #[TestDox('Can instantiate with initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create an index and seed it
        // with an initial associative array of entities

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $expectedData = [
            'user-001' => $entity1,
            'user-002' => $entity2,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IndexOfEntitiesWithStringIds($expectedData);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
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
            'user-001' => new EntityWithStringIdFixture(
                id: 'user-001',
                name: 'Alice',
            ),
            'user-002' => new EntityWithStringIdFixture(
                id: 'user-002',
                name: 'Bob',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IndexOfEntitiesWithStringIds($expectedData);
        $actualData = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user-001', 'user-002'],
            array_keys($actualData),
        );
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    #[TestDox('add() stores an entity using its ID as key')]
    public function test_add_stores_entity_using_id_as_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() derives the key from the
        // entity's getId() method and stores it at that key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($entity);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->has('user-001'));
        $this->assertSame($entity, $unit->get('user-001'));
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() overwrites existing entity with same ID')]
    public function test_add_overwrites_existing_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling add() with an entity that
        // has the same ID as an existing one overwrites it

        // ----------------------------------------------------------------
        // setup your test

        $original = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $replacement = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice Updated',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($original);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($replacement);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $unit->get('user-001'));
        $this->assertNotSame($original, $unit->get('user-001'));
        $this->assertCount(1, $unit);
    }

    #[TestDox('add() adds to existing data')]
    public function test_add_adds_to_existing_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() adds a new entity alongside
        // entities already in the index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $entity3 = new EntityWithStringIdFixture(
            id: 'user-003',
            name: 'Charlie',
        );
        $unit = new IndexOfEntitiesWithStringIds([
            'user-001' => $entity1,
            'user-002' => $entity2,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($entity3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'user-001' => $entity1,
                'user-002' => $entity2,
                'user-003' => $entity3,
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

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

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
        // together fluently to build up the index

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $entity3 = new EntityWithStringIdFixture(
            id: 'user-003',
            name: 'Charlie',
        );

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($entity1)
            ->add($entity2)
            ->add($entity3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($entity1, $unit->get('user-001'));
        $this->assertSame($entity2, $unit->get('user-002'));
        $this->assertSame($entity3, $unit->get('user-003'));
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('has() returns true for existing key')]
    public function test_has_returns_true_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns true when the index
        // contains the given string key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('user-001');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the index
        // does not contain the given key

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    #[TestDox('has() returns false for empty index')]
    public function test_has_returns_false_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that has() returns false when the index
        // is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->has('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeGet() returns entity for existing key')]
    public function test_maybe_get_returns_entity_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the entity
        // stored at the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('user-001');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity, $actualResult);
    }

    #[TestDox('maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // given key does not exist in the index

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns null for empty index')]
    public function test_maybe_get_returns_null_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns null when the
        // index is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('maybeGet() returns the overwritten entity after add()')]
    public function test_maybe_get_returns_overwritten_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGet() returns the most recent
        // entity after an ID has been overwritten with add()

        // ----------------------------------------------------------------
        // setup your test

        $original = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $replacement = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice Updated',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($original);
        $unit->add($replacement);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGet('user-001');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('get() returns entity for existing key')]
    public function test_get_returns_entity_for_existing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() returns the entity stored at
        // the given key when it exists

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->get('user-002');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity2, $actualResult);
    }

    #[TestDox('get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the index

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithStringIds does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('get() throws RuntimeException for empty index')]
    public function test_get_throws_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() throws a RuntimeException
        // when the index is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithStringIds does not contain anything',
        );

        $unit->get('anything');
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

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithStringIds does not contain '
            . 'my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns empty array for empty index')]
    public function test_to_array_returns_empty_array_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns an empty array
        // when the index contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

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

        // this test proves that toArray() returns all the entities
        // stored in the index, preserving keys

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user-001' => $entity1, 'user-002' => $entity2],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('count() returns 0 for empty index')]
    public function test_count_returns_zero_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns 0 when the index
        // contains no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->count();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('count() returns number of items in index')]
    public function test_count_returns_number_of_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that count() returns the correct number
        // of entities stored in the index

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-003',
            name: 'Charlie',
        ));

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

        // this test proves that the index works with PHP's built-in
        // count() function via the Countable interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = count($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('count() does not increase when overwriting an entity')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that overwriting an existing entity via
        // add() does not increase the count

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice Updated',
        ));

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

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIterator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Index can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the index can be used in a foreach
        // loop via the IteratorAggregate interface

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);
        $actualData = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user-001' => $entity1, 'user-002' => $entity2],
            $actualData,
        );
    }

    #[TestDox('Iterating empty index produces no iterations')]
    public function test_iterating_empty_index_produces_no_iterations(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating over an empty index does
        // not execute the loop body

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
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

    #[TestDox('Iteration keys match entity IDs')]
    public function test_iteration_keys_match_entity_ids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the keys produced during iteration
        // match the IDs of the stored entities

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        ));
        $actualKeys = [];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['user-001', 'user-002'], $actualKeys);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('merge() can merge an array into the index')]
    public function test_merge_can_merge_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $entity3 = new EntityWithStringIdFixture(
            id: 'user-003',
            name: 'Charlie',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge([
            'user-002' => $entity2,
            'user-003' => $entity3,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame($entity2, $unit->get('user-002'));
        $this->assertSame($entity3, $unit->get('user-003'));
        $this->assertSame($unit, $result);
    }

    #[TestDox('merge() can merge another IndexOfEntitiesWithStringIds')]
    public function test_merge_can_merge_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merge() can accept another
        // IndexOfEntitiesWithStringIds and merge its contents

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $other = new IndexOfEntitiesWithStringIds();
        $other->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->merge($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get('user-002'));
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeArray()
    //
    // ----------------------------------------------------------------

    #[TestDox('mergeArray() adds array items to the index')]
    public function test_merge_array_adds_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeArray() adds the given array's
        // key-value pairs to the index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeArray(['user-002' => $entity2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get('user-002'));
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeArray() overwrites matching keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when merging an array with matching
        // keys, the merged entities overwrite the originals

        // ----------------------------------------------------------------
        // setup your test

        $original = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $replacement = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice Updated',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($original);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeArray(['user-001' => $replacement]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($replacement, $unit->get('user-001'));
        $this->assertCount(1, $unit);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('mergeSelf() merges another index into this one')]
    public function test_merge_self_merges_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that mergeSelf() adds the contents of
        // another IndexOfEntitiesWithStringIds into this index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $other = new IndexOfEntitiesWithStringIds();
        $other->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get('user-002'));
        $this->assertSame($unit, $result);
    }

    #[TestDox('mergeSelf() does not modify the source index')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the index being merged from is not
        // modified by the merge operation

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $other = new IndexOfEntitiesWithStringIds();
        $other->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $unit->mergeSelf($other);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $other);
        $this->assertSame(
            ['user-002' => $entity2],
            $other->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeFirst() returns the first entity')]
    public function test_maybe_first_returns_first_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns the entity at
        // the first key in the index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity1, $actualResult);
    }

    #[TestDox('maybeFirst() returns null for empty index')]
    public function test_maybe_first_returns_null_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFirst() returns null when the
        // index is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeFirst();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('first() returns the first entity')]
    public function test_first_returns_first_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() returns the entity at the
        // first key in the index when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->first();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity1, $actualResult);
    }

    #[TestDox('first() throws RuntimeException for empty index')]
    public function test_first_throws_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that first() throws a RuntimeException
        // when the index is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithStringIds is empty',
        );

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('maybeLast() returns the last entity')]
    public function test_maybe_last_returns_last_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns the entity at
        // the last key in the index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity2, $actualResult);
    }

    #[TestDox('maybeLast() returns null for empty index')]
    public function test_maybe_last_returns_null_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeLast() returns null when the
        // index is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeLast();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('last() returns the last entity')]
    public function test_last_returns_last_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() returns the entity at the
        // last key in the index when it is not empty

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity2, $actualResult);
    }

    #[TestDox('last() throws RuntimeException for empty index')]
    public function test_last_throws_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that last() throws a RuntimeException
        // when the index is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithStringIds is empty',
        );

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('copy() returns a new index with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() returns a new
        // IndexOfEntitiesWithStringIds instance containing the
        // same data as the original

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            IndexOfEntitiesWithStringIds::class,
            $copy,
        );
        $this->assertNotSame($unit, $copy);
        $this->assertSame($unit->toArray(), $copy->toArray());
    }

    #[TestDox('copy() returns independent instance (adding to copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that adding to the copied index does
        // not affect the original index's key set

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity1);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        $copy->add($entity2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertCount(2, $copy);
        $this->assertFalse($unit->has('user-002'));
    }

    #[TestDox('copy() of empty index returns empty index')]
    public function test_copy_of_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copying an empty index returns a
        // new, empty IndexOfEntitiesWithStringIds instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            IndexOfEntitiesWithStringIds::class,
            $copy,
        );
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    #[TestDox('copy() shares entity references with original')]
    public function test_copy_shares_entity_references(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that copy() creates a shallow copy —
        // the copied index contains references to the same entity
        // instances, not clones

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity);

        // ----------------------------------------------------------------
        // perform the change

        $copy = $unit->copy();
        /** @var EntityWithStringIdFixture $copyEntity */
        $copyEntity = $copy->get('user-001');
        $copyEntity->name = 'Alice Mutated';

        // ----------------------------------------------------------------
        // test the results

        /** @var EntityWithStringIdFixture $originalEntity */
        $originalEntity = $unit->get('user-001');
        $this->assertSame(
            'Alice Mutated',
            $originalEntity->name,
        );
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('empty() returns true for empty index')]
    public function test_empty_returns_true_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns true when the
        // index has no data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->empty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    #[TestDox('empty() returns false for non-empty index')]
    public function test_empty_returns_false_for_non_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that empty() returns false when the
        // index contains data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

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

        // this test proves that empty() returns false after an
        // entity has been added via add()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));

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

    #[TestDox('getCollectionTypeAsString() returns "IndexOfEntitiesWithStringIds"')]
    public function test_get_collection_type_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getCollectionTypeAsString() returns
        // "IndexOfEntitiesWithStringIds" (the class name without
        // namespace)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getCollectionTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'IndexOfEntitiesWithStringIds',
            $actualResult,
        );
    }

    // ================================================================
    //
    // Single-item indexes
    //
    // ----------------------------------------------------------------

    #[TestDox('Index with one entity: first() and last() return the same entity')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that for an index with exactly one
        // entity, both first() and last() return that same entity

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity);

        // ----------------------------------------------------------------
        // perform the change

        $first = $unit->first();
        $last = $unit->last();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity, $first);
        $this->assertSame($entity, $last);
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('get() and maybeGet() return same entity for existing key')]
    public function test_get_and_maybe_get_return_same_entity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that get() and maybeGet() return the
        // same entity instance when the key exists

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity);

        // ----------------------------------------------------------------
        // perform the change

        $getResult = $unit->get('user-001');
        $maybeGetResult = $unit->maybeGet('user-001');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity, $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // Entity-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('Preserves entity identity (same instance, not a copy)')]
    public function test_preserves_entity_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that entities stored in the index are
        // the same instances (not cloned copies)

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity);

        // ----------------------------------------------------------------
        // perform the change

        $retrieved = $unit->get('user-001');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($entity, $retrieved);
        $this->assertSame('Alice', $retrieved->name);
    }

    #[TestDox('Mutations to retrieved entity are visible through the index')]
    public function test_mutations_visible_through_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that because entities are stored by
        // reference, mutations to a retrieved entity are visible
        // when the entity is retrieved again

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add($entity);

        // ----------------------------------------------------------------
        // perform the change

        /** @var EntityWithStringIdFixture $retrieved */
        $retrieved = $unit->get('user-001');
        $retrieved->name = 'Alice Updated';

        // ----------------------------------------------------------------
        // test the results

        /** @var EntityWithStringIdFixture $updatedEntity */
        $updatedEntity = $unit->get('user-001');
        $this->assertSame(
            'Alice Updated',
            $updatedEntity->name,
        );
    }

    // ================================================================
    //
    // getIds()
    //
    // ----------------------------------------------------------------

    #[TestDox('getIds() returns empty array for empty index')]
    public function test_get_ids_returns_empty_for_empty_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIds() returns an empty array
        // when the index contains no entities

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);
    }

    #[TestDox('getIds() returns the entity IDs')]
    public function test_get_ids_returns_entity_ids(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIds() returns the string IDs
        // of all entities stored in the index

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-003',
            name: 'Charlie',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['user-001', 'user-002', 'user-003'],
            $actualResult,
        );
    }

    #[TestDox('getIds() preserves insertion order')]
    public function test_get_ids_preserves_insertion_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIds() returns the IDs in the
        // order they were added to the index

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'charlie',
            name: 'Charlie',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'alice',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'bob',
            name: 'Bob',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getIds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['charlie', 'alice', 'bob'],
            $actualResult,
        );
    }

    #[TestDox('getIds() does not include duplicate IDs after overwrite')]
    public function test_get_ids_no_duplicates_after_overwrite(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that after overwriting an entity with
        // the same ID, getIds() does not contain duplicate entries

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice Updated',
        ));
        $actualResult = $unit->getIds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['user-001', 'user-002'], $actualResult);
        $this->assertCount(2, $actualResult);
    }

    #[TestDox('getIds() matches keys from toArray()')]
    public function test_get_ids_matches_to_array_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getIds() returns the same keys
        // as array_keys(toArray()), confirming consistency

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IndexOfEntitiesWithStringIds();
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-001',
            name: 'Alice',
        ));
        $unit->add(new EntityWithStringIdFixture(
            id: 'user-002',
            name: 'Bob',
        ));

        // ----------------------------------------------------------------
        // perform the change

        $ids = $unit->getIds();
        $arrayKeys = array_keys($unit->toArray());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($arrayKeys, $ids);
    }

    // ================================================================
    //
    // Key derivation from entity ID
    //
    // ----------------------------------------------------------------

    #[TestDox('add() uses getId() to derive the array key')]
    public function test_add_uses_get_id_for_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() automatically uses the
        // entity's getId() return value as the array key, so the
        // caller does not need to provide a key

        // ----------------------------------------------------------------
        // setup your test

        $entity = new EntityWithStringIdFixture(
            id: 'auto-key-123',
            name: 'Auto',
        );
        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($entity);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->has('auto-key-123'));
        $this->assertSame($entity, $unit->get('auto-key-123'));
        $this->assertSame(['auto-key-123'], $unit->getIds());
    }

    #[TestDox('Entities with different IDs are stored separately')]
    public function test_entities_with_different_ids_stored_separately(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that entities with different IDs are
        // stored as separate entries in the index

        // ----------------------------------------------------------------
        // setup your test

        $entity1 = new EntityWithStringIdFixture(
            id: 'entity-a',
            name: 'Entity A',
        );
        $entity2 = new EntityWithStringIdFixture(
            id: 'entity-b',
            name: 'Entity B',
        );
        $unit = new IndexOfEntitiesWithStringIds();

        // ----------------------------------------------------------------
        // perform the change

        $unit->add($entity1);
        $unit->add($entity2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame($entity1, $unit->get('entity-a'));
        $this->assertSame($entity2, $unit->get('entity-b'));
        $this->assertNotSame(
            $unit->get('entity-a'),
            $unit->get('entity-b'),
        );
    }
}
