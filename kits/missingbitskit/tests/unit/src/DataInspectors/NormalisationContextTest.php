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
// COPYRIGHT HOLDERS AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
// INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\MissingBitsKit\Tests\Unit\DataInspectors;

use ArrayObject;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use stdClass;
use StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext;
use StusDevKit\MissingBitsKit\DataInspectors\UnseenObjectException;

#[TestDox(NormalisationContext::class)]
class NormalisationContextTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\DataInspectors namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract. Callers
        // import NormalisationContext by its FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\DataInspectors';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(NormalisationContext::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published kind (class vs interface vs trait) is part
        // of the contract - switching kinds breaks every consumer
        // that depends on this type.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(NormalisationContext::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('is declared as final')]
    public function test_is_declared_as_final(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `final` is part of the contract: implementors that
        // subclassed NormalisationContext would be coupled to an
        // implementation detail (storage choice) the wrapper exists
        // to hide. Pin the final declaration so that drift fails
        // this test.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(NormalisationContext::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isFinal();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // Hierarchy
    //
    // ----------------------------------------------------------------

    #[TestDox('extends no other class')]
    public function test_extends_no_other_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // NormalisationContext is a standalone wrapper around its
        // private SplObjectStorage. Acquiring a parent class would
        // add inherited members to its public surface that this
        // test class doesn't pin - and that surface is what the
        // manual page documents.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(NormalisationContext::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('implements no interfaces')]
    public function test_implements_no_interfaces(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // implementing an interface (e.g. Countable, IteratorAggregate)
        // would add semantic obligations the wrapper does not intend
        // to make. Pin the no-interface shape so accidental drift
        // surfaces as a test failure.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $reflection = new ReflectionClass(NormalisationContext::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('uses no traits')]
    public function test_uses_no_traits(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // any trait the class uses would inject extra public methods
        // into the API surface. Pin the no-trait shape so the
        // enumerated method set below remains the authoritative
        // surface.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $reflection = new ReflectionClass(NormalisationContext::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getTraitNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Public method set
    //
    // ----------------------------------------------------------------

    #[TestDox('publishes exactly __construct/hasSeen/markSeen/visitIndexOf as its public method set')]
    public function test_publishes_the_expected_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the enumerated public method set. Adding a method is
        // a minor-version event; removing one is a major. Drift
        // shows up here as a named diff, not "expected 4, got 5".

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            '__construct',
            'hasSeen',
            'markSeen',
            'visitIndexOf',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = array_map(
            static fn (ReflectionMethod $m): string => $m->getName(),
            (new ReflectionClass(NormalisationContext::class))
                ->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($methodNames);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $methodNames);
    }

    // ================================================================
    //
    // ::__construct() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() takes zero parameters')]
    public function test_constructor_takes_zero_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the context owns its own SplObjectStorage and seeds it
        // empty. Adding a constructor parameter would let callers
        // pre-seed the visited-set, which is a deliberate
        // non-feature of the API. Pin the zero-arg shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 0;
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->hasSeen() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasSeen() takes exactly one parameter')]
    public function test_hasSeen_takes_one_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the predicate accepts the candidate object to look up and
        // nothing else. A second parameter would either change the
        // semantics (e.g. lookup-or-default) or duplicate state the
        // context owns. Pin the cardinality.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 1;
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('hasSeen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->hasSeen()\'s parameter is named $obj')]
    public function test_hasSeen_parameter_is_named_obj(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name appears in docblock prose and in any
        // call-site that uses named arguments. Pin the spelling so
        // a rename flushes the docs and any keyword callers in one
        // diff.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'obj';
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('hasSeen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getParameters()[0]->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->hasSeen()\'s parameter is typed as object')]
    public function test_hasSeen_parameter_is_typed_as_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a bare `object` typehint accepts anything that hashes by
        // identity, which is the only thing the context actually
        // needs. Narrowing to a specific type would cripple the
        // wrapper; widening to `mixed` would silently accept
        // scalars that have no identity.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('hasSeen');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getParameters()[0]->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('object', $type->getName());
    }

    #[TestDox('->hasSeen()\'s parameter is required, not optional')]
    public function test_hasSeen_parameter_is_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a default-null on the predicate would silently let
        // callers call hasSeen() with no argument and get back a
        // meaningless answer. Pin that the parameter is required.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('hasSeen');

        // ----------------------------------------------------------------
        // perform the change

        $parameter = $method->getParameters()[0];

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($parameter->isOptional());
        $this->assertFalse($parameter->allowsNull());
    }

    #[TestDox('->hasSeen() returns bool')]
    public function test_hasSeen_returns_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the predicate's return type is the answer to its question
        // - a plain `bool`. Widening to `mixed` or changing to `int`
        // would force callers to interpret the result rather than
        // branch on it directly.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('hasSeen');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('bool', $type->getName());
    }

    // ================================================================
    //
    // ->visitIndexOf() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->visitIndexOf() takes exactly one parameter')]
    public function test_visitIndexOf_takes_one_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the accessor takes the object to look up and nothing
        // else. Pin the cardinality.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 1;
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('visitIndexOf');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->visitIndexOf()\'s parameter is named $obj')]
    public function test_visitIndexOf_parameter_is_named_obj(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // matches the parameter name used by hasSeen() and
        // markSeen() so callers can pass the same variable through
        // all three methods without renaming.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'obj';
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('visitIndexOf');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getParameters()[0]->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->visitIndexOf()\'s parameter is typed as object')]
    public function test_visitIndexOf_parameter_is_typed_as_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // accepts anything object-shaped, same as hasSeen() and
        // markSeen(). Pin the bare `object` typehint.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('visitIndexOf');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getParameters()[0]->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('object', $type->getName());
    }

    #[TestDox('->visitIndexOf()\'s parameter is required, not optional')]
    public function test_visitIndexOf_parameter_is_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin that the parameter is required and non-nullable -
        // there is no meaningful default lookup.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('visitIndexOf');

        // ----------------------------------------------------------------
        // perform the change

        $parameter = $method->getParameters()[0];

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($parameter->isOptional());
        $this->assertFalse($parameter->allowsNull());
    }

    #[TestDox('->visitIndexOf() returns int')]
    public function test_visitIndexOf_returns_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the accessor returns a non-nullable int. Missing-object
        // lookups go through UnseenObjectException - they do NOT
        // surface as null, which would leak the storage's "missing
        // key" semantics through the wrapper.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('visitIndexOf');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
        $this->assertFalse($type->allowsNull());
    }

    // ================================================================
    //
    // ->markSeen() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->markSeen() takes exactly one parameter')]
    public function test_markSeen_takes_one_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the mutator records one object at a time. Pin the
        // cardinality.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 1;
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('markSeen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->markSeen()\'s parameter is named $obj')]
    public function test_markSeen_parameter_is_named_obj(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // matches the parameter name used by hasSeen() and
        // visitIndexOf() so callers can pass the same variable
        // through all three methods without renaming.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'obj';
        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('markSeen');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getParameters()[0]->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->markSeen()\'s parameter is typed as object')]
    public function test_markSeen_parameter_is_typed_as_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // accepts anything object-shaped, same as hasSeen() and
        // visitIndexOf(). Pin the bare `object` typehint.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('markSeen');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getParameters()[0]->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('object', $type->getName());
    }

    #[TestDox('->markSeen()\'s parameter is required, not optional')]
    public function test_markSeen_parameter_is_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin that the parameter is required and non-nullable -
        // there is no meaningful "record nothing" operation.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('markSeen');

        // ----------------------------------------------------------------
        // perform the change

        $parameter = $method->getParameters()[0];

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($parameter->isOptional());
        $this->assertFalse($parameter->allowsNull());
    }

    #[TestDox('->markSeen() returns int')]
    public function test_markSeen_returns_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the mutator returns the visit index of the recorded
        // object - non-nullable int.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisationContext::class))
            ->getMethod('markSeen');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
        $this->assertFalse($type->allowsNull());
    }

    // ================================================================
    //
    // Visit tracking behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('a fresh context reports no object as seen')]
    public function test_fresh_context_has_seen_nothing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the empty-state postcondition of __construct(). A
        // context that already "remembers" something at
        // construction time would break GetNormalisedForComparison's
        // per-call independence guarantee - two consecutive from()
        // calls might disagree about which objects are cycles.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $context->hasSeen($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->hasSeen() returns true for an object recorded via ->markSeen()')]
    public function test_hasSeen_returns_true_after_markSeen(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the round-trip headline: record an object, then ask if
        // it's been seen. If hasSeen() and markSeen() disagree
        // about the visited-set, every cycle-detection contract
        // downstream breaks.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();
        $context->markSeen($obj);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $context->hasSeen($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->markSeen() assigns visit index zero to the first object recorded')]
    public function test_first_markSeen_returns_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // back-references emitted by GetNormalisedForComparison
        // refer to objects by their visit index - the first object
        // recorded must be index 0 so the back-reference shape is
        // predictable: ['__cycle_ref' => 0] for a self-loop on the
        // top-level input.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();

        $expected = 0;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $context->markSeen($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->markSeen() assigns sequential indexes in order of first encounter')]
    public function test_sequential_markSeen_calls_assign_sequential_indexes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the "order of first encounter" invariant - 0, 1, 2, ...
        // in the order markSeen() is called on previously unseen
        // objects. This is what makes structurally-equivalent
        // cyclic graphs canonicalise to the same back-references
        // run after run.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $first = new stdClass();
        $second = new stdClass();
        $third = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $firstIndex = $context->markSeen($first);
        $secondIndex = $context->markSeen($second);
        $thirdIndex = $context->markSeen($third);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $firstIndex);
        $this->assertSame(1, $secondIndex);
        $this->assertSame(2, $thirdIndex);
    }

    #[TestDox('->markSeen() is idempotent: a second call returns the first-visit index')]
    public function test_markSeen_is_idempotent(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // calling markSeen() twice on the same object must return
        // the SAME index both times - the first-visit index. Any
        // overwriting behaviour would corrupt back-references
        // already emitted for that object, and would let a caller
        // who forgot to check hasSeen() first silently destroy the
        // cycle-detection state.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();
        $firstIndex = $context->markSeen($obj);

        // ----------------------------------------------------------------
        // perform the change

        $secondIndex = $context->markSeen($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($firstIndex, $secondIndex);
    }

    #[TestDox('->markSeen() does not increment the visit counter on a repeat call')]
    public function test_markSeen_repeat_does_not_advance_the_counter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // idempotency on the same object must NOT increment the
        // counter used to assign the next first-visit index to
        // a different object. If the second markSeen($a) bumped
        // the counter, the next markSeen($b) would land at the
        // wrong index and cycle references would point at the
        // wrong visit position.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $first = new stdClass();
        $second = new stdClass();
        $context->markSeen($first);
        $context->markSeen($first);

        // ----------------------------------------------------------------
        // perform the change

        $secondIndex = $context->markSeen($second);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $secondIndex);
    }

    #[TestDox('->visitIndexOf() returns the index that ->markSeen() assigned')]
    public function test_visitIndexOf_returns_the_recorded_index(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the round-trip: the index handed out by markSeen()
        // is the same index visitIndexOf() returns later. Any
        // off-by-one or independent counter would land a back-
        // reference at the wrong visit position.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();
        $assignedIndex = $context->markSeen($obj);

        // ----------------------------------------------------------------
        // perform the change

        $lookedUpIndex = $context->visitIndexOf($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($assignedIndex, $lookedUpIndex);
    }

    #[TestDox('->visitIndexOf() returns the correct index for an object stored among several')]
    public function test_visitIndexOf_returns_correct_index_among_several(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // visitIndexOf() must retrieve the index of the SPECIFIC
        // object asked for, not just "an" index. With a single
        // object stored the index is trivially zero, so a bug that
        // ignored the argument would still pass the one-object
        // round-trip test above. Store three objects and look up
        // the middle one - the answer must be exactly 1.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $first = new stdClass();
        $middle = new stdClass();
        $last = new stdClass();
        $context->markSeen($first);
        $context->markSeen($middle);
        $context->markSeen($last);

        $expected = 1;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $context->visitIndexOf($middle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->visitIndexOf() distinguishes objects by runtime identity, not by state')]
    public function test_visitIndexOf_distinguishes_objects_by_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // two distinct objects of the same class with identical
        // state must get distinct visit indexes - the context
        // tracks identity, not equality. If it didn't, two
        // independently-constructed value objects with the same
        // fields would be conflated into a single "cycle".

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $a = new stdClass();
        $b = new stdClass();
        $context->markSeen($a);
        $context->markSeen($b);

        // ----------------------------------------------------------------
        // perform the change

        $indexA = $context->visitIndexOf($a);
        $indexB = $context->visitIndexOf($b);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($indexA, $indexB);
    }

    #[TestDox('->hasSeen() reports false for a clone of a recorded object')]
    public function test_clone_of_a_recorded_object_is_not_seen(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "seen" status attaches to an object's runtime identity,
        // not to its state. `clone` produces a new object with a
        // fresh identity but identical state - so a clone of a
        // recorded object must read as UNSEEN. If the context
        // tracked by state instead of identity, a clone appearing
        // in a graph would be mistaken for a cycle back to its
        // original.
        //
        // An ArrayObject (rather than stdClass) is used here to
        // double as evidence that the context is type-agnostic -
        // it tracks identity for any object class.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $original = new ArrayObject(['state' => 'shared']);
        $context->markSeen($original);
        $twin = clone $original;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $context->hasSeen($twin);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->visitIndexOf() throws UnseenObjectException for an unrecorded object')]
    public function test_visitIndexOf_throws_for_unseen_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the typed-exception contract: a misuse surfaces as
        // our own UnseenObjectException, NOT as the underlying
        // SplObjectStorage's UnexpectedValueException. The whole
        // point of the wrapper class is that no caller has to
        // know about the storage's exception type.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();

        $this->expectException(UnseenObjectException::class);

        // ----------------------------------------------------------------
        // perform the change

        $context->visitIndexOf($obj);

        // ----------------------------------------------------------------
        // test the results

        // expectException above handles the assertion; reaching
        // this line would be an unexpected non-throw.
    }

    #[TestDox('->visitIndexOf()\'s exception names the offending object')]
    public function test_visitIndexOf_exception_names_the_offending_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // throwing UnseenObjectException is only half the contract -
        // the exception must also identify WHICH object was looked
        // up, so the diagnostic is useful. NormalisationContext's
        // job here is to hand the offending object to the
        // exception; pin that the object's class name reaches the
        // exception's `extra` payload.
        //
        // The exception's own contract (status, title, type URI)
        // belongs to UnseenObjectExceptionTest - this test pins
        // only what NormalisationContext is responsible for: that
        // the right object is handed over.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $probe = new stdClass();
        $caught = null;

        // ----------------------------------------------------------------
        // perform the change

        try {
            $context->visitIndexOf($probe);
        } catch (UnseenObjectException $e) {
            $caught = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UnseenObjectException::class, $caught);
        $this->assertSame(
            ['class_name' => stdClass::class],
            $caught->getExtra(),
        );
    }

    #[TestDox('->hasSeen() and ->visitIndexOf() agree that a recorded object is present')]
    public function test_hasSeen_and_visitIndexOf_agree_for_a_recorded_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // GetNormalisedForComparison's cycle check is
        // `if hasSeen($x) { ... visitIndexOf($x) }` - it relies on
        // the two methods agreeing about the visited-set. If a
        // future refactor changed one method's underlying check
        // but not the other, hasSeen() could return true while
        // visitIndexOf() threw. Pin the agreement directly: on a
        // recorded object, hasSeen() is true AND visitIndexOf()
        // succeeds with the assigned index.

        // ----------------------------------------------------------------
        // setup your test

        $context = new NormalisationContext();
        $obj = new stdClass();
        $assignedIndex = $context->markSeen($obj);

        // ----------------------------------------------------------------
        // perform the change

        $seen = $context->hasSeen($obj);
        $index = $context->visitIndexOf($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($seen);
        $this->assertSame($assignedIndex, $index);
    }

    // ================================================================
    //
    // Independence across contexts
    //
    // ----------------------------------------------------------------

    #[TestDox('two independent contexts do not share visited state')]
    public function test_independent_contexts_do_not_share_state(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every top-level GetNormalisedForComparison::from() call
        // creates a fresh NormalisationContext so cycle detection
        // is scoped to that single call. Sharing state across
        // contexts would let an unrelated earlier walk's visited-
        // set affect this walk's back-references. Pin the
        // independence so the per-call scoping survives any
        // accidental static-state regression in the wrapper.

        // ----------------------------------------------------------------
        // setup your test

        $first = new NormalisationContext();
        $second = new NormalisationContext();
        $obj = new stdClass();
        $first->markSeen($obj);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $second->hasSeen($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
