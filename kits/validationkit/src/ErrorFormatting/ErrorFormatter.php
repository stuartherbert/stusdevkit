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

namespace StusDevKit\ValidationKit\ErrorFormatting;

use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ErrorFormatter provides static methods for transforming
 * a ValidationException's issues into different output
 * formats.
 *
 * Usage:
 *
 *     try {
 *         $schema->parse($input);
 *     } catch (ValidationException $e) {
 *         // flat format for form handling
 *         $flat = ErrorFormatter::flatten($e);
 *         echo $flat->fieldErrors()['email'][0];
 *
 *         // tree format for nested data
 *         $tree = ErrorFormatter::treeify($e);
 *         $addrErrors = $tree->maybeChild('address');
 *
 *         // human-readable string
 *         echo ErrorFormatter::prettify($e);
 *     }
 */
final class ErrorFormatter
{
    private function __construct()
    {
    }

    // ================================================================
    //
    // Formatting Methods
    //
    // ----------------------------------------------------------------

    /**
     * flatten validation errors into form-level and
     * field-level groups
     *
     * Errors with no path become form errors. Errors with
     * a path are grouped by their dot-path string.
     */
    public static function flatten(
        ValidationException $exception,
    ): FlatError {
        $formErrors = [];
        /** @var array<string, list<string>> $fieldErrors */
        $fieldErrors = [];

        foreach ($exception->issues() as $issue) {
            $path = $issue->pathAsString();

            if ($path === '') {
                $formErrors[] = $issue->message;
            } else {
                if (! isset($fieldErrors[$path])) {
                    $fieldErrors[$path] = [];
                }
                $fieldErrors[$path][] = $issue->message;
            }
        }

        return new FlatError(
            formErrors: $formErrors,
            fieldErrors: $fieldErrors,
        );
    }

    /**
     * transform validation errors into a nested tree
     * structure that mirrors the validated data shape
     */
    public static function treeify(
        ValidationException $exception,
    ): TreeError {
        /** @var list<ValidationIssue> $issues */
        $issues = $exception->issues()->toArray();

        return self::buildTree($issues);
    }

    /**
     * format validation errors as a human-readable string
     *
     * Each issue is listed on its own line with its path
     * (if any) and message.
     */
    public static function prettify(
        ValidationException $exception,
    ): string {
        $lines = [];

        foreach ($exception->issues() as $issue) {
            $path = $issue->pathAsString();
            if ($path !== '') {
                $lines[] = '  ✗ ' . $path . ': '
                    . $issue->message;
            } else {
                $lines[] = '  ✗ ' . $issue->message;
            }
        }

        $count = count($exception->issues());
        $header = $count === 1
            ? '1 validation issue'
            : $count . ' validation issues';

        return $header . "\n" . implode("\n", $lines);
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * build a tree of errors from a flat list of issues
     *
     * @param list<ValidationIssue> $issues
     */
    private static function buildTree(array $issues): TreeError
    {
        // group issues by their first path segment
        $rootErrors = [];
        /** @var array<string|int, list<ValidationIssue>> $grouped */
        $grouped = [];

        foreach ($issues as $issue) {
            if (count($issue->path) === 0) {
                $rootErrors[] = $issue->message;
                continue;
            }

            $firstSegment = $issue->path[0];
            if (! isset($grouped[$firstSegment])) {
                $grouped[$firstSegment] = [];
            }

            // create a new issue with the first path
            // segment removed
            $childIssue = new ValidationIssue(
                type: $issue->type,
                input: $issue->input,
                path: array_slice($issue->path, 1),
                message: $issue->message,
                extra: $issue->extra,
            );
            $grouped[$firstSegment][] = $childIssue;
        }

        // recursively build child trees
        $children = [];
        foreach ($grouped as $key => $childIssues) {
            $children[$key] = self::buildTree($childIssues);
        }

        return new TreeError(
            errors: $rootErrors,
            children: $children,
        );
    }
}
