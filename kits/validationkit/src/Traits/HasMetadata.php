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

namespace StusDevKit\ValidationKit\Traits;

/**
 * HasMetadata provides describe() and meta() methods
 * for attaching metadata to schemas.
 *
 * Metadata does not affect validation behaviour. It is
 * used by tooling such as JSON Schema generation, code
 * generation, and documentation.
 *
 * @phpstan-type SchemaMetadata array<string, mixed>
 */
trait HasMetadata
{
    protected ?string $description = null;

    /** @var SchemaMetadata */
    protected array $metadata = [];

    // ================================================================
    //
    // Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * add a human-readable description to this schema
     *
     * This is a convenience shorthand for setting the
     * 'description' key in the metadata.
     *
     * @param non-empty-string $text
     */
    public function describe(string $text): static
    {
        $clone = clone $this;
        $clone->description = $text;

        return $clone;
    }

    /**
     * attach arbitrary metadata to this schema
     *
     * Metadata is merged with any existing metadata. To
     * replace all metadata, create a new schema instead.
     *
     * @param SchemaMetadata $data
     */
    public function meta(array $data): static
    {
        $clone = clone $this;
        $clone->metadata = array_merge($clone->metadata, $data);

        return $clone;
    }

    // ================================================================
    //
    // Getters
    //
    // ----------------------------------------------------------------

    /**
     * return the description, or null if none was set
     */
    public function maybeDescription(): ?string
    {
        return $this->description;
    }

    /**
     * return the metadata
     *
     * @return SchemaMetadata
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}
