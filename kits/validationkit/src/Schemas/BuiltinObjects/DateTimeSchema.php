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

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * DateTimeSchema validates that the input is a
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
class DateTimeSchema extends BaseSchema
{
    private ?DateTimeInterface $minDate = null;
    /** @var ErrorCallback */
    private mixed $minError;

    private ?DateTimeInterface $maxDate = null;
    /** @var ErrorCallback */
    private mixed $maxError;

    /**
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(?callable $typeCheckError = null)
    {
        $this->typeCheckError = $typeCheckError
            ?? static fn(mixed $data) => new ValidationIssue(
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
        $clone = clone $this;
        $clone->minDate = $date;
        $clone->minError = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                code: IssueCode::TooSmall,
                input: $data,
                path: [],
                message: 'Date must be on or after '
                    . $date->format('c'),
            );

        return $clone;
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
        $clone = clone $this;
        $clone->maxDate = $date;
        $clone->maxError = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                code: IssueCode::TooBig,
                input: $data,
                path: [],
                message: 'Date must be on or before '
                    . $date->format('c'),
            );

        return $clone;
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

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        assert($data instanceof DateTimeInterface);

        if ($this->minDate !== null && $data < $this->minDate) {
            /** @var ErrorCallback $minError */
            $minError = $this->minError;
            $this->invokeErrorCallback(
                callback: $minError,
                input: $data,
                context: $context,
            );
        }

        if ($this->maxDate !== null && $data > $this->maxDate) {
            /** @var ErrorCallback $maxError */
            $maxError = $this->maxError;
            $this->invokeErrorCallback(
                callback: $maxError,
                input: $data,
                context: $context,
            );
        }
    }

    protected function coerceValue(mixed $data): mixed
    {
        if (is_string($data)) {
            $dateTime = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $data,
            );
            if ($dateTime !== false) {
                return $dateTime;
            }

            // try a more lenient parse
            try {
                return new DateTimeImmutable($data);
            } catch (Exception) {
                // could not parse — return the original
                // string and let the type check handle it
                return $data;
            }
        }

        if (is_int($data)) {
            return (new DateTimeImmutable())
                ->setTimestamp($data);
        }

        return $data;
    }
}
