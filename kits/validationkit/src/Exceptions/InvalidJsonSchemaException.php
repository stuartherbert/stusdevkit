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

namespace StusDevKit\ValidationKit\Exceptions;

use RuntimeException;

/**
 * InvalidJsonSchemaException is thrown when a JSON Schema
 * document contains structural errors that prevent import.
 *
 * This is a development-time error indicating that the
 * JSON Schema is malformed, not a runtime validation
 * failure.
 *
 * Usage:
 *
 *     throw InvalidJsonSchemaException::unknownType(
 *         type: 'foo',
 *     );
 */
class InvalidJsonSchemaException extends RuntimeException
{
    /**
     * the JSON Schema type value is not recognised
     *
     * @param non-empty-string $type
     */
    public static function unknownType(string $type): self
    {
        return new self(
            'Unknown JSON Schema type: "' . $type . '"',
        );
    }

    /**
     * a $ref points to a definition that does not exist
     *
     * @param non-empty-string $ref
     */
    public static function unresolvedRef(string $ref): self
    {
        return new self(
            'Unresolved $ref: "' . $ref . '"',
        );
    }

    /**
     * a keyword is not valid for the given type
     *
     * @param non-empty-string $keyword
     * @param non-empty-string $type
     */
    public static function invalidKeyword(
        string $keyword,
        string $type,
    ): self {
        return new self(
            'Keyword "' . $keyword . '" is not valid'
                . ' for type "' . $type . '"',
        );
    }

    /**
     * the JSON Schema document is structurally invalid
     *
     * @param non-empty-string $reason
     */
    public static function malformed(string $reason): self
    {
        return new self(
            'Invalid JSON Schema: ' . $reason,
        );
    }
}
