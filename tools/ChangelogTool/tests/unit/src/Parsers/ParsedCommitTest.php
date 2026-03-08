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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\Parsers\ParsedCommit;

#[TestDox('ParsedCommit')]
class ParsedCommitTest extends TestCase
{
    // ================================================================
    //
    // isTicketScope()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string|null, bool}>
     */
    public static function provideScopes(): array
    {
        return [
            'null scope' => [null, false],
            'module scope' => ['parser', false],
            'GitHub issue #42' => ['#42', true],
            'GitHub issue #1' => ['#1', true],
            'Jira ticket PROJ-123' => ['PROJ-123', true],
            'Jira ticket AB-1' => ['AB-1', true],
            'lowercase not Jira' => ['proj-123', false],
            'single letter not Jira' => ['P-123', false],
            'hash without number' => ['#abc', false],
        ];
    }

    #[TestDox('Correctly identifies ticket scopes')]
    #[DataProvider('provideScopes')]
    public function test_is_ticket_scope(
        ?string $scope,
        bool $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that isTicketScope() correctly
        // distinguishes between ticket IDs (GitHub issues,
        // Jira tickets) and regular module scopes

        // ----------------------------------------------------------------
        // setup your test

        $commit = new ParsedCommit(
            type: 'feat',
            scope: $scope,
            description: 'test description',
            isBreaking: false,
            breakingDescription: null,
            hash: 'abc123def456',
            shortHash: 'abc123d',
            authorName: 'Stuart Herbert',
            authorEmail: 'stuart@example.com',
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $commit->isTicketScope();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $result);
    }
}
