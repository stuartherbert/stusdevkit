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

use JsonSerializable;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\CollectionsKit\Lists\CollectionAsList;
use StusDevKit\ValidationKit\ValidationIssue;
use StusDevKit\ValidationKit\ValidationIssuesList;

/**
 * Contract + behaviour tests for ValidationIssuesList.
 *
 * ValidationIssuesList is the collection of ValidationIssue
 * objects accumulated during a parse. It extends
 * CollectionAsList (so it inherits add(), count(), first(),
 * maybeFirst(), and toArray()) and implements
 * JsonSerializable so that the list can be compared
 * directly in test assertions and embedded in JSON API
 * responses. Its jsonSerialize() output is a load-bearing
 * contract: the docblock pins each element to a
 * type/path/message shape, so we lock it down with a
 * literal expected array.
 */
#[TestDox(ValidationIssuesList::class)]
class ValidationIssuesListTest extends TestCase
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

        $actual = (new ReflectionClass(ValidationIssuesList::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('extends CollectionAsList so collection operations are inherited')]
    public function test_extends_collection_as_list(): void
    {
        // callers rely on inherited collection methods
        // (add(), first(), count(), toArray()); pinning the
        // parent class keeps those methods part of the
        // contract.
        $list = new ValidationIssuesList();

        $this->assertInstanceOf(CollectionAsList::class, $list);
    }

    #[TestDox('implements JsonSerializable so issues can be json_encoded directly')]
    public function test_implements_json_serializable(): void
    {
        // the jsonSerialize() output is part of the public
        // API shape; implementing the interface is what lets
        // json_encode() find it.
        $list = new ValidationIssuesList();

        $this->assertInstanceOf(JsonSerializable::class, $list);
    }

    // ================================================================
    //
    // Empty-collection shape
    //
    // ----------------------------------------------------------------

    #[TestDox('is empty when first constructed')]
    public function test_is_empty_when_constructed(): void
    {
        // the default-constructed collection has no items;
        // count() is the canonical way to ask.
        $list = new ValidationIssuesList();

        $this->assertCount(0, $list);
    }

    #[TestDox('->maybeFirst() returns null when the collection is empty')]
    public function test_maybeFirst_returns_null_when_empty(): void
    {
        // maybeFirst() is the non-throwing partner of first();
        // on an empty collection it returns null rather than
        // throwing, so callers can branch on presence.
        $list = new ValidationIssuesList();

        $this->assertNull($list->maybeFirst());
    }

    #[TestDox('->toArray() returns [] when the collection is empty')]
    public function test_toArray_returns_empty_array_when_empty(): void
    {
        // toArray() yields the underlying storage; an empty
        // collection yields the literal empty array.
        $list = new ValidationIssuesList();

        $this->assertSame([], $list->toArray());
    }

    #[TestDox('->jsonSerialize() returns [] when the collection is empty')]
    public function test_jsonSerialize_returns_empty_array_when_empty(): void
    {
        // the JSON shape for zero issues is a JSON array of
        // zero elements; PHP's empty array serialises to "[]"
        // (a list) via json_encode() because it is list-keyed.
        $list = new ValidationIssuesList();

        $this->assertSame([], $list->jsonSerialize());
    }

    // ================================================================
    //
    // add()/count()/first() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->add() appends an issue to the collection')]
    public function test_add_appends_issue(): void
    {
        // add() is the canonical mutator inherited from
        // CollectionAsList; after one call the collection
        // holds that one item.
        $issue = $this->makeIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            path: ['field'],
            message: 'oops',
        );

        $list = new ValidationIssuesList();
        $list->add($issue);

        $this->assertCount(1, $list);
    }

    #[TestDox('->first() returns the earliest issue added')]
    public function test_first_returns_earliest_issue(): void
    {
        // first() is the throwing accessor for the head of a
        // list; after two adds it returns the one added first.
        $first = $this->makeIssue(
            type: 'tag:stusdevkit,2026:test:a',
            path: ['a'],
            message: 'first',
        );
        $second = $this->makeIssue(
            type: 'tag:stusdevkit,2026:test:b',
            path: ['b'],
            message: 'second',
        );

        $list = new ValidationIssuesList();
        $list->add($first);
        $list->add($second);

        $this->assertSame($first, $list->first());
    }

    #[TestDox('->maybeFirst() returns the earliest issue added when the collection is populated')]
    public function test_maybeFirst_returns_earliest_issue_when_populated(): void
    {
        // maybeFirst() agrees with first() on a non-empty
        // collection; it only diverges on the empty case.
        $issue = $this->makeIssue(
            type: 'tag:stusdevkit,2026:test:generic',
            path: ['field'],
            message: 'oops',
        );

        $list = new ValidationIssuesList();
        $list->add($issue);

        $this->assertSame($issue, $list->maybeFirst());
    }

    #[TestDox('->count() returns the total number of issues added')]
    public function test_count_tracks_number_of_adds(): void
    {
        // count() is incremented once per add(); we add three
        // distinct issues so the final count is the literal 3.
        $list = new ValidationIssuesList();
        $list->add($this->makeIssue(
            type: 'tag:stusdevkit,2026:test:a',
            path: ['a'],
            message: 'a',
        ));
        $list->add($this->makeIssue(
            type: 'tag:stusdevkit,2026:test:b',
            path: ['b'],
            message: 'b',
        ));
        $list->add($this->makeIssue(
            type: 'tag:stusdevkit,2026:test:c',
            path: ['c'],
            message: 'c',
        ));

        $this->assertSame(3, $list->count());
    }

    // ================================================================
    //
    // jsonSerialize() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->jsonSerialize() reduces each issue to type, path, and message')]
    public function test_jsonSerialize_reduces_each_issue_to_documented_shape(): void
    {
        // the class docblock pins the element shape to
        // type/path/message; extra and title are intentionally
        // dropped here because they are transport-specific.
        $list = new ValidationIssuesList();
        $list->add(new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:too-small',
            input: 'ab',
            path: ['username'],
            message: 'String must be at least 3 characters',
            title: 'Too short',
            extra: ['minimum' => 3],
        ));

        $expected = [
            [
                'type'    => 'tag:stusdevkit,2026:test:too-small',
                'path'    => ['username'],
                'message' => 'String must be at least 3 characters',
            ],
        ];

        $this->assertSame($expected, $list->jsonSerialize());
    }

    #[TestDox('->jsonSerialize() returns a list with one element per issue in insertion order')]
    public function test_jsonSerialize_preserves_insertion_order(): void
    {
        // the result is a list (sequential integer keys), so
        // consumers can rely on the order the issues were
        // added when comparing against a literal expected
        // value.
        $list = new ValidationIssuesList();
        $list->add(new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:a',
            input: 1,
            path: ['a'],
            message: 'first',
        ));
        $list->add(new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:b',
            input: 2,
            path: ['b'],
            message: 'second',
        ));

        $expected = [
            [
                'type'    => 'tag:stusdevkit,2026:test:a',
                'path'    => ['a'],
                'message' => 'first',
            ],
            [
                'type'    => 'tag:stusdevkit,2026:test:b',
                'path'    => ['b'],
                'message' => 'second',
            ],
        ];

        $this->assertSame($expected, $list->jsonSerialize());
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * @param non-empty-string $type
     * @param list<string|int> $path
     * @param non-empty-string $message
     */
    private function makeIssue(
        string $type,
        array $path,
        string $message,
    ): ValidationIssue {
        // shorthand
        // the tests build several minimal issues whose only
        // varying fields are type, path, and message; all
        // other slots are irrelevant to list-level behaviour
        // so we park them in a helper.
        return new ValidationIssue(
            type: $type,
            input: null,
            path: $path,
            message: $message,
        );
    }
}
