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

/**
 * JsonSchemaLoader resolves external JSON Schema
 * documents by URI.
 *
 * Implement this interface to provide the importer with
 * the ability to resolve external `$ref` values. The
 * loader is responsible for fetching and decoding the
 * schema document — whether from the filesystem, HTTP,
 * a database, or an in-memory cache.
 *
 * The URI passed to load() is always an absolute URI
 * with no fragment (the fragment is resolved separately
 * by the importer after loading). The URI may or may
 * not have a scheme — for example, a relative file
 * path like `address.json` will be resolved to an
 * absolute URI by the importer before calling load().
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\JsonSchema\JsonSchemaLoader;
 *     use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
 *
 *     class FileSchemaLoader implements JsonSchemaLoader
 *     {
 *         public function __construct(
 *             private readonly string $basePath,
 *         ) {}
 *
 *         public function load(
 *             string $uri,
 *         ): ?JsonSchema {
 *             $path = $this->basePath . '/'
 *                 . basename($uri);
 *             if (! file_exists($path)) {
 *                 return null;
 *             }
 *             $json = file_get_contents($path);
 *             if ($json === false) {
 *                 return null;
 *             }
 *             $decoded = json_decode($json);
 *             if (! $decoded instanceof \stdClass) {
 *                 return null;
 *             }
 *             return new JsonSchema($decoded);
 *         }
 *     }
 */
interface JsonSchemaLoader
{
    /**
     * load a JSON Schema document by its absolute URI
     *
     * Returns the schema document if found, or null if
     * the URI cannot be resolved. The importer will
     * throw an appropriate exception when null is
     * returned for a required `$ref`.
     *
     * The URI will not contain a fragment — fragments
     * are resolved by the importer after the document
     * is loaded.
     *
     * @param non-empty-string $uri
     * - the absolute URI identifying the schema
     *   document (no fragment)
     */
    public function load(string $uri): ?JsonSchema;
}
