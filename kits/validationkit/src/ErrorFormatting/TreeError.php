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

namespace StusDevKit\ValidationKit\ErrorFormatting;

/**
 * TreeError holds validation errors in a nested tree
 * structure that mirrors the shape of the validated data.
 *
 * Each node has its own errors and may have child nodes
 * for nested properties or array items.
 *
 * Usage:
 *
 *     $tree = ErrorFormatter::treeify($exception);
 *     $tree->errors();                 // this node's errors
 *     $tree->children();               // nested nodes
 *     $tree->maybeChild('address');     // specific child
 */
final class TreeError
{
    /**
     * @param list<string> $errors
     * - error messages at this node
     * @param array<string|int, TreeError> $children
     * - child nodes keyed by field name or array index
     */
    public function __construct(
        private readonly array $errors = [],
        private readonly array $children = [],
    ) {
    }

    // ================================================================
    //
    // Getters
    //
    // ----------------------------------------------------------------

    /**
     * return the error messages at this node
     *
     * @return list<string>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * return all child nodes
     *
     * @return array<string|int, TreeError>
     */
    public function children(): array
    {
        return $this->children;
    }

    /**
     * return a specific child node, or null if it does
     * not exist
     */
    public function maybeChild(string|int $key): ?self
    {
        return $this->children[$key] ?? null;
    }

    /**
     * does this node have any errors (at this level or
     * below)?
     */
    public function hasErrors(): bool
    {
        if (count($this->errors) > 0) {
            return true;
        }

        foreach ($this->children as $child) {
            if ($child->hasErrors()) {
                return true;
            }
        }

        return false;
    }
}
