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

use StusDevKit\ChangelogTool\Git\GitTag;

/**
 * Provides comparison and sorting logic for semantic
 * versions.
 *
 * Pre-release versions sort lower than their release
 * counterpart. Pre-release identifiers are sorted
 * alphabetically by text, then numerically by number.
 *
 * Usage:
 *
 *     // compare two versions
 *     $result = SemVerSorter::compare($a, $b);
 *
 *     // sort tags in reverse semver order
 *     $sorted = SemVerSorter::sortTagsDescending($tags);
 */
final class SemVerSorter
{
    /**
     * Compares two semantic versions.
     *
     * Returns a negative integer if $a < $b, a positive
     * integer if $a > $b, or zero if they are equal.
     *
     * Pre-release versions are considered lower than their
     * corresponding release version. Among pre-release
     * versions, identifiers are compared alphabetically
     * by text portion and numerically by number portion.
     *
     * @param SemVer $a the first version to compare
     * @param SemVer $b the second version to compare
     * @return int negative if $a < $b, positive if $a > $b,
     *     zero if equal
     */
    public static function compare(SemVer $a, SemVer $b): int
    {
        // compare major.minor.patch first
        $result = $a->major <=> $b->major;
        if ($result !== 0) {
            return $result;
        }

        $result = $a->minor <=> $b->minor;
        if ($result !== 0) {
            return $result;
        }

        $result = $a->patch <=> $b->patch;
        if ($result !== 0) {
            return $result;
        }

        // if both have no pre-release, they are equal
        if ($a->preRelease === null && $b->preRelease === null) {
            return 0;
        }

        // a release version is always greater than its
        // pre-release counterpart
        if ($a->preRelease === null) {
            return 1;
        }
        if ($b->preRelease === null) {
            return -1;
        }

        // compare pre-release identifiers
        return self::comparePreRelease(
            preReleaseA: $a->preRelease,
            preReleaseB: $b->preRelease,
        );
    }

    /**
     * Sorts an array of GitTag objects in descending
     * semantic version order (highest version first).
     *
     * @param list<GitTag> $tags the tags to sort
     * @return list<GitTag> the sorted tags
     */
    public static function sortTagsDescending(array $tags): array
    {
        usort($tags, static function (GitTag $a, GitTag $b): int {
            return self::compare(
                a: $b->semver,
                b: $a->semver,
            );
        });

        return $tags;
    }

    /**
     * Compares two pre-release identifier strings.
     *
     * Splits each identifier by "." and compares each
     * segment. Text segments are compared alphabetically,
     * numeric segments are compared numerically.
     *
     * @param string $preReleaseA the first pre-release
     *     identifier
     * @param string $preReleaseB the second pre-release
     *     identifier
     * @return int negative if $a < $b, positive if $a > $b,
     *     zero if equal
     */
    private static function comparePreRelease(
        string $preReleaseA,
        string $preReleaseB,
    ): int {
        $partsA = explode('.', $preReleaseA);
        $partsB = explode('.', $preReleaseB);

        $maxParts = max(count($partsA), count($partsB));

        for ($i = 0; $i < $maxParts; $i++) {
            // fewer identifiers sorts lower
            if (! isset($partsA[$i])) {
                return -1;
            }
            if (! isset($partsB[$i])) {
                return 1;
            }

            $aIsNumeric = ctype_digit($partsA[$i]);
            $bIsNumeric = ctype_digit($partsB[$i]);

            // numeric segments compare numerically
            if ($aIsNumeric && $bIsNumeric) {
                $result = (int) $partsA[$i] <=> (int) $partsB[$i];
                if ($result !== 0) {
                    return $result;
                }
                continue;
            }

            // text sorts higher than numeric
            if ($aIsNumeric !== $bIsNumeric) {
                return $aIsNumeric ? -1 : 1;
            }

            // both are text, compare alphabetically
            $result = strcmp($partsA[$i], $partsB[$i]);
            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }
}
