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

namespace StusDevKit\MissingBitsKit\Contracts;

/**
 * Contract for objects that produce their own canonical form for
 * structural comparison.
 *
 * Implementors return a fully-normalised representation of their
 * state. The caller treats the return value as final and does NOT
 * recurse into it. A generic recursive normaliser cannot tell a
 * key/value map from a sparse list when both are stored as a PHP
 * array - but the implementor knows. So the implementor produces
 * the final form.
 *
 * Originally added so the CollectionsKit `Dict*` family could
 * preserve their key-as-identity semantics through canonicalising
 * assertions. A reflection-based walk of a
 * `DictOfIntegers([42 => 1, 7 => 2])` sees only the backing array.
 * From the array alone it cannot tell whether the int keys are
 * positions (drop them) or identities (preserve them) - so it
 * guesses, and a dict with identity keys gets canonicalised the
 * same way as a list with positional keys. Implementing this
 * interface lets the dict declare its own canonical form, so the
 * comparison stays correct.
 *
 * Here Be Dragons
 * ===============
 *
 * - **Implementors handle nested values themselves.** If the
 *   implementor's state contains values that need normalising
 *   (other objects, nested arrays, enums), the implementor must
 *   normalise each one - typically by calling
 *   `NormaliseForComparison::from()` on it - and splice the
 *   results into its return value. The caller will not do this
 *   work.
 *
 * - **Stability is the implementor's responsibility.** Two
 *   instances with the same logical state must produce the same
 *   return value - same shape, same keys in the same order, same
 *   scalars at the same positions. If your state includes
 *   ordering-irrelevant collections (sets, etc.), sort them before
 *   returning so the output order is deterministic.
 */
interface NormalisesForComparison
{
    /**
     * return the canonical representation of this object's state
     * for use in structural comparison.
     *
     * See the class-level docblock for the full contract.
     *
     * @return mixed the object's state, suitable for comparison
     */
    public function getNormalisedForComparison(): mixed;
}
