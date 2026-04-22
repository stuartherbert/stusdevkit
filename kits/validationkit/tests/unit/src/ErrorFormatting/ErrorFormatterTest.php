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

namespace StusDevKit\ValidationKit\Tests\Unit\ErrorFormatting;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\ValidationKit\ErrorFormatting\ErrorFormatter;
use StusDevKit\ValidationKit\ErrorFormatting\FlatError;
use StusDevKit\ValidationKit\ErrorFormatting\TreeError;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\ValidationIssue;
use StusDevKit\ValidationKit\ValidationIssuesList;

/**
 * Contract + behaviour tests for ErrorFormatter.
 *
 * ErrorFormatter is a pure-static utility: the class itself
 * cannot be instantiated (private constructor). Its three
 * formatters (`flatten`, `treeify`, `prettify`) each take a
 * ValidationException and return a different shape suited
 * to a different consumer (form/API handlers, data-shape
 * mirrors, human-readable logs). Tests pin the class shape,
 * enforce non-instantiability, and verify each formatter's
 * output against pinned literals.
 */
#[TestDox(ErrorFormatter::class)]
class ErrorFormatterTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\ErrorFormatting namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\ErrorFormatting';

        $actual = (new ReflectionClass(ErrorFormatter::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a final class')]
    public function test_is_declared_final(): void
    {
        // `final` is part of the utility-class contract -
        // subclassing would only allow overriding the three
        // formatters, and the formatters are designed as
        // stable factory-style helpers. Extending is never
        // the right answer here.

        $reflection = new ReflectionClass(ErrorFormatter::class);

        $actual = $reflection->isFinal()
            && (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('cannot be instantiated')]
    public function test_constructor_is_private(): void
    {
        // the constructor is explicitly private because this
        // class exists only for its static methods. A rogue
        // `new ErrorFormatter()` would suggest the caller
        // had misread the API; pinning the private
        // constructor keeps the static-only contract
        // enforceable.

        $ctor = (new ReflectionClass(ErrorFormatter::class))
            ->getConstructor();

        $this->assertNotNull($ctor);
        $this->assertTrue($ctor->isPrivate());
    }

    #[TestDox('declares exactly flatten, prettify, and treeify as its static methods')]
    public function test_declares_expected_static_methods(): void
    {
        // the three-way split is deliberate: flatten for
        // form/API handlers, treeify for data-shape mirrors,
        // prettify for logs. Adding a fourth variant should
        // be a considered decision that updates this list.

        $expected = ['flatten', 'prettify', 'treeify'];
        $reflection = new ReflectionClass(ErrorFormatter::class);

        $staticMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->isStatic()) {
                $staticMethods[] = $m->getName();
            }
        }
        sort($staticMethods);

        $this->assertSame($expected, $staticMethods);
    }

    // ================================================================
    //
    // ::flatten() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::flatten() returns a FlatError with empty arrays when the exception carries no issues')]
    public function test_flatten_empty_issues(): void
    {
        $exception = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = ErrorFormatter::flatten($exception);

        $this->assertInstanceOf(FlatError::class, $actual);
        $this->assertSame([], $actual->getRootErrors());
        $this->assertSame([], $actual->getFieldErrors());
    }

    #[TestDox('::flatten() routes root-level issues (path = []) into getRootErrors()')]
    public function test_flatten_routes_root_issues_to_root_errors(): void
    {
        // issues without a path represent failures that
        // apply to the whole payload rather than a specific
        // field. They must end up in the rootErrors bucket
        // so form handlers can render them as a top-level
        // banner instead of attaching them to a field that
        // never existed.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: [],
                message: 'Payload must be an object',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $actual = ErrorFormatter::flatten($exception);

        $this->assertSame(
            ['Payload must be an object'],
            $actual->getRootErrors(),
        );
        $this->assertSame([], $actual->getFieldErrors());
    }

    #[TestDox('::flatten() groups issues with the same path under one bucket, in insertion order')]
    public function test_flatten_groups_field_issues_by_path(): void
    {
        // multiple failures against the same field must
        // collect into the same bucket so the form handler
        // can render all of them next to the input. The
        // order of messages within a bucket mirrors the
        // order of validation emission, so users see the
        // most fundamental failure first.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'Too short',
            ),
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_format',
                input: 'ab',
                path: ['username'],
                message: 'Not alphanumeric',
            ),
            new ValidationIssue(
                type: 'https://example.com/errors/required',
                input: null,
                path: ['email'],
                message: 'Required',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $actual = ErrorFormatter::flatten($exception);

        $this->assertSame([], $actual->getRootErrors());
        $this->assertSame(
            [
                'username' => ['Too short', 'Not alphanumeric'],
                'email'    => ['Required'],
            ],
            $actual->getFieldErrors(),
        );
    }

    // ================================================================
    //
    // ::treeify() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::treeify() returns an empty TreeError when the exception carries no issues')]
    public function test_treeify_empty_issues(): void
    {
        $exception = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = ErrorFormatter::treeify($exception);

        $this->assertInstanceOf(TreeError::class, $actual);
        $this->assertSame([], $actual->getErrors());
        $this->assertSame([], $actual->getChildren());
    }

    #[TestDox('::treeify() places root-level messages on the root node')]
    public function test_treeify_routes_root_issues_to_root_node(): void
    {
        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: [],
                message: 'Payload must be an object',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $actual = ErrorFormatter::treeify($exception);

        $this->assertSame(
            ['Payload must be an object'],
            $actual->getErrors(),
        );
        $this->assertSame([], $actual->getChildren());
    }

    #[TestDox('::treeify() nests issues under their first path segment, recursing into deeper paths')]
    public function test_treeify_nests_deep_paths(): void
    {
        // a path of ['address', 'zip'] must produce a root
        // node with an 'address' child, and that child's
        // 'zip' child must carry the message. The recursion
        // mirrors the shape of the input data so a UI
        // component for the address block can be passed
        // just the 'address' subtree.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_zip',
                input: 'XYZ',
                path: ['address', 'zip'],
                message: 'Invalid ZIP',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $tree = ErrorFormatter::treeify($exception);

        $address = $tree->maybeGetChild('address');
        $this->assertNotNull($address);
        $zip = $address->maybeGetChild('zip');
        $this->assertNotNull($zip);

        $this->assertSame(['Invalid ZIP'], $zip->getErrors());
        $this->assertSame([], $tree->getErrors());
        $this->assertSame([], $address->getErrors());
    }

    #[TestDox('::treeify() uses integer keys for numeric path segments')]
    public function test_treeify_preserves_int_indexes(): void
    {
        // array positions survive the transform as integer
        // keys - this matters for tuple-schema failures
        // where the consumer expects the UI to be driven
        // by numeric indices, not stringified numbers.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: ['items', 0],
                message: 'Expected int',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $tree = ErrorFormatter::treeify($exception);

        $items = $tree->maybeGetChild('items');
        $this->assertNotNull($items);
        $this->assertArrayHasKey(0, $items->getChildren());

        $zero = $items->maybeGetChild(0);
        $this->assertNotNull($zero);
        $this->assertSame(['Expected int'], $zero->getErrors());
    }

    // ================================================================
    //
    // ::prettify() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::prettify() returns a single-issue header and a path-prefixed line for one issue')]
    public function test_prettify_single_issue_with_path(): void
    {
        // the header is singular/plural matched ("1
        // validation issue" vs "N validation issues") so
        // that the output reads naturally in logs.
        // Path-prefixed lines use the "<path>: <message>"
        // format familiar from IDE diagnostics.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'Too short',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $actual = ErrorFormatter::prettify($exception);

        $this->assertSame(
            "1 validation issue\n  \u{2717} username: Too short",
            $actual,
        );
    }

    #[TestDox('::prettify() drops the path prefix for root-level issues')]
    public function test_prettify_single_root_issue(): void
    {
        // root-level issues have no path, so the "<path>: "
        // prefix would be an empty colon. prettify() skips
        // the prefix entirely in that case.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: [],
                message: 'Payload must be an object',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $actual = ErrorFormatter::prettify($exception);

        $this->assertSame(
            "1 validation issue\n  \u{2717} Payload must be an object",
            $actual,
        );
    }

    #[TestDox('::prettify() uses the plural header when multiple issues are present and lists each')]
    public function test_prettify_multiple_issues(): void
    {
        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'Too short',
            ),
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: ['age'],
                message: 'Expected int',
            ),
        ]);
        $exception = new ValidationException(issues: $issues);

        $actual = ErrorFormatter::prettify($exception);

        $this->assertSame(
            "2 validation issues\n"
                . "  \u{2717} username: Too short\n"
                . "  \u{2717} age: Expected int",
            $actual,
        );
    }
}
