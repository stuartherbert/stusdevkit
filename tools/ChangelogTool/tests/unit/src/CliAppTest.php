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
use StusDevKit\ChangelogTool\CliApp;

#[TestDox('CliApp')]
class CliAppTest extends TestCase
{
    // ================================================================
    //
    // parseArgs()
    //
    // ----------------------------------------------------------------

    #[TestDox('Uses defaults when no arguments given')]
    public function test_uses_defaults(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parseArgs() returns the
        // correct default values when no flags are provided

        // ----------------------------------------------------------------
        // setup your test

        $app = new CliApp();

        // ----------------------------------------------------------------
        // perform the change

        $options = $app->parseArgs(['changelog-tool']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CHANGELOG.md', $options->outputPath);
        $this->assertSame('.versionrc.json', $options->configPath);
        $this->assertFalse($options->dryRun);
    }

    #[TestDox('Can parse --output flag')]
    public function test_can_parse_output_flag(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parseArgs() correctly
        // reads the --output flag with a space separator

        // ----------------------------------------------------------------
        // setup your test

        $app = new CliApp();

        // ----------------------------------------------------------------
        // perform the change

        $options = $app->parseArgs([
            'changelog-tool',
            '--output',
            'docs/CHANGELOG.md',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('docs/CHANGELOG.md', $options->outputPath);
    }

    #[TestDox('Can parse --output= flag')]
    public function test_can_parse_output_equals_flag(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parseArgs() correctly
        // reads the --output flag with an = separator

        // ----------------------------------------------------------------
        // setup your test

        $app = new CliApp();

        // ----------------------------------------------------------------
        // perform the change

        $options = $app->parseArgs([
            'changelog-tool',
            '--output=docs/CHANGELOG.md',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('docs/CHANGELOG.md', $options->outputPath);
    }

    #[TestDox('Can parse --config flag')]
    public function test_can_parse_config_flag(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parseArgs() correctly
        // reads the --config flag

        // ----------------------------------------------------------------
        // setup your test

        $app = new CliApp();

        // ----------------------------------------------------------------
        // perform the change

        $options = $app->parseArgs([
            'changelog-tool',
            '--config',
            'custom.json',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('custom.json', $options->configPath);
    }

    #[TestDox('Can parse --dry-run flag')]
    public function test_can_parse_dry_run_flag(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parseArgs() correctly
        // sets dryRun to true when --dry-run is provided

        // ----------------------------------------------------------------
        // setup your test

        $app = new CliApp();

        // ----------------------------------------------------------------
        // perform the change

        $options = $app->parseArgs([
            'changelog-tool',
            '--dry-run',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($options->dryRun);
    }

    #[TestDox('Can parse all flags together')]
    public function test_can_parse_all_flags(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parseArgs() correctly
        // handles all flags provided together

        // ----------------------------------------------------------------
        // setup your test

        $app = new CliApp();

        // ----------------------------------------------------------------
        // perform the change

        $options = $app->parseArgs([
            'changelog-tool',
            '--output',
            'docs/CHANGES.md',
            '--config',
            'config/versions.json',
            '--dry-run',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('docs/CHANGES.md', $options->outputPath);
        $this->assertSame(
            'config/versions.json',
            $options->configPath,
        );
        $this->assertTrue($options->dryRun);
    }
}
