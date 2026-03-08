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
use StusDevKit\ChangelogTool\Git\GitTag;
use StusDevKit\ChangelogTool\Parsers\SemVer;
use StusDevKit\ChangelogTool\Parsers\SemVerSorter;

#[TestDox('SemVerSorter')]
class SemVerSorterTest extends TestCase
{
    // ================================================================
    //
    // compare()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{SemVer, SemVer, int}>
     */
    public static function provideComparisonPairs(): array
    {
        return [
            'equal versions' => [
                new SemVer(major: 1, minor: 0, patch: 0),
                new SemVer(major: 1, minor: 0, patch: 0),
                0,
            ],
            'major difference' => [
                new SemVer(major: 1, minor: 0, patch: 0),
                new SemVer(major: 2, minor: 0, patch: 0),
                -1,
            ],
            'minor difference' => [
                new SemVer(major: 1, minor: 1, patch: 0),
                new SemVer(major: 1, minor: 2, patch: 0),
                -1,
            ],
            'patch difference' => [
                new SemVer(major: 1, minor: 0, patch: 1),
                new SemVer(major: 1, minor: 0, patch: 2),
                -1,
            ],
            'pre-release lower than release' => [
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'alpha.1',
                ),
                new SemVer(major: 1, minor: 0, patch: 0),
                -1,
            ],
            'alpha before beta' => [
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'alpha.1',
                ),
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'beta.1',
                ),
                -1,
            ],
            'beta before rc' => [
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'beta.1',
                ),
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'rc.1',
                ),
                -1,
            ],
            'alpha.1 before alpha.2' => [
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'alpha.1',
                ),
                new SemVer(
                    major: 1,
                    minor: 0,
                    patch: 0,
                    preRelease: 'alpha.2',
                ),
                -1,
            ],
            'reverse: higher major is greater' => [
                new SemVer(major: 2, minor: 0, patch: 0),
                new SemVer(major: 1, minor: 0, patch: 0),
                1,
            ],
        ];
    }

    #[TestDox('Compares two semantic versions correctly')]
    #[DataProvider('provideComparisonPairs')]
    public function test_compare(
        SemVer $a,
        SemVer $b,
        int $expectedSign,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that compare() returns the
        // correct sign for the given pair of versions

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $result = SemVerSorter::compare(a: $a, b: $b);

        // ----------------------------------------------------------------
        // test the results

        if ($expectedSign === 0) {
            $this->assertSame(0, $result);
        } elseif ($expectedSign < 0) {
            $this->assertLessThan(0, $result);
        } else {
            $this->assertGreaterThan(0, $result);
        }
    }

    // ================================================================
    //
    // sortTagsDescending()
    //
    // ----------------------------------------------------------------

    #[TestDox('Sorts tags in reverse semver order')]
    public function test_sorts_tags_descending(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that sortTagsDescending() sorts
        // tags from highest to lowest semver, regardless
        // of input order

        // ----------------------------------------------------------------
        // setup your test

        $tags = [
            new GitTag(
                name: 'v1.0.0',
                date: '2026-01-01',
                semver: SemVer::fromTagName('v1.0.0'),
            ),
            new GitTag(
                name: 'v2.0.0',
                date: '2026-02-01',
                semver: SemVer::fromTagName('v2.0.0'),
            ),
            new GitTag(
                name: 'v1.2.1',
                date: '2026-03-01',
                semver: SemVer::fromTagName('v1.2.1'),
            ),
            new GitTag(
                name: '1.0.0-alpha.1',
                date: '2025-12-01',
                semver: SemVer::fromTagName('1.0.0-alpha.1'),
            ),
            new GitTag(
                name: 'v1.0.0-rc.1',
                date: '2025-12-15',
                semver: SemVer::fromTagName('v1.0.0-rc.1'),
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $sorted = SemVerSorter::sortTagsDescending($tags);

        // ----------------------------------------------------------------
        // test the results

        $names = array_map(
            static fn (GitTag $tag): string => $tag->name,
            $sorted,
        );

        $this->assertSame([
            'v2.0.0',
            'v1.2.1',
            'v1.0.0',
            'v1.0.0-rc.1',
            '1.0.0-alpha.1',
        ], $names);
    }
}
