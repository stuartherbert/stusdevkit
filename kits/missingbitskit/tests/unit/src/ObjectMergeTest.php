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

namespace StusDevKit\MissingBitsKit\Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionFunction;
use ReflectionParameter;

use function StusDevKit\MissingBitsKit\object_merge;

#[TestDox('object_merge()')]
class ObjectMergeTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is a function in the StusDevKit\\MissingBitsKit namespace')]
    public function test_exists_in_expected_namespace(): void
    {
        $this->assertTrue(
            \function_exists(
                'StusDevKit\\MissingBitsKit\\object_merge',
            ),
        );
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\object_merge',
        );
        $this->assertSame(
            'StusDevKit\\MissingBitsKit',
            $reflection->getNamespaceName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::object_merge() parameter names in order')]
    public function test_parameter_names(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\object_merge',
        );
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $reflection->getParameters(),
        );
        $this->assertSame(['target', 'sources'], $paramNames);
    }

    #[TestDox('::object_merge() parameter types in order')]
    public function test_parameter_types(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\object_merge',
        );
        $paramTypes = array_map(
            fn(ReflectionParameter $p) => (string) $p->getType(),
            $reflection->getParameters(),
        );
        $this->assertSame(['object', 'object'], $paramTypes);
    }

    #[TestDox('::object_merge() variadic parameter is sources')]
    public function test_variadic_parameter(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\object_merge',
        );
        $params = $reflection->getParameters();
        $this->assertFalse($params[0]->isVariadic());
        $this->assertTrue($params[1]->isVariadic());
    }

    #[TestDox('::object_merge() return type')]
    public function test_return_type(): void
    {
        $reflection = new ReflectionFunction(
            'StusDevKit\\MissingBitsKit\\object_merge',
        );
        $this->assertSame(
            'void',
            (string) $reflection->getReturnType(),
        );
    }

    // ================================================================
    //
    // Single Source
    //
    // ----------------------------------------------------------------

    #[TestDox('copies properties from source onto target')]
    public function test_copies_properties_from_source(): void
    {
        /**
         * copies all properties from the source object onto the
         * target
         */
        $target = (object) ['name' => 'Alice'];
        $source = (object) ['age' => 30];

        object_merge($target, $source);

        $this->assertSame('Alice', $target->name);
        $this->assertSame(30, $target->age);
    }

    #[TestDox('overwrites existing properties on target')]
    public function test_overwrites_existing_properties(): void
    {
        /**
         * when source has a property that already exists on the
         * target, the target's value is overwritten
         */
        $target = (object) ['name' => 'Alice', 'age' => 25];
        $source = (object) ['age' => 30];

        object_merge($target, $source);

        $this->assertSame('Alice', $target->name);
        $this->assertSame(30, $target->age);
    }

    #[TestDox('does not modify source object')]
    public function test_does_not_modify_source(): void
    {
        /** the source object is not modified by the merge */
        $target = (object) ['name' => 'Alice'];
        $source = (object) ['age' => 30];

        object_merge($target, $source);

        $this->assertFalse(
            property_exists($source, 'name'),
        );
        $this->assertSame(30, $source->age);
    }

    #[TestDox('handles empty source object')]
    public function test_handles_empty_source(): void
    {
        /** merging an empty source leaves the target unchanged */
        $target = (object) ['name' => 'Alice'];
        $source = new \stdClass();

        object_merge($target, $source);

        $this->assertSame('Alice', $target->name);
    }

    #[TestDox('handles empty target object')]
    public function test_handles_empty_target(): void
    {
        /** merging onto an empty target copies all source properties */
        $target = new \stdClass();
        $source = (object) ['name' => 'Alice', 'age' => 30];

        object_merge($target, $source);

        $this->assertSame('Alice', $target->name);
        $this->assertSame(30, $target->age);
    }

    // ================================================================
    //
    // Multiple Sources
    //
    // ----------------------------------------------------------------

    #[TestDox('merges multiple sources in order')]
    public function test_merges_multiple_sources(): void
    {
        /**
         * accepts multiple source objects and merges them left to
         * right onto the target
         */
        $target = (object) ['name' => 'Alice'];
        $source1 = (object) ['age' => 30];
        $source2 = (object) ['email' => 'alice@example.com'];

        object_merge($target, $source1, $source2);

        $this->assertSame('Alice', $target->name);
        $this->assertSame(30, $target->age);
        $this->assertSame(
            'alice@example.com',
            $target->email,
        );
    }

    #[TestDox('later sources overwrite earlier sources')]
    public function test_later_sources_overwrite_earlier(): void
    {
        /**
         * when multiple sources have the same property, the last
         * source wins
         */
        $target = (object) ['name' => 'Alice'];
        $source1 = (object) ['name' => 'Bob'];
        $source2 = (object) ['name' => 'Charlie'];

        object_merge($target, $source1, $source2);

        $this->assertSame('Charlie', $target->name);
    }

    #[TestDox('handles no sources')]
    public function test_handles_no_sources(): void
    {
        /**
         * calling object_merge() with no source arguments leaves
         * the target unchanged
         */
        $target = (object) ['name' => 'Alice'];

        object_merge($target);

        $this->assertSame('Alice', $target->name);
    }

    // ================================================================
    //
    // Value Types
    //
    // ----------------------------------------------------------------

    #[TestDox('copies null values')]
    public function test_copies_null_values(): void
    {
        /** null property values are copied, not skipped */
        $target = (object) ['name' => 'Alice'];
        $source = (object) ['name' => null];

        object_merge($target, $source);

        $this->assertNull($target->name);
    }

    #[TestDox('copies nested objects by reference')]
    public function test_copies_nested_objects_by_reference(): void
    {
        /**
         * nested object values are copied by reference (shallow
         * copy), not cloned
         */
        $inner = (object) ['x' => 1];
        $target = new \stdClass();
        $source = (object) ['nested' => $inner];

        object_merge($target, $source);

        $this->assertSame($inner, $target->nested);
    }
}
