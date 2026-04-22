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
use StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox('CollectionAsDict')]
class CollectionAsDictTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Dictionaries namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(CollectionAsDict::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Dictionaries',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(CollectionAsDict::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends AccessibleCollection')]
    public function test_extends_AccessibleCollection(): void
    {
        $reflection = new \ReflectionClass(CollectionAsDict::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\AccessibleCollection::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares only get/has/maybeGet/set as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(CollectionAsDict::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === CollectionAsDict::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['get', 'has', 'maybeGet', 'set'], $ownMethods);
    }

    #[TestDox('::set() signature: set(mixed $key, mixed $value): static')]
    public function test_set_signature(): void
    {
        $method = new \ReflectionMethod(CollectionAsDict::class, 'set');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['key', 'value'], $paramNames);
        foreach ($method->getParameters() as $param) {
            $type = $param->getType();
            $this->assertInstanceOf(\ReflectionNamedType::class, $type);
            $this->assertSame('mixed', $type->getName());
        }
    }

    #[TestDox('::get() signature: get($key): mixed')]
    public function test_get_signature(): void
    {
        $method = new \ReflectionMethod(CollectionAsDict::class, 'get');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['key'], $paramNames);
    }

    #[TestDox('::maybeGet() signature: maybeGet($key): mixed')]
    public function test_maybeGet_signature(): void
    {
        $method = new \ReflectionMethod(CollectionAsDict::class, 'maybeGet');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['key'], $paramNames);
    }

    #[TestDox('::has() signature: has($key): bool')]
    public function test_has_signature(): void
    {
        $method = new \ReflectionMethod(CollectionAsDict::class, 'has');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['key'], $paramNames);
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty dictionary')]
    public function test_can_instantiate_empty_dictionary(): void
    {
        $unit = new CollectionAsDict();

        $this->assertInstanceOf(CollectionAsDict::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];

        $unit = new CollectionAsDict($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        $expectedData = [
            'x' => 'alpha',
            'y' => 'bravo',
            'z' => 'charlie',
        ];

        $unit = new CollectionAsDict($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame(['x', 'y', 'z'], array_keys($actualData));
    }

    #[TestDox('::__construct() accepts integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        $expectedData = [
            10 => 'alpha',
            20 => 'bravo',
            30 => 'charlie',
        ];

        $unit = new CollectionAsDict($expectedData);

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
        $unit = new CollectionAsDict();

        $unit->set(key: 'name', value: 'alpha');

        $this->assertSame(['name' => 'alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        $unit = new CollectionAsDict();

        $unit->set(key: 42, value: 'alpha');

        $this->assertSame([42 => 'alpha'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() overwrites existing value at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $unit->set(key: 'name', value: 'bravo');

        $this->assertSame(['name' => 'bravo'], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() adds to existing data')]
    public function test_set_adds_to_existing_data(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        $unit->set(key: 'third', value: 'charlie');

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    #[TestDox('->set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        $unit = new CollectionAsDict();

        $result = $unit->set(key: 'name', value: 'alpha');

        $this->assertSame($unit, $result);
    }

    #[TestDox('->set() supports fluent chaining')]
    public function test_set_supports_fluent_chaining(): void
    {
        $unit = new CollectionAsDict();

        $unit->set(key: 'first', value: 'alpha')
            ->set(key: 'second', value: 'bravo')
            ->set(key: 'third', value: 'charlie');

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
    }

    #[TestDox('->set() can store values of different types')]
    public function test_set_can_store_mixed_types(): void
    {
        $unit = new CollectionAsDict();

        $unit->set(key: 'string', value: 'hello');
        $unit->set(key: 'int', value: 42);
        $unit->set(key: 'float', value: 3.14);
        $unit->set(key: 'bool', value: true);
        $unit->set(key: 'array', value: ['nested' => 'data']);

        $this->assertSame(
            [
                'string' => 'hello',
                'int' => 42,
                'float' => 3.14,
                'bool' => true,
                'array' => ['nested' => 'data'],
            ],
            $unit->toArray(),
        );
        $this->assertCount(5, $unit);
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_string_key(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $actualResult = $unit->has('name');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns true for existing integer key')]
    public function test_has_returns_true_for_existing_integer_key(): void
    {
        $unit = new CollectionAsDict([42 => 'alpha']);

        $actualResult = $unit->has(42);

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns true for key added via set()')]
    public function test_has_returns_true_for_key_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

        $actualResult = $unit->has('name');

        $this->assertTrue($actualResult);
    }

    #[TestDox('::__construct() rejects null values')]
    public function test_constructor_rejects_null_values(): void
    {
        $this->expectException(NullValueNotAllowedException::class);

        new CollectionAsDict(['name' => null]); // @phpstan-ignore argument.type
    }

    // ================================================================
    //
    // maybeGet()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGet() returns value for existing key')]
    public function test_maybe_get_returns_value_for_existing_key(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        $actualResult = $unit->maybeGet('first');

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns value added via set()')]
    public function test_maybe_get_returns_value_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

        $actualResult = $unit->maybeGet('name');

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->maybeGet() returns value with integer key')]
    public function test_maybe_get_returns_value_with_integer_key(): void
    {
        $unit = new CollectionAsDict([42 => 'alpha']);

        $actualResult = $unit->maybeGet(42);

        $this->assertSame('alpha', $actualResult);
    }


    #[TestDox('->maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);
        $unit->set(key: 'name', value: 'bravo');

        $actualResult = $unit->maybeGet('name');

        $this->assertSame('bravo', $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns value for existing key')]
    public function test_get_returns_value_for_existing_key(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        $actualResult = $unit->get('second');

        $this->assertSame('bravo', $actualResult);
    }

    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'CollectionAsDict does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('->get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'CollectionAsDict does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('->get() returns value added via set()')]
    public function test_get_returns_value_added_via_set(): void
    {
        /** @var CollectionAsDict<string, string> $unit */
        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

        $actualResult = $unit->get('name');

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->get() returns value with integer key')]
    public function test_get_returns_value_with_integer_key(): void
    {
        $unit = new CollectionAsDict([42 => 'alpha']);

        $actualResult = $unit->get(42);

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        $unit = new CollectionAsDict();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'CollectionAsDict does not contain my-special-key',
        );

        $unit->get('my-special-key');
    }

    // ================================================================
    //
    // Arrayable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns empty array for empty dict')]
    public function test_to_array_returns_empty_array_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];
        $unit = new CollectionAsDict($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() returns data added via set()')]
    public function test_to_array_returns_data_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

        $actualResult = $unit->toArray();

        $this->assertSame(
            ['first' => 'alpha', 'second' => 'bravo'],
            $actualResult,
        );
    }

    // ================================================================
    //
    // Countable interface
    //
    // ----------------------------------------------------------------

    #[TestDox('->count() returns 0 for empty dict')]
    public function test_count_returns_zero_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() reflects items added via set()')]
    public function test_count_reflects_items_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('->count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $unit->set(key: 'name', value: 'bravo');

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
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];
        $unit = new CollectionAsDict($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_set_produces_no_iterations(): void
    {
        $unit = new CollectionAsDict();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(['first', 'second', 'third'], $actualKeys);
    }

    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            ['first' => 'alpha', 'second' => 'bravo'],
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
        $unit = new CollectionAsDict(['first' => 'alpha']);
        $toMerge = ['second' => 'bravo', 'third' => 'charlie'];

        $result = $unit->merge($toMerge);

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another CollectionAsDict')]
    public function test_merge_can_merge_collection(): void
    {
        $unit = new CollectionAsDict(['first' => 'alpha']);
        $other = new CollectionAsDict([
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $result = $unit->merge($other);

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
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
        $unit = new CollectionAsDict(['first' => 'alpha']);
        $toMerge = ['second' => 'bravo', 'third' => 'charlie'];

        $result = $unit->mergeArray($toMerge);

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty dict dicts the data')]
    public function test_merge_array_into_empty_set(): void
    {
        /** @var CollectionAsDict<string, string> $unit */
        $unit = new CollectionAsDict();
        $toMerge = ['first' => 'alpha', 'second' => 'bravo'];

        $unit->mergeArray($toMerge);

        $this->assertSame(
            ['first' => 'alpha', 'second' => 'bravo'],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = ['first' => 'alpha', 'second' => 'bravo'];
        $unit = new CollectionAsDict($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        $unit = new CollectionAsDict([
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
        $unit = new CollectionAsDict(['first' => 'alpha']);

        $result = $unit->mergeArray(['second' => 'bravo']);

        $this->assertSame($unit, $result);
    }

    // ================================================================
    //
    // mergeSelf()
    //
    // ----------------------------------------------------------------

    #[TestDox('->mergeSelf() merges another dict into this one')]
    public function test_merge_self_merges_set(): void
    {
        $unit = new CollectionAsDict(['first' => 'alpha']);
        $other = new CollectionAsDict([
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new CollectionAsDict(['first' => 'alpha']);
        $other = new CollectionAsDict(['second' => 'bravo']);
        $expectedOtherData = ['second' => 'bravo'];

        $unit->mergeSelf($other);

        $this->assertSame($expectedOtherData, $other->toArray());
    }

    #[TestDox('->mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = ['first' => 'alpha', 'second' => 'bravo'];
        $unit = new CollectionAsDict($expectedData);
        $other = new CollectionAsDict();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        $unit = new CollectionAsDict([
            'name' => 'alpha',
            'value' => 100,
        ]);
        $other = new CollectionAsDict([
            'value' => 200,
            'extra' => 'new',
        ]);

        $unit->mergeSelf($other);

        $this->assertSame(
            ['name' => 'alpha', 'value' => 200, 'extra' => 'new'],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first item')]
    public function test_maybe_first_returns_first_item(): void
    {
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeFirst() returns the first item added via set()')]
    public function test_maybe_first_returns_first_item_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

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
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $actualResult = $unit->first();

        $this->assertSame('alpha', $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsDict is empty');

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
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $actualResult = $unit->maybeLast();

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeLast() returns the last item added via set()')]
    public function test_maybe_last_returns_last_item_added_via_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'first', value: 'alpha');
        $unit->set(key: 'second', value: 'bravo');

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
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ]);

        $actualResult = $unit->last();

        $this->assertSame('charlie', $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CollectionAsDict is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new CollectionAsDict with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = [
            'first' => 'alpha',
            'second' => 'bravo',
            'third' => 'charlie',
        ];
        $unit = new CollectionAsDict($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(CollectionAsDict::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $originalData = ['first' => 'alpha', 'second' => 'bravo'];
        $unit = new CollectionAsDict($originalData);

        $copy = $unit->copy();
        $copy->set(key: 'third', value: 'charlie');

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
            ],
            $copy->toArray(),
        );
    }

    #[TestDox('->copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $copy = $unit->copy();

        $this->assertInstanceOf(CollectionAsDict::class, $copy);
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
    public function test_empty_returns_true_for_empty_set(): void
    {
        $unit = new CollectionAsDict();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_set(): void
    {
        $unit = new CollectionAsDict(['name' => 'alpha']);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    #[TestDox('->empty() returns false after set()')]
    public function test_empty_returns_false_after_set(): void
    {
        $unit = new CollectionAsDict();
        $unit->set(key: 'name', value: 'alpha');

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
        $unit = new CollectionAsDict();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('CollectionAsDict', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('dict with one item: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new CollectionAsDict(['only' => 'item']);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame('item', $first);
        $this->assertSame('item', $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() and merge methods support fluent chaining together')]
    public function test_set_and_merge_support_chaining(): void
    {
        /** @var CollectionAsDict<string, string> $unit */
        $unit = new CollectionAsDict();
        $other = new CollectionAsDict(['fourth' => 'delta']);

        $unit->set(key: 'first', value: 'alpha')
            ->mergeArray([
                'second' => 'bravo',
                'third' => 'charlie',
            ])
            ->mergeSelf($other);

        $this->assertSame(
            [
                'first' => 'alpha',
                'second' => 'bravo',
                'third' => 'charlie',
                'fourth' => 'delta',
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
        $unit = new CollectionAsDict([
            'first' => 'alpha',
            'second' => 'bravo',
        ]);

        $getResult = $unit->get('first');
        $maybeGetResult = $unit->maybeGet('first');

        $this->assertSame('alpha', $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }
}
