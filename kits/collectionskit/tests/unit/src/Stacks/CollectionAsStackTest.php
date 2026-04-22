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

use ArrayIterator;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\CollectionsKit\Exceptions\EmptyStackException;
use StusDevKit\CollectionsKit\Stacks\CollectionAsStack;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox('CollectionAsStack')]
class CollectionAsStackTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Stacks namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // namespace is part of the published API; moving the class
        // would break every caller's `use` statement
        $reflection = new ReflectionClass(CollectionAsStack::class);

        $this->assertSame(
            'StusDevKit\\CollectionsKit\\Stacks',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // must be an instantiable class (not interface/trait) because
        // callers construct it directly
        $reflection = new ReflectionClass(CollectionAsStack::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends CollectionOfAnything')]
    public function test_extends_collection_of_anything(): void
    {
        // the stack inherits storage, count, empty() and construction
        // validation from the base collection
        $reflection = new ReflectionClass(CollectionAsStack::class);
        $parent = $reflection->getParentClass();

        $this->assertNotFalse($parent);
        $this->assertSame(
            CollectionOfAnything::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares exactly the expected set of own public methods')]
    public function test_declares_expected_own_public_methods(): void
    {
        // pin the exact surface the class introduces so that new
        // public methods force a conscious update to this test
        $reflection = new ReflectionClass(CollectionAsStack::class);

        // our return value
        $ownMethods = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === CollectionAsStack::class) {
                $ownMethods[] = $m->getName();
            }
        }

        sort($ownMethods);

        $expected = [
            'getIterator',
            'maybePeek',
            'maybePop',
            'peek',
            'pop',
            'push',
            'toArray',
        ];

        $this->assertSame($expected, $ownMethods);
    }

    #[TestDox('->push() accepts a mixed value and returns static')]
    public function test_push_signature(): void
    {
        // the fluent return type is part of the contract; losing it
        // breaks chained calls at every caller
        $method = new ReflectionMethod(CollectionAsStack::class, 'push');

        $this->assertSame(1, $method->getNumberOfParameters());
        $param = $method->getParameters()[0];
        $this->assertSame('value', $param->getName());

        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);
        $this->assertSame('mixed', $paramType->getName());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
    }

    #[TestDox('->pop() takes no parameters and returns mixed')]
    public function test_pop_signature(): void
    {
        // pop() is a pure remove-and-return; no parameters, no defaults
        $method = new ReflectionMethod(CollectionAsStack::class, 'pop');

        $this->assertSame(0, $method->getNumberOfParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->maybePop() takes no parameters and returns mixed')]
    public function test_maybe_pop_signature(): void
    {
        // maybePop() returns null on empty; mixed covers both the
        // stored TValue and the null sentinel
        $method = new ReflectionMethod(CollectionAsStack::class, 'maybePop');

        $this->assertSame(0, $method->getNumberOfParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->peek() takes no parameters and returns mixed')]
    public function test_peek_signature(): void
    {
        // peek() is pop() without the removal; same signature shape
        $method = new ReflectionMethod(CollectionAsStack::class, 'peek');

        $this->assertSame(0, $method->getNumberOfParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->maybePeek() takes no parameters and returns mixed')]
    public function test_maybe_peek_signature(): void
    {
        // maybePeek() returns null on empty; mixed covers both cases
        $method = new ReflectionMethod(CollectionAsStack::class, 'maybePeek');

        $this->assertSame(0, $method->getNumberOfParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->toArray() takes no parameters and returns array')]
    public function test_to_array_signature(): void
    {
        // toArray() overrides the parent's FIFO-order conversion
        // with a LIFO-order conversion; signature must still match
        // the parent so Arrayable is satisfied
        $method = new ReflectionMethod(CollectionAsStack::class, 'toArray');

        $this->assertSame(0, $method->getNumberOfParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
    }

    #[TestDox('->getIterator() takes no parameters and returns ArrayIterator')]
    public function test_get_iterator_signature(): void
    {
        // overrides IteratorAggregate::getIterator to yield LIFO; the
        // return type must still be ArrayIterator to satisfy the parent
        $method = new ReflectionMethod(CollectionAsStack::class, 'getIterator');

        $this->assertSame(0, $method->getNumberOfParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame(ArrayIterator::class, $returnType->getName());
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() creates an empty stack')]
    public function test_can_instantiate_empty_stack(): void
    {
        // empty construction is the default entry point; most callers
        // start empty and push their way up
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $this->assertInstanceOf(CollectionAsStack::class, $unit);
        $this->assertInstanceOf(CollectionOfAnything::class, $unit);
        $this->assertCount(0, $unit);
        $this->assertTrue($unit->empty());
    }

    #[TestDox('::__construct() rejects arrays that contain null values')]
    public function test_construct_rejects_null_values(): void
    {
        // null-rejection lives in the parent constructor via the
        // CollectionsKit null validator; a stack must inherit that
        // guard so null never reaches push/pop/peek
        $this->expectException(NullValueNotAllowedException::class);

        /** @phpstan-ignore argument.type */
        new CollectionAsStack(['first', null, 'third']);
    }

    // ================================================================
    //
    // push
    //
    // ----------------------------------------------------------------

    #[TestDox('->push() adds a value to the top of the stack')]
    public function test_push_adds_value(): void
    {
        // the just-pushed value must be visible at the top via peek()
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $unit->push('first');

        $this->assertCount(1, $unit);
        $this->assertFalse($unit->empty());
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->push() returns the same instance for fluent chaining')]
    public function test_push_returns_this(): void
    {
        // fluent chaining relies on push() returning the same object,
        // not a clone — otherwise `$stack->push(a)->push(b)` silently
        // mutates a throwaway
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $result = $unit->push('hello');

        $this->assertSame($unit, $result);
    }

    #[TestDox('->push() appends successive values so the last pushed is on top')]
    public function test_push_appends_in_order(): void
    {
        // push order dictates pop/peek order; if append direction ever
        // flipped the test below would catch it
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

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

    #[TestDox('->pop() returns the top value and removes it from the stack')]
    public function test_pop_returns_and_removes_top(): void
    {
        // LIFO! the top value is the most recently pushed one, and it
        // must be gone from the stack after pop()
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        $result = $unit->pop();

        $this->assertSame('second', $result);
        $this->assertCount(1, $unit);
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->pop() on an empty stack throws EmptyStackException')]
    public function test_pop_empty_throws(): void
    {
        // empty-stack pop is a programmer error; callers who can't
        // guarantee non-empty should use maybePop() instead
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $this->expectException(EmptyStackException::class);

        $unit->pop();
    }

    // ================================================================
    //
    // maybePop
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybePop() returns the top value and removes it from the stack')]
    public function test_maybe_pop_returns_and_removes_top(): void
    {
        // same LIFO-and-remove behaviour as pop() on non-empty
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        $result = $unit->maybePop();

        $this->assertSame('second', $result);
        $this->assertCount(1, $unit);
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->maybePop() on an empty stack returns null')]
    public function test_maybe_pop_empty_returns_null(): void
    {
        // null sentinel distinguishes maybePop() from pop(); callers
        // who might hit empty pick this overload to avoid the throw
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $result = $unit->maybePop();

        $this->assertNull($result);
    }

    // ================================================================
    //
    // peek
    //
    // ----------------------------------------------------------------

    #[TestDox('->peek() returns the top value without removing it')]
    public function test_peek_returns_top_without_removing(): void
    {
        // peek() must be idempotent — calling it twice returns the
        // same value and leaves the stack size unchanged
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        $result1 = $unit->peek();
        $result2 = $unit->peek();

        $this->assertSame('second', $result1);
        $this->assertSame('second', $result2);
        $this->assertCount(2, $unit);
    }

    #[TestDox('->peek() on an empty stack throws EmptyStackException')]
    public function test_peek_empty_throws(): void
    {
        // empty-stack peek is a programmer error; same contract as
        // pop() for the same reason
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $this->expectException(EmptyStackException::class);

        $unit->peek();
    }

    // ================================================================
    //
    // maybePeek
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybePeek() returns the top value without removing it')]
    public function test_maybe_peek_returns_top_without_removing(): void
    {
        // idempotent like peek(); the null-safe overload must behave
        // identically on non-empty stacks
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        $result1 = $unit->maybePeek();
        $result2 = $unit->maybePeek();

        $this->assertSame('second', $result1);
        $this->assertSame('second', $result2);
        $this->assertCount(2, $unit);
    }

    #[TestDox('->maybePeek() on an empty stack returns null')]
    public function test_maybe_peek_empty_returns_null(): void
    {
        // null sentinel mirrors maybePop(); callers that can't
        // guarantee non-empty use this to avoid the throw
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $result = $unit->maybePeek();

        $this->assertNull($result);
    }

    // ================================================================
    //
    // toArray
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArray() returns values in LIFO order (top first)')]
    public function test_to_array_returns_lifo_order(): void
    {
        // toArray() overrides the parent's insertion-order output
        // with LIFO order so that a stack's array form reads as the
        // pop-sequence a caller would see
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        $result = $unit->toArray();

        $this->assertSame(
            ['third', 'second', 'first'],
            $result,
        );
    }

    #[TestDox('->toArray() on an empty stack returns an empty array')]
    public function test_to_array_on_empty_stack(): void
    {
        // empty-stack edge case; array_reverse([]) must still return []
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        $this->assertSame([], $unit->toArray());
    }

    // ================================================================
    //
    // getIterator / foreach
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIterator() yields values in LIFO order (top to bottom)')]
    public function test_get_iterator_yields_lifo(): void
    {
        // foreach walks the iterator; the observable order must match
        // toArray() for consistency between iteration and conversion
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        // our return value
        $result = [];

        foreach ($unit as $value) {
            $result[] = $value;
        }

        $this->assertSame(
            ['third', 'second', 'first'],
            $result,
        );
    }

    #[TestDox('->getIterator() on an empty stack produces no iterations')]
    public function test_get_iterator_on_empty_stack(): void
    {
        // empty iteration must simply produce nothing — no errors,
        // no sentinel values
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // our return value
        $result = [];

        foreach ($unit as $value) {
            $result[] = $value;
        }

        $this->assertSame([], $result);
    }

    #[TestDox('->getIterator() does not modify the stack during iteration')]
    public function test_get_iterator_does_not_modify_stack(): void
    {
        // the iterator operates on a reversed copy of the data (via
        // array_reverse); the original stack must be intact once
        // iteration finishes
        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        foreach ($unit as $value) {
            // consume the iterator without touching the stack
        }

        $this->assertCount(2, $unit);
        $this->assertSame('second', $unit->peek());
    }
}
