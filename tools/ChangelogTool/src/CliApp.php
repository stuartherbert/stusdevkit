<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
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

declare(strict_types=1);

namespace StusDevKit\ChangelogTool;

use StusDevKit\ChangelogTool\Config\ChangelogConfig;
use StusDevKit\ChangelogTool\Git\ProcessGitGateway;
use StusDevKit\ChangelogTool\Parsers\ConventionalCommitParser;
use StusDevKit\ChangelogTool\Renderers\ChangelogRenderer;
use StusDevKit\ChangelogTool\Renderers\CommitLineRenderer;
use StusDevKit\ChangelogTool\Renderers\ReleaseRenderer;
use Throwable;

/**
 * CLI application entry point for the changelog-tool.
 *
 * Parses command-line arguments, constructs all
 * dependencies, runs the changelog generator, and writes
 * the output.
 *
 * Usage:
 *
 *     $app = new CliApp();
 *     $exitCode = $app->run($argv);
 */
final class CliApp
{
    /**
     * Runs the changelog-tool with the given command-line
     * arguments.
     *
     * @param list<string> $argv the command-line arguments
     * @return int the exit code (0 for success, 1 for
     *     error)
     */
    public function run(array $argv): int
    {
        $options = $this->parseArgs($argv);

        try {
            $config = ChangelogConfig::fromFile(
                $options->configPath,
            );

            // derive preamble path from config path
            // directory
            $configDir = dirname($options->configPath);
            $preamblePath = $configDir
                . '/.changelog.preamble.md';
            $config->loadPreamble($preamblePath);

            $git = new ProcessGitGateway();
            $parser = new ConventionalCommitParser();
            $commitLineRenderer = new CommitLineRenderer();
            $releaseRenderer = new ReleaseRenderer(
                commitLineRenderer: $commitLineRenderer,
                config: $config,
            );
            $changelogRenderer = new ChangelogRenderer(
                releaseRenderer: $releaseRenderer,
                config: $config,
            );
            $generator = new ChangelogGenerator(
                git: $git,
                renderer: $changelogRenderer,
                parser: $parser,
            );

            $changelog = $generator->generateChangelog();

            if ($options->dryRun) {
                fwrite(STDOUT, $changelog);
            } else {
                file_put_contents(
                    $options->outputPath,
                    $changelog,
                );
                fwrite(
                    STDERR,
                    "Changelog written to"
                    . " {$options->outputPath}\n",
                );
            }

            return 0;
        } catch (Throwable $e) {
            fwrite(STDERR, "Error: {$e->getMessage()}\n");
            return 1;
        }
    }

    /**
     * Parses the command-line arguments into a CliOptions
     * value object.
     *
     * Supported flags:
     * - `--output <path>` or `--output=<path>`: output file
     *     path (default: CHANGELOG.md)
     * - `--config <path>` or `--config=<path>`: config file
     *     path (default: .versionrc.json)
     * - `--dry-run`: print to stdout instead of writing
     *
     * @param list<string> $argv the command-line arguments
     * @return CliOptions the parsed options
     */
    public function parseArgs(array $argv): CliOptions
    {
        $outputPath = 'CHANGELOG.md';
        $configPath = '.versionrc.json';
        $dryRun = false;

        // skip the script name (argv[0])
        $args = array_slice($argv, 1);
        $count = count($args);

        for ($i = 0; $i < $count; $i++) {
            $arg = $args[$i];

            if ($arg === '--dry-run') {
                $dryRun = true;
                continue;
            }

            if ($arg === '--output' && isset($args[$i + 1])) {
                $outputPath = $args[++$i];
                continue;
            }

            if (str_starts_with($arg, '--output=')) {
                $outputPath = substr($arg, 9);
                continue;
            }

            if ($arg === '--config' && isset($args[$i + 1])) {
                $configPath = $args[++$i];
                continue;
            }

            if (str_starts_with($arg, '--config=')) {
                $configPath = substr($arg, 9);
                continue;
            }
        }

        return new CliOptions(
            outputPath: $outputPath,
            configPath: $configPath,
            dryRun: $dryRun,
        );
    }
}
