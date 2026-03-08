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

namespace StusDevKit\ChangelogTool\Tests\Unit\Config;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ChangelogTool\Config\ChangelogConfig;
use StusDevKit\ChangelogTool\Config\TypeMapping;
use StusDevKit\ChangelogTool\Exceptions\InvalidConfigException;

#[TestDox('ChangelogConfig')]
class ChangelogConfigTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can create with type mappings')]
    public function test_can_create_with_type_mappings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a
        // ChangelogConfig directly with type mappings

        // ----------------------------------------------------------------
        // setup your test

        $mappings = [
            new TypeMapping(
                type: 'feat',
                section: 'Features',
            ),
            new TypeMapping(
                type: 'fix',
                section: 'Bug Fixes',
            ),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $config = new ChangelogConfig($mappings);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Features', $config->sectionForType('feat'));
        $this->assertSame('Bug Fixes', $config->sectionForType('fix'));
    }

    // ================================================================
    //
    // fromFile()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can load config from a valid JSON file')]
    public function test_can_load_from_valid_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromFile() correctly reads
        // and parses a .versionrc.json file

        // ----------------------------------------------------------------
        // setup your test

        $configPath = __DIR__
            . '/../../../fixtures/src/valid-versionrc.json';

        // ----------------------------------------------------------------
        // perform the change

        $config = ChangelogConfig::fromFile($configPath);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Features', $config->sectionForType('feat'));
        $this->assertSame('Bug Fixes', $config->sectionForType('fix'));
        $this->assertTrue($config->isTypeHidden('chore'));
    }

    #[TestDox('Throws when config file is missing')]
    public function test_throws_when_file_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromFile() throws an
        // InvalidConfigException when the file does not
        // exist

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidConfigException::class);
        ChangelogConfig::fromFile('/nonexistent/path.json');
    }

    #[TestDox('Throws when config file has invalid JSON')]
    public function test_throws_on_invalid_json(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromFile() throws an
        // InvalidConfigException when the file contains
        // invalid JSON

        // ----------------------------------------------------------------
        // setup your test

        $configPath = __DIR__
            . '/../../../fixtures/src/invalid-json.json';

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidConfigException::class);
        ChangelogConfig::fromFile($configPath);
    }

    #[TestDox('Throws when config file has no types array')]
    public function test_throws_when_no_types_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromFile() throws an
        // InvalidConfigException when the JSON does not
        // contain a 'types' array

        // ----------------------------------------------------------------
        // setup your test

        $configPath = __DIR__
            . '/../../../fixtures/src/no-types.json';

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidConfigException::class);
        ChangelogConfig::fromFile($configPath);
    }

    // ================================================================
    //
    // sectionForType()
    //
    // ----------------------------------------------------------------

    #[TestDox('Returns null for hidden types')]
    public function test_returns_null_for_hidden_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that sectionForType() returns
        // null for types that are marked as hidden

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([
            new TypeMapping(
                type: 'chore',
                hidden: true,
            ),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $config->sectionForType('chore');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    #[TestDox('Returns null for unknown types')]
    public function test_returns_null_for_unknown_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that sectionForType() returns
        // null for types not listed in the configuration

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([
            new TypeMapping(
                type: 'feat',
                section: 'Features',
            ),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $config->sectionForType('unknown');

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    // ================================================================
    //
    // isTypeHidden()
    //
    // ----------------------------------------------------------------

    #[TestDox('Unknown types are hidden by default')]
    public function test_unknown_types_are_hidden(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that types not listed in the
        // configuration are considered hidden

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([
            new TypeMapping(
                type: 'feat',
                section: 'Features',
            ),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $config->isTypeHidden('unknown');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result);
    }

    // ================================================================
    //
    // sectionOrder()
    //
    // ----------------------------------------------------------------

    #[TestDox('Returns sections in config file order')]
    public function test_returns_sections_in_config_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that sectionOrder() returns
        // only non-hidden section names in the order they
        // appear in the configuration

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([
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
            new TypeMapping(
                type: 'perf',
                section: 'Performance',
            ),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $result = $config->sectionOrder();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([
            'Features',
            'Bug Fixes',
            'Performance',
        ], $result);
    }

    // ================================================================
    //
    // loadPreamble()
    //
    // ----------------------------------------------------------------

    #[TestDox('Can load a preamble file')]
    public function test_can_load_preamble(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that loadPreamble() reads the
        // contents of a preamble markdown file

        // ----------------------------------------------------------------
        // setup your test

        $preamblePath = __DIR__
            . '/../../../fixtures/src/test-preamble.md';
        $config = new ChangelogConfig([]);

        // ----------------------------------------------------------------
        // perform the change

        $config->loadPreamble($preamblePath);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            "# Changelog\n\nAll notable changes.\n",
            $config->preamble(),
        );
    }

    #[TestDox('Preamble is empty when file does not exist')]
    public function test_preamble_empty_when_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that loadPreamble() leaves
        // the preamble empty when the file does not exist

        // ----------------------------------------------------------------
        // setup your test

        $config = new ChangelogConfig([]);

        // ----------------------------------------------------------------
        // perform the change

        $config->loadPreamble('/nonexistent/preamble.md');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('', $config->preamble());
    }
}
