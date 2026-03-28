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

namespace StusDevKit\ValidationKit\Schemas\BuiltinObjects;

use DateTimeInterface;
use StusDevKit\ValidationKit\Coercions\CoerceToDateTime;
use StusDevKit\ValidationKit\Constraints\DateTimeMaxConstraint;
use StusDevKit\ValidationKit\Constraints\DateTimeMinConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * DateTimeInterfaceSchema validates that the input is a
 * DateTimeInterface instance.
 *
 * With coercion enabled, it can also accept ISO 8601
 * date strings and convert them to DateTimeImmutable.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $schema = Validate::dateTime();
 *     $schema->parse(new \DateTimeImmutable()); // ok
 *     $schema->parse('not a date');             // throws
 *
 *     // with coercion from string
 *     $schema = Validate::dateTime()->coerce();
 *     $schema->parse('2026-03-28T12:00:00Z');
 *     // returns DateTimeImmutable
 *
 * @extends BaseSchema<DateTimeInterface>
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
class DateTimeInterfaceSchema extends BaseSchema
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
            message: 'Expected DateTimeInterface, received '
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
        return 'DateTimeInterface';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if ($data instanceof DateTimeInterface) {
            return true;
        }

        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return false;
    }

    /**
     * enable type coercion for this schema
     *
     * Date strings and integer Unix timestamps are
     * converted to DateTimeImmutable.
     */
    public function coerce(): static
    {
        $clone = clone $this;
        $clone->coercion = new CoerceToDateTime();

        return $clone;
    }
}
