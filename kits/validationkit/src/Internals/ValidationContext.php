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

namespace StusDevKit\ValidationKit\Internals;

use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ValidationContext is an internal object that tracks the
 * current validation state during a parse operation.
 *
 * It accumulates validation issues and tracks the current
 * path through nested data structures. Collection schemas
 * (object, array) use this to pass path context to child
 * schemas.
 *
 * This class is not part of the public API and should not
 * be used directly by consumers of the library.
 *
 * @internal
 */
final class ValidationContext
{
    /** @var list<ValidationIssue> */
    private array $issues = [];

    /**
     * @param list<string|int> $path
     * - the current location in the data structure
     */
    public function __construct(
        private readonly array $path = [],
    ) {
    }

    // ================================================================
    //
    // Path Management
    //
    // ----------------------------------------------------------------

    /**
     * create a child context with an additional path segment
     *
     * Used by collection schemas to track nested locations.
     * The parent context's issues are shared with the child
     * so that all issues are collected in one place.
     */
    public function atPath(string|int $segment): self
    {
        $child = new self([...$this->path, $segment]);

        // share the same issues array by reference - child
        // issues are collected into the parent
        $child->issues = &$this->issues;

        return $child;
    }

    /**
     * return the current path
     *
     * @return list<string|int>
     */
    public function path(): array
    {
        return $this->path;
    }

    // ================================================================
    //
    // Issue Management
    //
    // ----------------------------------------------------------------

    /**
     * add a validation issue at the current path
     *
     * @param non-empty-string $message
     * @param array<string, int|string> $extra
     */
    public function addIssue(
        IssueCode $code,
        mixed $input,
        string $message,
        array $extra = [],
    ): void {
        $this->issues[] = new ValidationIssue(
            code: $code,
            input: $input,
            path: $this->path,
            message: $message,
            extra: $extra,
        );
    }

    /**
     * add a pre-built validation issue
     */
    public function addExistingIssue(ValidationIssue $issue): void
    {
        $this->issues[] = $issue;
    }

    /**
     * do we have any validation issues?
     */
    public function hasIssues(): bool
    {
        return count($this->issues) > 0;
    }

    /**
     * return all collected validation issues
     *
     * @return list<ValidationIssue>
     */
    public function issues(): array
    {
        return $this->issues;
    }
}
