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

use InvalidArgumentException;

/**
 * Represents a parsed semantic version number.
 *
 * Parses version strings like "1.2.3", "v1.2.3",
 * "1.0.0-alpha.1", and "v2.0.0-rc.1" into their
 * component parts.
 *
 * Usage:
 *
 *     $version = SemVer::fromTagName("v1.2.3");
 *     // $version->major === 1
 *     // $version->minor === 2
 *     // $version->patch === 3
 *     // $version->preRelease === null
 *
 *     $preRelease = SemVer::fromTagName("1.0.0-alpha.1");
 *     // $preRelease->preRelease === "alpha.1"
 */
final readonly class SemVer
{
    /**
     * @param int $major the major version number
     * @param int $minor the minor version number
     * @param int $patch the patch version number
     * @param string|null $preRelease the pre-release identifier,
     *     e.g. "alpha.1", "beta.2", "rc.1"
     */
    public function __construct(
        public int $major,
        public int $minor,
        public int $patch,
        public ?string $preRelease = null,
    ) {
    }

    /**
     * Creates a SemVer instance from a git tag name.
     *
     * Strips any leading "v" prefix before parsing.
     *
     * @param string $tag the git tag name, e.g. "v1.2.3"
     *     or "1.0.0-alpha.1"
     * @return self the parsed version
     * @throws InvalidArgumentException if the tag name
     *     cannot be parsed as a semantic version.
     */
    public static function fromTagName(string $tag): self
    {
        // strip optional leading "v"
        $version = ltrim($tag, 'v');

        $pattern = '/^(?P<major>\d+)\.(?P<minor>\d+)\.(?P<patch>\d+)(?:-(?P<preRelease>[a-zA-Z0-9.]+))?$/';

        if (! preg_match($pattern, $version, $matches)) {
            throw new InvalidArgumentException(
                "Cannot parse tag '{$tag}' as a semantic version"
            );
        }

        return new self(
            major: (int) $matches['major'],
            minor: (int) $matches['minor'],
            patch: (int) $matches['patch'],
            preRelease: $matches['preRelease'] ?? null,
        );
    }
}
