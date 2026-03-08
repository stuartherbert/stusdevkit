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

namespace StusDevKit\ChangelogTool\Config;

/**
 * Maps a conventional commit type to a changelog section.
 *
 * Each entry in the `.versionrc.json` "types" array becomes
 * a TypeMapping instance. Types can either be mapped to a
 * named section (e.g. "feat" -> "Features") or hidden from
 * the changelog entirely.
 *
 * Usage:
 *
 *     $mapping = new TypeMapping(
 *         type: "feat",
 *         section: "Features",
 *         hidden: false,
 *     );
 */
final readonly class TypeMapping
{
    /**
     * @param string $type the conventional commit type,
     *     e.g. "feat", "fix", "chore"
     * @param string|null $section the changelog section
     *     heading, e.g. "Features", "Bug Fixes". Null if
     *     the type is hidden.
     * @param bool $hidden whether commits of this type
     *     should be excluded from the changelog
     */
    public function __construct(
        public string $type,
        public ?string $section = null,
        public bool $hidden = false,
    ) {
    }
}
