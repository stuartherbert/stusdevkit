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

namespace StusDevKit\ValidationKit\JsonSchema;

use StusDevKit\CollectionsKit\Stacks\StackOfStrings;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;

/**
 * JsonSchemaRegistry maps definition names, URIs, and
 * anchors to validation schemas, enabling `$ref`,
 * `$defs`, `$id`, and `$anchor` support in both the
 * JSON Schema importer and exporter.
 *
 * The registry provides three independent lookup
 * mechanisms:
 *
 * 1. **Definition names** — `register()` / `get()` for
 *    `$defs`-based `$ref` resolution (e.g.
 *    `#/$defs/Address`).
 *
 * 2. **URIs** — `registerByUri()` / `resolveByUri()`
 *    for `$id`-based resolution and external `$ref`.
 *
 * 3. **Anchors** — `registerAnchor()` /
 *    `resolveAnchor()` for `$anchor`-based resolution
 *    (e.g. `#my-anchor`). Anchors are scoped to the
 *    base URI of the schema that declares them.
 *
 * A base URI stack (`pushBaseUri()` / `popBaseUri()`)
 * tracks the current `$id` scope during import, so
 * that relative URIs and anchors resolve correctly.
 *
 * Usage:
 *
 *     $registry = new JsonSchemaRegistry();
 *
 *     // register a named schema ($defs)
 *     $registry->register(
 *         name: 'Address',
 *         schema: Validate::object([...]),
 *     );
 *
 *     // resolve a $ref
 *     $schema = $registry->resolveRef(
 *         ref: '#/$defs/Address',
 *     );
 *
 *     // register by URI ($id)
 *     $registry->registerByUri(
 *         uri: 'https://example.com/address',
 *         schema: $addressSchema,
 *     );
 *
 *     // register an anchor ($anchor)
 *     $registry->registerAnchor(
 *         baseUri: 'https://example.com/person',
 *         anchor: 'name-def',
 *         schema: $nameSchema,
 *     );
 */
class JsonSchemaRegistry
{
    private const REF_PREFIX = '#/$defs/';

    /**
     * registered schemas keyed by definition name
     */
    private JsonSchemaIndex $schemas;

    /**
     * schemas keyed by absolute URI (from $id)
     */
    private JsonSchemaIndex $uriSchemas;

    /**
     * schemas keyed by "baseUri#anchor" (from $anchor)
     */
    private JsonSchemaIndex $anchorSchemas;

    /**
     * stack of base URIs tracking $id scope during import
     */
    private StackOfStrings $baseUriStack;

    // ================================================================
    //
    // Constructor
    //
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->schemas = new JsonSchemaIndex();
        $this->uriSchemas = new JsonSchemaIndex();
        $this->anchorSchemas = new JsonSchemaIndex();
        $this->baseUriStack = new StackOfStrings();
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
    // URI-Based Registration ($id)
    //
    // ----------------------------------------------------------------

    /**
     * register a schema by its absolute URI
     *
     * Used when a schema declares an `$id` keyword. The
     * URI should be absolute (no fragment).
     *
     * @param non-empty-string $uri
     * @param ValidationSchema<mixed> $schema
     */
    public function registerByUri(
        string $uri,
        ValidationSchema $schema,
    ): void {
        $this->uriSchemas->set(key: $uri, value: $schema);
    }

    /**
     * resolve a schema by its absolute URI
     *
     * @param non-empty-string $uri
     * @return ValidationSchema<mixed>
     * @throws InvalidJsonSchemaException
     *         if no schema is registered with this URI.
     */
    public function resolveByUri(string $uri): ValidationSchema
    {
        $schema = $this->uriSchemas->maybeGet(key: $uri);

        if ($schema === null) {
            throw InvalidJsonSchemaException::unresolvedRef(
                ref: $uri,
            );
        }

        return $schema;
    }

    /**
     * is a schema registered for this URI?
     *
     * @param non-empty-string $uri
     */
    public function hasByUri(string $uri): bool
    {
        return $this->uriSchemas->has(key: $uri);
    }

    // ================================================================
    //
    // Anchor Registration ($anchor)
    //
    // ----------------------------------------------------------------

    /**
     * register an anchor scoped to a base URI
     *
     * Anchors are identified by the combination of the
     * declaring schema's base URI and the anchor name.
     *
     * @param string $baseUri
     * - the absolute URI of the schema that declares
     *   the anchor (from `$id`)
     * @param non-empty-string $anchor
     * - the anchor name (without the `#` prefix)
     * @param ValidationSchema<mixed> $schema
     */
    public function registerAnchor(
        string $baseUri,
        string $anchor,
        ValidationSchema $schema,
    ): void {
        $key = $baseUri . '#' . $anchor;
        $this->anchorSchemas->set(
            key: $key,
            value: $schema,
        );
    }

    /**
     * resolve an anchor within a base URI scope
     *
     * @param non-empty-string $anchor
     * @return ValidationSchema<mixed>
     * @throws InvalidJsonSchemaException
     *         if the anchor is not registered.
     */
    public function resolveAnchor(
        string $baseUri,
        string $anchor,
    ): ValidationSchema {
        $key = $baseUri . '#' . $anchor;
        $schema = $this->anchorSchemas->maybeGet(key: $key);

        if ($schema === null) {
            throw InvalidJsonSchemaException::unresolvedRef(
                ref: $key,
            );
        }

        return $schema;
    }

    // ================================================================
    //
    // Base URI Stack
    //
    // ----------------------------------------------------------------

    /**
     * push a new base URI onto the stack
     *
     * Called when the importer encounters an `$id`
     * keyword. The pushed URI becomes the current base
     * for resolving relative URIs and anchors.
     *
     * @param non-empty-string $uri
     */
    public function pushBaseUri(string $uri): void
    {
        $this->baseUriStack->push($uri);
    }

    /**
     * pop the most recent base URI from the stack
     *
     * Called when the importer leaves a schema scope
     * that had an `$id`.
     */
    public function popBaseUri(): void
    {
        $this->baseUriStack->pop();
    }

    /**
     * return the current base URI
     *
     * Returns the most recently pushed base URI, or an
     * empty string if the stack is empty.
     */
    public function currentBaseUri(): string
    {
        return $this->baseUriStack->maybePeek() ?? '';
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
