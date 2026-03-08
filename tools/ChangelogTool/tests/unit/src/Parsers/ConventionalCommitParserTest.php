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

namespace StusDevKit\ChangelogTool\Tests\Unit\Parsers;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\Git\GitCommit;
use StusDevKit\ChangelogTool\Parsers\ConventionalCommitParser;

#[TestDox('ConventionalCommitParser')]
class ConventionalCommitParserTest extends TestCase
{
    // ================================================================
    //
    // parseCommit()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can parse a simple feat commit')]
    public function test_can_parse_simple_feat(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser correctly
        // extracts the type and description from a simple
        // feat commit message

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: 'feat: add new feature',
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('feat', $parsed->type);
        $this->assertNull($parsed->scope);
        $this->assertSame('add new feature', $parsed->description);
        $this->assertFalse($parsed->isBreaking);
        $this->assertNull($parsed->breakingDescription);
        $this->assertSame('abc123def456', $parsed->hash);
        $this->assertSame('abc123d', $parsed->shortHash);
        $this->assertSame('Stuart Herbert', $parsed->authorName);
        $this->assertSame('stuart@example.com', $parsed->authorEmail);
    }

    #[TestDox('Can parse a commit with scope')]
    public function test_can_parse_with_scope(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser extracts the
        // scope from a commit message with parenthesised
        // scope

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: 'fix(parser): handle null input',
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('fix', $parsed->type);
        $this->assertSame('parser', $parsed->scope);
        $this->assertSame('handle null input', $parsed->description);
        $this->assertFalse($parsed->isBreaking);
    }

    #[TestDox('Can parse a breaking change with ! indicator')]
    public function test_can_parse_breaking_with_bang(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser detects a
        // breaking change indicated by ! after the type

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: 'feat!: remove deprecated API',
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('feat', $parsed->type);
        $this->assertTrue($parsed->isBreaking);
        $this->assertSame(
            'remove deprecated API',
            $parsed->breakingDescription,
        );
    }

    #[TestDox('Can parse a breaking change with footer')]
    public function test_can_parse_breaking_with_footer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser detects a
        // breaking change from a BREAKING CHANGE footer
        // in the commit body

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: "feat: change return type\n\n"
                . "BREAKING CHANGE: method now returns int",
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('feat', $parsed->type);
        $this->assertTrue($parsed->isBreaking);
        $this->assertSame(
            'method now returns int',
            $parsed->breakingDescription,
        );
    }

    #[TestDox('Can parse a breaking change with hyphenated footer')]
    public function test_can_parse_breaking_with_hyphenated_footer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser detects a
        // breaking change from a BREAKING-CHANGE footer

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: "feat: update API\n\n"
                . "BREAKING-CHANGE: removed old endpoint",
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($parsed->isBreaking);
        $this->assertSame(
            'removed old endpoint',
            $parsed->breakingDescription,
        );
    }

    #[TestDox('Can parse a scoped breaking change with !')]
    public function test_can_parse_scoped_breaking_with_bang(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser correctly
        // handles a commit with both scope and ! breaking
        // indicator

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: 'feat(api)!: remove v1 endpoints',
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('feat', $parsed->type);
        $this->assertSame('api', $parsed->scope);
        $this->assertTrue($parsed->isBreaking);
        $this->assertSame(
            'remove v1 endpoints',
            $parsed->breakingDescription,
        );
    }

    #[TestDox('Assigns type "other" to non-conventional commits')]
    public function test_assigns_other_to_non_conventional(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that commits that do not follow
        // the conventional commit format are assigned the
        // type "other"

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: 'just a regular commit message',
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('other', $parsed->type);
        $this->assertNull($parsed->scope);
        $this->assertSame(
            'just a regular commit message',
            $parsed->description,
        );
        $this->assertFalse($parsed->isBreaking);
    }

    #[TestDox('Normalises type to lowercase')]
    public function test_normalises_type_to_lowercase(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the parser normalises the
        // commit type to lowercase

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: 'FEAT: add feature',
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('feat', $parsed->type);
    }

    #[TestDox('Uses only the first line as description')]
    public function test_uses_only_first_line(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that only the first line of
        // the commit message is used as the description,
        // even when a body is present

        // ----------------------------------------------------------------
        // setup your test

        $parser = new ConventionalCommitParser();
        $commit = new GitCommit(
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
            message: "feat: add feature\n\n"
                . "This is the body with more details.",
        );

        // ----------------------------------------------------------------
        // perform the change

        $parsed = $parser->parseCommit($commit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('add feature', $parsed->description);
    }
}
