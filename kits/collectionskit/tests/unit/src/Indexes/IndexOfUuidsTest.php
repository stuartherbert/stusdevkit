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
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\DictOfObjects;
use StusDevKit\CollectionsKit\Indexes\IndexOfUuids;

#[TestDox('IndexOfUuids')]
class IndexOfUuidsTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Indexes namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(IndexOfUuids::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Indexes',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(IndexOfUuids::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends DictOfObjects')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(IndexOfUuids::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Dictionaries\DictOfObjects::class,
            $parent->getName(),
        );
    }

    #[TestDox('uses the UuidConversions trait')]
    public function test_uses_UuidConversions_trait(): void
    {
        $traits = \class_uses(IndexOfUuids::class);
        $this->assertContains(
            \StusDevKit\CollectionsKit\Traits\UuidConversions::class,
            $traits,
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares add plus trait public methods as its own')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(IndexOfUuids::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === IndexOfUuids::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['add', 'toArrayOfStrings'], $ownMethods);
    }

    #[TestDox('::add() signature: add(UuidInterface $input): static')]
    public function test_add_signature(): void
    {
        $method = new \ReflectionMethod(IndexOfUuids::class, 'add');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['input'], $paramNames);
        $inputType = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $inputType);
        $this->assertSame(\Ramsey\Uuid\UuidInterface::class, $inputType->getName());
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty index')]
    public function test_can_instantiate_empty_index(): void
    {
        // this test proves that we can create a new, empty
        // IndexOfUuids

        $unit = new IndexOfUuids();

        $this->assertInstanceOf(IndexOfUuids::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends DictOfObjects')]
    public function test_extends_dict_of_objects(): void
    {
        // this test proves that IndexOfUuids is a subclass of
        // DictOfObjects

        $unit = new IndexOfUuids();

        $this->assertInstanceOf(DictOfObjects::class, $unit);
    }

    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // this test proves that we can create an index and seed it
        // with an initial associative array of UUIDs

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $expectedData = [
            (string) $uuid1 => $uuid1,
            (string) $uuid2 => $uuid2,
            (string) $uuid3 => $uuid3,
        ];

        $unit = new IndexOfUuids($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        // this test proves that when constructed with an associative
        // array, the string keys are preserved

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [
            (string) $uuid1 => $uuid1,
            (string) $uuid2 => $uuid2,
        ];

        $unit = new IndexOfUuids($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            array_keys($actualData),
        );
    }

    // ================================================================
    //
    // add()
    //
    // ----------------------------------------------------------------

    #[TestDox('->add() stores a UUID using its string representation as key')]
    public function test_add_stores_uuid_using_string_as_key(): void
    {
        // this test proves that add() derives the key from the
        // UUID's string representation and stores it at that key

        $unit = new IndexOfUuids();
        $uuid = Uuid::uuid4();

        $unit->add($uuid);

        $this->assertTrue($unit->has((string) $uuid));
        $this->assertSame($uuid, $unit->get((string) $uuid));
        $this->assertCount(1, $unit);
    }

    #[TestDox('->add() overwrites existing UUID with same string representation')]
    public function test_add_overwrites_existing_uuid(): void
    {
        // this test proves that calling add() with a UUID that has
        // the same string representation overwrites the previous
        // entry and does not increase the count

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        // adding the same UUID again should overwrite
        $unit->add($uuid);

        $this->assertSame($uuid, $unit->get((string) $uuid));
        $this->assertCount(1, $unit);
    }

    #[TestDox('->add() adds to existing data')]
    public function test_add_adds_to_existing_data(): void
    {
        // this test proves that add() adds a new UUID alongside
        // UUIDs already in the index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids([
            (string) $uuid1 => $uuid1,
            (string) $uuid2 => $uuid2,
        ]);

        $unit->add($uuid3);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
    }

    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        // this test proves that add() returns the same collection
        // instance for fluent method chaining

        $unit = new IndexOfUuids();

        $result = $unit->add(Uuid::uuid4());

        $this->assertSame($unit, $result);
    }

    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        // this test proves that add() calls can be chained
        // together fluently to build up the index

        $unit = new IndexOfUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();

        $unit->add($uuid1)
            ->add($uuid2)
            ->add($uuid3);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid1, $unit->get((string) $uuid1));
        $this->assertSame($uuid2, $unit->get((string) $uuid2));
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
    }

    // ================================================================
    //
    // set()
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() stores a UUID with a string key')]
    public function test_set_stores_uuid_with_string_key(): void
    {
        // this test proves that the inherited set() method also
        // works for storing UUIDs

        $unit = new IndexOfUuids();
        $uuid = Uuid::uuid4();

        $unit->set(key: (string) $uuid, value: $uuid);

        $this->assertSame($uuid, $unit->get((string) $uuid));
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        // this test proves that set() returns the same collection
        // instance for fluent method chaining

        $unit = new IndexOfUuids();

        $result = $unit->set(
            key: 'test-key',
            value: Uuid::uuid4(),
        );

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns true for existing key')]
    public function test_has_returns_true_for_existing_key(): void
    {
        // this test proves that has() returns true when the index
        // contains the given string key

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $actualResult = $unit->has((string) $uuid);

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        // this test proves that has() returns false when the index
        // does not contain the given key

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns false for empty index')]
    public function test_has_returns_false_for_empty_index(): void
    {
        // this test proves that has() returns false when the index
        // is empty

        $unit = new IndexOfUuids();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns true for key added via add()')]
    public function test_has_returns_true_for_key_added_via_add(): void
    {
        // this test proves that has() detects keys that were
        // auto-derived by the add() method

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $actualResult = $unit->has((string) $uuid);

        $this->assertTrue($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGet() returns UUID for existing key')]
    public function test_maybe_get_returns_uuid_for_existing_key(): void
    {
        // this test proves that maybeGet() returns the UUID stored
        // at the given key when it exists

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $actualResult = $unit->maybeGet((string) $uuid);

        $this->assertSame($uuid, $actualResult);
    }

    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        // this test proves that maybeGet() returns null when the
        // given key does not exist in the index

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns null for empty index')]
    public function test_maybe_get_returns_null_for_empty_index(): void
    {
        // this test proves that maybeGet() returns null when the
        // index is empty

        $unit = new IndexOfUuids();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns UUID added via add()')]
    public function test_maybe_get_returns_uuid_added_via_add(): void
    {
        // this test proves that maybeGet() retrieves UUIDs that
        // were stored using the add() method

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $actualResult = $unit->maybeGet((string) $uuid);

        $this->assertSame($uuid, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns UUID for existing key')]
    public function test_get_returns_uuid_for_existing_key(): void
    {
        // this test proves that get() returns the UUID stored at
        // the given key when it exists

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->get((string) $uuid2);

        $this->assertSame($uuid2, $actualResult);
    }

    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        // this test proves that get() throws a RuntimeException
        // when the given key does not exist in the index

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfUuids does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('->get() throws RuntimeException for empty index')]
    public function test_get_throws_for_empty_index(): void
    {
        // this test proves that get() throws a RuntimeException
        // when the index is empty

        $unit = new IndexOfUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfUuids does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('->get() returns UUID added via add()')]
    public function test_get_returns_uuid_added_via_add(): void
    {
        // this test proves that get() retrieves UUIDs that were
        // stored using the add() method

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $actualResult = $unit->get((string) $uuid);

        $this->assertSame($uuid, $actualResult);
    }

    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        // this test proves that the RuntimeException thrown by
        // get() includes the missing key in its message

        $unit = new IndexOfUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfUuids does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns empty array for empty index')]
    public function test_to_array_returns_empty_array_for_empty_index(): void
    {
        // this test proves that toArray() returns an empty array
        // when the index contains no data

        $unit = new IndexOfUuids();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        // this test proves that toArray() returns all the UUIDs
        // stored in the index, preserving keys

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->toArray();

        $this->assertSame(
            [
                (string) $uuid1 => $uuid1,
                (string) $uuid2 => $uuid2,
            ],
            $actualResult,
        );
    }

    #[TestDox('->toArray() returns data added via add()')]
    public function test_to_array_returns_data_added_via_add(): void
    {
        // this test proves that toArray() includes data that was
        // added using the add() method

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $unit->add($uuid3);

        $actualResult = $unit->toArray();

        $this->assertSame(
            [
                (string) $uuid1 => $uuid1,
                (string) $uuid2 => $uuid2,
                (string) $uuid3 => $uuid3,
            ],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for empty index')]
    public function test_count_returns_zero_for_empty_index(): void
    {
        // this test proves that count() returns 0 when the index
        // contains no data

        $unit = new IndexOfUuids();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in index')]
    public function test_count_returns_number_of_items(): void
    {
        // this test proves that count() returns the correct number
        // of UUIDs stored in the index

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // this test proves that the index works with PHP's built-in
        // count() function via the Countable interface

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() reflects items added via add()')]
    public function test_count_reflects_items_added_via_add(): void
    {
        // this test proves that count() correctly reflects items
        // added via the add() method

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('->count() does not increase when adding the same UUID')]
    public function test_count_does_not_increase_on_duplicate(): void
    {
        // this test proves that adding the same UUID twice does not
        // increase the count, because the key is the same

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $unit->add($uuid);

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

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Index can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // this test proves that the index can be used in a foreach
        // loop via the IteratorAggregate interface

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $unit->add($uuid3);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            [
                (string) $uuid1 => $uuid1,
                (string) $uuid2 => $uuid2,
                (string) $uuid3 => $uuid3,
            ],
            $actualData,
        );
    }

    #[TestDox('Iterating empty index produces no iterations')]
    public function test_iterating_empty_index_produces_no_iterations(): void
    {
        // this test proves that iterating over an empty index does
        // not execute the loop body

        $unit = new IndexOfUuids();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration keys match UUID string representations')]
    public function test_iteration_keys_match_uuid_strings(): void
    {
        // this test proves that the keys produced during iteration
        // match the string representations of the stored UUIDs

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            $actualKeys,
        );
    }

    #[TestDox('Iteration values are UuidInterface instances')]
    public function test_iteration_values_are_uuid_instances(): void
    {
        // this test proves that each value produced during iteration
        // is a UuidInterface instance

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        foreach ($unit as $value) {
            // ----------------------------------------------------------------
            // test the results

            $this->assertInstanceOf(UuidInterface::class, $value);
        }
    }

    #[TestDox('Iteration key matches the string representation of its value')]
    public function test_iteration_key_matches_value_string(): void
    {
        // this test proves that during iteration, each key is the
        // string representation of its corresponding UUID value

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        foreach ($unit as $key => $value) {
            // ----------------------------------------------------------------
            // test the results

            $this->assertSame((string) $value, $key);
        }
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('->merge() can merge an array into the index')]
    public function test_merge_can_merge_array(): void
    {
        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);

        $result = $unit->merge([
            (string) $uuid2 => $uuid2,
            (string) $uuid3 => $uuid3,
        ]);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid2, $unit->get((string) $uuid2));
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another IndexOfUuids')]
    public function test_merge_can_merge_index(): void
    {
        // this test proves that merge() can accept another
        // IndexOfUuids and merge its contents

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $other = new IndexOfUuids();
        $other->add($uuid2);
        $other->add($uuid3);

        $result = $unit->merge($other);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid2, $unit->get((string) $uuid2));
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeArray()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeArray() adds array items to the index')]
    public function test_merge_array_adds_items(): void
    {
        // this test proves that mergeArray() adds the given array's
        // key-value pairs to the index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);

        $result = $unit->mergeArray([
            (string) $uuid2 => $uuid2,
            (string) $uuid3 => $uuid3,
        ]);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid2, $unit->get((string) $uuid2));
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty index sets the data')]
    public function test_merge_array_into_empty_index(): void
    {
        // this test proves that mergeArray() works correctly when
        // the index is initially empty

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();

        $unit->mergeArray([
            (string) $uuid1 => $uuid1,
            (string) $uuid2 => $uuid2,
        ]);

        $this->assertSame(
            [
                (string) $uuid1 => $uuid1,
                (string) $uuid2 => $uuid2,
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() with empty array leaves index unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // this test proves that merging an empty array does not
        // alter the index's existing data

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $expectedData = $unit->toArray();

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() overwrites matching keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        // this test proves that when merging an array with matching
        // keys, the merged UUIDs overwrite the originals

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        // create a replacement for uuid1's key
        $replacement = Uuid::uuid4();

        $unit->mergeArray([(string) $uuid1 => $replacement]);

        $this->assertSame(
            $replacement,
            $unit->get((string) $uuid1),
        );
        $this->assertCount(2, $unit);
    }

    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // this test proves that mergeArray() returns the same index
        // instance for fluent method chaining

        $unit = new IndexOfUuids();

        $uuid = Uuid::uuid4();
        $result = $unit->mergeArray([(string) $uuid => $uuid]);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another index into this one')]
    public function test_merge_self_merges_index(): void
    {
        // this test proves that mergeSelf() adds the contents of
        // another IndexOfUuids into this index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $other = new IndexOfUuids();
        $other->add($uuid2);
        $other->add($uuid3);

        $result = $unit->mergeSelf($other);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid2, $unit->get((string) $uuid2));
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source index')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // this test proves that the index being merged from is not
        // modified by the merge operation

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $other = new IndexOfUuids();
        $other->add($uuid2);

        $unit->mergeSelf($other);

        $this->assertCount(1, $other);
        $this->assertSame(
            [(string) $uuid2 => $uuid2],
            $other->toArray(),
        );
    }

    #[TestDox('->mergeSelf() with empty source leaves index unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // this test proves that merging an empty index does not
        // alter the existing data

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $expectedData = $unit->toArray();
        $other = new IndexOfUuids();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        // this test proves that when merging an index with matching
        // keys, the merged UUIDs overwrite the originals

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        // create a replacement for uuid1's key in the other index
        $replacement = Uuid::uuid4();
        $other = new IndexOfUuids();
        $other->set(key: (string) $uuid1, value: $replacement);

        $unit->mergeSelf($other);

        $this->assertSame(
            $replacement,
            $unit->get((string) $uuid1),
        );
        $this->assertCount(2, $unit);
    }

    // ================================================================
    //
    // maybeFirst() / first()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first UUID')]
    public function test_maybe_first_returns_first_uuid(): void
    {
        // this test proves that maybeFirst() returns the UUID at
        // the first position in the index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->maybeFirst();

        $this->assertSame($uuid1, $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty index')]
    public function test_maybe_first_returns_null_for_empty_index(): void
    {
        // this test proves that maybeFirst() returns null when the
        // index is empty

        $unit = new IndexOfUuids();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->first() returns the first UUID')]
    public function test_first_returns_first_uuid(): void
    {
        // this test proves that first() returns the UUID at the
        // first position in the index when it is not empty

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->first();

        $this->assertSame($uuid1, $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty index')]
    public function test_first_throws_for_empty_index(): void
    {
        // this test proves that first() throws a RuntimeException
        // when the index is empty

        $unit = new IndexOfUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('IndexOfUuids is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast() / last()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last UUID')]
    public function test_maybe_last_returns_last_uuid(): void
    {
        // this test proves that maybeLast() returns the UUID at
        // the last position in the index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->maybeLast();

        $this->assertSame($uuid2, $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty index')]
    public function test_maybe_last_returns_null_for_empty_index(): void
    {
        // this test proves that maybeLast() returns null when the
        // index is empty

        $unit = new IndexOfUuids();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->last() returns the last UUID')]
    public function test_last_returns_last_uuid(): void
    {
        // this test proves that last() returns the UUID at the
        // last position in the index when it is not empty

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->last();

        $this->assertSame($uuid2, $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty index')]
    public function test_last_throws_for_empty_index(): void
    {
        // this test proves that last() throws a RuntimeException
        // when the index is empty

        $unit = new IndexOfUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('IndexOfUuids is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new IndexOfUuids with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // this test proves that copy() returns a new IndexOfUuids
        // instance containing the same data as the original

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $copy = $unit->copy();

        $this->assertInstanceOf(IndexOfUuids::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($unit->toArray(), $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (adding to copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // this test proves that adding to the copied index does not
        // affect the original index's data

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);

        $copy = $unit->copy();
        $copy->add($uuid2);

        $this->assertCount(1, $unit);
        $this->assertCount(2, $copy);
        $this->assertFalse($unit->has((string) $uuid2));
    }

    #[TestDox('->copy() of empty index returns empty index')]
    public function test_copy_of_empty_index(): void
    {
        // this test proves that copying an empty index returns a
        // new, empty IndexOfUuids instance

        $unit = new IndexOfUuids();

        $copy = $unit->copy();

        $this->assertInstanceOf(IndexOfUuids::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    #[TestDox('->copy() shares UUID references with original')]
    public function test_copy_shares_uuid_references(): void
    {
        // this test proves that copy() creates a shallow copy —
        // the copied index contains references to the same UUID
        // instances, not new objects

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $copy = $unit->copy();

        $this->assertSame(
            $unit->get((string) $uuid),
            $copy->get((string) $uuid),
        );
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('->empty() returns true for empty index')]
    public function test_empty_returns_true_for_empty_index(): void
    {
        // this test proves that empty() returns true when the
        // index has no data

        $unit = new IndexOfUuids();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty index')]
    public function test_empty_returns_false_for_non_empty_index(): void
    {
        // this test proves that empty() returns false when the
        // index contains data

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    #[TestDox('->empty() returns false after add()')]
    public function test_empty_returns_false_after_add(): void
    {
        // this test proves that empty() returns false after a UUID
        // has been added via add()

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns "IndexOfUuids"')]
    public function test_get_collection_type_as_string(): void
    {
        // this test proves that getCollectionTypeAsString() returns
        // "IndexOfUuids" (the class name without namespace)

        $unit = new IndexOfUuids();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('IndexOfUuids', $actualResult);
    }

    // ================================================================
    //
    // Single-item indexes
    //
    // ----------------------------------------------------------------

    #[TestDox('Index with one UUID: ->first() and ->last() return the same UUID')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // this test proves that for an index with exactly one UUID,
        // both first() and last() return that same UUID

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame($uuid, $first);
        $this->assertSame($uuid, $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('->add() and merge methods support fluent chaining together')]
    public function test_add_and_merge_support_chaining(): void
    {
        // this test proves that add() and merge methods can be
        // chained together fluently

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $other = new IndexOfUuids();
        $other->add($uuid3);

        $unit->add($uuid1)
            ->mergeArray([(string) $uuid2 => $uuid2])
            ->mergeSelf($other);

        $this->assertCount(3, $unit);
        $this->assertSame($uuid1, $unit->get((string) $uuid1));
        $this->assertSame($uuid2, $unit->get((string) $uuid2));
        $this->assertSame($uuid3, $unit->get((string) $uuid3));
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() and ->maybeGet() return same UUID for existing key')]
    public function test_get_and_maybe_get_return_same_uuid(): void
    {
        // this test proves that get() and maybeGet() return the
        // same UUID instance when the key exists

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $getResult = $unit->get((string) $uuid);
        $maybeGetResult = $unit->maybeGet((string) $uuid);

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
        // this test proves that UUIDs stored in the index are the
        // same instances (not cloned copies)

        $uuid = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid);

        $retrieved = $unit->get((string) $uuid);

        $this->assertSame($uuid, $retrieved);
    }

    #[TestDox('All stored values implement UuidInterface')]
    public function test_all_stored_values_are_uuids(): void
    {
        // this test proves that all values retrieved from the index
        // implement UuidInterface

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertInstanceOf(UuidInterface::class, $value);
        }
    }

    #[TestDox('Each UUID has a unique string representation')]
    public function test_each_uuid_has_unique_string(): void
    {
        // this test proves that each UUID stored in the index has
        // a unique string representation

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $strings = [];
        foreach ($unit as $value) {
            $strings[] = (string) $value;
        }

        $this->assertCount(3, array_unique($strings));
    }

    // ================================================================
    //
    // toArrayOfStrings()
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArrayOfStrings() returns empty array for empty index')]
    public function test_to_array_of_strings_returns_empty_for_empty_index(): void
    {
        // this test proves that toArrayOfStrings() returns an
        // empty array when the index contains no UUIDs

        $unit = new IndexOfUuids();

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArrayOfStrings() returns UUID string representations')]
    public function test_to_array_of_strings_returns_string_representations(): void
    {
        // this test proves that toArrayOfStrings() returns each
        // UUID converted to its string representation

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);
        $unit->add($uuid3);

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame(
            [
                (string) $uuid1 => (string) $uuid1,
                (string) $uuid2 => (string) $uuid2,
                (string) $uuid3 => (string) $uuid3,
            ],
            $actualResult,
        );
    }

    #[TestDox('->toArrayOfStrings() preserves string keys')]
    public function test_to_array_of_strings_preserves_keys(): void
    {
        // this test proves that toArrayOfStrings() preserves the
        // original string keys from the index

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            array_keys($actualResult),
        );
    }

    #[TestDox('->toArrayOfStrings() returns valid UUID strings')]
    public function test_to_array_of_strings_returns_valid_uuid_strings(): void
    {
        // this test proves that each string returned by
        // toArrayOfStrings() is a valid UUID string

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $actualResult = $unit->toArrayOfStrings();

        foreach ($actualResult as $uuidString) {
            $this->assertIsString($uuidString);
            $this->assertTrue(Uuid::isValid($uuidString));
        }
    }

    #[TestDox('->toArrayOfStrings() includes items added via add()')]
    public function test_to_array_of_strings_includes_items_from_add(): void
    {
        // this test proves that toArrayOfStrings() includes items
        // that were added via the add() method

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $actualResult = $unit->toArrayOfStrings();

        $this->assertSame(
            [
                (string) $uuid1 => (string) $uuid1,
                (string) $uuid2 => (string) $uuid2,
            ],
            $actualResult,
        );
    }

    #[TestDox('->toArrayOfStrings() values match UUID toString()')]
    public function test_to_array_of_strings_matches_to_string(): void
    {
        // this test proves that each value in the array returned
        // by toArrayOfStrings() matches calling toString() on the
        // corresponding UUID object

        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfUuids();
        $unit->add($uuid1);
        $unit->add($uuid2);

        $stringResult = $unit->toArrayOfStrings();
        $objectResult = $unit->toArray();

        foreach ($objectResult as $key => $uuid) {
            $this->assertSame(
                $uuid->toString(),
                $stringResult[$key],
            );
        }
    }

    #[TestDox('->toArrayOfStrings() keys match toArray() keys')]
    public function test_to_array_of_strings_keys_match_to_array_keys(): void
    {
        // this test proves that toArrayOfStrings() returns the same
        // keys as toArray(), confirming consistency

        $unit = new IndexOfUuids();
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());
        $unit->add(Uuid::uuid4());

        $stringKeys = array_keys($unit->toArrayOfStrings());
        $arrayKeys = array_keys($unit->toArray());

        $this->assertSame($arrayKeys, $stringKeys);
    }
}
