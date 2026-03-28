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

namespace StusDevKit\ValidationKit\Contracts;

use StusDevKit\ValidationKit\Internals\ValidationContext;

/**
 * PipelineStep defines the contract for all steps in the
 * validation pipeline — constraints, normalisers, and
 * transforms.
 *
 * Steps are executed in the order they are added. Each
 * step receives the current data and a validation context,
 * and returns the (possibly modified) data.
 *
 * Steps that should be skipped when prior issues exist
 * (e.g. data transforms) return true from skipOnIssues().
 * When the pipeline encounters such a step and there are
 * issues, it stops executing.
 */
interface PipelineStep
{
    /**
     * process the data
     *
     * Constraints validate and return $data unchanged.
     * Normalisers and transforms return modified data.
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed;

    /**
     * should this step cause the pipeline to stop when
     * prior issues exist?
     *
     * Return true for transforms (don't transform invalid
     * data). Return false for constraints and normalisers
     * (always run).
     */
    public function skipOnIssues(): bool;
}
