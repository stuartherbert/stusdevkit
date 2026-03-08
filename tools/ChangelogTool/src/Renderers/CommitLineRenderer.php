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

namespace StusDevKit\ChangelogTool\Renderers;

use StusDevKit\ChangelogTool\Git\GitRemoteUrl;
use StusDevKit\ChangelogTool\Parsers\ParsedCommit;

/**
 * Renders a single commit as a markdown bullet line for
 * the changelog.
 *
 * Output format:
 * - description ([hash](url)) — Author Name <email>
 *
 * When the commit has a ticket scope, the ticket ID is
 * displayed inline:
 * - **#42** description ([hash](url)) — Author Name <email>
 *
 * Usage:
 *
 *     $renderer = new CommitLineRenderer();
 *     $line = $renderer->formatCommit(
 *         commit: $parsedCommit,
 *         repoUrl: $remoteUrl,
 *     );
 */
final class CommitLineRenderer
{
    /**
     * Renders a parsed commit as a markdown bullet line.
     *
     * @param ParsedCommit $commit the parsed commit to
     *     render
     * @param GitRemoteUrl $repoUrl the repository URL for
     *     building commit links
     * @return string the formatted markdown line
     */
    public function formatCommit(
        ParsedCommit $commit,
        GitRemoteUrl $repoUrl,
    ): string {
        $commitUrl = $repoUrl->buildCommitUrl($commit->shortHash);
        $hashLink = "[{$commit->shortHash}]({$commitUrl})";
        $author = "{$commit->authorName} <{$commit->authorEmail}>";

        $description = $commit->description;

        // prepend ticket ID if scope is a ticket
        if ($commit->isTicketScope()) {
            $description = "**{$commit->scope}** {$description}";
        }

        return "- {$description} ({$hashLink}) — {$author}";
    }
}
