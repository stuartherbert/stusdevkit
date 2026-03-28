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

use StusDevKit\ValidationKit\Coercions\CoerceToString;
use StusDevKit\ValidationKit\Constraints\StringEmailConstraint;
use StusDevKit\ValidationKit\Constraints\StringEndsWithConstraint;
use StusDevKit\ValidationKit\Constraints\StringExactLengthConstraint;
use StusDevKit\ValidationKit\Constraints\StringIncludesConstraint;
use StusDevKit\ValidationKit\Constraints\StringIpv4Constraint;
use StusDevKit\ValidationKit\Constraints\StringIpv6Constraint;
use StusDevKit\ValidationKit\Constraints\StringMaxLengthConstraint;
use StusDevKit\ValidationKit\Constraints\StringMinLengthConstraint;
use StusDevKit\ValidationKit\Constraints\StringRegexConstraint;
use StusDevKit\ValidationKit\Constraints\StringStartsWithConstraint;
use StusDevKit\ValidationKit\Constraints\StringUrlConstraint;
use StusDevKit\ValidationKit\Constraints\StringUuidConstraint;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * StringSchema validates that the input is a string and
 * optionally applies length, format, and content
 * constraints.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // basic string with length constraints
 *     $name = Validate::string()
 *         ->min(length: 1)
 *         ->max(length: 100);
 *
 *     // email validation
 *     $email = Validate::string()->email();
 *
 *     // with transforms
 *     $tag = Validate::string()
 *         ->applyTrim()
 *         ->applyToLowerCase()
 *         ->min(length: 1);
 *
 *     // with custom error
 *     $name = Validate::string()->min(
 *         length: 1,
 *         error: fn($data) => new MyException(
 *             detail: 'Name is required',
 *         ),
 *     );
 *
 * @extends BaseSchema<string>
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
class StringSchema extends BaseSchema
{
    private bool $shouldTrim = false;
    private bool $shouldLowerCase = false;
    private bool $shouldUpperCase = false;

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
            message: 'Expected string, received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * require the string to have at least the given length
     *
     * @param ErrorCallback|null $error
     */
    public function min(int $length, ?callable $error = null): static
    {
        return $this->withConstraint(
            new StringMinLengthConstraint(
                length: $length,
                error: $error,
            ),
        );
    }

    /**
     * require the string to have at most the given length
     *
     * @param ErrorCallback|null $error
     */
    public function max(int $length, ?callable $error = null): static
    {
        return $this->withConstraint(
            new StringMaxLengthConstraint(
                length: $length,
                error: $error,
            ),
        );
    }

    /**
     * require the string to have exactly the given length
     *
     * @param ErrorCallback|null $error
     */
    public function length(int $length, ?callable $error = null): static
    {
        return $this->withConstraint(
            new StringExactLengthConstraint(
                length: $length,
                error: $error,
            ),
        );
    }

    /**
     * require the string to match the given regex pattern
     *
     * @param non-empty-string $pattern
     * - a PCRE pattern including delimiters,
     *   e.g. '/^[a-z]+$/i'
     * @param ErrorCallback|null $error
     */
    public function regex(
        string $pattern,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new StringRegexConstraint(
                pattern: $pattern,
                error: $error,
            ),
        );
    }

    /**
     * require the string to be a valid email address
     *
     * Uses PHP's FILTER_VALIDATE_EMAIL filter.
     *
     * @param ErrorCallback|null $error
     */
    public function email(?callable $error = null): static
    {
        return $this->withConstraint(
            new StringEmailConstraint(error: $error),
        );
    }

    /**
     * require the string to be a valid URL
     *
     * Uses PHP's FILTER_VALIDATE_URL filter.
     *
     * @param ErrorCallback|null $error
     */
    public function url(?callable $error = null): static
    {
        return $this->withConstraint(
            new StringUrlConstraint(error: $error),
        );
    }

    /**
     * require the string to be a valid UUID
     *
     * Accepts UUID v1-v8 in standard 8-4-4-4-12 format.
     *
     * @param ErrorCallback|null $error
     */
    public function uuid(?callable $error = null): static
    {
        return $this->withConstraint(
            new StringUuidConstraint(error: $error),
        );
    }

    /**
     * require the string to be a valid IPv4 address
     *
     * @param ErrorCallback|null $error
     */
    public function ipv4(?callable $error = null): static
    {
        return $this->withConstraint(
            new StringIpv4Constraint(error: $error),
        );
    }

    /**
     * require the string to be a valid IPv6 address
     *
     * @param ErrorCallback|null $error
     */
    public function ipv6(?callable $error = null): static
    {
        return $this->withConstraint(
            new StringIpv6Constraint(error: $error),
        );
    }

    /**
     * require the string to contain the given substring
     *
     * @param ErrorCallback|null $error
     */
    public function includes(
        string $needle,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new StringIncludesConstraint(
                needle: $needle,
                error: $error,
            ),
        );
    }

    /**
     * require the string to start with the given prefix
     *
     * @param ErrorCallback|null $error
     */
    public function startsWith(
        string $prefix,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new StringStartsWithConstraint(
                prefix: $prefix,
                error: $error,
            ),
        );
    }

    /**
     * require the string to end with the given suffix
     *
     * @param ErrorCallback|null $error
     */
    public function endsWith(
        string $suffix,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new StringEndsWithConstraint(
                suffix: $suffix,
                error: $error,
            ),
        );
    }

    // ================================================================
    //
    // Transform Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * trim whitespace from the string before validation
     *
     * Trimming is applied before constraint checks, so
     * min/max length checks operate on the trimmed string.
     */
    public function applyTrim(): static
    {
        $clone = clone $this;
        $clone->shouldTrim = true;

        return $clone;
    }

    /**
     * convert the string to lower case before validation
     */
    public function applyToLowerCase(): static
    {
        $clone = clone $this;
        $clone->shouldLowerCase = true;

        return $clone;
    }

    /**
     * convert the string to upper case before validation
     */
    public function applyToUpperCase(): static
    {
        $clone = clone $this;
        $clone->shouldUpperCase = true;

        return $clone;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'string';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if (is_string($data)) {
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
     * apply built-in transforms and check constraints
     *
     * Overrides the base parseWithContext to apply string
     * transforms (trim, toLowerCase, toUpperCase) after
     * the type check but before constraint checks.
     */
    public function parseWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // step 1: null/missing check (from base)
        if ($data === null) {
            if ($this->hasDefault) {
                return $this->defaultValue;
            }

            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return null;
        }

        // step 2: coerce
        $data = $this->coercion->coerce($data);

        // step 3: type check
        if (! is_string($data)) {
            $this->checkType(data: $data, context: $context);
            return $data;
        }

        // step 3.5: apply string transforms before
        // constraints
        $data = $this->applyStringTransforms($data);

        // step 4: constraint checks
        $this->checkConstraints(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            return $data;
        }

        // steps 5-7: pipeline and pipe (via base logic)
        /** @var array{mixed, bool} $pipelineResult */
        $pipelineResult = $this->runPipeline(
            data: $data,
            context: $context,
        );
        $data = $pipelineResult[0];
        $pipelineClean = $pipelineResult[1];

        if (! $pipelineClean) {
            return $data;
        }

        if ($this->pipeTarget !== null) {
            $data = $this->pipeTarget->parseWithContext(
                data: $data,
                context: $context,
            );
        }

        return $data;
    }

    protected function defaultCoercion(): ValueCoercion
    {
        return new CoerceToString();
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * apply built-in string transforms
     */
    private function applyStringTransforms(string $data): string
    {
        if ($this->shouldTrim) {
            $data = trim($data);
        }

        if ($this->shouldLowerCase) {
            $data = mb_strtolower($data);
        }

        if ($this->shouldUpperCase) {
            $data = mb_strtoupper($data);
        }

        return $data;
    }
}
