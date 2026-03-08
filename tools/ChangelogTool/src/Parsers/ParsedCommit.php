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

namespace StusDevKit\ChangelogTool\Parsers;

/**
 * Represents a commit that has been parsed according to
 * the Conventional Commits specification.
 *
 * Combines the parsed commit message fields (type, scope,
 * description, breaking change info) with git metadata
 * (hash, author).
 *
 * Usage:
 *
 *     $commit = new ParsedCommit(
 *         type: "feat",
 *         scope: "parser",
 *         description: "add array support",
 *         isBreaking: false,
 *         breakingDescription: null,
 *         hash: "a2c5282abc123...",
 *         shortHash: "a2c5282",
 *         authorName: "Stuart Herbert",
 *         authorEmail: "stuart@example.com",
 *     );
 *
 *     if ($commit->isTicketScope()) {
 *         // scope is a ticket ID like "#42" or "PROJ-123"
 *     }
 */
final readonly class ParsedCommit
{
    /**
     * @param string $type the conventional commit type,
     *     e.g. "feat", "fix", "chore"
     * @param string|null $scope the optional scope, e.g.
     *     "parser", "#42", "PROJ-123"
     * @param string $description the first-line description
     *     of the commit
     * @param bool $isBreaking whether this is a breaking
     *     change
     * @param string|null $breakingDescription an optional
     *     description of the breaking change from the
     *     BREAKING CHANGE footer
     * @param string $hash the full commit hash
     * @param string $shortHash the abbreviated commit hash
     * @param string $authorName the commit author's name
     * @param string $authorEmail the commit author's email
     */
    public function __construct(
        public string $type,
        public ?string $scope,
        public string $description,
        public bool $isBreaking,
        public ?string $breakingDescription,
        public string $hash,
        public string $shortHash,
        public string $authorName,
        public string $authorEmail,
    ) {
    }

    /**
     * Determines whether the scope looks like a ticket ID
     * rather than a code module name.
     *
     * Recognises GitHub issue references (e.g. "#42") and
     * Jira-style ticket IDs (e.g. "PROJ-123").
     *
     * When a scope is a ticket ID, it should be displayed
     * inline in the commit line rather than used as a
     * grouping sub-heading.
     *
     * @return bool true if the scope matches a ticket ID
     *     pattern
     */
    public function isTicketScope(): bool
    {
        if ($this->scope === null) {
            return false;
        }

        // GitHub issue reference: #42
        if (preg_match('/^#\d+$/', $this->scope)) {
            return true;
        }

        // Jira-style ticket ID: PROJ-123
        if (preg_match('/^[A-Z]{2,}-\d+$/', $this->scope)) {
            return true;
        }

        return false;
    }
}
