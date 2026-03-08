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

namespace StusDevKit\ChangelogTool\Tests\Unit\Git;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\Exceptions\CannotInferRepoUrlException;
use StusDevKit\ChangelogTool\Git\GitRemoteUrl;

#[TestDox('GitRemoteUrl')]
class GitRemoteUrlTest extends TestCase
{
    // ================================================================
    //
    // fromRemoteString()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, string, string, string}>
     */
    public static function provideValidRemoteUrls(): array
    {
        return [
            'SSH with .git suffix' => [
                'git@github.com:acme/widgets.git',
                'github.com',
                'acme',
                'widgets',
            ],
            'SSH without .git suffix' => [
                'git@github.com:acme/widgets',
                'github.com',
                'acme',
                'widgets',
            ],
            'HTTPS with .git suffix' => [
                'https://github.com/acme/widgets.git',
                'github.com',
                'acme',
                'widgets',
            ],
            'HTTPS without .git suffix' => [
                'https://github.com/acme/widgets',
                'github.com',
                'acme',
                'widgets',
            ],
            'GitLab SSH' => [
                'git@gitlab.com:org/project.git',
                'gitlab.com',
                'org',
                'project',
            ],
            'GitLab HTTPS' => [
                'https://gitlab.com/org/project.git',
                'gitlab.com',
                'org',
                'project',
            ],
            'HTTP URL' => [
                'http://github.com/acme/widgets.git',
                'github.com',
                'acme',
                'widgets',
            ],
            'URL with trailing whitespace' => [
                "git@github.com:acme/widgets.git\n",
                'github.com',
                'acme',
                'widgets',
            ],
        ];
    }

    #[TestDox('Can parse valid remote URLs')]
    #[DataProvider('provideValidRemoteUrls')]
    public function test_can_parse_valid_remote_urls(
        string $raw,
        string $expectedHost,
        string $expectedOwner,
        string $expectedRepo,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromRemoteString() can
        // parse both SSH and HTTPS remote URL formats

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $url = GitRemoteUrl::fromRemoteString($raw);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedHost, $url->host);
        $this->assertSame($expectedOwner, $url->owner);
        $this->assertSame($expectedRepo, $url->repo);
    }

    #[TestDox('Throws on unparseable remote URL')]
    public function test_throws_on_unparseable_url(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromRemoteString() throws
        // CannotInferRepoUrlException for URLs that cannot
        // be parsed

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(CannotInferRepoUrlException::class);
        GitRemoteUrl::fromRemoteString('not-a-valid-url');
    }

    // ================================================================
    //
    // buildCommitUrl()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can build a commit URL')]
    public function test_can_build_commit_url(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that buildCommitUrl() produces
        // the correct URL for a given short hash

        // ----------------------------------------------------------------
        // setup your test

        $url = new GitRemoteUrl(
            host: 'github.com',
            owner: 'acme',
            repo: 'widgets',
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $url->buildCommitUrl('a2c5282');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://github.com/acme/widgets/commit/a2c5282',
            $result,
        );
    }
}
