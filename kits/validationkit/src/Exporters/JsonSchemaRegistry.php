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

namespace StusDevKit\ValidationKit\Exporters;

use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;

/**
 * JsonSchemaRegistry maps definition names to validation
 * schemas, enabling `$ref` / `$defs` support in both
 * the JSON Schema importer and exporter.
 *
 * The importer populates the registry from the `$defs`
 * section of a JSON Schema document and resolves `$ref`
 * during import. The exporter reads the registry to emit
 * `$defs` and replace inline schemas with `$ref`.
 *
 * Definition names correspond to the keys inside the
 * JSON Schema `$defs` object. `$ref` values follow the
 * JSON Pointer format `#/$defs/<name>`.
 *
 * Usage:
 *
 *     $registry = new JsonSchemaRegistry();
 *
 *     // register a named schema
 *     $registry->register(
 *         name: 'Address',
 *         schema: Validate::object([...]),
 *     );
 *
 *     // resolve a $ref
 *     $schema = $registry->resolveRef(
 *         ref: '#/$defs/Address',
 *     );
 */
class JsonSchemaRegistry
{
    private const REF_PREFIX = '#/$defs/';

    /**
     * registered schemas keyed by definition name
     */
    private JsonSchemaIndex $schemas;

    // ================================================================
    //
    // Constructor
    //
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->schemas = new JsonSchemaIndex();
    }

    // ================================================================
    //
    // Registration
    //
    // ----------------------------------------------------------------

    /**
     * register a named schema
     *
     * If a schema with the same name already exists, it is
     * replaced.
     *
     * @param non-empty-string $name
     * @param ValidationSchema<mixed> $schema
     */
    public function register(
        string $name,
        ValidationSchema $schema,
    ): void {
        $this->schemas->set(key: $name, value: $schema);
    }

    // ================================================================
    //
    // Lookup
    //
    // ----------------------------------------------------------------

    /**
     * does a schema with this name exist?
     *
     * @param non-empty-string $name
     */
    public function has(string $name): bool
    {
        return $this->schemas->has(key: $name);
    }

    /**
     * return the schema for a definition name
     *
     * @param non-empty-string $name
     * @return ValidationSchema<mixed>
     * @throws InvalidJsonSchemaException
     *         if no schema is registered with this name.
     */
    public function get(string $name): ValidationSchema
    {
        $schema = $this->schemas->maybeGet(key: $name);

        if ($schema === null) {
            throw InvalidJsonSchemaException::unresolvedRef(
                ref: self::REF_PREFIX . $name,
            );
        }

        return $schema;
    }

    /**
     * resolve a `$ref` string to a schema
     *
     * Accepts the full JSON Pointer form
     * `#/$defs/<name>` and strips the prefix before
     * looking up the definition name.
     *
     * @param non-empty-string $ref
     * @return ValidationSchema<mixed>
     * @throws InvalidJsonSchemaException
     *         if the ref cannot be resolved.
     */
    public function resolveRef(string $ref): ValidationSchema
    {
        $name = $this->refToName($ref);

        return $this->get($name);
    }

    /**
     * return all registered schemas
     */
    public function all(): JsonSchemaIndex
    {
        return $this->schemas;
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * extract the definition name from a $ref string
     *
     * Strips the `#/$defs/` prefix. Throws if the ref
     * does not follow the expected format.
     *
     * @param non-empty-string $ref
     * @return non-empty-string
     * @throws InvalidJsonSchemaException
     *         if the ref format is not supported.
     */
    private function refToName(string $ref): string
    {
        if (! str_starts_with($ref, self::REF_PREFIX)) {
            throw InvalidJsonSchemaException::malformed(
                reason: '$ref "' . $ref . '" is not a'
                    . ' supported format; expected'
                    . ' "#/$defs/<name>"',
            );
        }

        $name = substr($ref, offset: strlen(self::REF_PREFIX));

        if ($name === '') {
            throw InvalidJsonSchemaException::malformed(
                reason: '$ref "' . $ref . '" has an empty'
                    . ' definition name',
            );
        }

        return $name;
    }
}
