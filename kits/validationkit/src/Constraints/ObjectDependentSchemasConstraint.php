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
use StusDevKit\ValidationKit\Schemas\BaseSchema;

/**
 * ObjectDependentSchemasConstraint validates that when
 * certain properties are present in the object, additional
 * schemas are satisfied.
 *
 * Each dependency maps a property name to a schema. When
 * the property exists in the data, the entire data object
 * is validated against that schema. Issues from the
 * dependent schemas propagate naturally through the
 * context.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\ObjectDependentSchemasConstraint;
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $constraint = new ObjectDependentSchemasConstraint(
 *         dependencies: [
 *             'billing_address' => Validate::object([
 *                 'billing_address' => Validate::string(),
 *                 'billing_city' => Validate::string(),
 *             ]),
 *         ],
 *     );
 *
 * @phpstan-type DependencyMap array<string, BaseSchema<mixed>>
 */
final class ObjectDependentSchemasConstraint implements ValidationConstraint
{
    /**
     * @param DependencyMap $dependencies
     * - map of property names to schemas that must be
     *   satisfied when that property is present in the
     *   data
     */
    public function __construct(
        private readonly array $dependencies,
    ) {
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check dependent schemas for present properties
     *
     * For each dependency, if the property exists in the
     * data, the entire data object is validated against the
     * dependent schema using the current context. Issues
     * propagate naturally.
     *
     * @param array<mixed> $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        foreach ($this->dependencies as $propertyName => $schema) {
            if (array_key_exists($propertyName, $data)) {
                $schema->parseWithContext(
                    data: $data,
                    context: $context,
                );
            }
        }

        return $data;
    }

    public function skipOnIssues(): bool
    {
        return false;
    }
}
