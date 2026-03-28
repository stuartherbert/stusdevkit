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
 * DiscriminatedAnyOfSchema validates that the input
 * matches one of the given schemas, selected by a
 * discriminator field.
 *
 * This is more efficient than AnyOfSchema because it
 * looks at the discriminator field value to pick the
 * correct schema directly, rather than trying each
 * schema in sequence.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $event = Validate::discriminatedAnyOf('type', [
 *         Validate::object([
 *             'type' => Validate::literal('click'),
 *             'x' => Validate::int(),
 *             'y' => Validate::int(),
 *         ]),
 *         Validate::object([
 *             'type' => Validate::literal('keypress'),
 *             'key' => Validate::string(),
 *         ]),
 *     ]);
 *
 *     $event->parse(['type' => 'click', 'x' => 10, 'y' => 20]);
 *
 * @extends BaseSchema<mixed>
 */
class DiscriminatedAnyOfSchema extends BaseSchema
{
    /**
     * @param non-empty-string $discriminator
     * - the key in the input array used to select the
     *   schema
     * @param list<BaseSchema<mixed>> $schemas
     * - the schemas to choose from; each must be an
     *   ObjectSchema with a literal field for the
     *   discriminator
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly string $discriminator,
        private readonly array $schemas,
        ?callable $typeCheckError = null,
    ) {
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
        return fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidType,
            input: $data,
            path: [],
            message: 'Expected object with discriminator'
                . ' "' . $this->discriminator . '",'
                . ' received '
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
        return 'discriminated union';
    }

    /**
     * null is allowed through to the child schemas
     */
    protected function acceptsNull(): bool
    {
        return true;
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // child schemas handle type checking
        return true;
    }

    /**
     * select the correct schema by discriminator value
     * and return its result
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // must be an array to have a discriminator field
        if (! is_array($data)) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return $data;
        }

        // check discriminator field exists
        if (! array_key_exists($this->discriminator, $data)) {
            $context->addIssue(
                code: IssueCode::InvalidUnion,
                input: $data,
                message: 'Missing discriminator field "'
                    . $this->discriminator . '"',
            );

            return $data;
        }

        // find the matching schema by trying each one
        foreach ($this->schemas as $schema) {
            $testContext = new ValidationContext(
                $context->path(),
            );
            $schema->parseWithContext(
                data: $data,
                context: $testContext,
            );

            if (! $testContext->hasIssues()) {
                return $schema->parseWithContext(
                    data: $data,
                    context: $context,
                );
            }
        }

        // no schema matched
        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return $data;
    }
}
