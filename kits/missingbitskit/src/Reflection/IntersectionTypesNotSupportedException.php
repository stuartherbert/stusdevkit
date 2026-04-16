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
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionIntersectionType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * IntersectionTypesNotSupportedException is raised when an operation
 * that produces a flat list of type names encounters an intersection
 * type that cannot be faithfully represented in that flat form.
 *
 * An intersection `A&B` means "a value that satisfies both A and B
 * simultaneously". Collapsing that to `['A', 'B']` discards the
 * "and" semantics - the flat list becomes indistinguishable from the
 * list produced for a union `A|B` (which means "a value that
 * satisfies either A or B"). Callers reasoning from the flat list
 * would therefore draw wrong conclusions. Rather than silently
 * produce misleading output, the flattener refuses the input.
 *
 * Distinct from UnsupportedReflectionTypeException: that one signals
 * "this is a ReflectionType subclass the flattener does not
 * recognise" (a library maintenance gap). This one signals "the
 * input is known and well-formed, but deliberately outside the
 * flattener's contract" (a caller contract violation).
 */
class IntersectionTypesNotSupportedException extends Rfc9457ProblemDetailsException
{
    public function __construct(
        ReflectionIntersectionType $refType,
    )
    {
        parent::__construct(
            type: "http://github.com/stuartherbert/stusdevkit/",
            status: 422,
            title: "Intersection types cannot be flattened to a list of names",
            extra: [
                'type' => (string)$refType,
            ],
        );
    }
}
