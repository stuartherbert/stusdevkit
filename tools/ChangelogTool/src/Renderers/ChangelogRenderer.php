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
 * Assembles the complete changelog document from the
 * preamble and individual release sections.
 *
 * Usage:
 *
 *     $renderer = new ChangelogRenderer(
 *         releaseRenderer: $releaseRenderer,
 *         config: $config,
 *     );
 *     $changelog = $renderer->renderChangelog(
 *         releases: $releases,
 *         repoUrl: $remoteUrl,
 *     );
 */
final class ChangelogRenderer
{
    public function __construct(
        private ReleaseRenderer $releaseRenderer,
        private ChangelogConfig $config,
    ) {
    }

    /**
     * Renders the complete changelog document.
     *
     * Prepends the preamble (if any), then renders each
     * release in order.
     *
     * @param list<array{heading: string, commits: list<ParsedCommit>}> $releases
     *     the releases to render, in display order (newest
     *     first)
     * @param GitRemoteUrl $repoUrl the repository URL for
     *     building commit links
     * @return string the complete changelog markdown
     */
    public function renderChangelog(
        array $releases,
        GitRemoteUrl $repoUrl,
    ): string {
        $parts = [];

        $preamble = $this->config->preamble();
        if ($preamble !== '') {
            $parts[] = $preamble;
        }

        foreach ($releases as $release) {
            $rendered = $this->releaseRenderer->renderRelease(
                heading: $release['heading'],
                commits: $release['commits'],
                repoUrl: $repoUrl,
            );
            if ($rendered !== '') {
                $parts[] = $rendered;
            }
        }

        return implode("\n", $parts);
    }
}
