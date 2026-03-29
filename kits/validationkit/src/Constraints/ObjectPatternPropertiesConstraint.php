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
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;

/**
 * ObjectPatternPropertiesConstraint validates properties
 * whose names match regex patterns against corresponding
 * schemas.
 *
 * Each pattern is a regex string mapped to a schema. For
 * every key in the data that matches a pattern, the value
 * is validated against that pattern's schema. Issues from
 * the child schemas propagate naturally through child
 * contexts.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\ObjectPatternPropertiesConstraint;
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $constraint = new ObjectPatternPropertiesConstraint(
 *         patterns: [
 *             '/^str_/' => Validate::string(),
 *             '/^num_/' => Validate::int(),
 *         ],
 *     );
 *
 * @phpstan-type PatternMap array<string, ValidationSchema<mixed>>
 */
final class ObjectPatternPropertiesConstraint implements ValidationConstraint
{
    /**
     * @param PatternMap $patterns
     * - map of regex patterns to schemas; each property
     *   whose name matches a pattern is validated against
     *   the corresponding schema
     */
    public function __construct(
        private readonly array $patterns,
    ) {
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the pattern-to-schema map
     *
     * @return array<string, ValidationSchema<mixed>>
     */
    public function patterns(): array
    {
        return $this->patterns;
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check properties matching patterns against their schemas
     *
     * For each pattern-schema pair, iterates all keys in the
     * data. If a key matches the pattern, its value is
     * validated against the schema using a child context at
     * that key's path. Issues propagate to the main context.
     *
     * @param array<mixed> $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        foreach ($this->patterns as $pattern => $schema) {
            foreach ($data as $key => $value) {
                $keyString = (string) $key;
                if (preg_match($pattern, $keyString)) {
                    $childContext = $context->atPath($keyString);
                    $schema->parseWithContext(
                        data: $value,
                        context: $childContext,
                    );
                }
            }
        }

        return $data;
    }

    public function skipOnIssues(): bool
    {
        return false;
    }
}
