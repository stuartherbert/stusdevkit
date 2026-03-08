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

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\Parsers\SemVer;

#[TestDox('SemVer')]
class SemVerTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can create a SemVer directly')]
    public function test_can_create_directly(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a SemVer
        // value object directly via the constructor

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new SemVer(
            major: 1,
            minor: 2,
            patch: 3,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $unit->major);
        $this->assertSame(2, $unit->minor);
        $this->assertSame(3, $unit->patch);
        $this->assertNull($unit->preRelease);
    }

    #[TestDox('Can create a SemVer with a pre-release identifier')]
    public function test_can_create_with_pre_release(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a SemVer
        // with a pre-release identifier

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new SemVer(
            major: 1,
            minor: 0,
            patch: 0,
            preRelease: 'alpha.1',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $unit->major);
        $this->assertSame(0, $unit->minor);
        $this->assertSame(0, $unit->patch);
        $this->assertSame('alpha.1', $unit->preRelease);
    }

    // ================================================================
    //
    // fromTagName()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, int, int, int, string|null}>
     */
    public static function provideValidTagNames(): array
    {
        return [
            'simple version' => [
                '1.2.3', 1, 2, 3, null,
            ],
            'version with v prefix' => [
                'v1.2.3', 1, 2, 3, null,
            ],
            'version with alpha pre-release' => [
                'v1.0.0-alpha.1', 1, 0, 0, 'alpha.1',
            ],
            'version with beta pre-release' => [
                '2.0.0-beta.2', 2, 0, 0, 'beta.2',
            ],
            'version with rc pre-release' => [
                'v3.1.0-rc.1', 3, 1, 0, 'rc.1',
            ],
            'version with zero parts' => [
                '0.0.1', 0, 0, 1, null,
            ],
            'large version numbers' => [
                'v100.200.300', 100, 200, 300, null,
            ],
        ];
    }

    #[TestDox('Can parse valid tag names')]
    #[DataProvider('provideValidTagNames')]
    public function test_can_parse_valid_tag_names(
        string $tag,
        int $expectedMajor,
        int $expectedMinor,
        int $expectedPatch,
        ?string $expectedPreRelease,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromTagName() correctly
        // parses valid tag names into their component parts

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = SemVer::fromTagName($tag);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedMajor, $unit->major);
        $this->assertSame($expectedMinor, $unit->minor);
        $this->assertSame($expectedPatch, $unit->patch);
        $this->assertSame($expectedPreRelease, $unit->preRelease);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidTagNames(): array
    {
        return [
            'no dots' => ['v1'],
            'only major.minor' => ['v1.2'],
            'non-numeric' => ['vone.two.three'],
            'empty string' => [''],
            'random text' => ['release-candidate'],
        ];
    }

    #[TestDox('Throws on invalid tag names')]
    #[DataProvider('provideInvalidTagNames')]
    public function test_throws_on_invalid_tag_names(
        string $tag,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromTagName() throws an
        // InvalidArgumentException for tag names that
        // cannot be parsed as semantic versions

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidArgumentException::class);
        SemVer::fromTagName($tag);
    }
}
