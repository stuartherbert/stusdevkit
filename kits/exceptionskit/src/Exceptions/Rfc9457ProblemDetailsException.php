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

namespace StusDevKit\ExceptionsKit\Exceptions;

use Exception;
use JsonSerializable;
use Throwable;

/**
 * Rfc9457ProblemDetailsException is a PHP Exception that holds data that
 * follows the RFC 9457 standard.
 *
 * @see https://www.rfc-editor.org/rfc/rfc9457
 *
 * FORWARDS-COMPATIBILITY NOTES:
 *
 * - at the time of writing, PHP 8.5's Uri extension is undocumented and
 *   unrecognised by my IDE of choice. (These things are probably related.)
 * - as a result, it isn't practical to make the `$type` and `$instance`
 * - parameters a `Uri` right now.
 * - a future release will update the `$type` parameter to accept
 *   `string|Uri`, and internally convert any passed-in string to a `Uri`
 *   instance.
 * - a future release will update the `$instance` parameter to accept
 *   `string|Uri`, and internally convert any passed-in string to a `Uri`
 *   instance.
 *
 * @phpstan-type ProblemReportExtraLeaf int|string|array<string,int|string>
 * @phpstan-type ProblemReportExtraNode int|string|array<string, ProblemReportExtraLeaf>
 * @phpstan-type ProblemReportExtra array<string, int|string|array<string,ProblemReportExtraNode>>
 */
class Rfc9457ProblemDetailsException extends Exception implements JsonSerializable
{
    /**
     * @param non-empty-string $type
     * - URI to a page that documents this class of problem
     * - for example, link to a Github wiki page explaining the error
     * @param int $status
     * - HTTP status code that describes the nature of this problem
     * - recommend sticking to 4xx and 5xx codes
     * - does not stop you using 3xx or other HTTP status codes
     * @param non-empty-string $title
     * - short, human-readable summary of the problem type
     * - used as the exception message if `$detail` is empty
     * @param non-empty-string|null $detail
     * - human-readable explanation specific to this occurrence of the problem
     * - used as the exception message unless empty
     * @param ProblemReportExtra $extra
     * - array of additional information specific to this occurrence of the
     *   problem
     * - think of these as additional fields to add to your application
     *   logs
     * @param non-empty-string|null $instance
     * - absolute URI to a resource specific to this occurrence of the problem
     * - for example, if the user needs to perform a manual step to solve
     *   this problem, $instance could be a link to the web page for that
     *   action
     * @param ?Throwable $previous
     * - was this exception caused by another exception?
     */
    public function __construct(
        protected string $type,
        protected int $status,
        protected string $title,
        protected array $extra = [],
        protected ?string $detail = null,
        protected ?string $instance = null,
        protected ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: $detail ?? $title,
            previous: $previous,
        );
    }

    // ================================================================
    //
    // Getters
    //
    // Please keep this in alphabetical order, grouped by the property
    // that the getters refer to.
    //
    // ----------------------------------------------------------------

    /**
     * does this problem include any further details?
     *
     * @phpstan-assert-if-true non-empty-string $this->maybeGetDetail()
     */
    public function hasDetail(): bool
    {
        return $this->detail !== null;
    }

    /**
     * return the `detail` of this problem (if any detail was provided)
     *
     * - human-readable explanation specific to this occurrence of the problem
     * - used as the exception message unless empty
     */
    public function maybeGetDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * does this problem report have any extra data available?
     */
    public function hasExtra(): bool
    {
        return ! empty($this->extra);
    }

    /**
     * @return ProblemReportExtra
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * Do we have an instance URI to share?
     *
     * @phpstan-assert-if-true non-empty-string $this->maybeGetInstanceAsString()
     */
    public function hasInstance(): bool
    {
        return $this->instance !== null;
    }

    /**
     * Return the `instance` URI as a string (if we have one).
     *
     * - absolute URI to a resource specific to this occurrence of the problem
     * - for example, if the user needs to perform a manual step to solve
     *   this problem, $instance could be a link to the web page for that
     *   action
     *
     * @return non-empty-string|null
     */
    public function maybeGetInstanceAsString(): ?string
    {
        return $this->instance;
    }

    // ================================================================
    //
    // When PHP's Uri extension is documented and supported by my
    // IDE, I will add:
    //
    // - `public function maybeGetInstance(): Uri`
    // - `public function getInstance(): Uri`
    //
    // ----------------------------------------------------------------

    /**
     * return the `status` of this problem
     *
     * - HTTP status code that describes the nature of this problem
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * return the `title` of this problem
     *
     * - short, human-readable summary of the problem type
     * - used as the exception message if `$detail` is empty
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * return the `type` of problem as a string
     *
     * - URI to a page that documents this class of problem
     * - for example, link to a Github wiki page explaining the error
     */
    public function getTypeAsString(): string
    {
        return $this->type;
    }

    // ================================================================
    //
    // When PHP's Uri extension is documented and supported by my
    // IDE, I will add:
    //
    // `public function getType(): Uri;`
    //
    // ----------------------------------------------------------------

    // ================================================================
    //
    // JsonSerializable
    //
    // ----------------------------------------------------------------

    /**
     * You can manually pass the results through `array_filter()` to
     * strip-out empty parts of the problem details.
     *
     * @return array{
     *     type: string,
     *     title: string,
     *     status: int,
     *     instance: string|null,
     *     detail: string|null,
     *     extra: ProblemReportExtra,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'type'     => $this->getTypeAsString(),
            'title'    => $this->getTitle(),
            'status'   => $this->getStatus(),
            'instance' => $this->maybeGetInstanceAsString(),
            'detail'   => $this->maybeGetDetail(),
            'extra'    => $this->getExtra(),
        ];
    }
}
