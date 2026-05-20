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
namespace StusDevKit\MissingBitsKit\TypeInspectors;

/**
 * Predicate. Use this to determine whether a value is a resource
 * that has been closed.
 *
 * Originally added to give AssertApi's closed-resource assertions
 * (`assertIsClosedResource()` and friends) a single, robust place
 * to make this check, instead of each one comparing the output of
 * a debug-only function against a hard-coded string.
 *
 * This predicate can be used in two ways:
 *
 * 1. statically, via `IsClosedResource::check()`
 * 2. invoked, by
 *    `$instance = new IsClosedResource() ; $instance($value)`
 *
 * Here Be Dragons
 * ===============
 *
 * PHP has no native closed-resource predicate, so this check is
 * necessarily indirect. It returns `true` ONLY for a resource
 * that has been closed: an OPEN resource returns `false` (reach
 * for `is_resource()` if that is what you want), and so does
 * every non-resource value.
 *
 * The check deliberately keys on the stable `resource` prefix
 * that `gettype()` reports, not on the exact `resource (closed)`
 * wording - the parenthetical text is a description PHP could
 * reword, whereas the resource type itself is a frozen, legacy
 * part of the language.
 */
class IsClosedResource
{
    /**
     * Predicate. Is the given `$value` a closed resource?
     *
     * Returns `true` only when `$value` is a resource that has
     * been closed (for example, a file handle after `fclose()`).
     * An open resource - and every non-resource value - returns
     * `false`.
     *
     * @param mixed $value
     *      the value to check
     * @return bool
     * - `true` when `$value` is a closed resource
     * - `false` otherwise, including for an open resource
     */
    public function __invoke(mixed $value): bool
    {
        return $this->check($value);
    }

    /**
     * Predicate. Is the given `$value` a closed resource?
     *
     * Returns `true` only when `$value` is a resource that has
     * been closed (for example, a file handle after `fclose()`).
     * An open resource - and every non-resource value - returns
     * `false`.
     *
     * @param mixed $value
     *      the value to check
     * @return bool
     * - `true` when `$value` is a closed resource
     * - `false` otherwise, including for an open resource
     */
    public static function check(mixed $value): bool
    {
        // do we have a resource?
        //
        // at the time of writing, the only way to reliably check
        // this is by using `gettype()`
        if (!str_starts_with(gettype($value), 'resource')) {
            return false;
        }

        // special case: an open resource
        //
        // is_resource() is true for a resource that is still open,
        // and false for everything else - including a closed
        // resource. Rule the open case out first.
        if (is_resource($value)) {
            return false;
        }

        // if we get here, we must have a closed resource
        return true;
    }
}
