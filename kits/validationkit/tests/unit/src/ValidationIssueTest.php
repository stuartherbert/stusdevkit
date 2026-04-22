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

namespace StusDevKit\ValidationKit\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * Contract + behaviour tests for ValidationIssue.
 *
 * ValidationIssue is the value object emitted by every
 * schema/constraint when something fails validation. It
 * carries the RFC 9457 `type` URI, the offending input, the
 * path from the root of the validated structure, a human
 * message, an optional title (defaulted when blank), and a
 * key/value `extra` bag. All fields are readonly, so the
 * only mutation surface is withPath(), which returns a copy
 * with the path replaced. pathAsString() renders the path
 * using dot + bracket notation so issues can be reported in
 * flat form.
 */
#[TestDox(ValidationIssue::class)]
class ValidationIssueTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit';

        $actual = (new ReflectionClass(ValidationIssue::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a final class')]
    public function test_is_final(): void
    {
        // ValidationIssue is a value object: callers compare
        // and serialise them, never extend them.
        $reflection = new ReflectionClass(ValidationIssue::class);

        $this->assertTrue($reflection->isFinal());
    }

    // ================================================================
    //
    // Constructor wiring
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() stores the type, input, path, and message')]
    public function test_constructor_stores_mandatory_fields(): void
    {
        // the four required constructor parameters map
        // one-to-one to readonly public fields; this test pins
        // that mapping so renames or reorderings show up loud.
        $issue = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:too-small',
            input: 'ab',
            path: ['username'],
            message: 'String must be at least 3 characters',
        );

        $this->assertSame(
            'tag:stusdevkit,2026:test:too-small',
            $issue->type,
        );
        $this->assertSame('ab', $issue->input);
        $this->assertSame(['username'], $issue->path);
        $this->assertSame(
            'String must be at least 3 characters',
            $issue->message,
        );
    }

    #[TestDox('::__construct() stores the extra context bag when supplied')]
    public function test_constructor_stores_extra_bag(): void
    {
        // the extra slot carries supporting context (minimum,
        // expected type, etc.) and is preserved exactly as
        // passed in.
        $issue = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:too-small',
            input: 'ab',
            path: ['username'],
            message: 'String must be at least 3 characters',
            extra: ['minimum' => 3],
        );

        $this->assertSame(['minimum' => 3], $issue->extra);
    }

    #[TestDox('::__construct() defaults the title to "Validation failed" when blank')]
    public function test_constructor_defaults_blank_title(): void
    {
        // leaving the title argument at its default empty
        // string triggers the fallback to "Validation failed",
        // so callers never have to know the default exists.
        $issue = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            input: null,
            path: [],
            message: 'something went wrong',
        );

        $this->assertSame('Validation failed', $issue->title);
    }

    #[TestDox('::__construct() preserves a custom non-empty title')]
    public function test_constructor_preserves_custom_title(): void
    {
        // a caller-supplied title overrides the default; the
        // fallback kicks in only for the empty-string case.
        $issue = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            input: null,
            path: [],
            message: 'something went wrong',
            title: 'Too short',
        );

        $this->assertSame('Too short', $issue->title);
    }

    #[TestDox('::__construct() defaults the extra bag to an empty array')]
    public function test_constructor_defaults_extra_to_empty_array(): void
    {
        // when callers do not supply extra context, the field
        // still holds an array so downstream code can iterate
        // over it without null checks.
        $issue = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            input: null,
            path: [],
            message: 'something went wrong',
        );

        $this->assertSame([], $issue->extra);
    }

    // ================================================================
    //
    // withPath() copying
    //
    // ----------------------------------------------------------------

    #[TestDox('->withPath() returns a new ValidationIssue with the given path')]
    public function test_withPath_returns_new_issue_with_given_path(): void
    {
        // withPath() is used when a callback returns an issue
        // without path context and the caller needs to stamp
        // the current path onto it.
        $original = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            input: 'x',
            path: [],
            message: 'oops',
        );

        $updated = $original->withPath(['items', 0, 'name']);

        $this->assertSame(['items', 0, 'name'], $updated->path);
    }

    #[TestDox('->withPath() preserves type, input, message, title, and extra')]
    public function test_withPath_preserves_other_fields(): void
    {
        // the only field that changes is `path`; everything
        // else must survive the copy unchanged so the issue
        // still identifies the same failure.
        $original = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:too-small',
            input: 'ab',
            path: [],
            message: 'String must be at least 3 characters',
            title: 'Too short',
            extra: ['minimum' => 3],
        );

        $updated = $original->withPath(['username']);

        $this->assertSame($original->type, $updated->type);
        $this->assertSame($original->input, $updated->input);
        $this->assertSame($original->message, $updated->message);
        $this->assertSame($original->title, $updated->title);
        $this->assertSame($original->extra, $updated->extra);
    }

    #[TestDox('->withPath() returns a different instance without mutating the original')]
    public function test_withPath_does_not_mutate_original(): void
    {
        // correctness!
        // all fields are readonly, so the copy must be a new
        // instance; if withPath() ever returned $this by
        // accident it would break that contract.
        $original = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            input: 'x',
            path: [],
            message: 'oops',
        );

        $updated = $original->withPath(['field']);

        $this->assertNotSame($original, $updated);
        $this->assertSame([], $original->path);
    }

    // ================================================================
    //
    // pathAsString() formatting
    //
    // ----------------------------------------------------------------

    /**
     * @return list<array{string, list<string|int>, string}>
     */
    public static function providePathFormats(): array
    {
        // one row per shape of path we advertise in the
        // pathAsString() docblock; each expected string is a
        // literal.
        return [
            ['empty path', [], ''],
            [
                'single string segment',
                ['username'],
                'username',
            ],
            [
                'two string segments separated by a dot',
                ['address', 'zip'],
                'address.zip',
            ],
            [
                'string followed by int index in brackets',
                ['items', 0],
                'items[0]',
            ],
            [
                'string, int, string combined',
                ['items', 0, 'name'],
                'items[0].name',
            ],
            [
                'leading int index in brackets',
                [0, 'name'],
                '[0].name',
            ],
            [
                'two consecutive int indexes',
                ['matrix', 1, 2],
                'matrix[1][2]',
            ],
        ];
    }

    /**
     * @param list<string|int> $path
     */
    #[DataProvider('providePathFormats')]
    #[TestDox('->pathAsString() formats $label as "$expected"')]
    public function test_pathAsString_formats_path(
        string $label,
        array $path,
        string $expected,
    ): void {
        // the label is only present so the TestDox output
        // reads as a sentence; the real assertion is the
        // literal expected string.
        $issue = new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            input: null,
            path: $path,
            message: 'oops',
        );

        $this->assertSame($expected, $issue->pathAsString());
    }
}
