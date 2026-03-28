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
 * @phpstan-type StringFormatCheck array{
 *     type: string,
 *     pattern: string,
 *     needle: string,
 *     error: ErrorCallback,
 * }
 */
class StringSchema extends BaseSchema
{
    private ?int $minLength = null;
    /** @var ErrorCallback */
    private mixed $minError;

    private ?int $maxLength = null;
    /** @var ErrorCallback */
    private mixed $maxError;

    private ?int $exactLength = null;
    /** @var ErrorCallback */
    private mixed $lengthError;

    /**
     * format and content checks, evaluated in order
     *
     * @var list<StringFormatCheck>
     */
    private array $formatChecks = [];

    private bool $shouldTrim = false;
    private bool $shouldLowerCase = false;
    private bool $shouldUpperCase = false;

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
            message: 'Expected string, received '
                . get_debug_type($data),
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForMin(int $length): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooSmall,
            input: $data,
            path: [],
            message: 'String must be at least ' . $length . ' characters',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForMax(int $length): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooBig,
            input: $data,
            path: [],
            message: 'String must be at most ' . $length . ' characters',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForLength(int $length): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::TooSmall,
            input: $data,
            path: [],
            message: 'String must be exactly ' . $length . ' characters',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForRegex(string $pattern): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'String does not match pattern ' . $pattern,
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForEmail(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'Invalid email address',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForUrl(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'Invalid URL',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForUuid(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'Invalid UUID',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForIpv4(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'Invalid IPv4 address',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForIpv6(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'Invalid IPv6 address',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForIncludes(string $needle): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'String must contain "' . $needle . '"',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForStartsWith(string $prefix): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'String must start with "' . $prefix . '"',
        );
    }

    protected function getDefaultTypeCheckErrorCallbackForEndsWith(string $suffix): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidString,
            input: $data,
            path: [],
            message: 'String must end with "' . $suffix . '"',
        );
    }

    // ================================================================
    //
    // Length Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * require the string to have at least the given length
     *
     * @param ErrorCallback|null $error
     */
    public function min(int $length, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->minLength = $length;
        $clone->minError = $error ?? $this->getDefaultTypeCheckErrorCallbackForMin($length);

        return $clone;
    }

    /**
     * require the string to have at most the given length
     *
     * @param ErrorCallback|null $error
     */
    public function max(int $length, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->maxLength = $length;
        $clone->maxError = $error ?? $this->getDefaultTypeCheckErrorCallbackForMax($length);

        return $clone;
    }

    /**
     * require the string to have exactly the given length
     *
     * @param ErrorCallback|null $error
     */
    public function length(int $length, ?callable $error = null): static
    {
        $clone = clone $this;
        $clone->exactLength = $length;
        $clone->lengthError = $error ?? $this->getDefaultTypeCheckErrorCallbackForLength($length);

        return $clone;
    }

    // ================================================================
    //
    // Format Constraint Builder Methods
    //
    // ----------------------------------------------------------------

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
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'regex',
            'pattern' => $pattern,
            'needle'  => '',
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForRegex($pattern),
        ];

        return $clone;
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
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'email',
            'pattern' => '',
            'needle'  => '',
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForEmail(),
        ];

        return $clone;
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
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'url',
            'pattern' => '',
            'needle'  => '',
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForUrl(),
        ];

        return $clone;
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
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'uuid',
            'pattern' => '',
            'needle'  => '',
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForUuid(),
        ];

        return $clone;
    }

    /**
     * require the string to be a valid IPv4 address
     *
     * @param ErrorCallback|null $error
     */
    public function ipv4(?callable $error = null): static
    {
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'ipv4',
            'pattern' => '',
            'needle'  => '',
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForIpv4(),
        ];

        return $clone;
    }

    /**
     * require the string to be a valid IPv6 address
     *
     * @param ErrorCallback|null $error
     */
    public function ipv6(?callable $error = null): static
    {
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'ipv6',
            'pattern' => '',
            'needle'  => '',
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForIpv6(),
        ];

        return $clone;
    }

    // ================================================================
    //
    // Content Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * require the string to contain the given substring
     *
     * @param ErrorCallback|null $error
     */
    public function includes(
        string $needle,
        ?callable $error = null,
    ): static {
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'includes',
            'pattern' => '',
            'needle'  => $needle,
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForIncludes($needle),
        ];

        return $clone;
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
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'startsWith',
            'pattern' => '',
            'needle'  => $prefix,
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForStartsWith($prefix),
        ];

        return $clone;
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
        $clone = clone $this;
        $clone->formatChecks[] = [
            'type'    => 'endsWith',
            'pattern' => '',
            'needle'  => $suffix,
            'error'   => $error ?? $this->getDefaultTypeCheckErrorCallbackForEndsWith($suffix),
        ];

        return $clone;
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

            if ($this->isNullable || $this->isOptional) {
                return null;
            }

            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return null;
        }

        // step 2: coerce (if enabled)
        if ($this->coercionEnabled) {
            $data = $this->coerceValue($data);
        }

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
        $pipelineResult = $this->runStringPipeline(
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

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        assert(is_string($data));

        $len = mb_strlen($data);

        // length constraints
        if ($this->exactLength !== null && $len !== $this->exactLength) {
            /** @var ErrorCallback $lengthError */
            $lengthError = $this->lengthError;
            $this->invokeErrorCallback(
                callback: $lengthError,
                input: $data,
                context: $context,
            );
        }

        if ($this->minLength !== null && $len < $this->minLength) {
            /** @var ErrorCallback $minError */
            $minError = $this->minError;
            $this->invokeErrorCallback(
                callback: $minError,
                input: $data,
                context: $context,
            );
        }

        if ($this->maxLength !== null && $len > $this->maxLength) {
            /** @var ErrorCallback $maxError */
            $maxError = $this->maxError;
            $this->invokeErrorCallback(
                callback: $maxError,
                input: $data,
                context: $context,
            );
        }

        // format and content checks
        foreach ($this->formatChecks as $check) {
            $this->runFormatCheck(
                data: $data,
                check: $check,
                context: $context,
            );
        }
    }

    protected function coerceValue(mixed $data): mixed
    {
        if (is_int($data) || is_float($data)) {
            return (string) $data;
        }

        if (is_bool($data)) {
            return $data ? 'true' : 'false';
        }

        return $data;
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

    /**
     * run a single format or content check
     *
     * @param StringFormatCheck $check
     */
    private function runFormatCheck(
        string $data,
        array $check,
        ValidationContext $context,
    ): void {
        /** @var ErrorCallback $error */
        $error = $check['error'];

        switch ($check['type']) {
            case 'regex':
                /** @var non-empty-string $pattern */
                $pattern = $check['pattern'];
                if (preg_match($pattern, $data) !== 1) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'email':
                if (filter_var($data, FILTER_VALIDATE_EMAIL) === false) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'url':
                if (filter_var($data, FILTER_VALIDATE_URL) === false) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'uuid':
                $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
                if (preg_match($uuidPattern, $data) !== 1) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'ipv4':
                if (filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'ipv6':
                if (filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'includes':
                if (! str_contains($data, $check['needle'])) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'startsWith':
                if (! str_starts_with($data, $check['needle'])) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;

            case 'endsWith':
                if (! str_ends_with($data, $check['needle'])) {
                    $this->invokeErrorCallback(
                        callback: $error,
                        input: $data,
                        context: $context,
                    );
                }
                break;
        }
    }

    /**
     * run refinements and transforms for the string schema
     *
     * @return array{mixed, bool}
     */
    private function runStringPipeline(
        mixed $data,
        ValidationContext $context,
    ): array {
        foreach ($this->pipeline as $entry) {
            switch ($entry['type']) {
                case 'refine':
                    /** @var callable(mixed): bool $fn */
                    $fn = $entry['callable'];
                    $passed = $fn($data);
                    if (! $passed) {
                        /** @var non-falsy-string $message */
                        $message = $entry['message'];
                        $context->addIssue(
                            code: IssueCode::Custom,
                            input: $data,
                            message: $message,
                        );
                    }
                    break;

                case 'superRefine':
                    /** @var callable(mixed, ValidationContext): void $fn */
                    $fn = $entry['callable'];
                    $fn($data, $context);
                    break;

                case 'transform':
                    if ($context->hasIssues()) {
                        return [$data, false];
                    }
                    /** @var callable(mixed): mixed $fn */
                    $fn = $entry['callable'];
                    $data = $fn($data);
                    break;
            }
        }

        return [$data, ! $context->hasIssues()];
    }
}
