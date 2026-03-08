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

use StusDevKit\ChangelogTool\Config\ChangelogConfig;
use StusDevKit\ChangelogTool\Git\GitRemoteUrl;
use StusDevKit\ChangelogTool\Parsers\ParsedCommit;

/**
 * Renders a single release section of the changelog.
 *
 * Handles grouping commits by type and scope, rendering
 * breaking changes in a dedicated section at the top, and
 * ordering sections according to the configuration.
 *
 * Usage:
 *
 *     $renderer = new ReleaseRenderer(
 *         commitLineRenderer: new CommitLineRenderer(),
 *         config: $changelogConfig,
 *     );
 *     $output = $renderer->renderRelease(
 *         heading: "## v1.2.0 (2026-03-01)",
 *         commits: $parsedCommits,
 *         repoUrl: $remoteUrl,
 *     );
 */
final class ReleaseRenderer
{
    public function __construct(
        private CommitLineRenderer $commitLineRenderer,
        private ChangelogConfig $config,
    ) {
    }

    /**
     * Renders a complete release section including heading,
     * breaking changes, and type/scope-grouped commits.
     *
     * @param string $heading the release heading, e.g.
     *     "## v1.2.0 (2026-03-01)" or "## Upcoming Release"
     * @param list<ParsedCommit> $commits the parsed commits
     *     for this release
     * @param GitRemoteUrl $repoUrl the repository URL for
     *     building commit links
     * @return string the rendered release section
     */
    public function renderRelease(
        string $heading,
        array $commits,
        GitRemoteUrl $repoUrl,
    ): string {
        if (count($commits) === 0) {
            return '';
        }

        $lines = [$heading, ''];

        // collect breaking changes
        $breakingCommits = $this->collectBreakingChanges($commits);
        if (count($breakingCommits) > 0) {
            $lines[] = '### BREAKING CHANGES';
            $lines[] = '';
            foreach ($breakingCommits as $commit) {
                $lines[] = $this->commitLineRenderer->formatCommit(
                    commit: $commit,
                    repoUrl: $repoUrl,
                );
            }
            $lines[] = '';
        }

        // group commits by section, then by scope
        $sectionOrder = $this->config->sectionOrder();
        $grouped = $this->groupCommitsBySectionAndScope(
            commits: $commits,
        );

        foreach ($sectionOrder as $section) {
            if (! isset($grouped[$section])) {
                continue;
            }

            $lines[] = "### {$section}";
            $lines[] = '';

            $scopeGroups = $grouped[$section];

            // render unscoped commits first
            if (isset($scopeGroups[''])) {
                foreach ($scopeGroups[''] as $commit) {
                    $lines[] = $this->commitLineRenderer->formatCommit(
                        commit: $commit,
                        repoUrl: $repoUrl,
                    );
                }
                unset($scopeGroups['']);
            }

            // render scoped commits under #### headings
            foreach ($scopeGroups as $scope => $scopeCommits) {
                $lines[] = '';
                $lines[] = "#### {$scope}";
                $lines[] = '';
                foreach ($scopeCommits as $commit) {
                    $lines[] = $this->commitLineRenderer->formatCommit(
                        commit: $commit,
                        repoUrl: $repoUrl,
                    );
                }
            }

            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /**
     * Collects all breaking change commits from the list.
     *
     * @param list<ParsedCommit> $commits the commits to
     *     search
     * @return list<ParsedCommit> the breaking change commits
     */
    private function collectBreakingChanges(
        array $commits,
    ): array {
        $breaking = [];
        foreach ($commits as $commit) {
            if ($commit->isBreaking) {
                $breaking[] = $commit;
            }
        }
        return $breaking;
    }

    /**
     * Groups commits by their section name and then by
     * scope.
     *
     * Hidden types and unknown types are excluded. Ticket
     * scopes are treated as unscoped (their ticket ID is
     * rendered inline by the CommitLineRenderer instead).
     *
     * @param list<ParsedCommit> $commits the commits to
     *     group
     * @return array<string, array<string, list<ParsedCommit>>>
     *     section -> scope -> commits
     */
    private function groupCommitsBySectionAndScope(
        array $commits,
    ): array {
        /** @var array<string, array<string, list<ParsedCommit>>> $grouped */
        $grouped = [];

        foreach ($commits as $commit) {
            $section = $this->config->sectionForType($commit->type);
            if ($section === null) {
                continue;
            }

            // ticket scopes are rendered inline, so they
            // go into the unscoped group
            $scope = '';
            if ($commit->scope !== null && ! $commit->isTicketScope()) {
                $scope = $commit->scope;
            }

            $grouped[$section][$scope][] = $commit;
        }

        return $grouped;
    }
}
