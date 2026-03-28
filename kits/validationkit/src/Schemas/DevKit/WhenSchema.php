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

namespace StusDevKit\ValidationKit\Schemas\DevKit;

use DateTimeInterface;
use StusDevKit\DateTimeKit\When;
use StusDevKit\ValidationKit\Coercions\CoerceToWhen;
use StusDevKit\ValidationKit\Constraints\DateTimeMaxConstraint;
use StusDevKit\ValidationKit\Constraints\DateTimeMinConstraint;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * WhenSchema validates that the input is a When instance.
 *
 * With coercion enabled, it can also accept ISO 8601
 * date strings, other DateTimeInterface instances, and
 * Unix timestamps, converting them to When via
 * When::from().
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $schema = Validate::when();
 *     $schema->parse(When::from('2026-01-15')); // ok
 *
 *     // with coercion
 *     $schema = Validate::when()->coerce();
 *     $schema->parse('2026-01-15'); // → When
 *     $schema->parse(1700000000);   // → When
 *
 *     // with constraints
 *     $schema = Validate::when()
 *         ->min(date: When::from('2020-01-01'))
 *         ->max(date: When::from('2030-12-31'));
 *
 * @extends BaseSchema<When>
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
class WhenSchema extends BaseSchema
{
    /**
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(?callable $typeCheckError = null)
    {
        parent::__construct();

        $this->typeCheckError = $typeCheckError
            ?? $this->getDefaultTypeCheckErrorCallbackForConstructor();
    }

    // ================================================================
    //
    // Default Error Callbacks
    //
    // ----------------------------------------------------------------

    protected function getDefaultTypeCheckErrorCallbackForConstructor(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidDate,
            input: $data,
            path: [],
            message: 'Expected When, received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * require the date to be on or after the given date
     *
     * @param ErrorCallback|null $error
     */
    public function min(
        DateTimeInterface $date,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new DateTimeMinConstraint(
                date: $date,
                error: $error,
            ),
        );
    }

    /**
     * require the date to be on or before the given date
     *
     * @param ErrorCallback|null $error
     */
    public function max(
        DateTimeInterface $date,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new DateTimeMaxConstraint(
                date: $date,
                error: $error,
            ),
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'When';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if ($data instanceof When) {
            return true;
        }

        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return false;
    }

    protected function defaultCoercion(): ValueCoercion
    {
        return new CoerceToWhen();
    }
}
