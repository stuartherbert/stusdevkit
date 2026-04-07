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

namespace StusDevKit\ValidationKit\Constraints;

use StusDevKit\ValidationKit\Internals\ValidationContext;

/**
 * SimpleConstraint is the easiest way to create a custom
 * validation constraint.
 *
 * Implement two methods:
 * - `getType()` — return the RFC 9457 type URI for
 *   this constraint's validation error
 * - `check()` — return null if the data is valid, or
 *   an error message string if it is not
 *
 * For constraints that need to report multiple issues,
 * use custom type URIs per issue, or access the
 * validation context directly, extend BaseConstraint
 * instead.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\SimpleConstraint;
 *
 *     final class NoForbiddenWords extends SimpleConstraint
 *     {
 *         protected function getType(): string
 *         {
 *             return 'https://example.com/errors/forbidden';
 *         }
 *
 *         protected function check(mixed $data): ?string
 *         {
 *             assert(is_string($data));
 *             return str_contains($data, 'forbidden')
 *                 ? 'Contains forbidden words'
 *                 : null;
 *         }
 *     }
 */
abstract class SimpleConstraint extends BaseConstraint
{
    // ================================================================
    //
    // Methods To Implement
    //
    // ----------------------------------------------------------------

    /**
     * return the RFC 9457 type URI for this constraint's
     * validation error
     *
     * This URI identifies the type of validation failure
     * so that error consumers can distinguish between
     * different constraints programmatically.
     *
     * @return non-empty-string
     */
    abstract protected function getType(): string;

    /**
     * check the data and return null on success or an
     * error message string on failure
     *
     * @return ?string
     * - null if the data is valid
     * - an error message string if it is not
     */
    abstract protected function check(mixed $data): ?string;

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        $message = $this->check($data);

        if ($message !== null && $message !== '') {
            $context->addIssue(
                type: $this->getType(),
                input: $data,
                message: $message,
            );
        }

        return $data;
    }
}
