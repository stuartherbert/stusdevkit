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

namespace StusDevKit\DateTimeKit\Formatters;

use NoDiscard;
use StusDevKit\DateTimeKit\When;

/**
 * Formats a `When` for use in filesystem paths and filenames.
 *
 * All formats sort naturally in `ls` output and in most
 * filesystem browsers.
 *
 * Usage:
 *
 *     $when->asFormat()->filesystem()->yearMonth();
 *     $when->asFormat()->filesystem()->date();
 *     $when->asFormat()->filesystem()->dateTime();
 *     $when->asFormat()->filesystem()->dateTimeAndMilliseconds();
 */
class WhenFilesystemFormatter implements WhenGroupFormatterInterface
{
    public function __construct(
        private readonly When $when,
    ) {
    }

    /**
     * Returns year,month in `YYYY-MM` format.
     *
     * Useful for creating per-month directories on your
     * filesystem.
     */
    #[NoDiscard]
    public function yearMonth(): string
    {
        return $this->when->format('Y-m');
    }

    /**
     * Returns year/month/day in `YYYY-MM-DD` format.
     *
     * Useful for creating per-day directories on your
     * filesystem.
     */
    #[NoDiscard]
    public function date(): string
    {
        return $this->when->format('Y-m-d');
    }

    /**
     * Returns year,month,day,hours,minutes,seconds in
     * `YYYYMMDD-HHMMSS` format.
     *
     * Useful for creating filenames on your filesystem.
     */
    #[NoDiscard]
    public function dateTime(): string
    {
        return $this->when->format('Ymd-His');
    }

    /**
     * Returns year,month,day,hours,minutes,seconds,milliseconds
     * in `YYYYMMDD-HHMMSS-MS` format.
     *
     * Useful for creating filenames on your filesystem.
     */
    #[NoDiscard]
    public function dateTimeAndMilliseconds(): string
    {
        return $this->when->format('Ymd-His-v');
    }
}
