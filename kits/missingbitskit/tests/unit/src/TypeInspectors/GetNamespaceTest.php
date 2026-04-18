<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
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

declare(strict_types=1);

namespace StusDevKit\MissingBitsKit\Tests\Unit\TypeInspectors;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\TypeInspectors\GetNamespace;

#[TestDox(GetNamespace::class)]
class GetNamespaceTest extends TestCase
{
    // ================================================================
    //
    // from() - class name input
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns the namespace part of a fully-qualified class name')]
    public function test_from_returns_namespace_for_namespaced_class_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when given a namespaced
        // class-string, from() returns everything up to but not
        // including the final backslash separator

        // ----------------------------------------------------------------
        // setup your test

        $input = SampleClass::class;
        $expected = 'StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNamespace::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns empty string for a class name without a namespace')]
    public function test_from_returns_empty_for_global_class_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class-string with no backslash
        // separator (i.e. a class in the global namespace)
        // produces an empty namespace string

        // ----------------------------------------------------------------
        // setup your test

        $input = stdClass::class;
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNamespace::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - object instance input
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns the namespace for a namespaced object instance')]
    public function test_from_returns_namespace_for_namespaced_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when given an object, from()
        // resolves to the class name and returns the namespace
        // part

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleClass();
        $expected = 'StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNamespace::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns empty string for an object of a global-namespace class')]
    public function test_from_returns_empty_for_global_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object whose class lives in
        // the global namespace (e.g. stdClass) produces an empty
        // namespace string

        // ----------------------------------------------------------------
        // setup your test

        $input = new stdClass();
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNamespace::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
