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

namespace StusDevKit\CollectionsKit\Stacks;

use ArrayIterator;
use StusDevKit\CollectionsKit\CollectionOfAnything;
use StusDevKit\CollectionsKit\Exceptions\EmptyStackException;

/**
 * CollectionAsStack holds a collection of data as a
 * last-in-first-out (LIFO) stack.
 *
 * Items are added with push() and removed with pop().
 * The most recently pushed item is always at the top
 * of the stack.
 *
 * Unlike CollectionAsList, stacks do not support
 * random access. Iteration via foreach is supported
 * and yields values in LIFO order (top to bottom)
 * without modifying the stack.
 *
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends CollectionOfAnything<int, TValue>
 */
class CollectionAsStack extends CollectionOfAnything
{
    // ================================================================
    //
    // Data Management
    //
    // ----------------------------------------------------------------

    /**
     * push a value onto the top of the stack
     *
     * @param TValue $value
     */
    public function push(mixed $value): static
    {
        $this->data[] = $value;

        return $this;
    }

    /**
     * remove and return the top value from the stack
     *
     * @return TValue
     * @throws EmptyStackException
     *         if the stack is empty.
     */
    public function pop(): mixed
    {
        $value = $this->maybePop();

        if ($value === null) {
            throw new EmptyStackException(
                $this->getCollectionTypeAsString(),
            );
        }

        return $value;
    }

    /**
     * remove and return the top value, or null if empty
     *
     * @return TValue|null
     */
    public function maybePop(): mixed
    {
        if ($this->data === []) {
            return null;
        }

        return array_pop($this->data);
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    /**
     * return the top value without removing it
     *
     * @return TValue
     * @throws EmptyStackException
     *         if the stack is empty.
     */
    public function peek(): mixed
    {
        $value = $this->maybePeek();

        if ($value === null) {
            throw new EmptyStackException(
                $this->getCollectionTypeAsString(),
            );
        }

        return $value;
    }

    /**
     * return the top value without removing it, or null
     * if empty
     *
     * @return TValue|null
     */
    public function maybePeek(): mixed
    {
        if ($this->data === []) {
            return null;
        }

        return $this->data[array_key_last($this->data)];
    }

    // ================================================================
    //
    // IteratorAggregate interface (LIFO override)
    //
    // ----------------------------------------------------------------

    /**
     * iterate the stack in LIFO order (top to bottom)
     *
     * The stack is not modified by iteration.
     *
     * @return ArrayIterator<int, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(array_reverse($this->data));
    }
}
