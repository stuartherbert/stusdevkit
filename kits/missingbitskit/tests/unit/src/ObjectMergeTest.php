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

use function StusDevKit\MissingBitsKit\object_merge;

#[TestDox('object_merge()')]
class ObjectMergeTest extends TestCase
{
    // ================================================================
    //
    // Single Source
    //
    // ----------------------------------------------------------------

    #[TestDox('copies properties from source onto target')]
    public function test_copies_properties_from_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that object_merge() copies all
        // properties from the source object onto the target

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];
        $source = (object) ['age' => 30];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $target->name);
        $this->assertSame(30, $target->age);
    }

    #[TestDox('overwrites existing properties on target')]
    public function test_overwrites_existing_properties(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when source has a property
        // that already exists on the target, the target's
        // value is overwritten

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice', 'age' => 25];
        $source = (object) ['age' => 30];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $target->name);
        $this->assertSame(30, $target->age);
    }

    #[TestDox('does not modify source object')]
    public function test_does_not_modify_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the source object is not
        // modified by the merge

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];
        $source = (object) ['age' => 30];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse(
            property_exists($source, 'name'),
        );
        $this->assertSame(30, $source->age);
    }

    #[TestDox('handles empty source object')]
    public function test_handles_empty_source(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging an empty source
        // leaves the target unchanged

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];
        $source = new \stdClass();

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Alice', $target->name);
    }

    #[TestDox('handles empty target object')]
    public function test_handles_empty_target(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that merging onto an empty target
        // copies all source properties

        // ----------------------------------------------------------------
        // setup your test

        $target = new \stdClass();
        $source = (object) ['name' => 'Alice', 'age' => 30];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that object_merge() accepts
        // multiple source objects and merges them left
        // to right onto the target

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];
        $source1 = (object) ['age' => 30];
        $source2 = (object) ['email' => 'alice@example.com'];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source1, $source2);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when multiple sources have
        // the same property, the last source wins

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];
        $source1 = (object) ['name' => 'Bob'];
        $source2 = (object) ['name' => 'Charlie'];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source1, $source2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Charlie', $target->name);
    }

    #[TestDox('handles no sources')]
    public function test_handles_no_sources(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling object_merge() with
        // no source arguments leaves the target unchanged

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target);

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that null property values are
        // copied, not skipped

        // ----------------------------------------------------------------
        // setup your test

        $target = (object) ['name' => 'Alice'];
        $source = (object) ['name' => null];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($target->name);
    }

    #[TestDox('copies nested objects by reference')]
    public function test_copies_nested_objects_by_reference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that nested object values are
        // copied by reference (shallow copy), not cloned

        // ----------------------------------------------------------------
        // setup your test

        $inner = (object) ['x' => 1];
        $target = new \stdClass();
        $source = (object) ['nested' => $inner];

        // ----------------------------------------------------------------
        // perform the change

        object_merge($target, $source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inner, $target->nested);
    }
}
