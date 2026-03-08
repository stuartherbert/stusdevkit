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

use StusDevKit\ChangelogTool\Exceptions\InvalidConfigException;

/**
 * Reads and holds the changelog configuration from
 * `.versionrc.json` and the preamble from
 * `.changelog.preamble.md`.
 *
 * The configuration controls which commit types appear
 * in the changelog and under which section headings.
 * The order of types in the config determines the display
 * order of sections in the changelog.
 *
 * Usage:
 *
 *     $config = ChangelogConfig::fromFile(".versionrc.json");
 *     $config->loadPreamble(".changelog.preamble.md");
 *
 *     $section = $config->sectionForType("feat");
 *     // "Features"
 *
 *     if ($config->isTypeHidden("chore")) {
 *         // skip this commit type
 *     }
 */
final class ChangelogConfig
{
    /**
     * @var list<TypeMapping> the type-to-section mappings,
     *     in config file order
     */
    private array $typeMappings;

    /**
     * @var string the preamble content to prepend to the
     *     changelog
     */
    private string $preamble = '';

    /**
     * @param list<TypeMapping> $typeMappings the type
     *     mappings from the config file
     */
    public function __construct(array $typeMappings)
    {
        $this->typeMappings = $typeMappings;
    }

    /**
     * Creates a ChangelogConfig from a `.versionrc.json`
     * file.
     *
     * @param string $configPath the path to the config file
     * @return self the parsed configuration
     * @throws InvalidConfigException if the file is missing,
     *     malformed, or has an invalid structure.
     */
    public static function fromFile(string $configPath): self
    {
        if (! file_exists($configPath)) {
            throw new InvalidConfigException(
                "Config file not found: {$configPath}"
            );
        }

        $contents = file_get_contents($configPath);
        if ($contents === false) {
            throw new InvalidConfigException(
                "Cannot read config file: {$configPath}"
            );
        }

        $data = json_decode($contents, associative: true);
        if (! is_array($data)) {
            throw new InvalidConfigException(
                "Invalid JSON in config file: {$configPath}"
            );
        }

        if (! isset($data['types']) || ! is_array($data['types'])) {
            throw new InvalidConfigException(
                "Config file must contain a 'types' array:"
                . " {$configPath}"
            );
        }

        $mappings = [];
        foreach ($data['types'] as $entry) {
            if (! is_array($entry) || ! isset($entry['type'])) {
                throw new InvalidConfigException(
                    "Each type entry must have a 'type' field:"
                    . " {$configPath}"
                );
            }

            /** @var string $type */
            $type = $entry['type'];
            /** @var string|null $section */
            $section = $entry['section'] ?? null;
            /** @var bool $hidden */
            $hidden = $entry['hidden'] ?? false;

            $mappings[] = new TypeMapping(
                type: $type,
                section: $section,
                hidden: (bool) $hidden,
            );
        }

        return new self($mappings);
    }

    /**
     * Loads the preamble content from a markdown file.
     *
     * If the file does not exist, the preamble remains
     * empty.
     *
     * @param string $preamblePath the path to the preamble
     *     file
     */
    public function loadPreamble(string $preamblePath): void
    {
        if (! file_exists($preamblePath)) {
            return;
        }

        $contents = file_get_contents($preamblePath);
        if ($contents === false) {
            return;
        }

        $this->preamble = $contents;
    }

    /**
     * Returns the changelog section name for a given
     * commit type.
     *
     * @param string $type the conventional commit type
     * @return string|null the section heading, or null if
     *     the type is hidden or unknown
     */
    public function sectionForType(string $type): ?string
    {
        foreach ($this->typeMappings as $mapping) {
            if ($mapping->type === $type) {
                if ($mapping->hidden) {
                    return null;
                }
                return $mapping->section;
            }
        }

        // unknown types are hidden by default
        return null;
    }

    /**
     * Determines whether a given commit type should be
     * hidden from the changelog.
     *
     * @param string $type the conventional commit type
     * @return bool true if the type is hidden or unknown
     */
    public function isTypeHidden(string $type): bool
    {
        foreach ($this->typeMappings as $mapping) {
            if ($mapping->type === $type) {
                return $mapping->hidden;
            }
        }

        // unknown types are hidden by default
        return true;
    }

    /**
     * Returns the preamble content to prepend to the
     * changelog.
     *
     * @return string the preamble markdown content
     */
    public function preamble(): string
    {
        return $this->preamble;
    }

    /**
     * Returns the section names in the order they appear
     * in the configuration file.
     *
     * This preserves the user's desired display order for
     * changelog sections. Only returns sections for
     * non-hidden types.
     *
     * @return list<string> the ordered section names
     */
    public function sectionOrder(): array
    {
        $sections = [];
        $seen = [];

        foreach ($this->typeMappings as $mapping) {
            if ($mapping->hidden || $mapping->section === null) {
                continue;
            }
            if (isset($seen[$mapping->section])) {
                continue;
            }
            $seen[$mapping->section] = true;
            $sections[] = $mapping->section;
        }

        return $sections;
    }
}
