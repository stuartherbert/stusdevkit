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
 * Exists as a dedicated class so that the implementation can
 * remain independent of the PHP runtime version.
 *
 * @internal
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
     *
     * @param object $obj
     *      the object that we want to check for
     * @return bool
     * - `true` if we have seen this object before
     * - `false` otherwise
     */
    public function hasSeen(object $obj): bool
    {
        return $this->seen->offsetExists($obj);
    }

    /**
     * return the visit index assigned to the given object.
     *
     * Visit indexes are assigned in order of first encounter (0,
     * 1, 2, ...) and never change once assigned, so the returned
     * value is stable for the lifetime of the context.
     *
     * Pair with {@see hasSeen} - calling `visitIndexOf()` on an
     * object that has not been recorded is a programmer error
     * and throws {@see UnseenObjectException}.
     *
     * @param object $obj
     *      the object that we want to get the index of
     * @return int
     *      the zero-based visit index assigned at first sight
     * @throws UnseenObjectException
     *      if `$obj` has not been recorded via {@see markSeen}
     */
    public function visitIndexOf(object $obj): int
    {
        // robustness!
        // explicit existence check + our own typed exception keeps
        // the underlying storage's own exception type
        // (SplObjectStorage throws UnexpectedValueException on
        // missing keys) from leaking through this class's
        // boundary. The caller is expected to pair this with
        // hasSeen() - reaching the throw means that protocol was
        // broken.
        if (! $this->seen->offsetExists($obj)) {
            throw new UnseenObjectException($obj);
        }

        return $this->seen[$obj];
    }

    /**
     * record an object as visited and return the visit index it
     * carries.
     *
     * Idempotent: if the object has been seen before, the existing
     * first-visit index is returned and nothing else changes.
     * Duplicate calls are safe; the "order of first encounter"
     * invariant survives any call pattern.
     *
     * Visit indexes are assigned in order of first encounter (0,
     * 1, 2, ...), so structurally-equivalent cyclic graphs
     * normalise to the same back-references regardless of the
     * runtime object identities involved.
     *
     * @param object $obj
     *      the object that we want to record as visited
     * @return int
     * - the freshly-assigned visit index, on first sight
     * - the existing first-visit index, on subsequent sights
     */
    public function markSeen(object $obj): int
    {
        // robustness!
        // if already seen, preserve the first-visit index instead
        // of overwriting. Overwriting would corrupt the "order of
        // first encounter" invariant - the very thing that makes
        // structurally-equivalent cyclic graphs canonicalise to
        // the same back-references run after run.
        if ($this->seen->offsetExists($obj)) {
            return $this->seen[$obj];
        }

        // first sight - assign the next index in encounter order
        $index = $this->seen->count();
        $this->seen[$obj] = $index;
        return $index;
    }
}
