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

#[TestDox(CollectionAsStack::class)]
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
            CollectionAsStack::class,
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

        $reflection = new ReflectionClass(CollectionAsStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('extends CollectionOfAnything')]
    public function test_extends_CollectionOfAnything(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parent class fixes which inherited methods are
        // available; changing it is a breaking change for every
        // subclass and caller that relies on inherited behaviour.

        // ----------------------------------------------------------------
        // setup your test

        $expected = CollectionOfAnything::class;

        // ----------------------------------------------------------------
        // perform the change

        $parent = (new ReflectionClass(
            CollectionAsStack::class,
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
    // CollectionAsStack adds a LIFO stack API on top of
    // CollectionOfAnything. Shape of the inherited surface is pinned
    // on the parent class; this section pins the public methods
    // declared by CollectionAsStack itself, plus the signatures of
    // each one so that an accidental change to a parameter or return
    // type fails this test.
    //
    // ----------------------------------------------------------------

    #[TestDox('declares exactly the expected set of own public methods')]
    public function test_declares_expected_own_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the exact surface the class introduces so that new
        // public methods force a conscious update to this test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'getIterator',
            'maybePeek',
            'maybePop',
            'peek',
            'pop',
            'push',
            'toArray',
        ];
        $reflection = new ReflectionClass(CollectionAsStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === CollectionAsStack::class) {
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

        // empty construction is the default entry point; most
        // callers start empty and push their way up.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CollectionAsStack::class, $unit);
        $this->assertInstanceOf(CollectionOfAnything::class, $unit);
        $this->assertCount(0, $unit);
        $this->assertTrue($unit->empty());
    }

    #[TestDox('::__construct() rejects arrays that contain null values')]
    public function test_construct_rejects_null_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // null-rejection lives in the parent constructor via the
        // CollectionsKit null validator; a stack must inherit that
        // guard so null never reaches push/pop/peek.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        /** @phpstan-ignore argument.type */
        new CollectionAsStack(['first', null, 'third']);

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }


    #[TestDox('->push() accepts a mixed value and returns static')]
    public function test_push_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the fluent return type is part of the contract; losing
        // it breaks chained calls at every caller.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'push');

        // ----------------------------------------------------------------
        // perform the change

        $param = $method->getParameters()[0];
        $paramType = $param->getType();
        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $method->getNumberOfParameters());
        $this->assertSame('value', $param->getName());
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);
        $this->assertSame('mixed', $paramType->getName());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
    }

    #[TestDox('->pop() takes no parameters and returns mixed')]
    public function test_pop_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pop() is a pure remove-and-return; no parameters, no
        // defaults.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'pop');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $method->getNumberOfParameters());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->maybePop() takes no parameters and returns mixed')]
    public function test_maybe_pop_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // maybePop() returns null on empty; mixed covers both the
        // stored TValue and the null sentinel.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'maybePop');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $method->getNumberOfParameters());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->peek() takes no parameters and returns mixed')]
    public function test_peek_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // peek() is pop() without the removal; same signature
        // shape.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'peek');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $method->getNumberOfParameters());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->maybePeek() takes no parameters and returns mixed')]
    public function test_maybe_peek_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // maybePeek() returns null on empty; mixed covers both
        // cases.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'maybePeek');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $method->getNumberOfParameters());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('mixed', $returnType->getName());
    }

    #[TestDox('->toArray() takes no parameters and returns array')]
    public function test_to_array_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toArray() overrides the parent's FIFO-order conversion
        // with a LIFO-order conversion; signature must still
        // match the parent so Arrayable is satisfied.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'toArray');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $method->getNumberOfParameters());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
    }

    #[TestDox('->getIterator() takes no parameters and returns ArrayIterator')]
    public function test_get_iterator_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // overrides IteratorAggregate::getIterator to yield LIFO;
        // the return type must still be ArrayIterator to satisfy
        // the parent.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(CollectionAsStack::class, 'getIterator');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $method->getNumberOfParameters());
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame(ArrayIterator::class, $returnType->getName());
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

        // the just-pushed value must be visible at the top via
        // peek().

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // perform the change

        $unit->push('first');

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $unit);
        $this->assertFalse($unit->empty());
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->push() returns the same instance for fluent chaining')]
    public function test_push_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // fluent chaining relies on push() returning the same
        // object, not a clone — otherwise
        // `$stack->push(a)->push(b)` silently mutates a throwaway.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->push('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $result);
    }

    #[TestDox('->push() appends successive values so the last pushed is on top')]
    public function test_push_appends_in_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // push order dictates pop/peek order; if append direction
        // ever flipped the test below would catch it.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

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

    #[TestDox('->pop() returns the top value and removes it from the stack')]
    public function test_pop_returns_and_removes_top(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // LIFO! the top value is the most recently pushed one,
        // and it must be gone from the stack after pop().

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
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

    #[TestDox('->pop() on an empty stack throws EmptyStackException')]
    public function test_pop_empty_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // empty-stack pop is a programmer error; callers who
        // can't guarantee non-empty should use maybePop() instead.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $this->expectException(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->pop();

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    // ================================================================
    //
    // maybePop
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybePop() returns the top value and removes it from the stack')]
    public function test_maybe_pop_returns_and_removes_top(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same LIFO-and-remove behaviour as pop() on non-empty.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->maybePop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('second', $result);
        $this->assertCount(1, $unit);
        $this->assertSame('first', $unit->peek());
    }

    #[TestDox('->maybePop() on an empty stack returns null')]
    public function test_maybe_pop_empty_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // null sentinel distinguishes maybePop() from pop();
        // callers who might hit empty pick this overload to
        // avoid the throw.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->maybePop();

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // peek() must be idempotent — calling it twice returns
        // the same value and leaves the stack size unchanged.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        // ----------------------------------------------------------------
        // perform the change

        $result1 = $unit->peek();
        $result2 = $unit->peek();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('second', $result1);
        $this->assertSame('second', $result2);
        $this->assertCount(2, $unit);
    }

    #[TestDox('->peek() on an empty stack throws EmptyStackException')]
    public function test_peek_empty_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // empty-stack peek is a programmer error; same contract
        // as pop() for the same reason.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $this->expectException(EmptyStackException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->peek();

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    // ================================================================
    //
    // maybePeek
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybePeek() returns the top value without removing it')]
    public function test_maybe_peek_returns_top_without_removing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // idempotent like peek(); the null-safe overload must
        // behave identically on non-empty stacks.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        // ----------------------------------------------------------------
        // perform the change

        $result1 = $unit->maybePeek();
        $result2 = $unit->maybePeek();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('second', $result1);
        $this->assertSame('second', $result2);
        $this->assertCount(2, $unit);
    }

    #[TestDox('->maybePeek() on an empty stack returns null')]
    public function test_maybe_peek_empty_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // null sentinel mirrors maybePop(); callers that can't
        // guarantee non-empty use this to avoid the throw.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->maybePeek();

        // ----------------------------------------------------------------
        // test the results

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
        // ----------------------------------------------------------------
        // explain your test

        // toArray() overrides the parent's insertion-order output
        // with LIFO order so that a stack's array form reads as
        // the pop-sequence a caller would see.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
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

    #[TestDox('->toArray() on an empty stack returns an empty array')]
    public function test_to_array_on_empty_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // empty-stack edge case; array_reverse([]) must still
        // return [].

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $result);
    }

    // ================================================================
    //
    // getIterator / foreach
    //
    // ----------------------------------------------------------------

    #[TestDox('->getIterator() yields values in LIFO order (top to bottom)')]
    public function test_get_iterator_yields_lifo(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // foreach walks the iterator; the observable order must
        // match toArray() for consistency between iteration and
        // conversion.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');
        $unit->push('third');

        // ----------------------------------------------------------------
        // perform the change

        // our return value
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

    #[TestDox('->getIterator() on an empty stack produces no iterations')]
    public function test_get_iterator_on_empty_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // empty iteration must simply produce nothing — no
        // errors, no sentinel values.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();

        // ----------------------------------------------------------------
        // perform the change

        // our return value
        $result = [];

        foreach ($unit as $value) {
            $result[] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $result);
    }

    #[TestDox('->getIterator() does not modify the stack during iteration')]
    public function test_get_iterator_does_not_modify_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the iterator operates on a reversed copy of the data
        // (via array_reverse); the original stack must be intact
        // once iteration finishes.

        // ----------------------------------------------------------------
        // setup your test

        /** @var CollectionAsStack<string> $unit */
        $unit = new CollectionAsStack();
        $unit->push('first');
        $unit->push('second');

        // ----------------------------------------------------------------
        // perform the change

        foreach ($unit as $value) {
            // consume the iterator without touching the stack
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $unit);
        $this->assertSame('second', $unit->peek());
    }
}
