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

/**
 * IssueCode categorises the kind of validation failure
 * that occurred.
 *
 * Each code maps to a specific class of validation error,
 * following the same taxonomy as Zod's ZodIssueCode.
 *
 * Usage:
 *
 *     $issue = new ValidationIssue(
 *         code: IssueCode::InvalidType,
 *         input: $data,
 *         path: ['address', 'zip'],
 *         message: 'Expected string, received int',
 *     );
 */
enum IssueCode: string
{
    // ================================================================
    //
    // Type Errors
    //
    // ----------------------------------------------------------------

    /** the input is not the expected type */
    case InvalidType = 'invalid_type';

    // ================================================================
    //
    // Size / Length / Range Errors
    //
    // ----------------------------------------------------------------

    /** the input is below the minimum allowed value or length */
    case TooSmall = 'too_small';

    /** the input exceeds the maximum allowed value or length */
    case TooBig = 'too_big';

    /** the input is not a multiple of the required value */
    case NotMultipleOf = 'not_multiple_of';

    /** the input is not a finite number */
    case NotFinite = 'not_finite';

    // ================================================================
    //
    // String Validation Errors
    //
    // ----------------------------------------------------------------

    /**
     * the input string does not match the required format
     *
     * Used for: email, url, uuid, regex, ipv4, ipv6, includes,
     * startsWith, endsWith
     */
    case InvalidString = 'invalid_string';

    // ================================================================
    //
    // Enum / Literal Errors
    //
    // ----------------------------------------------------------------

    /** the input does not match any allowed enum value */
    case InvalidEnum = 'invalid_enum';

    /** the input does not match the expected literal value */
    case InvalidLiteral = 'invalid_literal';

    // ================================================================
    //
    // Composite Schema Errors
    //
    // ----------------------------------------------------------------

    /** the input does not match any schema in a union */
    case InvalidUnion = 'invalid_union';

    /**
     * the input does not satisfy all schemas in an
     * intersection
     */
    case InvalidIntersection = 'invalid_intersection';

    /**
     * the input object contains keys not defined
     * in the schema
     */
    case UnrecognizedKeys = 'unrecognized_keys';

    // ================================================================
    //
    // Date Errors
    //
    // ----------------------------------------------------------------

    /** the input is not a valid date */
    case InvalidDate = 'invalid_date';

    // ================================================================
    //
    // Custom Validation Errors
    //
    // ----------------------------------------------------------------

    /**
     * a custom validation rule (refine/superRefine) failed
     */
    case Custom = 'custom';

    // ================================================================
    //
    // Default RFC 9457 Fields
    //
    // ----------------------------------------------------------------

    /**
     * return the default documentation URI for this issue
     * code
     *
     * Used as the `type` field in ValidationIssue when no
     * custom type is provided. Follows RFC 9457 conventions.
     *
     * @return non-empty-string
     */
    public function defaultType(): string
    {
        return 'https://stusdevkit.dev/errors/validation/'
            . $this->value;
    }

    /**
     * return the default human-readable title for this
     * issue code
     *
     * Used as the `title` field in ValidationIssue when no
     * custom title is provided. Follows RFC 9457 conventions.
     *
     * @return non-empty-string
     */
    public function defaultTitle(): string
    {
        return match ($this) {
            self::InvalidType => 'Invalid type',
            self::TooSmall => 'Value too small',
            self::TooBig => 'Value too big',
            self::NotMultipleOf => 'Not a multiple',
            self::NotFinite => 'Not finite',
            self::InvalidString => 'Invalid string format',
            self::InvalidEnum => 'Invalid enum value',
            self::InvalidLiteral => 'Invalid literal value',
            self::InvalidUnion => 'No matching union member',
            self::InvalidIntersection => 'Intersection mismatch',
            self::UnrecognizedKeys => 'Unrecognized keys',
            self::InvalidDate => 'Invalid date',
            self::Custom => 'Validation failed',
        };
    }
}
