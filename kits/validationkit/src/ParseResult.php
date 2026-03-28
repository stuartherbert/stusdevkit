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

namespace StusDevKit\ValidationKit;

use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\ValidationIssuesList;

/**
 * ParseResult holds the outcome of a safeParse() call.
 *
 * It is a discriminated result type: either the parse
 * succeeded (and data is available) or it failed (and a
 * ValidationException is available). Use the
 * succeeded()/failed() methods to check which case
 * applies.
 *
 * Usage:
 *
 *     $result = $schema->safeParse($input);
 *
 *     if ($result->succeeded()) {
 *         // use the validated data
 *         $data = $result->data();
 *     } else {
 *         // handle the error
 *         $error = $result->error();
 *         foreach ($error->issues() as $issue) {
 *             echo $issue->pathAsString() . ': '
 *                 . $issue->message;
 *         }
 *     }
 *
 *     // or use the maybe accessors
 *     $data = $result->maybeData(); // null if failed
 *     $error = $result->maybeError(); // null if succeeded
 *
 * @template-covariant TOutput
 */
final class ParseResult
{
    /**
     * @param TOutput|null $data
     */
    private function __construct(
        private readonly bool $success,
        private readonly mixed $data,
        private readonly ?ValidationException $exception,
    ) {
    }

    // ================================================================
    //
    // Named Constructors
    //
    // ----------------------------------------------------------------

    /**
     * create a successful parse result
     *
     * @template T
     * @param T $data
     * - the validated and possibly transformed data
     * @return self<T>
     */
    public static function ok(mixed $data): self
    {
        return new self(
            success: true,
            data: $data,
            exception: null,
        );
    }

    /**
     * create a failed parse result
     *
     * @return self<mixed>
     */
    public static function fail(
        ValidationException $exception,
    ): self {
        /** @var self<mixed> $result */
        $result = new self(
            success: false,
            data: null,
            exception: $exception,
        );

        return $result;
    }

    // ================================================================
    //
    // Status Checks
    //
    // ----------------------------------------------------------------

    /**
     * did the parse succeed?
     *
     * @phpstan-assert-if-true TOutput $this->data()
     * @phpstan-assert-if-true TOutput $this->maybeData()
     * @phpstan-assert-if-true null $this->maybeError()
     */
    public function succeeded(): bool
    {
        return $this->success;
    }

    /**
     * did the parse fail?
     *
     * @phpstan-assert-if-true ValidationException $this->maybeError()
     * @phpstan-assert-if-true null $this->maybeData()
     */
    public function failed(): bool
    {
        return ! $this->success;
    }

    // ================================================================
    //
    // Data Accessors
    //
    // ----------------------------------------------------------------

    /**
     * return the validated data
     *
     * @return TOutput
     * @throws ValidationException if the parse failed.
     */
    public function data(): mixed
    {
        if (! $this->success) {
            // exception is guaranteed non-null for failed
            // results (fail() requires one)
            /** @var ValidationException $ex */
            $ex = $this->exception;
            throw $ex;
        }

        /** @var TOutput $data */
        $data = $this->data;

        return $data;
    }

    /**
     * return the validated data, or null if the parse
     * failed
     *
     * @return TOutput|null
     */
    public function maybeData(): mixed
    {
        if (! $this->success) {
            return null;
        }

        return $this->data;
    }

    // ================================================================
    //
    // Error Accessors
    //
    // ----------------------------------------------------------------

    /**
     * return the validation error
     *
     * @throws ValidationException if the parse succeeded
     *         (there is no error to return).
     */
    public function error(): ValidationException
    {
        if ($this->success) {
            throw new ValidationException(new ValidationIssuesList());
        }

        // this is guaranteed to be non-null because
        // the only way to create a failed result is
        // via fail(), which requires an exception
        /** @var ValidationException $error */
        $error = $this->exception;

        return $error;
    }

    /**
     * return the validation error, or null if the parse
     * succeeded
     */
    public function maybeError(): ?ValidationException
    {
        return $this->exception;
    }
}
