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

use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Thrown when a caller asks {@see NormalisationContext::visitIndexOf}
 * for the visit index of an object that has never been recorded
 * via {@see NormalisationContext::markSeen}.
 *
 * Originally added so `NormalisationContext` could surface its
 * own typed exception on misuse, rather than leaking the
 * underlying `SplObjectStorage`'s `UnexpectedValueException`
 * through the wrapper's boundary. Pair `visitIndexOf()` with
 * `hasSeen()` to avoid this exception entirely; reaching it
 * means that calling protocol was broken.
 */
class UnseenObjectException extends Rfc9457ProblemDetailsException
{
    /**
     * build an exception describing an unseen-object misuse of
     * {@see NormalisationContext::visitIndexOf}.
     *
     * @param object $obj
     *      the object that `visitIndexOf()` was called with, but
     *      which had never been recorded via
     *      {@see NormalisationContext::markSeen}
     */
    public function __construct(
        object $obj,
    ) {
        parent::__construct(
            type: "http://github.com/stuartherbert/stusdevkit/",
            status: 500,
            title: "visitIndexOf() called on an object that NormalisationContext has not seen",
            extra: [
                'class_name' => $obj::class,
            ],
        );
    }
}
