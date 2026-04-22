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

namespace StusDevKit\CollectionsKit\Tests\Unit\Stacks;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\CollectionsKit\Exceptions\EmptyStackException;
use StusDevKit\CollectionsKit\Stacks\StackOfStrings;

#[TestDox('StackOfStrings')]
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
        $reflection = new \ReflectionClass(StackOfStrings::class);
        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Stacks',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(StackOfStrings::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionAsStack')]
    public function test_extends_parent(): void
    {
        $reflection = new \ReflectionClass(StackOfStrings::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            \StusDevKit\CollectionsKit\Stacks\CollectionAsStack::class,
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
        $reflection = new \ReflectionClass(StackOfStrings::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StackOfStrings::class) {
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

    #[TestDox('::__construct() creates an empty stack')]
    public function test_can_instantiate_empty_stack(): void
    {
        $unit = new StackOfStrings();

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
        $unit = new StackOfStrings();

        $unit->push('first');

        $this->assertCount(1, $unit);
        $this->assertFalse($unit->empty());
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->push() returns the stack for chaining')]
    public function test_push_returns_static(): void
    {
        $unit = new StackOfStrings();

        $result = $unit->push('hello');

        $this->assertSame($unit, $result);
    }

    #[TestDox('->push() maintains LIFO order')]
    public function test_push_maintains_lifo_order(): void
    {
        $unit = new StackOfStrings();

        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

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
        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');

        $result = $unit->pop();

        $this->assertSame('second', $result);
        $this->assertCount(1, $unit);
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->pop() on empty stack throws RuntimeException')]
    public function test_pop_empty_throws(): void
    {
        $unit = new StackOfStrings();

        $this->expectException(EmptyStackException::class);

        $unit->pop();
    }

    #[TestDox('->maybePop() returns null on empty stack')]
    public function test_maybe_pop_returns_null(): void
    {
        $unit = new StackOfStrings();

        $result = $unit->maybePop();

        $this->assertNull($result);
    }

    #[TestDox('->maybePop() removes and returns the top value')]
    public function test_maybe_pop_removes_top(): void
    {
        $unit = new StackOfStrings();
        $unit->push('hello');

        $result = $unit->maybePop();

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
        $unit = new StackOfStrings();
        $unit->push('hello');

        $result1 = $unit->peek();
        $result2 = $unit->peek();

        $this->assertSame('hello', $result1);
        $this->assertSame('hello', $result2);
        $this->assertCount(1, $unit);
    }

    #[TestDox('->peek() on empty stack throws RuntimeException')]
    public function test_peek_empty_throws(): void
    {
        $unit = new StackOfStrings();

        $this->expectException(EmptyStackException::class);

        $unit->peek();
    }

    #[TestDox('->maybePeek() returns null on empty stack')]
    public function test_maybe_peek_returns_null(): void
    {
        $unit = new StackOfStrings();

        $result = $unit->maybePeek();

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
        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        $r1 = $unit->pop();
        $r2 = $unit->pop();
        $r3 = $unit->pop();

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
        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        $result = [];
        foreach ($unit as $value) {
            $result[] = $value;
        }

        $this->assertSame(
            ['third', 'second', 'first'],
            $result,
        );
    }

    #[TestDox('foreach on empty stack produces no iterations')]
    public function test_foreach_empty_stack(): void
    {
        $unit = new StackOfStrings();

        $result = [];
        foreach ($unit as $value) {
            $result[] = $value;
        }

        $this->assertSame([], $result);
    }

    #[TestDox('->toArray() returns values in LIFO order')]
    public function test_to_array_returns_lifo_order(): void
    {
        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        $result = $unit->toArray();

        $this->assertSame(
            ['third', 'second', 'first'],
            $result,
        );
    }

    #[TestDox('iteration does not modify the stack')]
    public function test_iteration_does_not_modify(): void
    {
        $unit = new StackOfStrings();
        $unit->push('first');
        $unit->push('second');

        foreach ($unit as $value) {
            // consume the iterator
        }

        $this->assertCount(2, $unit);
        $this->assertSame('second', $unit->peek());
    }
}
