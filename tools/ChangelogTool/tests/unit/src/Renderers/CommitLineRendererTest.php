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
use StusDevKit\ChangelogTool\Git\GitRemoteUrl;
use StusDevKit\ChangelogTool\Parsers\ParsedCommit;
use StusDevKit\ChangelogTool\Renderers\CommitLineRenderer;

#[TestDox('CommitLineRenderer')]
class CommitLineRendererTest extends TestCase
{
    // ================================================================
    //
    // formatCommit()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can format a commit without scope')]
    public function test_can_format_without_scope(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatCommit() produces
        // the correct markdown for a commit without a scope

        // ----------------------------------------------------------------
        // setup your test

        $renderer = new CommitLineRenderer();
        $repoUrl = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );
        $commit = new ParsedCommit(
            type: 'feat',
            scope: null,
            description: 'add new feature',
            isBreaking: false,
            breakingDescription: null,
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->formatCommit(
            commit: $commit,
            repoUrl: $repoUrl,
        );

        // ----------------------------------------------------------------
        // test the results

        $expected = '- add new feature'
            . ' ([abc123d]'
            . '(https://github.com/acme/widgets/commit/abc123d))'
            . ' — Stuart Herbert <stuart@example.com>';
        $this->assertSame($expected, $result);
    }

    #[TestDox('Can format a commit with module scope')]
    public function test_can_format_with_module_scope(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a commit with a non-ticket
        // scope does not include the scope in the line
        // (scope grouping is handled by ReleaseRenderer)

        // ----------------------------------------------------------------
        // setup your test

        $renderer = new CommitLineRenderer();
        $repoUrl = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );
        $commit = new ParsedCommit(
            type: 'feat',
            scope: 'parser',
            description: 'add array support',
            isBreaking: false,
            breakingDescription: null,
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->formatCommit(
            commit: $commit,
            repoUrl: $repoUrl,
        );

        // ----------------------------------------------------------------
        // test the results

        $expected = '- add array support'
            . ' ([abc123d]'
            . '(https://github.com/acme/widgets/commit/abc123d))'
            . ' — Stuart Herbert <stuart@example.com>';
        $this->assertSame($expected, $result);
    }

    #[TestDox('Can format a commit with GitHub ticket scope')]
    public function test_can_format_with_github_ticket_scope(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a commit with a GitHub
        // issue scope includes the ticket ID inline

        // ----------------------------------------------------------------
        // setup your test

        $renderer = new CommitLineRenderer();
        $repoUrl = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );
        $commit = new ParsedCommit(
            type: 'fix',
            scope: '#42',
            description: 'handle null input',
            isBreaking: false,
            breakingDescription: null,
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->formatCommit(
            commit: $commit,
            repoUrl: $repoUrl,
        );

        // ----------------------------------------------------------------
        // test the results

        $expected = '- **#42** handle null input'
            . ' ([abc123d]'
            . '(https://github.com/acme/widgets/commit/abc123d))'
            . ' — Stuart Herbert <stuart@example.com>';
        $this->assertSame($expected, $result);
    }

    #[TestDox('Can format a commit with Jira ticket scope')]
    public function test_can_format_with_jira_ticket_scope(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a commit with a Jira
        // ticket scope includes the ticket ID inline

        // ----------------------------------------------------------------
        // setup your test

        $renderer = new CommitLineRenderer();
        $repoUrl = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );
        $commit = new ParsedCommit(
            type: 'fix',
            scope: 'PROJ-123',
            description: 'fix login bug',
            isBreaking: false,
            breakingDescription: null,
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Jane Doe',
            authorEmail: 'jane@example.com',
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $renderer->formatCommit(
            commit: $commit,
            repoUrl: $repoUrl,
        );

        // ----------------------------------------------------------------
        // test the results

        $expected = '- **PROJ-123** fix login bug'
            . ' ([abc123d]'
            . '(https://github.com/acme/widgets/commit/abc123d))'
            . ' — Jane Doe <jane@example.com>';
        $this->assertSame($expected, $result);
    }
}
