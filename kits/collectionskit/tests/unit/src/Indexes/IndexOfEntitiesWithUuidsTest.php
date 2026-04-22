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
use StusDevKit\CollectionsKit\Indexes\IndexOfEntitiesWithUuids;
use StusDevKit\CollectionsKit\Tests\Fixtures\EntityWithUuidFixture;

#[TestDox('IndexOfEntitiesWithUuids')]
class IndexOfEntitiesWithUuidsTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Indexes namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(IndexOfEntitiesWithUuids::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Indexes',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(IndexOfEntitiesWithUuids::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends DictOfObjects')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(IndexOfEntitiesWithUuids::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Dictionaries\DictOfObjects::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares add/getIds/getIdsAsStrings as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(IndexOfEntitiesWithUuids::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === IndexOfEntitiesWithUuids::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['add', 'getIds', 'getIdsAsStrings'], $ownMethods);
    }

    #[TestDox('::add() signature: add(EntityWithUuid $input): static')]
    public function test_add_signature(): void
    {
        $method = new \ReflectionMethod(IndexOfEntitiesWithUuids::class, 'add');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['input'], $paramNames);
        $inputType = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $inputType);
        $this->assertSame(\StusDevKit\CollectionsKit\Contracts\EntityWithUuid::class, $inputType->getName());
    }

    #[TestDox('::getIds() signature: getIds(): array')]
    public function test_getIds_signature(): void
    {
        $method = new \ReflectionMethod(IndexOfEntitiesWithUuids::class, 'getIds');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::getIdsAsStrings() signature: getIdsAsStrings(): array')]
    public function test_getIdsAsStrings_signature(): void
    {
        $method = new \ReflectionMethod(IndexOfEntitiesWithUuids::class, 'getIdsAsStrings');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty index')]
    public function test_can_instantiate_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $this->assertInstanceOf(
            IndexOfEntitiesWithUuids::class,
            $unit,
        );
        $this->assertCount(0, $unit);
    }

    #[TestDox('Extends DictOfObjects')]
    public function test_extends_dict_of_objects(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $this->assertInstanceOf(DictOfObjects::class, $unit);
    }

    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $expectedData = [
            (string) $uuid1 => $entity1,
            (string) $uuid2 => $entity2,
        ];

        $unit = new IndexOfEntitiesWithUuids($expectedData);

        $this->assertCount(2, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $expectedData = [
            (string) $uuid1 => new EntityWithUuidFixture(
                id: $uuid1,
                name: 'Alice',
            ),
            (string) $uuid2 => new EntityWithUuidFixture(
                id: $uuid2,
                name: 'Bob',
            ),
        ];

        $unit = new IndexOfEntitiesWithUuids($expectedData);
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

    #[TestDox('->add() stores an entity using its UUID string as key')]
    public function test_add_stores_entity_using_uuid_as_key(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $uuid = Uuid::uuid4();
        $entity = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );

        $unit->add($entity);

        $this->assertTrue($unit->has((string) $uuid));
        $this->assertSame($entity, $unit->get((string) $uuid));
        $this->assertCount(1, $unit);
    }

    #[TestDox('->add() overwrites existing entity with same UUID')]
    public function test_add_overwrites_existing_entity(): void
    {
        $uuid = Uuid::uuid4();
        $original = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $replacement = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice Updated',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($original);

        $unit->add($replacement);

        $this->assertSame(
            $replacement,
            $unit->get((string) $uuid),
        );
        $this->assertNotSame(
            $original,
            $unit->get((string) $uuid),
        );
        $this->assertCount(1, $unit);
    }

    #[TestDox('->add() adds to existing data')]
    public function test_add_adds_to_existing_data(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $entity3 = new EntityWithUuidFixture(
            id: $uuid3,
            name: 'Charlie',
        );
        $unit = new IndexOfEntitiesWithUuids([
            (string) $uuid1 => $entity1,
            (string) $uuid2 => $entity2,
        ]);

        $unit->add($entity3);

        $this->assertCount(3, $unit);
        $this->assertSame($entity3, $unit->get((string) $uuid3));
    }

    #[TestDox('->add() returns $this for method chaining')]
    public function test_add_returns_this(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $result = $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));

        $this->assertSame($unit, $result);
    }

    #[TestDox('->add() supports fluent chaining')]
    public function test_add_supports_fluent_chaining(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $entity3 = new EntityWithUuidFixture(
            id: $uuid3,
            name: 'Charlie',
        );

        $unit->add($entity1)
            ->add($entity2)
            ->add($entity3);

        $this->assertCount(3, $unit);
        $this->assertSame($entity1, $unit->get((string) $uuid1));
        $this->assertSame($entity2, $unit->get((string) $uuid2));
        $this->assertSame($entity3, $unit->get((string) $uuid3));
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns true for existing key')]
    public function test_has_returns_true_for_existing_key(): void
    {
        $uuid = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        ));

        $actualResult = $unit->has((string) $uuid);

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns false for empty index')]
    public function test_has_returns_false_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGet() returns entity for existing key')]
    public function test_maybe_get_returns_entity_for_existing_key(): void
    {
        $uuid = Uuid::uuid4();
        $entity = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity);

        $actualResult = $unit->maybeGet((string) $uuid);

        $this->assertSame($entity, $actualResult);
    }

    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns null for empty index')]
    public function test_maybe_get_returns_null_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns the overwritten entity after add()')]
    public function test_maybe_get_returns_overwritten_entity(): void
    {
        $uuid = Uuid::uuid4();
        $original = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $replacement = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice Updated',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($original);
        $unit->add($replacement);

        $actualResult = $unit->maybeGet((string) $uuid);

        $this->assertSame($replacement, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns entity for existing key')]
    public function test_get_returns_entity_for_existing_key(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $actualResult = $unit->get((string) $uuid2);

        $this->assertSame($entity2, $actualResult);
    }

    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithUuids does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('->get() throws RuntimeException for empty index')]
    public function test_get_throws_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithUuids does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithUuids does not contain '
            . 'my-special-key',
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
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $actualResult = $unit->toArray();

        $this->assertSame(
            [
                (string) $uuid1 => $entity1,
                (string) $uuid2 => $entity2,
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
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in index')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Charlie',
        ));

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        ));

        $actualResult = count($unit);

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('->count() does not increase when overwriting an entity')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        $uuid = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        ));

        $unit->add(new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice Updated',
        ));

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
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Index can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            [
                (string) $uuid1 => $entity1,
                (string) $uuid2 => $entity2,
            ],
            $actualData,
        );
    }

    #[TestDox('Iterating empty index produces no iterations')]
    public function test_iterating_empty_index_produces_no_iterations(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration keys match entity UUID strings')]
    public function test_iteration_keys_match_uuid_strings(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            $actualKeys,
        );
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('->merge() can merge an array into the index')]
    public function test_merge_can_merge_array(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);

        $result = $unit->merge([(string) $uuid2 => $entity2]);

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get((string) $uuid2));
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another IndexOfEntitiesWithUuids')]
    public function test_merge_can_merge_index(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $other = new IndexOfEntitiesWithUuids();
        $other->add($entity2);

        $result = $unit->merge($other);

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get((string) $uuid2));
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
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);

        $result = $unit->mergeArray([(string) $uuid2 => $entity2]);

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get((string) $uuid2));
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() overwrites matching keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        $uuid = Uuid::uuid4();
        $original = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $replacement = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice Updated',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($original);

        $unit->mergeArray([(string) $uuid => $replacement]);

        $this->assertSame(
            $replacement,
            $unit->get((string) $uuid),
        );
        $this->assertCount(1, $unit);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another index into this one')]
    public function test_merge_self_merges_index(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $other = new IndexOfEntitiesWithUuids();
        $other->add($entity2);

        $result = $unit->mergeSelf($other);

        $this->assertCount(2, $unit);
        $this->assertSame($entity2, $unit->get((string) $uuid2));
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source index')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $entity1 = new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $other = new IndexOfEntitiesWithUuids();
        $other->add($entity2);

        $unit->mergeSelf($other);

        $this->assertCount(1, $other);
        $this->assertSame(
            [(string) $uuid2 => $entity2],
            $other->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst() / first()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first entity')]
    public function test_maybe_first_returns_first_entity(): void
    {
        $entity1 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $actualResult = $unit->maybeFirst();

        $this->assertSame($entity1, $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty index')]
    public function test_maybe_first_returns_null_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->first() returns the first entity')]
    public function test_first_returns_first_entity(): void
    {
        $entity1 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $actualResult = $unit->first();

        $this->assertSame($entity1, $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty index')]
    public function test_first_throws_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithUuids is empty',
        );

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast() / last()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last entity')]
    public function test_maybe_last_returns_last_entity(): void
    {
        $entity1 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $actualResult = $unit->maybeLast();

        $this->assertSame($entity2, $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty index')]
    public function test_maybe_last_returns_null_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->last() returns the last entity')]
    public function test_last_returns_last_entity(): void
    {
        $entity1 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $actualResult = $unit->last();

        $this->assertSame($entity2, $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty index')]
    public function test_last_throws_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'IndexOfEntitiesWithUuids is empty',
        );

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new index with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $entity1 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);
        $unit->add($entity2);

        $copy = $unit->copy();

        $this->assertInstanceOf(
            IndexOfEntitiesWithUuids::class,
            $copy,
        );
        $this->assertNotSame($unit, $copy);
        $this->assertSame($unit->toArray(), $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (adding to copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $entity1 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $entity2 = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity1);

        $copy = $unit->copy();
        $copy->add($entity2);

        $this->assertCount(1, $unit);
        $this->assertCount(2, $copy);
    }

    #[TestDox('->copy() of empty index returns empty index')]
    public function test_copy_of_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $copy = $unit->copy();

        $this->assertInstanceOf(
            IndexOfEntitiesWithUuids::class,
            $copy,
        );
        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    #[TestDox('->copy() shares entity references with original')]
    public function test_copy_shares_entity_references(): void
    {
        $uuid = Uuid::uuid4();
        $entity = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity);

        $copy = $unit->copy();
        /** @var EntityWithUuidFixture $copyEntity */
        $copyEntity = $copy->get((string) $uuid);
        $copyEntity->name = 'Alice Mutated';

        /** @var EntityWithUuidFixture $originalEntity */
        $originalEntity = $unit->get((string) $uuid);
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

    #[TestDox('->empty() returns true for empty index')]
    public function test_empty_returns_true_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty index')]
    public function test_empty_returns_false_for_non_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns "IndexOfEntitiesWithUuids"')]
    public function test_get_collection_type_as_string(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame(
            'IndexOfEntitiesWithUuids',
            $actualResult,
        );
    }

    // ================================================================
    //
    // Single-item indexes
    //
    // ----------------------------------------------------------------

    #[TestDox('Index with one entity: ->first() and ->last() return the same entity')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $entity = new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame($entity, $first);
        $this->assertSame($entity, $last);
    }

    // ================================================================
    //
    // get() and maybeGet() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() and ->maybeGet() return same entity for existing key')]
    public function test_get_and_maybe_get_return_same_entity(): void
    {
        $uuid = Uuid::uuid4();
        $entity = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity);

        $getResult = $unit->get((string) $uuid);
        $maybeGetResult = $unit->maybeGet((string) $uuid);

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
        $uuid = Uuid::uuid4();
        $entity = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity);

        $retrieved = $unit->get((string) $uuid);

        $this->assertSame($entity, $retrieved);
        $this->assertSame('Alice', $retrieved->name);
    }

    #[TestDox('Mutations to retrieved entity are visible through the index')]
    public function test_mutations_visible_through_index(): void
    {
        $uuid = Uuid::uuid4();
        $entity = new EntityWithUuidFixture(
            id: $uuid,
            name: 'Alice',
        );
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add($entity);

        /** @var EntityWithUuidFixture $retrieved */
        $retrieved = $unit->get((string) $uuid);
        $retrieved->name = 'Alice Updated';

        /** @var EntityWithUuidFixture $updatedEntity */
        $updatedEntity = $unit->get((string) $uuid);
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

    #[TestDox('->getIds() returns empty array for empty index')]
    public function test_get_ids_returns_empty_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->getIds();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->getIds() returns UuidInterface objects')]
    public function test_get_ids_returns_uuid_objects(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));

        $actualResult = $unit->getIds();

        foreach ($actualResult as $id) {
            $this->assertInstanceOf(UuidInterface::class, $id);
        }
    }

    #[TestDox('->getIds() returns the same UUID instances from the entities')]
    public function test_get_ids_returns_same_uuid_instances(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));

        $actualResult = $unit->getIds();

        $this->assertSame($uuid1, $actualResult[(string) $uuid1]);
        $this->assertSame($uuid2, $actualResult[(string) $uuid2]);
    }

    #[TestDox('->getIds() preserves string keys from the index')]
    public function test_get_ids_preserves_string_keys(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));

        $actualResult = $unit->getIds();

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            array_keys($actualResult),
        );
    }

    #[TestDox('->getIds() does not contain duplicates after overwrite')]
    public function test_get_ids_no_duplicates_after_overwrite(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));

        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice Updated',
        ));
        $actualResult = $unit->getIds();

        $this->assertCount(2, $actualResult);
    }

    // ================================================================
    //
    // getIdsAsStrings()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIdsAsStrings() returns empty array for empty index')]
    public function test_get_ids_as_strings_returns_empty_for_empty_index(): void
    {
        $unit = new IndexOfEntitiesWithUuids();

        $actualResult = $unit->getIdsAsStrings();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->getIdsAsStrings() returns UUID string representations')]
    public function test_get_ids_as_strings_returns_uuid_strings(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid3,
            name: 'Charlie',
        ));

        $actualResult = $unit->getIdsAsStrings();

        $this->assertSame(
            [(string) $uuid1, (string) $uuid2, (string) $uuid3],
            $actualResult,
        );
    }

    #[TestDox('->getIdsAsStrings() returns all strings')]
    public function test_get_ids_as_strings_returns_all_strings(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        ));

        $actualResult = $unit->getIdsAsStrings();

        foreach ($actualResult as $id) {
            $this->assertIsString($id);
        }
    }

    #[TestDox('->getIdsAsStrings() returns valid UUID strings')]
    public function test_get_ids_as_strings_returns_valid_uuids(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        ));

        $actualResult = $unit->getIdsAsStrings();

        foreach ($actualResult as $uuidString) {
            $this->assertTrue(Uuid::isValid($uuidString));
        }
    }

    #[TestDox('->getIdsAsStrings() preserves insertion order')]
    public function test_get_ids_as_strings_preserves_order(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $uuid3 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid3,
            name: 'Charlie',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));

        $actualResult = $unit->getIdsAsStrings();

        $this->assertSame(
            [(string) $uuid3, (string) $uuid1, (string) $uuid2],
            $actualResult,
        );
    }

    #[TestDox('->getIdsAsStrings() does not contain duplicates after overwrite')]
    public function test_get_ids_as_strings_no_duplicates_after_overwrite(): void
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: $uuid2,
            name: 'Bob',
        ));

        $unit->add(new EntityWithUuidFixture(
            id: $uuid1,
            name: 'Alice Updated',
        ));
        $actualResult = $unit->getIdsAsStrings();

        $this->assertCount(2, $actualResult);
        $this->assertSame(
            [(string) $uuid1, (string) $uuid2],
            $actualResult,
        );
    }

    #[TestDox('->getIdsAsStrings() matches keys from toArray()')]
    public function test_get_ids_as_strings_matches_to_array_keys(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        ));

        $ids = $unit->getIdsAsStrings();
        $arrayKeys = array_keys($unit->toArray());

        $this->assertSame($arrayKeys, $ids);
    }

    // ================================================================
    //
    // getIds() and getIdsAsStrings() consistency
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIds() UUID strings match getIdsAsStrings()')]
    public function test_get_ids_strings_match_get_ids_as_strings(): void
    {
        $unit = new IndexOfEntitiesWithUuids();
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Alice',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Bob',
        ));
        $unit->add(new EntityWithUuidFixture(
            id: Uuid::uuid4(),
            name: 'Charlie',
        ));

        $uuidObjects = $unit->getIds();
        $uuidStrings = $unit->getIdsAsStrings();

        $castStrings = array_map(
            fn(UuidInterface $id) => (string) $id,
            $uuidObjects,
        );
        $this->assertSame(
            array_values($uuidStrings),
            array_values($castStrings),
        );
    }
}
