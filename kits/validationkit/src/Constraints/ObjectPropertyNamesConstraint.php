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

namespace StusDevKit\ValidationKit\Constraints;

use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ObjectPropertyNamesConstraint validates that all property
 * names (keys) of the object satisfy the given schema.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\ObjectPropertyNamesConstraint;
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // with default error message
 *     $constraint = new ObjectPropertyNamesConstraint(
 *         schema: Validate::string()->min(length: 1),
 *     );
 *
 *     // with custom error callback
 *     $constraint = new ObjectPropertyNamesConstraint(
 *         schema: Validate::string()->min(length: 1),
 *         error: fn($data) => new ValidationIssue(
 *             code: IssueCode::Custom,
 *             input: $data,
 *             path: [],
 *             message: 'Property names must be non-empty',
 *         ),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class ObjectPropertyNamesConstraint implements ValidationConstraint
{
    /** @var ErrorCallback */
    private readonly mixed $error;

    /**
     * @param BaseSchema<mixed> $schema
     * - the schema that every property name must satisfy
     * @param ErrorCallback|null $error
     * - optional custom error callback; if null, a default
     *   callback is used that creates a ValidationIssue
     *   with IssueCode::Custom
     */
    public function __construct(
        private readonly BaseSchema $schema,
        ?callable $error = null,
    ) {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                code: IssueCode::Custom,
                input: $data,
                path: [],
                message: 'One or more property names'
                    . ' are invalid',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that all property names satisfy the schema
     *
     * Iterates through the keys of the data array and
     * validates each one against the schema. If any key
     * fails validation, the error callback is invoked and
     * the check returns immediately.
     *
     * @param array<mixed> $data
     */
    public function check(
        mixed $data,
        ValidationContext $context,
    ): void {
        assert(is_array($data));

        foreach (array_keys($data) as $key) {
            $freshContext = new ValidationContext();
            $this->schema->parseWithContext(
                data: $key,
                context: $freshContext,
            );

            if ($freshContext->hasIssues()) {
                $issue = ($this->error)($data);
                $context->addExistingIssue(
                    $issue->withPath($context->path()),
                );
                return;
            }
        }
    }
}
