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

namespace StusDevKit\ValidationKit\Schemas\Builtins;

use StusDevKit\ValidationKit\Coercions\CoerceToInt;
use StusDevKit\ValidationKit\Constraints\NumericGtConstraint;
use StusDevKit\ValidationKit\Constraints\NumericGteConstraint;
use StusDevKit\ValidationKit\Constraints\NumericLtConstraint;
use StusDevKit\ValidationKit\Constraints\NumericLteConstraint;
use StusDevKit\ValidationKit\Constraints\NumericMultipleOfConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * IntSchema validates that the input is an integer.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $schema = Validate::int()->gte(value: 0)->lte(value: 100);
 *     $schema->parse(42);    // 42
 *     $schema->parse(3.14);  // throws (not an int)
 *     $schema->parse(-1);    // throws (below minimum)
 *
 *     // with coercion
 *     $schema = Validate::int()->coerce();
 *     $schema->parse('42');  // 42
 *
 * @extends BaseSchema<int>
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
class IntSchema extends BaseSchema
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
            code: IssueCode::InvalidType,
            input: $data,
            path: [],
            message: 'Expected int, received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * require the value to be greater than the given value
     *
     * @param ErrorCallback|null $error
     */
    public function gt(int $value, ?callable $error = null): static
    {
        return $this->withConstraint(
            new NumericGtConstraint(
                value: $value,
                error: $error,
            ),
        );
    }

    /**
     * require the value to be greater than or equal to the
     * given value
     *
     * @param ErrorCallback|null $error
     */
    public function gte(int $value, ?callable $error = null): static
    {
        return $this->withConstraint(
            new NumericGteConstraint(
                value: $value,
                error: $error,
            ),
        );
    }

    /**
     * require the value to be less than the given value
     *
     * @param ErrorCallback|null $error
     */
    public function lt(int $value, ?callable $error = null): static
    {
        return $this->withConstraint(
            new NumericLtConstraint(
                value: $value,
                error: $error,
            ),
        );
    }

    /**
     * require the value to be less than or equal to the
     * given value
     *
     * @param ErrorCallback|null $error
     */
    public function lte(int $value, ?callable $error = null): static
    {
        return $this->withConstraint(
            new NumericLteConstraint(
                value: $value,
                error: $error,
            ),
        );
    }

    /**
     * require the value to be positive (> 0)
     *
     * @param ErrorCallback|null $error
     */
    public function positive(?callable $error = null): static
    {
        return $this->gt(value: 0, error: $error);
    }

    /**
     * require the value to be negative (< 0)
     *
     * @param ErrorCallback|null $error
     */
    public function negative(?callable $error = null): static
    {
        return $this->lt(value: 0, error: $error);
    }

    /**
     * require the value to be non-negative (>= 0)
     *
     * @param ErrorCallback|null $error
     */
    public function nonNegative(?callable $error = null): static
    {
        return $this->gte(value: 0, error: $error);
    }

    /**
     * require the value to be non-positive (<= 0)
     *
     * @param ErrorCallback|null $error
     */
    public function nonPositive(?callable $error = null): static
    {
        return $this->lte(value: 0, error: $error);
    }

    /**
     * require the value to be a multiple of the given value
     *
     * @param ErrorCallback|null $error
     */
    public function multipleOf(
        int $value,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new NumericMultipleOfConstraint(
                value: $value,
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
        return 'int';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if (is_int($data)) {
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
     * Numeric strings, floats with no fractional part,
     * and booleans are converted to int.
     */
    public function coerce(): static
    {
        $clone = clone $this;
        $clone->coercion = new CoerceToInt();

        return $clone;
    }
}
