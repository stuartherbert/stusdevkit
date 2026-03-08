<?php

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
//

declare(strict_types=1);

namespace StusDevKit\ChangelogTool\Tests\Unit\Renderers;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\Config\ChangelogConfig;
use StusDevKit\ChangelogTool\Config\TypeMapping;
use StusDevKit\ChangelogTool\Git\GitRemoteUrl;
use StusDevKit\ChangelogTool\Parsers\ParsedCommit;
use StusDevKit\ChangelogTool\Renderers\ChangelogRenderer;
use StusDevKit\ChangelogTool\Renderers\CommitLineRenderer;
use StusDevKit\ChangelogTool\Renderers\ReleaseRenderer;

#[TestDox('ChangelogRenderer')]
class ChangelogRendererTest extends TestCase
{
    // ================================================================
    //
    // renderChangelog()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can render a changelog with preamble and releases')]
    public function test_can_render_with_preamble_and_releases(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that renderChangelog() assembles
        // the preamble and release sections into a complete
        // changelog document

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([
            new TypeMapping(
                type: 'feat',
                section: 'Features',
            ),
        ]);
        $config->loadPreamble(
            __DIR__ . '/../../../fixtures/src/test-preamble.md',
        );

        $renderer = new ChangelogRenderer(
            releaseRenderer: new ReleaseRenderer(
                commitLineRenderer: new CommitLineRenderer(),
                config: $config,
            ),
            config: $config,
        );

        $repoUrl = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );

        $releases = [
            [
                'heading' => '## Upcoming Release',
                'commits' => [
                    new ParsedCommit(
                        type: 'feat',
                        scope: null,
                        description: 'new unreleased feature',
                        isBreaking: false,
                        breakingDescription: null,
                        hash: 'abc123def456',
                        shortHash: 'abc123d',
                        authorName: 'Stuart Herbert',
                        authorEmail: 'stuart@example.com',
                    ),
                ],
            ],
            [
                'heading' => '## v1.0.0 (2026-01-01)',
                'commits' => [
                    new ParsedCommit(
                        type: 'feat',
                        scope: null,
                        description: 'initial release',
                        isBreaking: false,
                        breakingDescription: null,
                        hash: 'def456abc123',
                        shortHash: 'def456a',
                        authorName: 'Stuart Herbert',
                        authorEmail: 'stuart@example.com',
                    ),
                ],
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderChangelog(
            releases: $releases,
            repoUrl: $repoUrl,
        );

        // ----------------------------------------------------------------
        // test the results

        // preamble should appear first
        $this->assertStringStartsWith('# Changelog', $result);

        // both releases should be present
        $this->assertStringContainsString(
            '## Upcoming Release',
            $result,
        );
        $this->assertStringContainsString(
            '## v1.0.0 (2026-01-01)',
            $result,
        );

        // upcoming should appear before v1.0.0
        $upcomingPos = strpos($result, '## Upcoming Release');
        $v100Pos = strpos($result, '## v1.0.0');
        $this->assertLessThan($v100Pos, $upcomingPos);
    }

    #[TestDox('Can render without preamble')]
    public function test_can_render_without_preamble(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that renderChangelog() works
        // correctly when no preamble is configured

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([
            new TypeMapping(
                type: 'feat',
                section: 'Features',
            ),
        ]);

        $renderer = new ChangelogRenderer(
            releaseRenderer: new ReleaseRenderer(
                commitLineRenderer: new CommitLineRenderer(),
                config: $config,
            ),
            config: $config,
        );

        $repoUrl = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );

        $releases = [
            [
                'heading' => '## v1.0.0 (2026-01-01)',
                'commits' => [
                    new ParsedCommit(
                        type: 'feat',
                        scope: null,
                        description: 'initial release',
                        isBreaking: false,
                        breakingDescription: null,
                        hash: 'def456abc123',
                        shortHash: 'def456a',
                        authorName: 'Stuart Herbert',
                        authorEmail: 'stuart@example.com',
                    ),
                ],
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderChangelog(
            releases: $releases,
            repoUrl: $repoUrl,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringStartsWith(
            '## v1.0.0 (2026-01-01)',
            $result,
        );
    }
}
