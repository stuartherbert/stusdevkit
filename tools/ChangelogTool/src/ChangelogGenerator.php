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

namespace StusDevKit\ChangelogTool;

use StusDevKit\ChangelogTool\Contracts\GitGateway;
use StusDevKit\ChangelogTool\Git\GitCommit;
use StusDevKit\ChangelogTool\Parsers\ConventionalCommitParser;
use StusDevKit\ChangelogTool\Parsers\ParsedCommit;
use StusDevKit\ChangelogTool\Parsers\SemVerSorter;
use StusDevKit\ChangelogTool\Renderers\ChangelogRenderer;

/**
 * Top-level orchestrator that ties together git operations,
 * commit parsing, and changelog rendering to produce a
 * complete CHANGELOG.md.
 *
 * Usage:
 *
 *     $generator = new ChangelogGenerator(
 *         git: $gitGateway,
 *         config: $config,
 *         renderer: $changelogRenderer,
 *         parser: new ConventionalCommitParser(),
 *     );
 *     $changelog = $generator->generateChangelog();
 */
final class ChangelogGenerator
{
    public function __construct(
        private GitGateway $git,
        private ChangelogRenderer $renderer,
        private ConventionalCommitParser $parser,
    ) {
    }

    /**
     * Generates the complete changelog content.
     *
     * Algorithm:
     * 1. Infer the remote URL for commit links
     * 2. List and sort all tags by semver (descending)
     * 3. Build release boundaries between tags
     * 4. For each boundary, fetch and parse commits
     * 5. If unreleased commits exist, create an
     *    "Upcoming Release" section
     * 6. Render all releases into a complete document
     *
     * @return string the complete changelog markdown
     */
    public function generateChangelog(): string
    {
        $repoUrl = $this->git->inferRemoteUrl();

        $tags = $this->git->listTags();
        $tags = SemVerSorter::sortTagsDescending($tags);

        $releases = [];

        // check for unreleased commits (HEAD to latest tag)
        if (count($tags) > 0) {
            $unreleasedCommits = $this->git->listCommitsBetween(
                fromRef: $tags[0]->name,
                toRef: null,
            );

            if (count($unreleasedCommits) > 0) {
                $releases[] = [
                    'heading' => '## Upcoming Release',
                    'commits' => $this->parseCommits(
                        $unreleasedCommits,
                    ),
                ];
            }
        }

        // build release sections for each tag
        for ($i = 0; $i < count($tags); $i++) {
            $tag = $tags[$i];
            $heading = "## {$tag->name} ({$tag->date})";

            // determine the "from" ref (the next older tag)
            $fromRef = isset($tags[$i + 1])
                ? $tags[$i + 1]->name
                : null;

            $commits = $this->git->listCommitsBetween(
                fromRef: $fromRef,
                toRef: $tag->name,
            );

            $releases[] = [
                'heading' => $heading,
                'commits' => $this->parseCommits($commits),
            ];
        }

        // if there are no tags at all, all commits go
        // under "Upcoming Release"
        if (count($tags) === 0) {
            $allCommits = $this->git->listCommitsBetween(
                fromRef: null,
                toRef: null,
            );

            if (count($allCommits) > 0) {
                $releases[] = [
                    'heading' => '## Upcoming Release',
                    'commits' => $this->parseCommits(
                        $allCommits,
                    ),
                ];
            }
        }

        return $this->renderer->renderChangelog(
            releases: $releases,
            repoUrl: $repoUrl,
        );
    }

    /**
     * Parses a list of raw git commits into parsed commits.
     *
     * @param list<GitCommit> $commits the raw commits
     * @return list<ParsedCommit> the parsed commits
     */
    private function parseCommits(array $commits): array
    {
        $parsed = [];
        foreach ($commits as $commit) {
            $parsed[] = $this->parser->parseCommit($commit);
        }
        return $parsed;
    }
}
