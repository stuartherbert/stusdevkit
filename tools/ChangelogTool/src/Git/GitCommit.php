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

namespace StusDevKit\ChangelogTool\Git;

/**
 * Represents a single git commit with its metadata.
 *
 * This is a raw git commit before parsing by the
 * ConventionalCommitParser. It holds the data retrieved
 * from `git log`.
 *
 * Usage:
 *
 *     $commit = new GitCommit(
 *         hash: "a2c5282abc123def456...",
 *         shortHash: "a2c5282",
 *         authorName: "Stuart Herbert",
 *         authorEmail: "stuart@example.com",
 *         message: "feat(parser): add array support",
 *     );
 */
final readonly class GitCommit
{
    /**
     * @param string $hash the full commit hash
     * @param string $shortHash the abbreviated commit hash
     *     (7 characters)
     * @param string $authorName the commit author's name
     * @param string $authorEmail the commit author's email
     * @param string $message the full commit message
     *     including body and footers
     */
    public function __construct(
        public string $hash,
        public string $shortHash,
        public string $authorName,
        public string $authorEmail,
        public string $message,
    ) {
    }
}
