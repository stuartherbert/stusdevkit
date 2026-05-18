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

namespace StusDevKit\MissingBitsKit\DataInspectors;

use SplObjectStorage;

/**
 * Shared state passed through a single top-level normalisation walk
 * by {@see GetNormalisedForComparison}.
 *
 * Originally added so implementors of
 * {@see \StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison}
 * can recurse back into the normaliser via
 * {@see GetNormalisedForComparison::fromNested()} while preserving the
 * parent walk's cycle-detection state. Without a shared context,
 * an implementor whose canonical form references itself (directly
 * or transitively) infinite-loops, because every recursive call to
 * the top-level `from()` starts a fresh visited-set.
 *
 * Owned by `GetNormalisedForComparison`; implementors only ever
 * forward the context they were handed - they should not construct
 * their own, query its internals, or store it past the duration of
 * a single `getNormalisedForComparison()` call.
 *
 * Wraps `SplObjectStorage` so the storage choice stays an
 * implementation detail. Future additions to the protocol (a
 * depth counter, normalisation options, etc.) can land here
 * without breaking the
 * {@see \StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison}
 * interface again.
 */
final class NormalisationContext
{
    /**
     * Visited-objects set. Maps each previously-seen object to the
     * zero-based index at which it was first encountered, so that
     * back-references can name the visit they refer to.
     *
     * @var SplObjectStorage<object, int>
     */
    private SplObjectStorage $seen;

    public function __construct()
    {
        $this->seen = new SplObjectStorage();
    }

    /**
     * has the given object already been visited during this walk?
     *
     * Pair with {@see visitIndexOf} to recover the index of the
     * earlier visit when this returns `true`.
     */
    public function hasSeen(object $obj): bool
    {
        return $this->seen->offsetExists($obj);
    }

    /**
     * return the visit index assigned to a previously-seen object.
     *
     * Caller MUST check {@see hasSeen} first - calling
     * `visitIndexOf()` on an unseen object is a programmer error
     * (SplObjectStorage will throw `UnexpectedValueException`).
     */
    public function visitIndexOf(object $obj): int
    {
        return $this->seen[$obj];
    }

    /**
     * record an object as visited and return the visit index it
     * was assigned.
     *
     * Visit indexes are assigned in order of first encounter (0,
     * 1, 2, ...), so structurally-equivalent cyclic graphs
     * normalise to the same back-references regardless of the
     * runtime object identities involved.
     *
     * Calling `markSeen()` on an already-seen object overwrites
     * its visit index; callers should check {@see hasSeen} first
     * to keep the "order of first encounter" invariant intact.
     */
    public function markSeen(object $obj): int
    {
        $index = $this->seen->count();
        $this->seen[$obj] = $index;
        return $index;
    }
}
