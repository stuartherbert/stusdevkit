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

/**
 * ObjectDependentRequiredConstraint validates that when
 * certain properties are present in the object, additional
 * properties are also required to be present.
 *
 * Each dependency maps a property name to a list of
 * property names that must also exist when the trigger
 * property is present.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\ObjectDependentRequiredConstraint;
 *
 *     $constraint = new ObjectDependentRequiredConstraint(
 *         dependencies: [
 *             'billing_address' => [
 *                 'billing_city',
 *                 'billing_zip',
 *             ],
 *         ],
 *     );
 */
final class ObjectDependentRequiredConstraint implements ValidationConstraint
{
    /**
     * @param array<string, list<string>> $dependencies
     * - map of property names to lists of property names
     *   that must also be present when the trigger property
     *   exists
     */
    public function __construct(
        private readonly array $dependencies,
    ) {
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the dependency map
     *
     * @return array<string, list<string>>
     */
    public function dependencies(): array
    {
        return $this->dependencies;
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check dependent required properties
     *
     * For each dependency, if the trigger property exists
     * in the data, checks that all required properties are
     * also present. For each missing required property, a
     * validation issue is added.
     *
     * @param array<mixed>|object $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data) || is_object($data));

        /** @var array<string, mixed> $properties */
        $properties = is_object($data)
            ? get_object_vars($data)
            : $data;

        foreach ($this->dependencies as $propertyName => $requiredProperties) {
            if (array_key_exists($propertyName, $properties)) {
                foreach ($requiredProperties as $requiredProperty) {
                    if (! array_key_exists($requiredProperty, $properties)) {
                        $context->addIssue(
                            type: 'https://stusdevkit.dev/errors/validation/custom',
                            input: $data,
                            message: 'Property "' . $propertyName
                                . '" requires property "'
                                . $requiredProperty
                                . '" to also be present',
                        );
                    }
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
