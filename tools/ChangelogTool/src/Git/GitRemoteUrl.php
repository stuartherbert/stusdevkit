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

use StusDevKit\ChangelogTool\Exceptions\CannotInferRepoUrlException;

/**
 * Represents a parsed git remote URL, providing the host,
 * owner, and repository name needed to build commit URLs
 * for the changelog.
 *
 * Supports both SSH and HTTPS remote URL formats:
 * - SSH: `git@github.com:org/repo.git`
 * - HTTPS: `https://github.com/org/repo.git`
 *
 * Usage:
 *
 *     $url = GitRemoteUrl::fromRemoteString(
 *         "git@github.com:acme/widgets.git"
 *     );
 *     $commitUrl = $url->buildCommitUrl("a2c5282");
 *     // "https://github.com/acme/widgets/commit/a2c5282"
 */
final readonly class GitRemoteUrl
{
    /**
     * @param string $host the remote host, e.g.
     *     "github.com" or "gitlab.com"
     * @param string $owner the repository owner or
     *     organisation, e.g. "acme"
     * @param string $repo the repository name, e.g.
     *     "widgets"
     */
    public function __construct(
        public string $host,
        public string $owner,
        public string $repo,
    ) {
    }

    /**
     * Parses a git remote URL string into a GitRemoteUrl.
     *
     * Handles both SSH format
     * (`git@github.com:org/repo.git`) and HTTPS format
     * (`https://github.com/org/repo.git`).
     *
     * @param string $raw the raw remote URL string from
     *     `git remote get-url`
     * @return self the parsed remote URL
     * @throws CannotInferRepoUrlException if the URL
     *     cannot be parsed.
     */
    public static function fromRemoteString(string $raw): self
    {
        $raw = trim($raw);

        // try SSH format: git@host:owner/repo.git
        $sshPattern = '/^git@(?P<host>[^:]+):(?P<owner>[^\/]+)\/(?P<repo>[^\/]+?)(?:\.git)?$/';
        if (preg_match($sshPattern, $raw, $matches)) {
            return new self(
                host: $matches['host'],
                owner: $matches['owner'],
                repo: $matches['repo'],
            );
        }

        // try HTTPS format: https://host/owner/repo.git
        $httpsPattern = '/^https?:\/\/(?P<host>[^\/]+)\/(?P<owner>[^\/]+)\/(?P<repo>[^\/]+?)(?:\.git)?$/';
        if (preg_match($httpsPattern, $raw, $matches)) {
            return new self(
                host: $matches['host'],
                owner: $matches['owner'],
                repo: $matches['repo'],
            );
        }

        throw new CannotInferRepoUrlException(
            "Cannot parse remote URL: {$raw}"
        );
    }

    /**
     * Builds a full URL to a specific commit.
     *
     * @param string $shortHash the abbreviated commit hash
     * @return string the full URL to the commit, e.g.
     *     "https://github.com/acme/widgets/commit/a2c5282"
     */
    public function buildCommitUrl(string $shortHash): string
    {
        return "https://{$this->host}/{$this->owner}/{$this->repo}/commit/{$shortHash}";
    }
}
