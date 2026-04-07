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

namespace StusDevKit\ValidationKit\Tests\Fixtures;

use stdClass;
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaLoader;

/**
 * InMemorySchemaLoader is a test fixture that resolves
 * external JSON Schema documents from a pre-configured
 * in-memory map.
 *
 * Usage:
 *
 *     $loader = new InMemorySchemaLoader();
 *     $loader->addFromJson(
 *         uri: 'https://example.com/address.json',
 *         json: '{"type": "object", ...}',
 *     );
 *
 *     $schema = $loader->load(
 *         'https://example.com/address.json',
 *     );
 */
class InMemorySchemaLoader implements JsonSchemaLoader
{
    /** @var array<string, JsonSchema> */
    private array $schemas = [];

    /**
     * how many times load() has been called
     */
    private int $loadCount = 0;

    /**
     * register a schema from a JSON string
     *
     * @param non-empty-string $uri
     * @param non-empty-string $json
     */
    public function addFromJson(
        string $uri,
        string $json,
    ): void {
        $decoded = json_decode($json);
        assert($decoded instanceof stdClass);

        $this->schemas[$uri] = new JsonSchema($decoded);
    }

    /**
     * load a JSON Schema document by its absolute URI
     *
     * @param non-empty-string $uri
     */
    public function load(string $uri): ?JsonSchema
    {
        $this->loadCount++;

        return $this->schemas[$uri] ?? null;
    }

    /**
     * return how many times load() has been called
     */
    public function loadCount(): int
    {
        return $this->loadCount;
    }
}
