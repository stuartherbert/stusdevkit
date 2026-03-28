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

namespace StusDevKit\ValidationKit\Schemas\Logic;

use BackedEnum;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * EnumSchema validates that the input is one of a set of
 * allowed values.
 *
 * Supports two modes:
 * - **String literal mode**: validates against a list of
 *   allowed string values
 * - **PHP enum mode**: validates against a PHP BackedEnum
 *   class, accepting the backing value and returning the
 *   enum case
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // string literal mode
 *     $status = Validate::enum(['active', 'inactive']);
 *     $status->parse('active');   // 'active'
 *     $status->parse('deleted');  // throws
 *
 *     // PHP enum mode
 *     enum Status: string {
 *         case Active = 'active';
 *         case Inactive = 'inactive';
 *     }
 *     $status = Validate::enum(Status::class);
 *     $status->parse('active');   // Status::Active
 *
 * @extends BaseSchema<mixed>
 */
class EnumSchema extends BaseSchema
{
    /**
     * the allowed values (string literal mode)
     *
     * @var list<string|int>|null
     */
    private ?array $allowedValues = null;

    /**
     * the BackedEnum class name (PHP enum mode)
     *
     * @var class-string<BackedEnum>|null
     */
    private ?string $enumClass = null;

    /**
     * @param list<string|int>|class-string<BackedEnum> $valuesOrEnumClass
     * - either a list of allowed values or a BackedEnum
     *   class name
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        array|string $valuesOrEnumClass,
        ?callable $typeCheckError = null,
    ) {
        if (is_string($valuesOrEnumClass)) {
            $this->enumClass = $valuesOrEnumClass;
        } else {
            $this->allowedValues = $valuesOrEnumClass;
        }

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
        return fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidEnum,
            input: $data,
            path: [],
            message: 'Value is not one of the allowed'
                . ' enum values: '
                . $this->describeAllowedValues(),
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'enum';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // in enum mode, we do all checking in checkType
        // since the result type changes (backing value →
        // enum case)
        return true;
    }

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        // string literal mode
        if ($this->allowedValues !== null) {
            if (! in_array($data, $this->allowedValues, true)) {
                $this->reportInvalidEnum(
                    data: $data,
                    context: $context,
                );
            }

            return;
        }

        // PHP enum mode
        if ($this->enumClass !== null) {
            if (! is_string($data) && ! is_int($data)) {
                $this->reportInvalidEnum(
                    data: $data,
                    context: $context,
                );

                return;
            }

            $enumCase = $this->enumClass::tryFrom($data);
            if ($enumCase === null) {
                $this->reportInvalidEnum(
                    data: $data,
                    context: $context,
                );
            }
        }
    }

    /**
     * override parseWithContext to return the enum case
     * in PHP enum mode
     */
    public function parseWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // step 1: null/missing check
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

        // string literal mode — return the value as-is
        if ($this->allowedValues !== null) {
            if (in_array($data, $this->allowedValues, true)) {
                return $this->runOwnPipeline(
                    data: $data,
                    context: $context,
                );
            }

            $this->reportInvalidEnum(
                data: $data,
                context: $context,
            );

            return $data;
        }

        // PHP enum mode — return the enum case
        if ($this->enumClass !== null) {
            if (! is_string($data) && ! is_int($data)) {
                $this->reportInvalidEnum(
                    data: $data,
                    context: $context,
                );

                return $data;
            }

            $enumCase = $this->enumClass::tryFrom($data);
            if ($enumCase !== null) {
                return $this->runOwnPipeline(
                    data: $enumCase,
                    context: $context,
                );
            }

            $this->reportInvalidEnum(
                data: $data,
                context: $context,
            );
        }

        return $data;
    }

    /**
     * run this schema's own transform/refine/pipe
     * pipeline after validation succeeds
     */
    private function runOwnPipeline(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        foreach ($this->pipeline as $entry) {
            switch ($entry['type']) {
                case 'refine':
                    /** @var callable(mixed): bool $fn */
                    $fn = $entry['callable'];
                    if (! $fn($data)) {
                        /** @var non-empty-string $message */
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
                        return $data;
                    }
                    /** @var callable(mixed): mixed $fn */
                    $fn = $entry['callable'];
                    $data = $fn($data);
                    break;
            }
        }

        if ($this->pipeTarget !== null && ! $context->hasIssues()) {
            $data = $this->pipeTarget->parseWithContext(
                data: $data,
                context: $context,
            );
        }

        return $data;
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * report an invalid enum value
     */
    private function reportInvalidEnum(
        mixed $data,
        ValidationContext $context,
    ): void {
        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );
    }

    /**
     * produce a human-readable list of allowed values
     *
     * @return non-empty-string
     */
    private function describeAllowedValues(): string
    {
        if ($this->allowedValues !== null) {
            $items = array_map(
                static fn(string|int $v): string => is_string($v)
                    ? '"' . $v . '"'
                    : (string) $v,
                $this->allowedValues,
            );

            return implode(', ', $items) ?: '(none)';
        }

        if ($this->enumClass !== null) {
            $cases = $this->enumClass::cases();
            $items = array_map(
                static fn(BackedEnum $case): string => is_string($case->value)
                    ? '"' . $case->value . '"'
                    : (string) $case->value,
                $cases,
            );

            return implode(', ', $items) ?: '(none)';
        }

        return '(none)';
    }
}
