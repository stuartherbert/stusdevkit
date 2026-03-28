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

namespace StusDevKit\ValidationKit;

use JsonSerializable;
use StusDevKit\CollectionsKit\Lists\CollectionAsList;

/**
 * ValidationIssuesList holds a list of ValidationIssue
 * objects collected during schema validation.
 *
 * Implements JsonSerializable to provide a concise array
 * representation of the issues, suitable for assertions
 * in tests and for JSON API responses.
 *
 * Usage:
 *
 *     // in tests, compare issues as a single array
 *     $this->assertSame(
 *         [
 *             [
 *                 'type' => 'https://stusdevkit.dev/errors/validation/invalid_type',
 *                 'path' => ['age'],
 *                 'message' => 'Expected int, received string',
 *             ],
 *         ],
 *         $result->maybeError()->issues()->jsonSerialize(),
 *     );
 *
 *     // for JSON API responses
 *     echo json_encode($exception->issues());
 *
 * @extends CollectionAsList<ValidationIssue>
 */
class ValidationIssuesList extends CollectionAsList implements JsonSerializable
{
    // ================================================================
    //
    // JsonSerializable Interface
    //
    // ----------------------------------------------------------------

    /**
     * return a concise array representation of each issue
     *
     * Each issue is reduced to its type, path, and message.
     *
     * @return list<array{
     *     type: string,
     *     path: list<string|int>,
     *     message: string,
     * }>
     */
    public function jsonSerialize(): array
    {
        return array_map(
            fn(ValidationIssue $issue) => [
                'type'    => $issue->type,
                'path'    => $issue->path,
                'message' => $issue->message,
            ],
            array_values($this->toArray()),
        );
    }
}
