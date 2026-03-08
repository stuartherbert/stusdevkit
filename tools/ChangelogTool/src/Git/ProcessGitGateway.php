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

namespace StusDevKit\ChangelogTool\Git;

use InvalidArgumentException;
use StusDevKit\ChangelogTool\Contracts\GitGateway;
use StusDevKit\ChangelogTool\Exceptions\CannotInferRepoUrlException;
use StusDevKit\ChangelogTool\Parsers\SemVer;

/**
 * Production implementation of GitGateway that runs git
 * commands via proc_open.
 *
 * Uses proc_open instead of shell_exec for better error
 * handling (separate stdout and stderr streams).
 *
 * Usage:
 *
 *     $git = new ProcessGitGateway();
 *     $tags = $git->listTags();
 *     $commits = $git->listCommitsBetween(
 *         fromRef: 'v1.0.0',
 *         toRef: 'v1.1.0',
 *     );
 */
final class ProcessGitGateway implements GitGateway
{
    /**
     * The delimiter used to separate commit records in
     * git log output. This is a control character (SOH)
     * that is unlikely to appear in commit messages.
     *
     * In git format strings, use %x01. In PHP output
     * parsing, use this constant.
     */
    private const string RECORD_SEPARATOR = "\x01";

    /**
     * The delimiter used to separate fields within a
     * single commit record.
     *
     * We use the unit separator character (US, 0x1F)
     * because null bytes cannot be passed as process
     * arguments. In git format strings, use %x1f.
     */
    private const string FIELD_SEPARATOR = "\x1f";

    /**
     * {@inheritDoc}
     */
    public function listTags(): array
    {
        // use git for-each-ref to get tag names and dates
        // in a single command
        //
        // %(creatordate:short) returns the tag date for
        // annotated tags, or the commit date for
        // lightweight tags
        // use %x1f (unit separator) between fields
        $format = '%(refname:short)%x1f%(creatordate:short)';

        $output = $this->runGitCommand([
            'for-each-ref',
            '--format=' . $format,
            'refs/tags/',
        ]);

        if (trim($output) === '') {
            return [];
        }

        $tags = [];
        $lines = explode("\n", trim($output));

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = explode(self::FIELD_SEPARATOR, $line);
            if (count($parts) !== 2) {
                continue;
            }

            $tagName = $parts[0];
            $date = $parts[1];

            try {
                $semver = SemVer::fromTagName($tagName);
            } catch (InvalidArgumentException) {
                // skip non-semver tags silently
                continue;
            }

            $tags[] = new GitTag(
                name: $tagName,
                date: $date,
                semver: $semver,
            );
        }

        return $tags;
    }

    /**
     * {@inheritDoc}
     */
    public function listCommitsBetween(
        ?string $fromRef,
        ?string $toRef,
    ): array {
        // build the ref range
        $range = '';
        if ($fromRef !== null && $toRef !== null) {
            $range = "{$fromRef}..{$toRef}";
        } elseif ($fromRef !== null) {
            $range = "{$fromRef}..HEAD";
        } elseif ($toRef !== null) {
            $range = $toRef;
        } else {
            $range = 'HEAD';
        }

        // use a custom format with field and record
        // separators to reliably parse output
        //
        // %H  = full hash
        // %h  = short hash
        // %an = author name
        // %ae = author email
        // %B  = full commit message (subject + body)
        // use %x1f (unit separator) between fields and
        // %x01 (SOH) as record separator
        $format = '%H%x1f%h%x1f%an%x1f%ae%x1f%B%x01';

        $output = $this->runGitCommand([
            'log',
            '--format=' . $format,
            $range,
        ]);

        if (trim($output) === '') {
            return [];
        }

        $commits = [];
        $records = explode(self::RECORD_SEPARATOR, $output);

        foreach ($records as $record) {
            $record = trim($record);
            if ($record === '') {
                continue;
            }

            $fields = explode(self::FIELD_SEPARATOR, $record, 5);
            if (count($fields) !== 5) {
                continue;
            }

            $commits[] = new GitCommit(
                hash: $fields[0],
                shortHash: $fields[1],
                authorName: $fields[2],
                authorEmail: $fields[3],
                message: trim($fields[4]),
            );
        }

        return $commits;
    }

    /**
     * {@inheritDoc}
     */
    public function inferRemoteUrl(): GitRemoteUrl
    {
        // determine the tracking remote of the current
        // branch
        $upstream = trim($this->runGitCommand([
            'rev-parse',
            '--abbrev-ref',
            '--symbolic-full-name',
            '@{upstream}',
        ]));

        if ($upstream === '') {
            throw new CannotInferRepoUrlException(
                'Current branch has no tracking remote.'
                . ' Cannot infer repository URL.'
            );
        }

        // extract the remote name from "remote/branch"
        $remoteName = explode('/', $upstream)[0];

        // get the remote URL
        $remoteUrl = trim($this->runGitCommand([
            'remote',
            'get-url',
            $remoteName,
        ]));

        if ($remoteUrl === '') {
            throw new CannotInferRepoUrlException(
                "Cannot get URL for remote '{$remoteName}'"
            );
        }

        return GitRemoteUrl::fromRemoteString($remoteUrl);
    }

    /**
     * Runs a git command and returns its stdout output.
     *
     * Uses proc_open for better error handling, separating
     * stdout from stderr.
     *
     * @param list<string> $args the git subcommand and
     *     arguments (without the 'git' prefix)
     * @return string the stdout output
     * @throws CannotInferRepoUrlException if the command
     *     fails.
     */
    private function runGitCommand(array $args): string
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        // use array form to avoid shell interpretation
        $command = array_merge(['git'], $args);
        $commandStr = implode(' ', $command);

        $process = proc_open(
            $command,
            $descriptors,
            $pipes,
        );

        if (! is_resource($process)) {
            throw new CannotInferRepoUrlException(
                "Failed to run git command: {$commandStr}"
            );
        }

        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new CannotInferRepoUrlException(
                "Git command failed (exit {$exitCode}):"
                . " {$commandStr}\n{$stderr}"
            );
        }

        return $stdout !== false ? $stdout : '';
    }
}
