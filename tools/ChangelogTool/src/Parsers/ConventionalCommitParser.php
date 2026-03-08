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

namespace StusDevKit\ChangelogTool\Parsers;

use StusDevKit\ChangelogTool\Git\GitCommit;

/**
 * Parses git commit messages according to the Conventional
 * Commits v1.0.0 specification.
 *
 * Extracts the type, optional scope, description, and
 * breaking change information from commit messages. Commits
 * that do not follow the conventional format are assigned
 * the type "other".
 *
 * Usage:
 *
 *     $parser = new ConventionalCommitParser();
 *     $parsed = $parser->parseCommit($gitCommit);
 *     // $parsed->type === "feat"
 *     // $parsed->scope === "parser"
 *     // $parsed->description === "add array support"
 */
final class ConventionalCommitParser
{
    /**
     * The regex pattern for parsing the first line of a
     * conventional commit message.
     *
     * Captures: type, optional scope (in parentheses),
     * optional breaking change indicator (!), and the
     * description after the colon.
     */
    private const string FIRST_LINE_PATTERN
        = '/^(?P<type>[a-zA-Z]+)(?:\((?P<scope>[^)]+)\))?(?P<breaking>!)?:\s*(?P<description>.+)$/';

    /**
     * The regex pattern for detecting a BREAKING CHANGE
     * footer in the commit body.
     *
     * Matches both "BREAKING CHANGE:" and "BREAKING-CHANGE:"
     * as specified by the Conventional Commits spec.
     */
    private const string BREAKING_CHANGE_FOOTER_PATTERN
        = '/^BREAKING[ -]CHANGE:\s*(?P<description>.+)$/m';

    /**
     * Parses a GitCommit into a ParsedCommit by extracting
     * conventional commit fields from the message.
     *
     * If the commit message does not follow the conventional
     * commit format, the commit is assigned type "other"
     * with the full first line as the description.
     *
     * @param GitCommit $commit the raw git commit to parse
     * @return ParsedCommit the parsed commit data
     */
    public function parseCommit(GitCommit $commit): ParsedCommit
    {
        $lines = explode("\n", $commit->message);
        $firstLine = trim($lines[0]);

        if (! preg_match(self::FIRST_LINE_PATTERN, $firstLine, $matches)) {
            // not a conventional commit
            return new ParsedCommit(
                type: 'other',
                scope: null,
                description: $firstLine,
                isBreaking: false,
                breakingDescription: null,
                hash: $commit->hash,
                shortHash: $commit->shortHash,
                authorName: $commit->authorName,
                authorEmail: $commit->authorEmail,
            );
        }

        $type = strtolower($matches['type']);
        $scope = $matches['scope'] !== '' ? $matches['scope'] : null;
        $description = $matches['description'];
        $isBreaking = $matches['breaking'] === '!';
        $breakingDescription = null;

        // check for BREAKING CHANGE footer in the body
        $body = implode("\n", array_slice($lines, 1));
        if (preg_match(self::BREAKING_CHANGE_FOOTER_PATTERN, $body, $footerMatches)) {
            $isBreaking = true;
            $breakingDescription = trim($footerMatches['description']);
        }

        // if breaking was indicated by ! but no footer
        // description, use the commit description
        if ($isBreaking && $breakingDescription === null) {
            $breakingDescription = $description;
        }

        return new ParsedCommit(
            type: $type,
            scope: $scope,
            description: $description,
            isBreaking: $isBreaking,
            breakingDescription: $breakingDescription,
            hash: $commit->hash,
            shortHash: $commit->shortHash,
            authorName: $commit->authorName,
            authorEmail: $commit->authorEmail,
        );
    }
}
