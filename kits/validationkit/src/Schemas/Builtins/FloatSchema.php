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

use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * FloatSchema validates that the input is a float.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $schema = Validate::float()->gte(value: 0.0);
 *     $schema->parse(3.14);  // 3.14
 *     $schema->parse(42);    // throws (not a float)
 *     $schema->parse(-1.0);  // throws (below minimum)
 *
 * @extends BaseSchema<float>
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
class FloatSchema extends BaseSchema
{
    private ?float $gtValue = null;
    /** @var ErrorCallback */
    private mixed $gtError;

    private ?float $gteValue = null;
    /** @var ErrorCallback */
    private mixed $gteError;

    private ?float $ltValue = null;
    /** @var ErrorCallback */
    private mixed $ltError;

    private ?float $lteValue = null;
    /** @var ErrorCallback */
    private mixed $lteError;

    private ?float $multipleOfValue = null;
    /** @var ErrorCallback */
    private mixed $multipleOfError;

    private bool $mustBeFinite = false;
    /** @var ErrorCallback */
    private mixed $finiteError;

    /**
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(?callable $typeCheckError = null)
    {
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
            message: 'Expected float, received '
                . get_debug_type($data),
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForGt(float $value): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooSmall,
            input: $data,
            path: [],
            message: 'Number must be greater than ' . $value,
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForGte(float $value): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooSmall,
            input: $data,
            path: [],
            message: 'Number must be greater than or equal to '
                . $value,
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForLt(float $value): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooBig,
            input: $data,
            path: [],
            message: 'Number must be less than ' . $value,
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForLte(float $value): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooBig,
            input: $data,
            path: [],
            message: 'Number must be less than or equal to '
                . $value,
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForMultipleOf(
        float $value,
    ): callable {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::NotMultipleOf,
            input: $data,
            path: [],
            message: 'Number must be a multiple of ' . $value,
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForFinite(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::NotFinite,
            input: $data,
            path: [],
            message: 'Number must be finite',
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
    public function gt(float $value, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->gtValue = $value;
        $clone->gtError = $error ?? $this->getDefaultTypeCheckErrorCallbackForGt($value);

        return $clone;
    }

    /**
     * require the value to be greater than or equal to the
     * given value
     *
     * @param ErrorCallback|null $error
     */
    public function gte(float $value, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->gteValue = $value;
        $clone->gteError = $error ?? $this->getDefaultTypeCheckErrorCallbackForGte($value);

        return $clone;
    }

    /**
     * require the value to be less than the given value
     *
     * @param ErrorCallback|null $error
     */
    public function lt(float $value, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->ltValue = $value;
        $clone->ltError = $error ?? $this->getDefaultTypeCheckErrorCallbackForLt($value);

        return $clone;
    }

    /**
     * require the value to be less than or equal to the
     * given value
     *
     * @param ErrorCallback|null $error
     */
    public function lte(float $value, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->lteValue = $value;
        $clone->lteError = $error ?? $this->getDefaultTypeCheckErrorCallbackForLte($value);

        return $clone;
    }

    /**
     * require the value to be positive (> 0)
     *
     * @param ErrorCallback|null $error
     */
    public function positive(?callable $error = null): static
    {
        return $this->gt(value: 0.0, error: $error);
    }

    /**
     * require the value to be negative (< 0)
     *
     * @param ErrorCallback|null $error
     */
    public function negative(?callable $error = null): static
    {
        return $this->lt(value: 0.0, error: $error);
    }

    /**
     * require the value to be non-negative (>= 0)
     *
     * @param ErrorCallback|null $error
     */
    public function nonNegative(?callable $error = null): static
    {
        return $this->gte(value: 0.0, error: $error);
    }

    /**
     * require the value to be non-positive (<= 0)
     *
     * @param ErrorCallback|null $error
     */
    public function nonPositive(?callable $error = null): static
    {
        return $this->lte(value: 0.0, error: $error);
    }

    /**
     * require the value to be a multiple of the given value
     *
     * @param ErrorCallback|null $error
     */
    public function multipleOf(
        float $value,
        ?callable $error = null,
    ): static {
        $clone = clone $this;
        $clone->multipleOfValue = $value;
        $clone->multipleOfError = $error ?? $this->getDefaultTypeCheckErrorCallbackForMultipleOf($value);

        return $clone;
    }

    /**
     * require the value to be finite (not INF or NAN)
     *
     * @param ErrorCallback|null $error
     */
    public function finite(?callable $error = null): static
    {
        $clone = clone $this;
        $clone->mustBeFinite = true;
        $clone->finiteError = $error ?? $this->getDefaultTypeCheckErrorCallbackForFinite();

        return $clone;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'float';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if (is_float($data)) {
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
        assert(is_float($data));

        if ($this->mustBeFinite && ! is_finite($data)) {
            /** @var ErrorCallback $finiteError */
            $finiteError = $this->finiteError;
            $this->invokeErrorCallback(
                callback: $finiteError,
                input: $data,
                context: $context,
            );
        }

        if ($this->gtValue !== null && $data <= $this->gtValue) {
            /** @var ErrorCallback $gtError */
            $gtError = $this->gtError;
            $this->invokeErrorCallback(
                callback: $gtError,
                input: $data,
                context: $context,
            );
        }

        if ($this->gteValue !== null && $data < $this->gteValue) {
            /** @var ErrorCallback $gteError */
            $gteError = $this->gteError;
            $this->invokeErrorCallback(
                callback: $gteError,
                input: $data,
                context: $context,
            );
        }

        if ($this->ltValue !== null && $data >= $this->ltValue) {
            /** @var ErrorCallback $ltError */
            $ltError = $this->ltError;
            $this->invokeErrorCallback(
                callback: $ltError,
                input: $data,
                context: $context,
            );
        }

        if ($this->lteValue !== null && $data > $this->lteValue) {
            /** @var ErrorCallback $lteError */
            $lteError = $this->lteError;
            $this->invokeErrorCallback(
                callback: $lteError,
                input: $data,
                context: $context,
            );
        }

        if ($this->multipleOfValue !== null) {
            $remainder = fmod($data, $this->multipleOfValue);
            if (abs($remainder) > PHP_FLOAT_EPSILON) {
                /** @var ErrorCallback $multipleOfError */
                $multipleOfError = $this->multipleOfError;
                $this->invokeErrorCallback(
                    callback: $multipleOfError,
                    input: $data,
                    context: $context,
                );
            }
        }
    }

    protected function coerceValue(mixed $data): mixed
    {
        if (is_string($data) && is_numeric($data)) {
            return (float) $data;
        }

        if (is_int($data)) {
            return (float) $data;
        }

        if (is_bool($data)) {
            return $data ? 1.0 : 0.0;
        }

        return $data;
    }
}
