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

namespace StusDevKit\CollectionsKit\Tests\Unit;

use ArrayIterator;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox('AccessibleCollection')]
class AccessibleCollectionTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(AccessibleCollection::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(AccessibleCollection::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionOfAnything')]
    public function test_extends_CollectionOfAnything(): void
    {
        $reflection = new \ReflectionClass(AccessibleCollection::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\CollectionOfAnything::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares the expected set of own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(AccessibleCollection::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === AccessibleCollection::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(
            ['copy', 'first', 'last', 'maybeFirst', 'maybeLast', 'merge', 'mergeArray', 'mergeSelf'],
            $ownMethods,
        );
    }

    #[TestDox('::merge() signature: merge(self|array $input): static')]
    public function test_merge_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'merge');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['input'], $paramNames);
    }

    #[TestDox('::mergeArray() signature: mergeArray(array $input): static')]
    public function test_mergeArray_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'mergeArray');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['input'], $paramNames);
        $paramType = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $paramType);
        $this->assertSame('array', $paramType->getName());
    }

    #[TestDox('::mergeSelf() signature: mergeSelf(CollectionOfAnything $input): static')]
    public function test_mergeSelf_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'mergeSelf');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['input'], $paramNames);
        $paramType = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $paramType);
        $this->assertSame(\StusDevKit\CollectionsKit\CollectionOfAnything::class, $paramType->getName());
    }

    #[TestDox('::first() signature: first(): mixed')]
    public function test_first_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'first');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::maybeFirst() signature: maybeFirst(): mixed')]
    public function test_maybeFirst_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'maybeFirst');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::last() signature: last(): mixed')]
    public function test_last_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'last');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::maybeLast() signature: maybeLast(): mixed')]
    public function test_maybeLast_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'maybeLast');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('::copy() signature: copy(): static')]
    public function test_copy_signature(): void
    {
        $method = new \ReflectionMethod(AccessibleCollection::class, 'copy');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $this->assertSame([], $method->getParameters());
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty collection')]
    public function test_can_instantiate_empty_collection(): void
    {
        // this test proves that we can create a new, empty
        // CollectionOfAnything

        $unit = new AccessibleCollection();

        $this->assertInstanceOf(AccessibleCollection::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        // this test proves that we can create a CollectionOfAnything
        // and seed it with an initial array of data

        $expectedData = ['alpha', 'bravo', 'charlie'];

        $unit = new AccessibleCollection($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() accepts associative array')]
    public function test_can_instantiate_with_associative_array(): void
    {
        // this test proves that we can create a CollectionOfAnything
        // with string keys (associative array)

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];

        $unit = new AccessibleCollection($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns empty array for empty collection')]
    public function test_to_array_returns_empty_array_for_empty_collection(): void
    {
        // this test proves that toArray() returns an empty array
        // when the collection contains no data

        $unit = new AccessibleCollection();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        // this test proves that toArray() returns all the data
        // stored in the collection

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new AccessibleCollection($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for empty collection')]
    public function test_count_returns_zero_for_empty_collection(): void
    {
        // this test proves that count() returns 0 when the
        // collection contains no data

        $unit = new AccessibleCollection();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in collection')]
    public function test_count_returns_number_of_items(): void
    {
        // this test proves that count() returns the correct number
        // of items stored in the collection

        $unit = new AccessibleCollection(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        // this test proves that the collection works with PHP's
        // built-in count() function via the Countable interface

        $unit = new AccessibleCollection(['alpha', 'bravo', 'charlie']);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
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

        $unit = new AccessibleCollection(['alpha', 'bravo']);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Collection can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        // this test proves that the collection can be used in a
        // foreach loop via the IteratorAggregate interface

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new AccessibleCollection($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('Iterating empty collection produces no iterations')]
    public function test_iterating_empty_collection_produces_no_iterations(): void
    {
        // this test proves that iterating over an empty collection
        // does not execute the loop body

        $unit = new AccessibleCollection();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        // this test proves that iterating preserves the associative
        // keys of the underlying data

        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
        ];
        $unit = new AccessibleCollection($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    // ================================================================
    //
    // merge()
    //
    // ----------------------------------------------------------------

    #[TestDox('->merge() can merge an array into the collection')]
    public function test_merge_can_merge_array(): void
    {
        // this test proves that merge() can accept a plain PHP
        // array and merge its contents into the collection

        $unit = new AccessibleCollection(['alpha', 'bravo']);
        $toMerge = ['charlie', 'delta'];

        $result = $unit->merge($toMerge);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another collection')]
    public function test_merge_can_merge_collection(): void
    {
        // this test proves that merge() can accept another
        // CollectionOfAnything and merge its contents

        $unit = new AccessibleCollection(['alpha', 'bravo']);
        $other = new AccessibleCollection(['charlie', 'delta']);

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

    #[TestDox('->mergeArray() adds array items to the collection')]
    public function test_merge_array_adds_items(): void
    {
        // this test proves that mergeArray() appends the given
        // array's contents to the collection's data

        $unit = new AccessibleCollection(['alpha']);
        $toMerge = ['bravo', 'charlie'];

        $result = $unit->mergeArray($toMerge);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty collection sets the data')]
    public function test_merge_array_into_empty_collection(): void
    {
        // this test proves that mergeArray() works correctly when
        // the collection is initially empty

        /** @var AccessibleCollection<int, string> $unit */
        $unit = new AccessibleCollection();
        $toMerge = ['alpha', 'bravo'];

        $unit->mergeArray($toMerge);

        $this->assertSame(['alpha', 'bravo'], $unit->toArray());
    }

    #[TestDox('->mergeArray() with empty array leaves collection unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        // this test proves that merging an empty array does not
        // alter the collection's existing data

        $expectedData = ['alpha', 'bravo'];
        $unit = new AccessibleCollection($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() with associative keys overwrites matching keys')]
    public function test_merge_array_overwrites_matching_string_keys(): void
    {
        // this test proves that when merging associative arrays,
        // matching string keys are overwritten by the merged data
        // (standard PHP spread operator behavior)

        $unit = new AccessibleCollection([
            'name' => 'alpha',
            'value' => 100,
        ]);

        $unit->mergeArray(['value' => 200, 'extra' => 'new']);

        $this->assertSame(
            ['name' => 'alpha', 'value' => 200, 'extra' => 'new'],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        // this test proves that mergeArray() returns the same
        // collection instance for fluent method chaining

        $unit = new AccessibleCollection(['alpha']);

        $result = $unit->mergeArray(['bravo']);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another collection into this one')]
    public function test_merge_self_merges_collection(): void
    {
        // this test proves that mergeSelf() appends the contents
        // of another CollectionOfAnything into this collection

        $unit = new AccessibleCollection(['alpha']);
        $other = new AccessibleCollection(['bravo', 'charlie']);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source collection')]
    public function test_merge_self_does_not_modify_source(): void
    {
        // this test proves that the collection being merged from
        // is not modified by the merge operation

        $unit = new AccessibleCollection(['alpha']);
        $other = new AccessibleCollection(['bravo']);
        $expectedOtherData = ['bravo'];

        $unit->mergeSelf($other);

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    #[TestDox('->mergeSelf() with empty source leaves collection unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        // this test proves that merging an empty collection does
        // not alter the existing data

        $expectedData = ['alpha', 'bravo'];
        $unit = new AccessibleCollection($expectedData);
        $other = new AccessibleCollection();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first item')]
    public function test_maybe_first_returns_first_item(): void
    {
        // this test proves that maybeFirst() returns the first item
        // in the collection when it is not empty

        $unit = new AccessibleCollection(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->maybeFirst();

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty collection')]
    public function test_maybe_first_returns_null_for_empty_collection(): void
    {
        // this test proves that maybeFirst() returns null when the
        // collection is empty, rather than throwing an exception

        $unit = new AccessibleCollection();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeFirst() returns first item from associative array')]
    public function test_maybe_first_returns_first_from_associative(): void
    {
        // this test proves that maybeFirst() correctly returns the
        // value associated with the first key in an associative
        // collection

        $unit = new AccessibleCollection([
            'x' => 'alpha',
            'y' => 'bravo',
        ]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame('alpha', $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('->first() returns the first item')]
    public function test_first_returns_first_item(): void
    {
        // this test proves that first() returns the first item in
        // the collection when it is not empty

        $unit = new AccessibleCollection(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->first();

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty collection')]
    public function test_first_throws_for_empty_collection(): void
    {
        // this test proves that first() throws a RuntimeException
        // when the collection is empty

        $unit = new AccessibleCollection();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('AccessibleCollection is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last item')]
    public function test_maybe_last_returns_last_item(): void
    {
        // this test proves that maybeLast() returns the last item
        // in the collection when it is not empty

        $unit = new AccessibleCollection(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->maybeLast();

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty collection')]
    public function test_maybe_last_returns_null_for_empty_collection(): void
    {
        // this test proves that maybeLast() returns null when the
        // collection is empty, rather than throwing an exception

        $unit = new AccessibleCollection();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeLast() returns last item from associative array')]
    public function test_maybe_last_returns_last_from_associative(): void
    {
        // this test proves that maybeLast() correctly returns the
        // value associated with the last key in an associative
        // collection

        $unit = new AccessibleCollection([
            'x' => 'alpha',
            'y' => 'bravo',
        ]);

        $actualResult = $unit->maybeLast();

        $this->assertSame('bravo', $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('->last() returns the last item')]
    public function test_last_returns_last_item(): void
    {
        // this test proves that last() returns the last item in
        // the collection when it is not empty

        $unit = new AccessibleCollection(['alpha', 'bravo', 'charlie']);

        $actualResult = $unit->last();

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty collection')]
    public function test_last_throws_for_empty_collection(): void
    {
        // this test proves that last() throws a RuntimeException
        // when the collection is empty

        $unit = new AccessibleCollection();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('AccessibleCollection is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new instance with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        // this test proves that copy() returns a new collection
        // instance containing the same data as the original

        $expectedData = ['alpha', 'bravo', 'charlie'];
        $unit = new AccessibleCollection($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(AccessibleCollection::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        // this test proves that modifying the copied collection
        // does not affect the original collection's data

        $originalData = ['alpha', 'bravo'];
        $unit = new AccessibleCollection($originalData);

        $copy = $unit->copy();
        $copy->mergeArray(['charlie']);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            ['alpha', 'bravo', 'charlie'],
            $copy->toArray(),
        );
    }

    #[TestDox('->copy() of empty collection returns empty collection')]
    public function test_copy_of_empty_collection(): void
    {
        // this test proves that copying an empty collection
        // returns a new, empty collection instance

        $unit = new AccessibleCollection();

        $copy = $unit->copy();

        $this->assertNotSame($unit, $copy);
        $this->assertSame([], $copy->toArray());
        $this->assertCount(0, $copy);
    }

    // ================================================================
    //
    // empty()
    //
    // ----------------------------------------------------------------

    #[TestDox('->empty() returns true for empty collection')]
    public function test_empty_returns_true_for_empty_collection(): void
    {
        // this test proves that empty() returns true when the
        // collection has no data

        $unit = new AccessibleCollection();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty collection')]
    public function test_empty_returns_false_for_non_empty_collection(): void
    {
        // this test proves that empty() returns false when the
        // collection contains data

        $unit = new AccessibleCollection(['alpha']);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns the class basename')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        // this test proves that getCollectionTypeAsString() returns
        // just the class name without the namespace prefix

        $unit = new AccessibleCollection();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('AccessibleCollection', $actualResult);
    }

    // ================================================================
    //
    // Single-item collections
    //
    // ----------------------------------------------------------------

    #[TestDox('Collection with one item: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        // this test proves that for a collection with exactly one
        // item, both first() and last() return that same item

        $unit = new AccessibleCollection(['only-item']);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame('only-item', $first);
        $this->assertSame('only-item', $last);
    }

    // ================================================================
    //
    // Mixed type data
    //
    // ----------------------------------------------------------------

    #[TestDox('Collection can hold mixed types')]
    public function test_can_hold_mixed_types(): void
    {
        // this test proves that CollectionOfAnything can store
        // values of different types in the same collection

        $expectedData = [
            'a string',
            42,
            3.14,
            true,
            ['nested' => 'array'],
        ];

        $unit = new AccessibleCollection($expectedData);

        $this->assertSame($expectedData, $unit->toArray());
        $this->assertCount(5, $unit);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('Merge methods support fluent chaining')]
    public function test_merge_methods_support_chaining(): void
    {
        // this test proves that merge methods return the collection
        // instance, enabling fluent method chaining

        $unit = new AccessibleCollection(['alpha']);
        $other = new AccessibleCollection(['delta']);

        $unit
            ->mergeArray(['bravo', 'charlie'])
            ->mergeSelf($other);

        $this->assertSame(
            ['alpha', 'bravo', 'charlie', 'delta'],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // Null value rejection
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() rejects array containing null')]
    public function test_constructor_rejects_null_in_array(): void
    {
        // this test proves that the constructor throws a
        // NullValueNotAllowed exception when the initial data
        // array contains a null value

        $this->expectException(NullValueNotAllowedException::class);

        new AccessibleCollection(['alpha', null, 'bravo']); // @phpstan-ignore argument.type
    }

    #[TestDox('->mergeArray() rejects array containing null')]
    public function test_merge_array_rejects_null_in_array(): void
    {
        // this test proves that mergeArray() throws a
        // NullValueNotAllowed exception when the input array
        // contains a null value, and does not modify the
        // existing collection

        $unit = new AccessibleCollection(['alpha']);

        $this->expectException(NullValueNotAllowedException::class);

        $unit->mergeArray(['bravo', null]); // @phpstan-ignore argument.type
    }
}
