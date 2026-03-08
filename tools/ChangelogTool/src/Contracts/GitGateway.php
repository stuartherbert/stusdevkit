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

namespace StusDevKit\ChangelogTool\Contracts;

use StusDevKit\ChangelogTool\Exceptions\CannotInferRepoUrlException;
use StusDevKit\ChangelogTool\Git\GitCommit;
use StusDevKit\ChangelogTool\Git\GitRemoteUrl;
use StusDevKit\ChangelogTool\Git\GitTag;

/**
 * Abstracts all git operations needed by the changelog
 * generator.
 *
 * This interface exists to allow testing without a real
 * git repository. The production implementation uses
 * `proc_open` to run git commands, while tests can use
 * a stub with canned data.
 */
interface GitGateway
{
    /**
     * Lists all semver-compatible tags in the repository.
     *
     * Tags that cannot be parsed as semantic versions are
     * silently skipped.
     *
     * @return list<GitTag> the tags found in the repository
     */
    public function listTags(): array;

    /**
     * Lists commits between two refs.
     *
     * When $fromRef is null, returns all commits up to
     * $toRef. When $toRef is null, returns all commits
     * from $fromRef to HEAD.
     *
     * @param string|null $fromRef the starting ref
     *     (exclusive), or null for the beginning of history
     * @param string|null $toRef the ending ref (inclusive),
     *     or null for HEAD
     * @return list<GitCommit> the commits in the range
     */
    public function listCommitsBetween(
        ?string $fromRef,
        ?string $toRef,
    ): array;

    /**
     * Infers the repository URL from the git remote
     * configuration.
     *
     * Determines the remote by inspecting the tracking
     * branch of the current branch.
     *
     * @return GitRemoteUrl the parsed remote URL
     * @throws CannotInferRepoUrlException if the URL
     *     cannot be determined.
     */
    public function inferRemoteUrl(): GitRemoteUrl;
}
