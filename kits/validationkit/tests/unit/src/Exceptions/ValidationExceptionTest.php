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

namespace StusDevKit\ValidationKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\ValidationIssue;
use StusDevKit\ValidationKit\ValidationIssuesList;

/**
 * Contract + behaviour tests for ValidationException.
 *
 * ValidationException is the default throwable emitted when
 * schema validation fails and no custom error callback has
 * been provided. It is an RFC 9457 problem-details exception
 * with fixed `type`, `status`, and `title`; the per-throw
 * payload is the ValidationIssuesList, serialised into the
 * `extra` slot under issue_N keys whose leaves are string
 * scalars (because ProblemReportExtra only allows int|string
 * at leaf level). The `detail` slot is synthesised from the
 * issues list so that getMessage() returns a useful summary.
 */
#[TestDox(ValidationException::class)]
class ValidationExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\Exceptions';

        $actual = (new ReflectionClass(
            ValidationException::class,
        ))->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        $reflection = new ReflectionClass(ValidationException::class);

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('extends Rfc9457ProblemDetailsException')]
    public function test_extends_Rfc9457ProblemDetailsException(): void
    {
        // the parent gives this exception its RFC 9457 wire
        // format - JsonSerializable output, the
        // type/status/title/extra/instance/detail slots, and
        // accompanying accessors. Swapping the parent would
        // silently remove the problem-details contract that
        // HTTP error handlers depend on.

        $reflection = new ReflectionClass(ValidationException::class);

        $actual = $reflection->getParentClass();

        $this->assertNotFalse($actual);
        $this->assertSame(
            Rfc9457ProblemDetailsException::class,
            $actual->getName(),
        );
    }

    #[TestDox('declares exactly __construct and issues as its own public methods')]
    public function test_declares_expected_own_public_methods(): void
    {
        // issues() is the named accessor for the carried
        // ValidationIssuesList - pinning the set as a
        // literal list catches any silent surface-area
        // expansion. Callers that want the wire-format
        // payload use getExtra() inherited from the parent.

        $expected = ['__construct', 'issues'];
        $reflection = new ReflectionClass(ValidationException::class);

        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName()
                === ValidationException::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame($expected, $ownMethods);
    }

    // ================================================================
    //
    // ::__construct() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() is public')]
    public function test_construct_is_public(): void
    {
        $method = (new ReflectionClass(
            ValidationException::class,
        ))->getMethod('__construct');

        $this->assertTrue($method->isPublic());
    }

    #[TestDox('::__construct() declares $issues as its only parameter')]
    public function test_construct_declares_issues_as_only_parameter(): void
    {
        // the constructor narrows the parent's wider parameter
        // set down to just `$issues` - every other
        // problem-details field is pinned to a fixed literal
        // value. Adding parameters is a breaking change for
        // every throw-site.

        $expected = ['issues'];
        $method = (new ReflectionClass(
            ValidationException::class,
        ))->getMethod('__construct');

        $actual = array_map(
            static fn ($p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() declares $issues as ValidationIssuesList')]
    public function test_construct_declares_issues_as_ValidationIssuesList(): void
    {
        $expected = ValidationIssuesList::class;
        $param = (new ReflectionClass(
            ValidationException::class,
        ))->getMethod('__construct')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        $actual = $paramType->getName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->issues() declares return type ValidationIssuesList')]
    public function test_issues_return_type(): void
    {
        $method = new ReflectionMethod(
            ValidationException::class,
            'issues',
        );
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        $this->assertSame(
            ValidationIssuesList::class,
            $type->getName(),
        );
    }

    // ================================================================
    //
    // Pinned RFC 9457 fields
    //
    // ----------------------------------------------------------------

    #[TestDox('->getTypeAsString() returns the fixed tag URI')]
    public function test_getTypeAsString_returns_the_fixed_uri(): void
    {
        // the type URI is a tag: URI (RFC 4151) rather than
        // an HTTP URL because no live documentation page is
        // promised - the tag is a stable opaque identifier.
        // Pinning the literal guards against typos in the
        // tag value.

        $unit = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = $unit->getTypeAsString();

        $this->assertSame(
            'tag:stusdevkit,2026:validation-failed',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 422')]
    public function test_getStatus_returns_422(): void
    {
        // 422 Unprocessable Content is the RFC-correct status
        // for a payload whose syntax was valid but whose
        // semantics failed validation.

        $unit = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = $unit->getStatus();

        $this->assertSame(422, $actual);
    }

    #[TestDox('->getTitle() returns the fixed title')]
    public function test_getTitle_returns_the_fixed_title(): void
    {
        // the title is a short, stable, human-readable
        // summary - pinning the literal keeps consumer log
        // aggregation consistent.

        $unit = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = $unit->getTitle();

        $this->assertSame('Validation failed', $actual);
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        $unit = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = $unit->hasInstance();

        $this->assertFalse($actual);
    }

    // ================================================================
    //
    // ->issues() accessor
    //
    // ----------------------------------------------------------------

    #[TestDox('->issues() returns the list passed into the constructor')]
    public function test_issues_returns_the_constructor_list(): void
    {
        // the accessor is a pure getter - the exception does
        // not clone or filter the list, so callers receive
        // the exact instance they supplied.

        $expected = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'String must be at least 3 characters',
            ),
        ]);

        $unit = new ValidationException(issues: $expected);

        $actual = $unit->issues();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->maybeGetDetail() synthesis
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGetDetail() returns "Validation failed" when the issues list is empty')]
    public function test_maybeGetDetail_empty_list(): void
    {
        // an empty list still yields a non-null detail so
        // that getMessage() never falls back to the parent's
        // title-only path. The literal is pinned because log
        // searches anchor on it.

        $unit = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = $unit->maybeGetDetail();

        $this->assertSame('Validation failed', $actual);
    }

    #[TestDox('->maybeGetDetail() embeds path and message when exactly one issue is present')]
    public function test_maybeGetDetail_single_issue_with_path(): void
    {
        // a single issue with a non-empty path is summarised
        // as "<path>: <message>" so the developer sees the
        // offending location on the first line of the log.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'String must be at least 3 characters',
            ),
        ]);
        $unit = new ValidationException(issues: $issues);

        $actual = $unit->maybeGetDetail();

        $this->assertSame(
            'username: String must be at least 3 characters',
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns just the message when the single issue has no path')]
    public function test_maybeGetDetail_single_issue_without_path(): void
    {
        // when the sole issue is root-level, the "<path>: "
        // prefix would be an empty string followed by a
        // colon, which is ugly. The summary drops the
        // prefix entirely in that case.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/required',
                input: null,
                path: [],
                message: 'Payload must be an object',
            ),
        ]);
        $unit = new ValidationException(issues: $issues);

        $actual = $unit->maybeGetDetail();

        $this->assertSame('Payload must be an object', $actual);
    }

    #[TestDox('->maybeGetDetail() reports a count summary when multiple issues are present')]
    public function test_maybeGetDetail_multiple_issues(): void
    {
        // with more than one issue, trying to print each in
        // the detail slot would explode. Instead we report
        // the count and leave the per-issue detail to
        // `extra`.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'String must be at least 3 characters',
            ),
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: ['age'],
                message: 'Expected int, received string',
            ),
        ]);
        $unit = new ValidationException(issues: $issues);

        $actual = $unit->maybeGetDetail();

        $this->assertSame('2 validation issues found', $actual);
    }

    // ================================================================
    //
    // ->getExtra() serialisation
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasExtra() returns false when the issues list is empty')]
    public function test_hasExtra_empty_list(): void
    {
        // an empty issues list produces an empty extra
        // array, which hasExtra() reports as absent. This is
        // consistent with the rest of the RFC 9457 contract:
        // extra only becomes populated once there is
        // something to report.

        $unit = new ValidationException(
            issues: new ValidationIssuesList([]),
        );

        $actual = $unit->hasExtra();

        $this->assertFalse($actual);
    }

    #[TestDox('->getExtra() serialises each issue under an issue_N key')]
    public function test_getExtra_serialises_each_issue(): void
    {
        // the `extra` slot uses issue_N keys because
        // ProblemReportExtra only permits string keys at the
        // top level, and numeric keys would violate the
        // array-shape contract. Each issue is flattened to
        // a scalar-string payload (type, title, path,
        // message) because leaves must be int|string.
        // Pinning the exact shape here guarantees the wire
        // format is stable for API consumers.

        $issues = new ValidationIssuesList([
            new ValidationIssue(
                type: 'https://example.com/errors/too_short',
                input: 'ab',
                path: ['username'],
                message: 'String must be at least 3 characters',
            ),
            new ValidationIssue(
                type: 'https://example.com/errors/invalid_type',
                input: 'abc',
                path: ['age'],
                message: 'Expected int, received string',
            ),
        ]);

        $unit = new ValidationException(issues: $issues);

        $actual = $unit->getExtra();

        $this->assertSame(
            [
                'issue_0' => [
                    'type'    => 'https://example.com/errors/too_short',
                    'title'   => 'Validation failed',
                    'path'    => 'username',
                    'message' => 'String must be at least 3 characters',
                ],
                'issue_1' => [
                    'type'    => 'https://example.com/errors/invalid_type',
                    'title'   => 'Validation failed',
                    'path'    => 'age',
                    'message' => 'Expected int, received string',
                ],
            ],
            $actual,
        );
    }

    // ================================================================
    //
    // Throwability
    //
    // ----------------------------------------------------------------

    #[TestDox('instances are throwable as Rfc9457ProblemDetailsException')]
    public function test_instances_are_throwable_as_parent(): void
    {
        $this->expectException(
            Rfc9457ProblemDetailsException::class,
        );

        throw new ValidationException(
            issues: new ValidationIssuesList([]),
        );
    }
}
