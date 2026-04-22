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

namespace StusDevKit\ValidationKit\Tests\Unit\Internals;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;
use StusDevKit\ValidationKit\ValidationIssuesList;

/**
 * Contract + behaviour tests for ValidationContext.
 *
 * ValidationContext is the internal accumulator threaded
 * through the validation pipeline. It tracks three things:
 * the current path through the data structure, the issues
 * collected so far, and the keys/indices evaluated at the
 * current nesting level (for JSON Schema's unevaluated*
 * keywords). Tests pin the class shape and verify each of
 * those three state machines independently, including the
 * non-obvious behaviour that `atPath()` shares issues with
 * the parent but not evaluatedKeys.
 */
#[TestDox(ValidationContext::class)]
class ValidationContextTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Internals namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\Internals';

        $actual = (new ReflectionClass(ValidationContext::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a final class')]
    public function test_is_declared_final(): void
    {
        // the class is final and marked @internal - callers
        // outside the library must not subclass or depend
        // on it. Pinning `final` keeps that boundary
        // enforceable at the language level.

        $reflection = new ReflectionClass(ValidationContext::class);

        $actual = $reflection->isFinal()
            && (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('declares the expected internal public method set')]
    public function test_declares_expected_public_methods(): void
    {
        // the method set is pinned by enumeration. Because
        // this class is @internal, changes are allowed
        // without a major version bump, but the test still
        // locks the set so changes happen deliberately
        // rather than by accident.

        $expected = [
            '__construct',
            'addExistingIssue',
            'addIssue',
            'atPath',
            'evaluatedKeys',
            'hasIssues',
            'isEvaluated',
            'issues',
            'markEvaluated',
            'mergeEvaluatedKeys',
            'path',
        ];
        $reflection = new ReflectionClass(ValidationContext::class);

        $methodNames = array_values(array_map(
            static fn ($m) => $m->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        ));
        sort($methodNames);

        $this->assertSame($expected, $methodNames);
    }

    // ================================================================
    //
    // Path management
    //
    // ----------------------------------------------------------------

    #[TestDox('->path() defaults to [] for a context constructed with no arguments')]
    public function test_path_defaults_to_empty(): void
    {
        $unit = new ValidationContext();

        $actual = $unit->path();

        $this->assertSame([], $actual);
    }

    #[TestDox('->path() returns the path passed into the constructor')]
    public function test_path_returns_constructor_input(): void
    {
        $expected = ['address', 'zip'];

        $unit = new ValidationContext(path: $expected);

        $actual = $unit->path();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->atPath() appends a string segment and returns a new context')]
    public function test_atPath_appends_string_segment(): void
    {
        // atPath() is how collection schemas descend into
        // their children. The returned context is a new
        // instance so the parent's path is not mutated -
        // callers can fork multiple children from the same
        // parent without interfering.

        $parent = new ValidationContext(path: ['address']);

        $child = $parent->atPath('zip');

        $this->assertNotSame($parent, $child);
        $this->assertSame(['address'], $parent->path());
        $this->assertSame(['address', 'zip'], $child->path());
    }

    #[TestDox('->atPath() appends an integer segment when descending into an array index')]
    public function test_atPath_appends_int_segment(): void
    {
        // integer segments represent array indices and
        // must survive as integers (not stringified
        // numbers) so that the downstream consumer can
        // distinguish `["items", 0]` from `["items", "0"]`.

        $parent = new ValidationContext(path: ['items']);

        $child = $parent->atPath(0);

        $this->assertSame(['items', 0], $child->path());
    }

    // ================================================================
    //
    // Issue management
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasIssues() returns false on a fresh context')]
    public function test_hasIssues_false_when_new(): void
    {
        $unit = new ValidationContext();

        $actual = $unit->hasIssues();

        $this->assertFalse($actual);
    }

    #[TestDox('->issues() returns an empty ValidationIssuesList on a fresh context')]
    public function test_issues_empty_when_new(): void
    {
        $unit = new ValidationContext();

        $actual = $unit->issues();

        $this->assertInstanceOf(
            ValidationIssuesList::class,
            $actual,
        );
        $this->assertCount(0, $actual);
    }

    #[TestDox('->addIssue() records an issue at the context\'s current path')]
    public function test_addIssue_records_issue_at_current_path(): void
    {
        // the addIssue() helper picks up the path from the
        // context, so schemas do not have to pass the path
        // explicitly. This keeps child schemas decoupled
        // from the containing data structure.

        $unit = new ValidationContext(path: ['username']);

        $unit->addIssue(
            type: 'https://example.com/errors/too_short',
            input: 'ab',
            message: 'Too short',
            extra: ['minimum' => 3],
        );

        $this->assertTrue($unit->hasIssues());

        $issues = $unit->issues();
        $this->assertCount(1, $issues);

        $issue = $issues->first();
        $this->assertSame(
            'https://example.com/errors/too_short',
            $issue->type,
        );
        $this->assertSame('ab', $issue->input);
        $this->assertSame(['username'], $issue->path);
        $this->assertSame('Too short', $issue->message);
        $this->assertSame(['minimum' => 3], $issue->extra);
    }

    #[TestDox('->addExistingIssue() appends a pre-built ValidationIssue verbatim')]
    public function test_addExistingIssue_appends_verbatim(): void
    {
        // callbacks that produce ValidationIssue instances
        // directly (for example, custom refine callbacks)
        // bypass the path-filling helper and go in as-is.
        // The context stores the exact reference so no
        // information is lost in translation.

        $existing = new ValidationIssue(
            type: 'https://example.com/errors/custom',
            input: 42,
            path: ['root', 'nested'],
            message: 'Custom failure',
        );

        $unit = new ValidationContext();

        $unit->addExistingIssue($existing);

        $this->assertTrue($unit->hasIssues());
        $this->assertSame($existing, $unit->issues()->first());
    }

    #[TestDox('->atPath() shares the issues array with its parent context')]
    public function test_atPath_shares_issues_with_parent(): void
    {
        // the child binds its $issues property by reference
        // to the parent's, so every issue raised against a
        // nested path bubbles up to a single collection.
        // This is what lets `$rootContext->issues()` at the
        // end of validation return every failure no matter
        // how deep it occurred.

        $parent = new ValidationContext();
        $child = $parent->atPath('address');

        $child->addIssue(
            type: 'https://example.com/errors/too_short',
            input: 'ab',
            message: 'Too short',
        );

        $this->assertTrue($parent->hasIssues());
        $this->assertCount(1, $parent->issues());
        $this->assertSame(
            ['address'],
            $parent->issues()->first()->path,
        );
    }

    // ================================================================
    //
    // Evaluation tracking
    //
    // ----------------------------------------------------------------

    #[TestDox('->isEvaluated() returns false for an unmarked key')]
    public function test_isEvaluated_false_when_unmarked(): void
    {
        $unit = new ValidationContext();

        $actual = $unit->isEvaluated('username');

        $this->assertFalse($actual);
    }

    #[TestDox('->markEvaluated() records a string key that ->isEvaluated() then reports as true')]
    public function test_markEvaluated_string_key(): void
    {
        // evaluation tracking underpins JSON Schema's
        // unevaluatedProperties/unevaluatedItems semantics:
        // once a sub-schema has validated a key, the
        // containing schema must know not to flag it as
        // unevaluated. Pinning the mark/read round-trip
        // here is the foundation for that feature.

        $unit = new ValidationContext();

        $unit->markEvaluated('username');

        $this->assertTrue($unit->isEvaluated('username'));
        $this->assertFalse($unit->isEvaluated('email'));
    }

    #[TestDox('->markEvaluated() records integer indices distinctly from their string equivalents')]
    public function test_markEvaluated_int_key(): void
    {
        // array indices and string keys must not alias.
        // Marking index 0 must not cause isEvaluated("0")
        // to fire, because the caller needs to distinguish
        // tuple positions from object property names.

        $unit = new ValidationContext();

        $unit->markEvaluated(0);

        $this->assertTrue($unit->isEvaluated(0));
    }

    #[TestDox('->evaluatedKeys() returns the list of keys marked at the current level')]
    public function test_evaluatedKeys_returns_marked_set(): void
    {
        $unit = new ValidationContext();

        $unit->markEvaluated('username');
        $unit->markEvaluated('email');

        $actual = $unit->evaluatedKeys();

        $this->assertSame(['username', 'email'], $actual);
    }

    #[TestDox('->evaluatedKeys() dedupes when the same key is marked twice')]
    public function test_evaluatedKeys_dedupes_repeats(): void
    {
        // the implementation uses an array<key, true> map,
        // so marking the same key twice is idempotent -
        // the key appears once in the returned list. Two
        // different sub-schemas both validating the same
        // property must not double-count it.

        $unit = new ValidationContext();

        $unit->markEvaluated('username');
        $unit->markEvaluated('username');

        $this->assertSame(['username'], $unit->evaluatedKeys());
    }

    #[TestDox('->atPath() does NOT share evaluatedKeys with its parent context')]
    public function test_atPath_does_not_share_evaluatedKeys(): void
    {
        // evaluation tracking is per-level, not
        // per-path-depth - each nesting level independently
        // decides which of its own keys/indices have been
        // evaluated. The child shares issues with the
        // parent but keeps its own evaluatedKeys table, so
        // marking a key on the child must not leak up to
        // the parent.

        $parent = new ValidationContext();
        $child = $parent->atPath('address');

        $child->markEvaluated('zip');

        $this->assertTrue($child->isEvaluated('zip'));
        $this->assertFalse($parent->isEvaluated('zip'));
    }

    #[TestDox('->mergeEvaluatedKeys() pulls another context\'s marks into this one')]
    public function test_mergeEvaluatedKeys_merges_in(): void
    {
        // composition schemas (AnyOf, OneOf, conditional)
        // run sub-schemas in their own contexts and then
        // promote the winning branch's evaluations back up
        // to the parent. mergeEvaluatedKeys() is the tool
        // for that promotion step.

        $target = new ValidationContext();
        $target->markEvaluated('username');

        $donor = new ValidationContext();
        $donor->markEvaluated('email');
        $donor->markEvaluated('phone');

        $target->mergeEvaluatedKeys($donor);

        $this->assertTrue($target->isEvaluated('username'));
        $this->assertTrue($target->isEvaluated('email'));
        $this->assertTrue($target->isEvaluated('phone'));
    }

    #[TestDox('->mergeEvaluatedKeys() leaves the donor\'s own marks alone')]
    public function test_mergeEvaluatedKeys_does_not_mutate_donor(): void
    {
        // the donor context must survive the merge
        // unchanged - later code paths may still query it.
        // Accidentally mutating the donor during merge
        // would be a subtle concurrency-style footgun.

        $target = new ValidationContext();

        $donor = new ValidationContext();
        $donor->markEvaluated('email');

        $target->mergeEvaluatedKeys($donor);

        $this->assertSame(['email'], $donor->evaluatedKeys());
    }
}
