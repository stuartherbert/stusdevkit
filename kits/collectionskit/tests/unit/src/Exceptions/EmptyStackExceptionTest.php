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

namespace StusDevKit\CollectionsKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\CollectionsKit\Exceptions\EmptyCollectionException;
use StusDevKit\CollectionsKit\Exceptions\EmptyStackException;

/**
 * Contract + behaviour tests for EmptyStackException.
 *
 * EmptyStackException is a thin marker subclass of
 * EmptyCollectionException - it adds no methods of its own and
 * inherits the full constructor and accessor surface from its
 * parent. These tests pin the subclass relationship and the
 * empty-body shape so any future drift (e.g. someone adding a
 * bespoke method or overriding the constructor) surfaces here
 * rather than as a silent API expansion.
 */
#[TestDox(EmptyStackException::class)]
class EmptyStackExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import EmptyStackException by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(EmptyStackException::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // EmptyStackException is a concrete throwable class - not a
        // trait, not an interface, not an enum. Pinning this prevents
        // a silent reshape (e.g. promoting to an interface) from
        // slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('extends EmptyCollectionException')]
    public function test_extends_empty_collection_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parent class chain is what gives EmptyStackException its
        // RFC 9457 problem-details wire format and its fixed-shape
        // constructor. Swapping the parent for something else would
        // silently drop the inherited type URL, status 500, and
        // title-composition behaviour.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($actual);
        $this->assertSame(
            EmptyCollectionException::class,
            $actual->getName(),
        );
    }

    #[TestDox('declares no methods of its own')]
    public function test_declares_no_methods_of_its_own(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // EmptyStackException exists solely as a type-level marker so
        // callers can `catch (EmptyStackException $e)` for the specific
        // stack case. Any method declared directly on this subclass is
        // a surface-area expansion the parent would not pick up, so
        // the declared-here method set is pinned as the empty list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $reflection = new ReflectionClass(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === EmptyStackException::class,
            ),
        ));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() inherits from the parent and composes the title from the type')]
    public function test_construct_inherits_and_composes_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // because EmptyStackException has an empty body, constructing
        // it delegates entirely to EmptyCollectionException's
        // constructor - which composes the title as "{type} is empty".
        // Pinning this end-to-end confirms the inheritance chain wires
        // up correctly and that no accidental override has crept in
        // that would shadow the parent's behaviour.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new EmptyStackException(
            collectionType: 'StackOfStrings',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('StackOfStrings is empty', $unit->getTitle());
    }
}
