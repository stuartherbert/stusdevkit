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

namespace StusDevKit\ChangelogTool\Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\ChangelogGenerator;
use StusDevKit\ChangelogTool\Config\ChangelogConfig;
use StusDevKit\ChangelogTool\Config\TypeMapping;
use StusDevKit\ChangelogTool\Git\GitCommit;
use StusDevKit\ChangelogTool\Git\GitRemoteUrl;
use StusDevKit\ChangelogTool\Git\GitTag;
use StusDevKit\ChangelogTool\Parsers\ConventionalCommitParser;
use StusDevKit\ChangelogTool\Parsers\SemVer;
use StusDevKit\ChangelogTool\Renderers\ChangelogRenderer;
use StusDevKit\ChangelogTool\Renderers\CommitLineRenderer;
use StusDevKit\ChangelogTool\Renderers\ReleaseRenderer;
use StusDevKit\ChangelogTool\Tests\Fixtures\StubGitGateway;

#[TestDox('ChangelogGenerator')]
class ChangelogGeneratorTest extends TestCase
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

    private function createGenerator(
        StubGitGateway $git,
    ): ChangelogGenerator {
        $config = $this->createConfig();
        $commitLineRenderer = new CommitLineRenderer();
        $releaseRenderer = new ReleaseRenderer(
            commitLineRenderer: $commitLineRenderer,
            config: $config,
        );
        $changelogRenderer = new ChangelogRenderer(
            releaseRenderer: $releaseRenderer,
            config: $config,
        );

        return new ChangelogGenerator(
            git: $git,
            renderer: $changelogRenderer,
            parser: new ConventionalCommitParser(),
        );
    }

    // ================================================================
    //
    // generateChangelog()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can generate changelog with tags and unreleased commits')]
    public function test_can_generate_with_tags_and_unreleased(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the generator creates an
        // "Upcoming Release" section for commits after the
        // latest tag and a versioned section for each tag

        // ----------------------------------------------------------------
        // setup your test

        $git = new StubGitGateway(
            tags: [
                new GitTag(
                    name: 'v1.0.0',
                    date: '2026-01-01',
                    semver: new SemVer(
                        major: 1,
                        minor: 0,
                        patch: 0,
                    ),
                ),
            ],
            commitsByRange: [
                'v1.0.0..null' => [
                    new GitCommit(
                        hash: 'aaa111bbb222',
                        shortHash: 'aaa111b',
                        authorName: 'Stuart Herbert',
                        authorEmail: 'stuart@example.com',
                        message: 'feat: unreleased feature',
                    ),
                ],
                'null..v1.0.0' => [
                    new GitCommit(
                        hash: 'ccc333ddd444',
                        shortHash: 'ccc333d',
                        authorName: 'Jane Doe',
                        authorEmail: 'jane@example.com',
                        message: 'feat: initial feature',
                    ),
                ],
            ],
            remoteUrl: $this->createRepoUrl(),
        );

        $generator = $this->createGenerator($git);

        // ----------------------------------------------------------------
        // perform the change

        $result = $generator->generateChangelog();

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            '## Upcoming Release',
            $result,
        );
        $this->assertStringContainsString(
            'unreleased feature',
            $result,
        );
        $this->assertStringContainsString(
            '## v1.0.0 (2026-01-01)',
            $result,
        );
        $this->assertStringContainsString(
            'initial feature',
            $result,
        );
    }

    #[TestDox('Omits Upcoming Release when no unreleased commits')]
    public function test_omits_upcoming_when_no_unreleased(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the "Upcoming Release"
        // section is omitted when there are no commits
        // after the latest tag

        // ----------------------------------------------------------------
        // setup your test

        $git = new StubGitGateway(
            tags: [
                new GitTag(
                    name: 'v1.0.0',
                    date: '2026-01-01',
                    semver: new SemVer(
                        major: 1,
                        minor: 0,
                        patch: 0,
                    ),
                ),
            ],
            commitsByRange: [
                'v1.0.0..null' => [],
                'null..v1.0.0' => [
                    new GitCommit(
                        hash: 'ccc333ddd444',
                        shortHash: 'ccc333d',
                        authorName: 'Jane Doe',
                        authorEmail: 'jane@example.com',
                        message: 'feat: initial feature',
                    ),
                ],
            ],
            remoteUrl: $this->createRepoUrl(),
        );

        $generator = $this->createGenerator($git);

        // ----------------------------------------------------------------
        // perform the change

        $result = $generator->generateChangelog();

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringNotContainsString(
            '## Upcoming Release',
            $result,
        );
        $this->assertStringContainsString(
            '## v1.0.0 (2026-01-01)',
            $result,
        );
    }

    #[TestDox('All commits go under Upcoming Release when no tags')]
    public function test_all_commits_upcoming_when_no_tags(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when there are no tags at
        // all, all commits are placed under the "Upcoming
        // Release" heading

        // ----------------------------------------------------------------
        // setup your test

        $git = new StubGitGateway(
            tags: [],
            commitsByRange: [
                'null..null' => [
                    new GitCommit(
                        hash: 'aaa111bbb222',
                        shortHash: 'aaa111b',
                        authorName: 'Stuart Herbert',
                        authorEmail: 'stuart@example.com',
                        message: 'feat: first commit',
                    ),
                ],
            ],
            remoteUrl: $this->createRepoUrl(),
        );

        $generator = $this->createGenerator($git);

        // ----------------------------------------------------------------
        // perform the change

        $result = $generator->generateChangelog();

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            '## Upcoming Release',
            $result,
        );
        $this->assertStringContainsString(
            'first commit',
            $result,
        );
    }

    #[TestDox('Sorts tags in reverse semver order')]
    public function test_sorts_tags_in_reverse_semver_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the generator sorts tags
        // so that higher versions appear first in the
        // changelog, regardless of input order

        // ----------------------------------------------------------------
        // setup your test

        $git = new StubGitGateway(
            tags: [
                new GitTag(
                    name: 'v1.0.0',
                    date: '2026-01-01',
                    semver: new SemVer(
                        major: 1,
                        minor: 0,
                        patch: 0,
                    ),
                ),
                new GitTag(
                    name: 'v2.0.0',
                    date: '2026-02-01',
                    semver: new SemVer(
                        major: 2,
                        minor: 0,
                        patch: 0,
                    ),
                ),
            ],
            commitsByRange: [
                'v2.0.0..null' => [],
                'v1.0.0..v2.0.0' => [
                    new GitCommit(
                        hash: 'aaa111bbb222',
                        shortHash: 'aaa111b',
                        authorName: 'Stuart Herbert',
                        authorEmail: 'stuart@example.com',
                        message: 'feat: v2 feature',
                    ),
                ],
                'null..v1.0.0' => [
                    new GitCommit(
                        hash: 'ccc333ddd444',
                        shortHash: 'ccc333d',
                        authorName: 'Jane Doe',
                        authorEmail: 'jane@example.com',
                        message: 'feat: v1 feature',
                    ),
                ],
            ],
            remoteUrl: $this->createRepoUrl(),
        );

        $generator = $this->createGenerator($git);

        // ----------------------------------------------------------------
        // perform the change

        $result = $generator->generateChangelog();

        // ----------------------------------------------------------------
        // test the results

        $v2Pos = strpos($result, '## v2.0.0');
        $v1Pos = strpos($result, '## v1.0.0');

        $this->assertNotFalse($v2Pos);
        $this->assertNotFalse($v1Pos);
        $this->assertLessThan(
            $v1Pos,
            $v2Pos,
            'v2.0.0 should appear before v1.0.0',
        );
    }
}
