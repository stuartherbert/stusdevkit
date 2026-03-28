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
 * AllOfSchema validates that the input matches all of
 * the given schemas ("and" logic).
 *
 * All schemas are run and all issues from all are
 * collected. Primarily useful for combining object
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
 *     $person = Validate::allOf([$hasName, $hasAge]);
 *     $person->parse(['name' => 'Stuart', 'age' => 42]);
 *
 * @extends BaseSchema<mixed>
 */
class AllOfSchema extends BaseSchema
{
    /**
     * @param list<BaseSchema<mixed>> $schemas
     * - the schemas that must all pass
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly array $schemas,
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
     * override parseWithContext to validate against all
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

            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return null;
        }

        // validate against all schemas — issues from all
        // are collected into the same context
        $result = $data;
        foreach ($this->schemas as $schema) {
            $schemaResult = $schema->parseWithContext(
                data: $data,
                context: $context,
            );

            // if both the current result and the schema
            // result are arrays, merge them
            $result = is_array($result) && is_array($schemaResult)
                ? array_merge($result, $schemaResult)
                : $schemaResult;
        }

        if ($context->hasIssues()) {
            return $result;
        }

        // run any constraints added via withConstraint()
        $this->checkConstraints(
            data: $result,
            context: $context,
        );

        if ($context->hasIssues()) {
            return $result;
        }

        // run this schema's own pipeline
        // (transform, refine, pipe)
        /** @var array{mixed, bool} $pipelineResult */
        $pipelineResult = $this->runPipeline(
            data: $result,
            context: $context,
        );
        $result = $pipelineResult[0];

        if ($pipelineResult[1] && $this->pipeTarget !== null) {
            $result = $this->pipeTarget->parseWithContext(
                data: $result,
                context: $context,
            );
        }

        return $result;
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // handled by parseWithContext override
        return true;
    }
}
