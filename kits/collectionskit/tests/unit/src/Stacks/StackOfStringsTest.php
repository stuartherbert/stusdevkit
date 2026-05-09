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

namespace StusDevKit\CollectionsKit\Tests\Unit\Stacks;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\CollectionsKit\Exceptions\EmptyStackException;
use StusDevKit\CollectionsKit\Stacks\CollectionAsStack;
use StusDevKit\CollectionsKit\Stacks\StackOfStrings;

#[TestDox(StackOfStrings::class)]
class StackOfStringsTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Stacks namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract — every
        // caller imports the class by FQN, so moving it is a
        // breaking change that must go through a major version
        // bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Stacks';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            StackOfStrings::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published kind (class vs interface vs trait) is part
        // of the contract — switching kinds breaks every consumer
        // that depends on this type.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(StackOfStrings::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('extends CollectionAsStack')]
    public function test_extends_CollectionAsStack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parent class fixes which inherited methods are
        // available; changing it is a breaking change for every
        // subclass and caller that relies on inherited behaviour.

        // ----------------------------------------------------------------
        // setup your test

        $expected = CollectionAsStack::class;

        // ----------------------------------------------------------------
        // perform the change

        $parent = (new ReflectionClass(
            StackOfStrings::class,
        ))->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($parent);
        $this->assertSame($expected, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------
    //
    // StackOfStrings is a type-narrowed alias of
    // CollectionAsStack<string> — its public surface is entirely
    // inherited from the parent. Pinning the empty list of own
    // public methods catches an accidental new method (which would
    // belong on the parent if it should apply to all stacks).
    //
    // ----------------------------------------------------------------

    #[TestDox('declares no public methods of its own beyond inherited methods')]
    public function test_declares_no_own_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // StackOfStrings exists purely to narrow the value type;
        // any method declared here would belong on
        // CollectionAsStack instead so every stack subclass picks
        // it up.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $reflection = new ReflectionClass(StackOfStrings::class);

        // ----------------------------------------------------------------
        // perform the change

        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StackOfStrings::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $ownMethods);
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty stack')]
    public function test_can_instantiate_empty_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a new, empty
        // StackOfStrings

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            StackOfStrings::class,
            $unit,
        );
        $this->assertInstanceOf(
            CollectionOfAnything::class,
            $unit,
        );
        $this->assertCount(0, $unit);
        $this->assertTrue($unit->empty());
    }

    // ================================================================
    //
    // push
    //
    // ----------------------------------------------------------------

    #[TestDox('->push() adds a value to the top of the stack')]
    public function test_push_adds_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that push() adds a value to
        // the top of the stack and increments the count

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->push('first');

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertFalse($unit->empty());
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->push() returns the stack for chaining')]
    public function test_push_returns_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that push() returns the stack
        // instance for method chaining

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->push('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('->push() maintains LIFO order')]
    public function test_push_maintains_lifo_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that multiple pushes maintain
        // last-in-first-out order — peek returns the most
        // recently pushed value

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(3, $unit);
        $this->assertSame('third', $unit->peek());
    }

    // ================================================================
    //
    // pop
    //
    // ----------------------------------------------------------------

    #[TestDox('->pop() removes and returns the top value')]
    public function test_pop_removes_top(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pop() removes the top
        // value from the stack and returns it

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->pop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('second', $result);
        $this->assertCount(1, $unit);
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->pop() on empty stack throws RuntimeException')]
    public function test_pop_empty_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pop() throws when the
        // stack is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $this->expectException(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->pop();

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('->maybePop() returns null on empty stack')]
    public function test_maybe_pop_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybePop() returns null
        // instead of throwing when the stack is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->maybePop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    #[TestDox('->maybePop() removes and returns the top value')]
    public function test_maybe_pop_removes_top(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybePop() behaves like
        // pop() when the stack is non-empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('hello');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->maybePop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $result);
        $this->assertTrue($unit->empty());
    }

    // ================================================================
    //
    // peek
    //
    // ----------------------------------------------------------------

    #[TestDox('->peek() returns the top value without removing it')]
    public function test_peek_does_not_remove(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that peek() returns the top
        // value without modifying the stack

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('hello');

        // ----------------------------------------------------------------
        // perform the change

        $result1 = $unit->peek();
        $result2 = $unit->peek();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $result1);
        $this->assertSame('hello', $result2);
        $this->assertCount(1, $unit);
    }

    #[TestDox('->peek() on empty stack throws RuntimeException')]
    public function test_peek_empty_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that peek() throws when the
        // stack is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $this->expectException(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->peek();

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('->maybePeek() returns null on empty stack')]
    public function test_maybe_peek_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybePeek() returns null
        // instead of throwing when the stack is empty

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->maybePeek();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    // ================================================================
    //
    // LIFO order verification
    //
    // ----------------------------------------------------------------

    #[TestDox('->push() and ->pop() maintain LIFO order')]
    public function test_lifo_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves the full LIFO contract: values
        // come out in reverse order of insertion

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        // ----------------------------------------------------------------
        // perform the change

        $r1 = $unit->pop();
        $r2 = $unit->pop();
        $r3 = $unit->pop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('third', $r1);
        $this->assertSame('second', $r2);
        $this->assertSame('first', $r3);
        $this->assertTrue($unit->empty());
    }

    // ================================================================
    //
    // Iteration
    //
    // ----------------------------------------------------------------

    #[TestDox('foreach iterates in LIFO order')]
    public function test_foreach_iterates_lifo(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating a stack with
        // foreach yields values in LIFO order (last
        // pushed first)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        // ----------------------------------------------------------------
        // perform the change

        $result = [];
        foreach ($unit as $value) {
            $result[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['third', 'second', 'first'],
            $result,
        );
    }

    #[TestDox('foreach on empty stack produces no iterations')]
    public function test_foreach_empty_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating an empty stack
        // produces no iterations

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $result = [];
        foreach ($unit as $value) {
            $result[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $result);
    }

    #[TestDox('->toArray() returns values in LIFO order')]
    public function test_to_array_returns_lifo_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that toArray() returns the elements
        // in LIFO order (last pushed first), mirroring the
        // behavior of pop() and the iterator

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['third', 'second', 'first'],
            $result,
        );
    }

    #[TestDox('iteration does not modify the stack')]
    public function test_iteration_does_not_modify(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iterating a stack does
        // not remove any elements — the stack is
        // unchanged after iteration

        // ----------------------------------------------------------------
        // setup your test

        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            // consume the iterator
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame('second', $unit->peek());
    }
}
