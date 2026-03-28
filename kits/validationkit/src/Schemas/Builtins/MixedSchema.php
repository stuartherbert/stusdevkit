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

/**
 * MixedSchema accepts any value. It is the equivalent of
 * Zod's z.any() and z.unknown().
 *
 * This schema performs no type checking or constraint
 * checking. It is useful as a passthrough or when combined
 * with refine() for fully custom validation.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $schema = Validate::mixed();
 *     $schema->parse('anything'); // 'anything'
 *     $schema->parse(42);         // 42
 *     $schema->parse(null);       // null
 *
 *     // useful with refine for custom validation
 *     $schema = Validate::mixed()->refine(
 *         fn($data) => $data !== '',
 *         'Value must not be empty string',
 *     );
 *
 * @extends BaseSchema<mixed>
 */
class MixedSchema extends BaseSchema
{
    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'mixed';
    }

    /**
     * override the base null handling because MixedSchema
     * accepts anything including null
     */
    public function parseWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // skip null handling from BaseSchema — null is a
        // valid value for mixed

        // run the pipeline (refinements, transforms, pipe)
        /** @var array{mixed, bool} $pipelineResult */
        $pipelineResult = $this->runMixedPipeline(
            data: $data,
            context: $context,
        );
        $data = $pipelineResult[0];
        $pipelineClean = $pipelineResult[1];

        if (! $pipelineClean) {
            return $data;
        }

        // pipe to another schema
        if ($this->pipeTarget !== null) {
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
        // mixed accepts everything
        return true;
    }

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        // mixed has no constraints
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * run refinements and transforms for the mixed schema
     *
     * Duplicated from BaseSchema::runPipeline because that
     * method is private and MixedSchema overrides
     * parseWithContext.
     *
     * @return array{mixed, bool}
     */
    private function runMixedPipeline(
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

        $clean = ! $context->hasIssues();

        return [$data, $clean];
    }
}
