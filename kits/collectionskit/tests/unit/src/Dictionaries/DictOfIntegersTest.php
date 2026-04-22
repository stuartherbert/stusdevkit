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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers;

#[TestDox('DictOfIntegers')]
class DictOfIntegersTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Dictionaries namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(DictOfIntegers::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Dictionaries',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(DictOfIntegers::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends DictOfNumbers')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(DictOfIntegers::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers::class,
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
        $reflection = new \ReflectionClass(DictOfIntegers::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === DictOfIntegers::class) {
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

    #[TestDox('::__construct() creates an empty dict')]
    public function test_can_instantiate_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $this->assertInstanceOf(DictOfIntegers::class, $unit);
        $this->assertCount(0, $unit);
    }

    #[TestDox('::__construct() accepts initial data')]
    public function test_can_instantiate_with_initial_data(): void
    {
        $expectedData = [
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ];

        $unit = new DictOfIntegers($expectedData);

        $this->assertCount(3, $unit);
        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('::__construct() preserves string keys')]
    public function test_constructor_preserves_string_keys(): void
    {
        $expectedData = [
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ];

        $unit = new DictOfIntegers($expectedData);
        $actualData = $unit->toArray();

        $this->assertSame(
            ['width', 'height', 'depth'],
            array_keys($actualData),
        );
    }

    #[TestDox('::__construct() accepts integer keys')]
    public function test_can_instantiate_with_integer_keys(): void
    {
        $expectedData = [
            10 => 100,
            20 => 200,
            30 => 300,
        ];

        $unit = new DictOfIntegers($expectedData);

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
        $unit = new DictOfIntegers();

        $unit->set(key: 'width', value: 1920);

        $this->assertSame(['width' => 1920], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() stores a value with an integer key')]
    public function test_set_stores_value_with_integer_key(): void
    {
        $unit = new DictOfIntegers();

        $unit->set(key: 42, value: 100);

        $this->assertSame([42 => 100], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() overwrites existing value at same key')]
    public function test_set_overwrites_existing_value(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $unit->set(key: 'width', value: 2560);

        $this->assertSame(['width' => 2560], $unit->toArray());
        $this->assertCount(1, $unit);
    }

    #[TestDox('->set() adds to existing data')]
    public function test_set_adds_to_existing_data(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $unit->set(key: 'depth', value: 32);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
            ],
            $unit->toArray(),
        );
        $this->assertCount(3, $unit);
    }

    #[TestDox('->set() returns $this for method chaining')]
    public function test_set_returns_this(): void
    {
        $unit = new DictOfIntegers();

        $result = $unit->set(key: 'width', value: 1920);

        $this->assertSame($unit, $result);
    }

    #[TestDox('->set() supports fluent chaining')]
    public function test_set_supports_fluent_chaining(): void
    {
        $unit = new DictOfIntegers();

        $unit->set(key: 'width', value: 1920)
            ->set(key: 'height', value: 1080)
            ->set(key: 'depth', value: 32);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
            ],
            $unit->toArray(),
        );
    }

    /**
     * @return array<string, array{0: int}>
     */
    public static function provideIntegerVariants(): array
    {
        return [
            'positive integer' => [42],
            'negative integer' => [-99],
            'zero' => [0],
            'one' => [1],
            'negative one' => [-1],
            'large positive' => [PHP_INT_MAX],
            'large negative' => [PHP_INT_MIN],
            'power of two' => [1024],
            'hex-friendly value' => [255],
            'small negative' => [-7],
        ];
    }

    #[TestDox('->set() accepts various integer values')]
    #[DataProvider('provideIntegerVariants')]
    public function test_set_accepts_various_integer_values(
        int $input,
    ): void {
        $unit = new DictOfIntegers();

        $unit->set(key: 'value', value: $input);

        $this->assertCount(1, $unit);
        $this->assertSame($input, $unit->get('value'));
    }

    // ================================================================
    //
    // has()
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns true for existing string key')]
    public function test_has_returns_true_for_existing_string_key(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $actualResult = $unit->has('width');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns true for key with zero value')]
    public function test_has_returns_true_for_key_with_zero_value(): void
    {
        $unit = new DictOfIntegers(['offset' => 0]);

        $actualResult = $unit->has('offset');

        $this->assertTrue($actualResult);
    }

    #[TestDox('->has() returns false for missing key')]
    public function test_has_returns_false_for_missing_key(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $actualResult = $unit->has('missing');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns false for empty dict')]
    public function test_has_returns_false_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $actualResult = $unit->has('anything');

        $this->assertFalse($actualResult);
    }

    #[TestDox('->has() returns true for key added via set()')]
    public function test_has_returns_true_for_key_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);

        $actualResult = $unit->has('width');

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
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $actualResult = $unit->maybeGet('width');

        $this->assertSame(1920, $actualResult);
    }

    #[TestDox('->maybeGet() returns null for missing key')]
    public function test_maybe_get_returns_null_for_missing_key(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $actualResult = $unit->maybeGet('missing');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns null for empty dict')]
    public function test_maybe_get_returns_null_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $actualResult = $unit->maybeGet('anything');

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeGet() returns value added via set()')]
    public function test_maybe_get_returns_value_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);

        $actualResult = $unit->maybeGet('width');

        $this->assertSame(1920, $actualResult);
    }

    #[TestDox('->maybeGet() returns value with integer key')]
    public function test_maybe_get_returns_value_with_integer_key(): void
    {
        $unit = new DictOfIntegers([42 => 100]);

        $actualResult = $unit->maybeGet(42);

        $this->assertSame(100, $actualResult);
    }

    #[TestDox('->maybeGet() returns the overwritten value after set()')]
    public function test_maybe_get_returns_overwritten_value(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);
        $unit->set(key: 'width', value: 2560);

        $actualResult = $unit->maybeGet('width');

        $this->assertSame(2560, $actualResult);
    }

    // ================================================================
    //
    // get()
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns value for existing key')]
    public function test_get_returns_value_for_existing_key(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $actualResult = $unit->get('height');

        $this->assertSame(1080, $actualResult);
    }

    #[TestDox('->get() throws RuntimeException for missing key')]
    public function test_get_throws_for_missing_key(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfIntegers does not contain missing',
        );

        $unit->get('missing');
    }

    #[TestDox('->get() throws RuntimeException for empty dict')]
    public function test_get_throws_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfIntegers does not contain anything',
        );

        $unit->get('anything');
    }

    #[TestDox('->get() returns value added via set()')]
    public function test_get_returns_value_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);

        $actualResult = $unit->get('width');

        $this->assertSame(1920, $actualResult);
    }

    #[TestDox('->get() returns value with integer key')]
    public function test_get_returns_value_with_integer_key(): void
    {
        $unit = new DictOfIntegers([42 => 100]);

        $actualResult = $unit->get(42);

        $this->assertSame(100, $actualResult);
    }

    #[TestDox('->get() exception message includes the missing key')]
    public function test_get_exception_includes_key(): void
    {
        $unit = new DictOfIntegers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'DictOfIntegers does not contain my-special-key',
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
        $unit = new DictOfIntegers();

        $actualResult = $unit->toArray();

        $this->assertSame([], $actualResult);
    }

    #[TestDox('->toArray() returns the internal data as a PHP array')]
    public function test_to_array_returns_internal_data(): void
    {
        $expectedData = [
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ];
        $unit = new DictOfIntegers($expectedData);

        $actualResult = $unit->toArray();

        $this->assertSame($expectedData, $actualResult);
    }

    #[TestDox('->toArray() returns data added via set()')]
    public function test_to_array_returns_data_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);
        $unit->set(key: 'height', value: 1080);

        $actualResult = $unit->toArray();

        $this->assertSame(
            ['width' => 1920, 'height' => 1080],
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
        $unit = new DictOfIntegers();

        $actualResult = $unit->count();

        $this->assertSame(0, $actualResult);
    }

    #[TestDox('->count() returns number of items in dict')]
    public function test_count_returns_number_of_items(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ]);

        $actualResult = $unit->count();

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() works with PHP count() function')]
    public function test_count_works_with_php_count_function(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ]);

        $actualResult = count($unit);

        $this->assertSame(3, $actualResult);
    }

    #[TestDox('->count() reflects items added via set()')]
    public function test_count_reflects_items_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);
        $unit->set(key: 'height', value: 1080);

        $actualResult = $unit->count();

        $this->assertSame(2, $actualResult);
    }

    #[TestDox('->count() does not increase when overwriting a key')]
    public function test_count_does_not_increase_on_overwrite(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $unit->set(key: 'width', value: 2560);

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
        $unit = new DictOfIntegers(['width' => 1920]);

        $actualResult = $unit->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $actualResult);
    }

    #[TestDox('Dict can be iterated with foreach')]
    public function test_can_iterate_with_foreach(): void
    {
        $expectedData = [
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ];
        $unit = new DictOfIntegers($expectedData);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame($expectedData, $actualData);
    }

    #[TestDox('Iterating empty dict produces no iterations')]
    public function test_iterating_empty_dict_produces_no_iterations(): void
    {
        $unit = new DictOfIntegers();
        $iterationCount = 0;

        foreach ($unit as $value) {
            $iterationCount++;
        }

        $this->assertSame(0, $iterationCount);
    }

    #[TestDox('Iteration preserves string keys')]
    public function test_iteration_preserves_string_keys(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ]);
        $actualKeys = [];

        foreach ($unit as $key => $value) {
            $actualKeys[] = $key;
        }

        $this->assertSame(['width', 'height', 'depth'], $actualKeys);
    }

    #[TestDox('Iteration includes items added via set()')]
    public function test_iteration_includes_items_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);
        $unit->set(key: 'height', value: 1080);
        $actualData = [];

        foreach ($unit as $key => $value) {
            $actualData[$key] = $value;
        }

        $this->assertSame(
            ['width' => 1920, 'height' => 1080],
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
        $unit = new DictOfIntegers(['width' => 1920]);

        $result = $unit->merge([
            'height' => 1080,
            'depth' => 32,
        ]);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->merge() can merge another DictOfIntegers')]
    public function test_merge_can_merge_dict_of_integers(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);
        $other = new DictOfIntegers([
            'height' => 1080,
            'depth' => 32,
        ]);

        $result = $unit->merge($other);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
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
        $unit = new DictOfIntegers(['width' => 1920]);

        $result = $unit->mergeArray([
            'height' => 1080,
            'depth' => 32,
        ]);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeArray() into empty dict sets the data')]
    public function test_merge_array_into_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $unit->mergeArray(['width' => 1920, 'height' => 1080]);

        $this->assertSame(
            ['width' => 1920, 'height' => 1080],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() with empty array leaves dict unchanged')]
    public function test_merge_array_with_empty_array(): void
    {
        $expectedData = ['width' => 1920, 'height' => 1080];
        $unit = new DictOfIntegers($expectedData);

        $unit->mergeArray([]);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeArray() overwrites matching string keys')]
    public function test_merge_array_overwrites_matching_keys(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $unit->mergeArray(['height' => 1440, 'depth' => 48]);

        $this->assertSame(
            ['width' => 1920, 'height' => 1440, 'depth' => 48],
            $unit->toArray(),
        );
    }

    #[TestDox('->mergeArray() returns $this for method chaining')]
    public function test_merge_array_returns_this(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $result = $unit->mergeArray(['height' => 1080]);

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
        $unit = new DictOfIntegers(['width' => 1920]);
        $other = new DictOfIntegers([
            'height' => 1080,
            'depth' => 32,
        ]);

        $result = $unit->mergeSelf($other);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
            ],
            $unit->toArray(),
        );
        $this->assertSame($unit, $result);
    }

    #[TestDox('->mergeSelf() does not modify the source dict')]
    public function test_merge_self_does_not_modify_source(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);
        $other = new DictOfIntegers(['height' => 1080]);

        $unit->mergeSelf($other);

        $this->assertSame(['height' => 1080], $other->toArray());
    }

    #[TestDox('->mergeSelf() with empty source leaves dict unchanged')]
    public function test_merge_self_with_empty_source(): void
    {
        $expectedData = ['width' => 1920, 'height' => 1080];
        $unit = new DictOfIntegers($expectedData);
        $other = new DictOfIntegers();

        $unit->mergeSelf($other);

        $this->assertSame($expectedData, $unit->toArray());
    }

    #[TestDox('->mergeSelf() overwrites matching keys')]
    public function test_merge_self_overwrites_matching_keys(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);
        $other = new DictOfIntegers([
            'height' => 1440,
            'depth' => 48,
        ]);

        $unit->mergeSelf($other);

        $this->assertSame(
            ['width' => 1920, 'height' => 1440, 'depth' => 48],
            $unit->toArray(),
        );
    }

    // ================================================================
    //
    // maybeFirst()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeFirst() returns the first integer')]
    public function test_maybe_first_returns_first_integer(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(1920, $actualResult);
    }

    #[TestDox('->maybeFirst() returns null for empty dict')]
    public function test_maybe_first_returns_null_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $actualResult = $unit->maybeFirst();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeFirst() returns the first integer added via set()')]
    public function test_maybe_first_returns_first_integer_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);
        $unit->set(key: 'height', value: 1080);

        $actualResult = $unit->maybeFirst();

        $this->assertSame(1920, $actualResult);
    }

    // ================================================================
    //
    // first()
    //
    // ----------------------------------------------------------------

    #[TestDox('->first() returns the first integer')]
    public function test_first_returns_first_integer(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $actualResult = $unit->first();

        $this->assertSame(1920, $actualResult);
    }

    #[TestDox('->first() throws RuntimeException for empty dict')]
    public function test_first_throws_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfIntegers is empty');

        $unit->first();
    }

    // ================================================================
    //
    // maybeLast()
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeLast() returns the last integer')]
    public function test_maybe_last_returns_last_integer(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $actualResult = $unit->maybeLast();

        $this->assertSame(1080, $actualResult);
    }

    #[TestDox('->maybeLast() returns null for empty dict')]
    public function test_maybe_last_returns_null_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $actualResult = $unit->maybeLast();

        $this->assertNull($actualResult);
    }

    #[TestDox('->maybeLast() returns the last integer added via set()')]
    public function test_maybe_last_returns_last_integer_added_via_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);
        $unit->set(key: 'height', value: 1080);

        $actualResult = $unit->maybeLast();

        $this->assertSame(1080, $actualResult);
    }

    // ================================================================
    //
    // last()
    //
    // ----------------------------------------------------------------

    #[TestDox('->last() returns the last integer')]
    public function test_last_returns_last_integer(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $actualResult = $unit->last();

        $this->assertSame(1080, $actualResult);
    }

    #[TestDox('->last() throws RuntimeException for empty dict')]
    public function test_last_throws_for_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DictOfIntegers is empty');

        $unit->last();
    }

    // ================================================================
    //
    // copy()
    //
    // ----------------------------------------------------------------

    #[TestDox('->copy() returns a new DictOfIntegers with the same data')]
    public function test_copy_returns_new_instance_with_same_data(): void
    {
        $expectedData = [
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ];
        $unit = new DictOfIntegers($expectedData);

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfIntegers::class, $copy);
        $this->assertNotSame($unit, $copy);
        $this->assertSame($expectedData, $copy->toArray());
    }

    #[TestDox('->copy() returns independent instance (modifying copy does not affect original)')]
    public function test_copy_returns_independent_instance(): void
    {
        $originalData = ['width' => 1920, 'height' => 1080];
        $unit = new DictOfIntegers($originalData);

        $copy = $unit->copy();
        $copy->set(key: 'depth', value: 32);

        $this->assertSame($originalData, $unit->toArray());
        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
            ],
            $copy->toArray(),
        );
    }

    #[TestDox('->copy() of empty dict returns empty dict')]
    public function test_copy_of_empty_dict(): void
    {
        $unit = new DictOfIntegers();

        $copy = $unit->copy();

        $this->assertInstanceOf(DictOfIntegers::class, $copy);
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
        $unit = new DictOfIntegers();

        $actualResult = $unit->empty();

        $this->assertTrue($actualResult);
    }

    #[TestDox('->empty() returns false for non-empty dict')]
    public function test_empty_returns_false_for_non_empty_dict(): void
    {
        $unit = new DictOfIntegers(['width' => 1920]);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    #[TestDox('->empty() returns false after set()')]
    public function test_empty_returns_false_after_set(): void
    {
        $unit = new DictOfIntegers();
        $unit->set(key: 'width', value: 1920);

        $actualResult = $unit->empty();

        $this->assertFalse($actualResult);
    }

    // ================================================================
    //
    // getCollectionTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCollectionTypeAsString() returns "DictOfIntegers"')]
    public function test_get_collection_type_as_string_returns_class_basename(): void
    {
        $unit = new DictOfIntegers();

        $actualResult = $unit->getCollectionTypeAsString();

        $this->assertSame('DictOfIntegers', $actualResult);
    }

    // ================================================================
    //
    // Single-item dicts
    //
    // ----------------------------------------------------------------

    #[TestDox('Dict with one integer: ->first() and ->last() return the same value')]
    public function test_single_item_first_and_last_are_same(): void
    {
        $unit = new DictOfIntegers(['only' => 42]);

        $first = $unit->first();
        $last = $unit->last();

        $this->assertSame(42, $first);
        $this->assertSame(42, $last);
    }

    // ================================================================
    //
    // Method chaining
    //
    // ----------------------------------------------------------------

    #[TestDox('->set() and merge methods support fluent chaining together')]
    public function test_set_and_merge_support_chaining(): void
    {
        $unit = new DictOfIntegers();
        $other = new DictOfIntegers(['depth' => 32]);

        $unit->set(key: 'width', value: 1920)
            ->mergeArray(['height' => 1080])
            ->mergeSelf($other);

        $this->assertSame(
            [
                'width' => 1920,
                'height' => 1080,
                'depth' => 32,
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
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
        ]);

        $getResult = $unit->get('width');
        $maybeGetResult = $unit->maybeGet('width');

        $this->assertSame(1920, $getResult);
        $this->assertSame($getResult, $maybeGetResult);
    }

    // ================================================================
    //
    // Integer-specific behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('All stored values are integers')]
    public function test_all_stored_values_are_integers(): void
    {
        $unit = new DictOfIntegers([
            'width' => 1920,
            'height' => 1080,
            'depth' => 32,
        ]);

        $actualResult = $unit->toArray();

        foreach ($actualResult as $value) {
            $this->assertIsInt($value);
        }
    }

    #[TestDox('Handles negative integers correctly')]
    public function test_handles_negative_integers(): void
    {
        $unit = new DictOfIntegers([
            'loss' => -100,
            'adjustment' => -1,
        ]);

        $this->assertSame(-100, $unit->get('loss'));
        $this->assertSame(-1, $unit->get('adjustment'));
    }

    #[TestDox('Handles boundary integer values')]
    public function test_handles_boundary_integer_values(): void
    {
        $unit = new DictOfIntegers([
            'max' => PHP_INT_MAX,
            'min' => PHP_INT_MIN,
        ]);

        $this->assertSame(PHP_INT_MAX, $unit->get('max'));
        $this->assertSame(PHP_INT_MIN, $unit->get('min'));
    }

    #[TestDox('Handles zero value correctly')]
    public function test_handles_zero_value(): void
    {
        $unit = new DictOfIntegers(['offset' => 0]);

        $this->assertSame(0, $unit->get('offset'));
        $this->assertTrue($unit->has('offset'));
        $this->assertSame(0, $unit->maybeGet('offset'));
    }
}
