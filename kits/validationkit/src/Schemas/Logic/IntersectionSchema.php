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

use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * IntersectionSchema validates that the input matches
 * both the left and right schemas ("and" logic).
 *
 * Both schemas are run and all issues from both are
 * collected. Primarily useful for combining two object
 * schemas.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $hasName = Validate::object([
 *         'name' => Validate::string(),
 *     ]);
 *     $hasAge = Validate::object([
 *         'age' => Validate::int(),
 *     ]);
 *     $person = Validate::intersection($hasName, $hasAge);
 *     $person->parse(['name' => 'Stuart', 'age' => 42]);
 *
 * @extends BaseSchema<mixed>
 */
class IntersectionSchema extends BaseSchema
{
    /**
     * @param BaseSchema<mixed> $left
     * @param BaseSchema<mixed> $right
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly BaseSchema $left,
        private readonly BaseSchema $right,
        ?callable $typeCheckError = null,
    ) {
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
            message: 'Expected intersection, received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'intersection';
    }

    /**
     * override parseWithContext to validate against both
     * schemas
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

        // validate against both schemas — issues from both
        // are collected into the same context
        $leftResult = $this->left->parseWithContext(
            data: $data,
            context: $context,
        );

        $rightResult = $this->right->parseWithContext(
            data: $data,
            context: $context,
        );

        // if both schemas produce array outputs, merge them
        $result = is_array($leftResult) && is_array($rightResult)
            ? array_merge($leftResult, $rightResult)
            : $leftResult;

        if ($context->hasIssues()) {
            return $result;
        }

        // run this schema's own pipeline
        // (transform, refine, pipe)
        return $this->runOwnPipeline(
            data: $result,
            context: $context,
        );
    }

    /**
     * run this schema's own transform/refine/pipe
     * pipeline after both child schemas have validated
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

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // handled by parseWithContext override
        return true;
    }

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        // handled by parseWithContext override
    }
}
