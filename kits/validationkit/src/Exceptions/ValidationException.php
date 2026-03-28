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

use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ValidationException is the default exception thrown when
 * schema validation fails and no custom error callback has
 * been provided.
 *
 * It carries a list of ValidationIssue objects describing
 * every validation failure that was detected. The issues
 * are also serialised into the RFC 9457 `extra` field for
 * structured error reporting in API responses.
 *
 * Usage:
 *
 *     try {
 *         $schema->parse($input);
 *     } catch (ValidationException $e) {
 *         // inspect individual issues
 *         foreach ($e->issues() as $issue) {
 *             echo $issue->pathAsString() . ': '
 *                 . $issue->message . "\n";
 *         }
 *
 *         // or serialise for an API response
 *         header('Content-Type: application/problem+json');
 *         echo json_encode($e);
 *     }
 */
class ValidationException extends Rfc9457ProblemDetailsException
{
    /** @var list<ValidationIssue> */
    private array $issues;

    /**
     * @param list<ValidationIssue> $issues
     * - the validation failures that were detected
     */
    public function __construct(
        array $issues,
    ) {
        $this->issues = $issues;

        parent::__construct(
            type: 'tag:stusdevkit,2026:validation-failed',
            status: 422,
            title: 'Validation failed',
            extra: self::issuesToExtra($issues),
            detail: self::issuesToSummary($issues),
        );
    }

    // ================================================================
    //
    // Getters
    //
    // ----------------------------------------------------------------

    /**
     * return the list of validation issues
     *
     * @return list<ValidationIssue>
     */
    public function issues(): array
    {
        return $this->issues;
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * build a human-readable summary of the validation
     * failures
     *
     * @param list<ValidationIssue> $issues
     * @return non-empty-string
     */
    private static function issuesToSummary(array $issues): string
    {
        $count = count($issues);

        if ($count === 0) {
            return 'Validation failed';
        }

        if ($count === 1) {
            $issue = $issues[0];
            $path = $issue->pathAsString();

            if ($path !== '') {
                return $path . ': ' . $issue->message;
            }

            return $issue->message;
        }

        return $count . ' validation issues found';
    }

    /**
     * serialise issues into the RFC 9457 `extra` field
     * format
     *
     * The ProblemReportExtra type only allows int|string at
     * leaf level, so we convert each issue into a flat
     * string-keyed structure.
     *
     * @param list<ValidationIssue> $issues
     * @return array<string, string|array<string, string>>
     */
    private static function issuesToExtra(
        array $issues,
    ): array {
        $serialised = [];

        foreach ($issues as $index => $issue) {
            $serialised['issue_' . $index] = [
                'type'    => $issue->type,
                'title'   => $issue->title,
                'code'    => $issue->code->value,
                'path'    => $issue->pathAsString(),
                'message' => $issue->message,
            ];
        }

        return $serialised;
    }
}
