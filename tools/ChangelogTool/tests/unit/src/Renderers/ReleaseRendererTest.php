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
use StusDevKit\ChangelogTool\Renderers\CommitLineRenderer;
use StusDevKit\ChangelogTool\Renderers\ReleaseRenderer;

#[TestDox('ReleaseRenderer')]
class ReleaseRendererTest extends TestCase
{
    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    private function createConfig(): ChangelogConfig
    {
        return new ChangelogConfig([
            new TypeMapping(
                type: 'feat',
                section: 'Features',
            ),
            new TypeMapping(
                type: 'fix',
                section: 'Bug Fixes',
            ),
            new TypeMapping(
                type: 'chore',
                hidden: true,
            ),
        ]);
    }

    private function createRepoUrl(): GitRemoteUrl
    {
        return new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );
    }

    private function createRenderer(): ReleaseRenderer
    {
        return new ReleaseRenderer(
            commitLineRenderer: new CommitLineRenderer(),
            config: $this->createConfig(),
        );
    }

    // ================================================================
    //
    // renderRelease()
    //
    // ----------------------------------------------------------------

    #[TestDox('Returns empty string for no commits')]
    public function test_returns_empty_for_no_commits(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that renderRelease() returns
        // an empty string when given no commits

        // ----------------------------------------------------------------
        // setup your test

        $renderer = $this->createRenderer();

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderRelease(
            heading: '## v1.0.0 (2026-01-01)',
            commits: [],
            repoUrl: $this->createRepoUrl(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('', $result);
    }

    #[TestDox('Can render a release with simple commits')]
    public function test_can_render_simple_release(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that renderRelease() correctly
        // groups commits by type section and renders them

        // ----------------------------------------------------------------
        // setup your test

        $renderer = $this->createRenderer();
        $commits = [
            new ParsedCommit(
                type: 'feat',
                scope: null,
                description: 'add feature A',
                isBreaking: false,
                breakingDescription: null,
                hash: 'abc123def456',
                shortHash: 'abc123d',
                authorName: 'Stuart Herbert',
                authorEmail: 'stuart@example.com',
            ),
            new ParsedCommit(
                type: 'fix',
                scope: null,
                description: 'fix bug B',
                isBreaking: false,
                breakingDescription: null,
                hash: 'def456abc123',
                shortHash: 'def456a',
                authorName: 'Jane Doe',
                authorEmail: 'jane@example.com',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderRelease(
            heading: '## v1.0.0 (2026-01-01)',
            commits: $commits,
            repoUrl: $this->createRepoUrl(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            '## v1.0.0 (2026-01-01)',
            $result,
        );
        $this->assertStringContainsString('### Features', $result);
        $this->assertStringContainsString(
            'add feature A',
            $result,
        );
        $this->assertStringContainsString('### Bug Fixes', $result);
        $this->assertStringContainsString('fix bug B', $result);
    }

    #[TestDox('Renders breaking changes section at top')]
    public function test_renders_breaking_changes_at_top(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that breaking change commits
        // appear in a dedicated BREAKING CHANGES section
        // before other sections

        // ----------------------------------------------------------------
        // setup your test

        $renderer = $this->createRenderer();
        $commits = [
            new ParsedCommit(
                type: 'feat',
                scope: null,
                description: 'remove old API',
                isBreaking: true,
                breakingDescription: 'remove old API',
                hash: 'abc123def456',
                shortHash: 'abc123d',
                authorName: 'Stuart Herbert',
                authorEmail: 'stuart@example.com',
            ),
            new ParsedCommit(
                type: 'feat',
                scope: null,
                description: 'add new feature',
                isBreaking: false,
                breakingDescription: null,
                hash: 'def456abc123',
                shortHash: 'def456a',
                authorName: 'Jane Doe',
                authorEmail: 'jane@example.com',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderRelease(
            heading: '## v2.0.0 (2026-02-01)',
            commits: $commits,
            repoUrl: $this->createRepoUrl(),
        );

        // ----------------------------------------------------------------
        // test the results

        $breakingPos = strpos($result, '### BREAKING CHANGES');
        $featuresPos = strpos($result, '### Features');

        $this->assertNotFalse($breakingPos);
        $this->assertNotFalse($featuresPos);
        $this->assertLessThan(
            $featuresPos,
            $breakingPos,
            'BREAKING CHANGES should appear before Features',
        );
    }

    #[TestDox('Groups commits by scope under sub-headings')]
    public function test_groups_commits_by_scope(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that commits with non-ticket
        // scopes are grouped under #### scope sub-headings

        // ----------------------------------------------------------------
        // setup your test

        $renderer = $this->createRenderer();
        $commits = [
            new ParsedCommit(
                type: 'feat',
                scope: 'parser',
                description: 'add array support',
                isBreaking: false,
                breakingDescription: null,
                hash: 'abc123def456',
                shortHash: 'abc123d',
                authorName: 'Stuart Herbert',
                authorEmail: 'stuart@example.com',
            ),
            new ParsedCommit(
                type: 'feat',
                scope: 'lexer',
                description: 'handle unicode',
                isBreaking: false,
                breakingDescription: null,
                hash: 'def456abc123',
                shortHash: 'def456a',
                authorName: 'Jane Doe',
                authorEmail: 'jane@example.com',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderRelease(
            heading: '## v1.0.0 (2026-01-01)',
            commits: $commits,
            repoUrl: $this->createRepoUrl(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            '#### parser',
            $result,
        );
        $this->assertStringContainsString(
            '#### lexer',
            $result,
        );
    }

    #[TestDox('Omits hidden commit types')]
    public function test_omits_hidden_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that commits with hidden types
        // (e.g. "chore") do not appear in the output

        // ----------------------------------------------------------------
        // setup your test

        $renderer = $this->createRenderer();
        $commits = [
            new ParsedCommit(
                type: 'chore',
                scope: null,
                description: 'update deps',
                isBreaking: false,
                breakingDescription: null,
                hash: 'abc123def456',
                shortHash: 'abc123d',
                authorName: 'Stuart Herbert',
                authorEmail: 'stuart@example.com',
            ),
            new ParsedCommit(
                type: 'feat',
                scope: null,
                description: 'add feature',
                isBreaking: false,
                breakingDescription: null,
                hash: 'def456abc123',
                shortHash: 'def456a',
                authorName: 'Jane Doe',
                authorEmail: 'jane@example.com',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderRelease(
            heading: '## v1.0.0 (2026-01-01)',
            commits: $commits,
            repoUrl: $this->createRepoUrl(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringNotContainsString(
            'update deps',
            $result,
        );
        $this->assertStringContainsString(
            'add feature',
            $result,
        );
    }

    #[TestDox('Ticket scopes are rendered inline, not as sub-headings')]
    public function test_ticket_scopes_inline(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that commits with ticket scopes
        // (e.g. "#42") are rendered with the ticket inline
        // rather than under a separate scope heading

        // ----------------------------------------------------------------
        // setup your test

        $renderer = $this->createRenderer();
        $commits = [
            new ParsedCommit(
                type: 'fix',
                scope: '#42',
                description: 'handle null input',
                isBreaking: false,
                breakingDescription: null,
                hash: 'abc123def456',
                shortHash: 'abc123d',
                authorName: 'Stuart Herbert',
                authorEmail: 'stuart@example.com',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->renderRelease(
            heading: '## v1.0.0 (2026-01-01)',
            commits: $commits,
            repoUrl: $this->createRepoUrl(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringNotContainsString('#### #42', $result);
        $this->assertStringContainsString('**#42**', $result);
    }
}
