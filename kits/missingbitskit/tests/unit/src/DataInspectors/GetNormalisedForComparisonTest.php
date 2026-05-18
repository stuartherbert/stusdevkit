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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers;
use StusDevKit\MissingBitsKit\DataInspectors\GetNormalisedForComparison;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ChildOfParentWithPrivateProperty;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ClassWithArrayProperty;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ClassWithIntKeyedCanonicalForm;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ClassWithSelfReference;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ClassWithStaticAndInstanceProperty;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ClassWithUninitialisedProperty;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\ParentWithPrivateProperty;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\SampleColour;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\SampleStatus;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser\SelfReferencingCanonicalForm;

#[TestDox(GetNormalisedForComparison::class)]
class GetNormalisedForComparisonTest extends TestCase
{
    // ================================================================
    //
    // Scalar pass-through
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function scalarPassthruCases(): array
    {
        return [
            'int 42'                  => ['the int 42', 42],
            'float 3.14'              => ['the float 3.14', 3.14],
            'non-empty string'        => ['the string "hello"', 'hello'],
            'empty string'            => ['the empty string', ''],
            'true'                    => ['the boolean true', true],
            'false'                   => ['the boolean false', false],
            'null'                    => ['null', null],
        ];
    }

    #[DataProvider('scalarPassthruCases')]
    #[TestDox('returns $label unchanged')]
    public function test_scalar_input_passes_through_unchanged(
        string $label,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // scalars and null have no internal structure to canonicalise,
        // so they hit the fallback branch in fromInternal that just
        // returns the input. Pin this directly: a regression that
        // started wrapping scalars (e.g. boxing them into an
        // array-shape) would still pass every existing test as long
        // as the boxed form happened to compare structurally equal
        // to the original somewhere downstream. Catching that means
        // asserting `===` on each scalar type the contract claims to
        // round-trip.

        // ----------------------------------------------------------------
        // setup your test

        // $input is the value under test (varies per data set);
        // $label is just for the TestDox description.

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($input, $actual);
    }

    // ================================================================
    //
    // Uninitialised typed properties
    //
    // ----------------------------------------------------------------

    #[TestDox('represents an uninitialised typed property with the UNINITIALISED_PROPERTY sentinel')]
    public function test_uninitialised_typed_property_uses_sentinel(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP throws a `Error: Typed property ... must not be accessed
        // before initialization` if you try to read a typed property
        // that has no default and was never assigned. A reflection-
        // based normaliser must not propagate that throw - canonical
        // comparison would become unusable on any value object whose
        // construction is two-phase. The replacement value must be
        // a fixed, well-known sentinel so two uninitialised properties
        // on either side of a comparison collapse to the same value.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithUninitialisedProperty();

        $expected = [
            'class' => ClassWithUninitialisedProperty::class,
            'properties' => [
                'alwaysSet' => 'set',
                'neverSet' => GetNormalisedForComparison::UNINITIALISED_PROPERTY,
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not throw when an uninitialised typed property is encountered')]
    public function test_uninitialised_typed_property_does_not_throw(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Companion to the sentinel test above: pin the no-throw
        // contract explicitly so a regression that re-introduces the
        // raw `getRawValue` call fails this test with a clear
        // "expected no throw, got Error" message, not just a shape
        // mismatch elsewhere.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithUninitialisedProperty();

        // ----------------------------------------------------------------
        // perform the change

        // no try/catch - if `from()` throws, PHPUnit reports the
        // uncaught error as a test failure
        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        // if we get here, no exception was thrown - the assertion
        // exists only so PHPUnit doesn't flag the test as risky
        $this->assertIsArray($actual);
    }

    // ================================================================
    //
    // Cycle detection
    //
    // ----------------------------------------------------------------

    #[TestDox('represents a self-referencing object with the __cycle_ref marker')]
    public function test_self_referencing_object_emits_cycle_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // The simplest cycle: an object's property points at the
        // object itself. Without cycle detection the recursive walk
        // would re-enter `fromObject` forever and blow the stack.
        // The normaliser must instead emit a back-reference marker
        // pointing at the visit index of the previously-seen object.
        //
        // For the first object encountered, the visit index is 0,
        // so `child` here normalises to ['__cycle_ref' => 0].

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithSelfReference('root');
        $input->child = $input;

        $expected = [
            'class' => ClassWithSelfReference::class,
            'properties' => [
                'child' => ['__cycle_ref' => 0],
                'label' => 'root',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('represents a two-node cycle with sequential visit indexes')]
    public function test_two_node_cycle_emits_sequential_cycle_refs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // A two-node cycle: $a->child = $b, $b->child = $a. When the
        // walk reaches $b's `child` property, $a is the first object
        // visited (index 0), so the back-reference is __cycle_ref:0.
        // Visit indexes are assigned in order of first encounter,
        // which is what makes structurally-equivalent cyclic graphs
        // normalise identically: the indexes depend on traversal
        // order, not on the runtime object identity.

        // ----------------------------------------------------------------
        // setup your test

        $a = new ClassWithSelfReference('a');
        $b = new ClassWithSelfReference('b');
        $a->child = $b;
        $b->child = $a;

        $expected = [
            'class' => ClassWithSelfReference::class,
            'properties' => [
                'child' => [
                    'class' => ClassWithSelfReference::class,
                    'properties' => [
                        'child' => ['__cycle_ref' => 0],
                        'label' => 'b',
                    ],
                ],
                'label' => 'a',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($a);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('detects a cycle through a NormalisesForComparison implementor that threads the context')]
    public function test_self_referencing_implementor_emits_cycle_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // The cycle-safety guarantee of the NormalisesForComparison
        // contract: an implementor whose canonical form references
        // itself must emit __cycle_ref rather than infinite-loop.
        //
        // The contract requires implementors to thread the supplied
        // NormalisationContext through every recursive normalisation
        // via fromNested(). The fixture does exactly that on its own
        // $next pointer. With $loopy->next = $loopy, the second
        // visit to $loopy is the SAME object the parent walk just
        // marked seen, so the cycle-check at the top of
        // fromObjectInternal fires and emits __cycle_ref pointing at
        // the visit index of the first encounter (0).
        //
        // Without the cycle-check before the interface branch and
        // without the context being threaded, this test would hang
        // the test runner with a stack overflow - the exact
        // regression we're guarding against.

        // ----------------------------------------------------------------
        // setup your test

        $loopy = new SelfReferencingCanonicalForm('root');
        $loopy->next = $loopy;

        $expected = [
            'class' => SelfReferencingCanonicalForm::class,
            'canonical' => [
                'label' => 'root',
                'next' => ['__cycle_ref' => 0],
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($loopy);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('a shared (non-cyclic) child is still normalised in full at each occurrence')]
    public function test_shared_child_is_not_treated_as_a_cycle_on_separate_top_level_calls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Cycle detection is scoped to a single top-level call. Two
        // independent `from()` calls each get a fresh visited-set,
        // so an object passed to both calls normalises in full both
        // times - it is not "seen" on the second call.
        //
        // This pins the public-API contract: every entry point starts
        // a fresh cycle-tracking context.

        // ----------------------------------------------------------------
        // setup your test

        $shared = new ClassWithSelfReference('shared');

        $expectedShape = [
            'class' => ClassWithSelfReference::class,
            'properties' => [
                'child' => null,
                'label' => 'shared',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $first = GetNormalisedForComparison::from($shared);
        $second = GetNormalisedForComparison::from($shared);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedShape, $first);
        $this->assertSame($expectedShape, $second);
    }

    // ================================================================
    //
    // Enum handling
    //
    // ----------------------------------------------------------------

    #[TestDox('represents a pure enum case as an enum/case pair')]
    public function test_pure_enum_case_normalises_to_enum_and_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Pure enums have no reflectable user properties, so the
        // generic object path collapses every case of the same enum
        // to an identical empty-properties shape - two distinct
        // colours would compare equal, which is a correctness
        // disaster for canonical comparison. The case name is the
        // identity for pure enums and must appear in the output.

        // ----------------------------------------------------------------
        // setup your test

        $input = SampleColour::GREEN;

        $expected = [
            'enum' => SampleColour::class,
            'case' => 'GREEN',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('represents a backed enum case as an enum/case/value triple')]
    public function test_backed_enum_case_normalises_to_enum_case_and_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Backed enums carry a scalar value alongside the case name.
        // Two cases that share a name across different enums, or two
        // backed enums whose cases share a name but differ in backing
        // value, must remain distinguishable in the normalised form.
        // Including the backing value as well as the case name is
        // belt-and-braces: either alone would already distinguish
        // them in practice, but including both removes ambiguity.

        // ----------------------------------------------------------------
        // setup your test

        $input = SampleStatus::ACTIVE;

        $expected = [
            'enum' => SampleStatus::class,
            'case' => 'ACTIVE',
            'value' => 'active',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('distinct pure enum cases normalise to distinct values')]
    public function test_distinct_pure_enum_cases_normalise_differently(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // The whole point of the enum special case is that distinct
        // cases must NOT compare equal under canonicalising
        // comparison. Pin this directly: normalise two different
        // cases of the same enum and assert their normalised forms
        // differ. Without this guard, a regression that drops the
        // enum branch would still pass the per-case shape tests
        // above (each case would just collapse to the generic empty-
        // properties object form) while silently making every case
        // of an enum equal to every other.

        // ----------------------------------------------------------------
        // setup your test

        $red = SampleColour::RED;
        $blue = SampleColour::BLUE;

        // ----------------------------------------------------------------
        // perform the change

        $normalisedRed = GetNormalisedForComparison::from($red);
        $normalisedBlue = GetNormalisedForComparison::from($blue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($normalisedRed, $normalisedBlue);
    }

    // ================================================================
    //
    // Inherited private properties
    //
    // ----------------------------------------------------------------

    #[TestDox('includes private properties declared on a parent class, qualified by their declaring class')]
    public function test_inherited_private_properties_appear_qualified_by_declaring_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ReflectionObject::getProperties() on a child instance returns
        // the child's own properties plus public/protected properties
        // inherited from parents - but NOT private properties declared
        // on parents. A naive reflection walk silently drops that
        // inherited state, making two child instances that differ only
        // in parent-private state compare equal.
        //
        // GetNormalisedForComparison must walk the parent chain itself and
        // surface those inherited privates. To stay unambiguous when a
        // child re-declares a private property with the same name as a
        // parent's, the inherited slot's key is qualified with the
        // declaring class as `<propertyName>@<DeclaringFQCN>`.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ChildOfParentWithPrivateProperty();

        $expected = [
            'class' => ChildOfParentWithPrivateProperty::class,
            'properties' => [
                'onlyInChild' => 'child only',
                'onlyInParent@' . ParentWithPrivateProperty::class => 'parent only',
                'secret' => 'child secret',
                'secret@' . ParentWithPrivateProperty::class => 'parent secret',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Static properties
    //
    // ----------------------------------------------------------------

    #[TestDox('excludes static properties from the normalised output')]
    public function test_static_properties_are_excluded(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Static properties are class-scoped storage, not instance
        // state. Two instances of the same class share the same static
        // slot, so including it in a per-instance canonical comparison
        // means tests can flap whenever unrelated code touches the
        // static between the two assertion-side normalisations.
        //
        // The normalised output describes the instance, so the static
        // slot must not appear at all.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithStaticAndInstanceProperty();

        $expected = [
            'class' => ClassWithStaticAndInstanceProperty::class,
            'properties' => [
                'value' => 'instance',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // List handling (all-integer keys)
    //
    // ----------------------------------------------------------------

    #[TestDox('normalises an empty array to an empty list')]
    public function test_empty_array_normalises_to_empty_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array is ambiguous at the type level - it could
        // be the empty-set view of either a list OR a map. GetArrayShape
        // resolves the ambiguity by classifying empty as LIST (matching
        // PHP's own array_is_list([]) returning true), so the
        // normaliser hits the list path and returns an empty list.
        //
        // Pin both halves at once: empty in, empty out, AND the empty
        // is a list (not a map). A regression that flipped the
        // GetArrayShape default to MAP would still produce `[]` here,
        // so the assertions check both shape and emptiness.

        // ----------------------------------------------------------------
        // setup your test

        $input = [];

        $expected = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsArray($actual);
        $this->assertSame($expected, $actual);
        $this->assertSame([], array_keys($actual));
    }

    #[TestDox('drops integer keys from an all-int-keyed array and re-indexes from zero')]
    public function test_list_array_is_reindexed_from_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP arrays with only integer keys are overwhelmingly used
        // as lists: literal `[a, b, c]`, accumulators built with
        // `[]=`, `array_filter` output. For those, the keys carry
        // no information the value sequence does not already - two
        // lists with the same values should normalise identically
        // regardless of any gaps left behind by `array_filter` or
        // `unset`.
        //
        // The normaliser drops the int keys entirely and re-indexes
        // from zero, so a sparse `[0 => 'a', 2 => 'c']` and a
        // freshly-built `['a', 'c']` produce the same canonical
        // form.

        // ----------------------------------------------------------------
        // setup your test

        // simulate the post-array_filter shape: the literal index 1
        // has been removed, so PHP leaves a gap in the keys.
        $input = [0 => 'a', 2 => 'c'];

        $expected = ['a', 'c'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsArray($actual);
        $this->assertSame($expected, $actual);
        $this->assertSame([0, 1], array_keys($actual));
    }

    #[TestDox('preserves the value order of an int-keyed array (values are never sorted)')]
    public function test_list_value_order_is_preserved(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Dropping the keys must NOT come with sorting the values.
        // List order usually carries meaning - event sequences,
        // sorted result sets, fixed display orders - and reordering
        // them would erase information the test author put there
        // on purpose. The keys go, the value order stays.

        // ----------------------------------------------------------------
        // setup your test

        // not-sorted on purpose - if the normaliser sorted values,
        // the result would be ['a', 'b', 'c'] and this test would
        // catch the regression.
        $input = ['c', 'a', 'b'];

        $expected = ['c', 'a', 'b'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('two int-keyed arrays with the same value sequence normalise identically regardless of key gaps')]
    public function test_lists_with_same_value_sequence_compare_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // This is the headline guarantee for canonical comparison
        // of lists: a freshly-built `['a', 'b']` and a sparse
        // `[3 => 'a', 7 => 'b']` describe the same list and must
        // normalise to the same value. Pin it directly so a
        // regression that re-introduces key preservation shows up
        // here as a clear "same values, different keys, unequal"
        // failure.

        // ----------------------------------------------------------------
        // setup your test

        $dense = ['a', 'b'];
        $sparse = [3 => 'a', 7 => 'b'];

        // ----------------------------------------------------------------
        // perform the change

        $normalisedDense = GetNormalisedForComparison::from($dense);
        $normalisedSparse = GetNormalisedForComparison::from($sparse);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($normalisedDense, $normalisedSparse);
    }

    // ================================================================
    //
    // Map handling (any string key)
    //
    // ----------------------------------------------------------------

    #[TestDox('preserves all keys when at least one key is a string, even mixed int+string')]
    public function test_map_with_string_key_preserves_all_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // The presence of any string key makes the whole array a
        // map, not a list - the keys ARE the identity of each
        // entry. Even the int-typed keys must survive the
        // normalisation so they remain part of the comparison.

        // ----------------------------------------------------------------
        // setup your test

        // mixed: a non-numeric string key ('name') alongside int
        // keys 10 and 2. The string key forces map semantics.
        $input = ['name' => 'x', 10 => 'a', 2 => 'b'];

        // under SORT_STRING the keys sort as the strings "10",
        // "2", "name" - lex order, not numeric order.
        $expected = [10 => 'a', 2 => 'b', 'name' => 'x'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsArray($actual);
        $this->assertSame($expected, $actual);
        $this->assertSame([10, 2, 'name'], array_keys($actual));
    }

    #[TestDox('sorts map keys lexicographically (SORT_STRING) so numeric-looking strings stay in lex order')]
    public function test_map_keys_are_sorted_lexicographically(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // For maps, the canonical ordering must be deterministic
        // and independent of PHP's loose-comparison rules. SORT_STRING
        // gives a single, predictable rule: compare every key as a
        // string. Without it, mixed int/string keys would sort by
        // PHP's SORT_REGULAR comparison (numeric for ints, string
        // for strings), which produces different orderings depending
        // on the key types involved.
        //
        // Under SORT_STRING, the keys 10, 2, 'name' sort as the
        // strings "10" < "2" < "name". Under SORT_REGULAR, the
        // ints would sort numerically before the string, giving
        // 2, 10, 'name' - a different order.

        // ----------------------------------------------------------------
        // setup your test

        $input = ['name' => 'x', 10 => 'a', 2 => 'b'];

        // expected key order under SORT_STRING: "10", "2", "name"
        $expectedKeyOrder = [10, 2, 'name'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsArray($actual);
        $this->assertSame($expectedKeyOrder, array_keys($actual));
    }

    // ================================================================
    //
    // Nested structures
    //
    // ----------------------------------------------------------------

    #[TestDox('recurses into a map nested inside a list')]
    public function test_list_containing_a_map_recurses_into_the_map(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // canonicalisation is recursive. A list element that happens
        // to be a map must itself get the map treatment - keys
        // preserved, sorted lexicographically by SORT_STRING - and
        // not just be passed through unchanged. Pin this by feeding
        // a list whose single element is a map with out-of-order
        // keys: the outer list survives, the inner map's keys
        // arrive sorted.

        // ----------------------------------------------------------------
        // setup your test

        // inner map is deliberately declared in reverse order so the
        // ksort step is observable in the output.
        $input = [['b' => 1, 'a' => 2]];

        $expected = [['a' => 2, 'b' => 1]];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('recurses into a list nested inside a map')]
    public function test_map_containing_a_list_recurses_into_the_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // mirror of the previous test, in the other direction. A map
        // value that happens to be a list must itself get the list
        // treatment - int keys dropped and re-indexed from zero -
        // not just be passed through unchanged. Pin this by feeding
        // a map whose single value is a sparse list: the outer map
        // preserves the string key, the inner list drops the int
        // keys.

        // ----------------------------------------------------------------
        // setup your test

        // inner list is sparse on purpose so the key-dropping +
        // re-indexing step is observable in the output.
        $input = ['outer' => [10 => 'x', 20 => 'y']];

        $expected = ['outer' => ['x', 'y']];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('recurses into an array held in an object property')]
    public function test_object_property_holding_an_array_is_recursed_into(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a property whose value is an array must be normalised by
        // the same recursive walk as a top-level array - same
        // list/map discrimination, same key-handling. Pin that the
        // reflection-walk hands array-valued properties back through
        // the dispatcher (fromInternal), not just stashes them in
        // the canonical form verbatim.
        //
        // The inner array here is a list, so the list path runs at
        // depth 2 (the outer object is depth 1). The list section
        // already pins the list path's own behaviour at depth 1;
        // this test pins that depth 2 reaches the same code.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithArrayProperty(
            label: 'root',
            items: ['alpha', 'bravo', 'charlie'],
        );

        $expected = [
            'class' => ClassWithArrayProperty::class,
            'properties' => [
                'items' => ['alpha', 'bravo', 'charlie'],
                'label' => 'root',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('recurses through several alternating layers of object and array')]
    public function test_deep_nesting_round_trips_through_object_array_object_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin that recursion does not bottom out at depth 2. Build
        // an outer ClassWithArrayProperty whose `items` array
        // contains an inner ClassWithArrayProperty, whose own
        // `items` array contains scalars. The walk traverses:
        //
        //   layer 0: outer object
        //   layer 1: outer.items array (list)
        //   layer 2: outer.items[0] inner object
        //   layer 3: inner.items array (list)
        //   layer 4: scalar leaves
        //
        // If any layer's recursion fell back to "use verbatim", the
        // resulting shape would be detectably different - either
        // because an inner ClassWithArrayProperty would appear as a
        // raw object identity rather than its `class`/`properties`
        // canonical form, or because the inner list would be
        // sparse/keyed when it should be a dense list.

        // ----------------------------------------------------------------
        // setup your test

        $leaf = new ClassWithArrayProperty(
            label: 'leaf',
            items: ['x', 'y'],
        );
        $root = new ClassWithArrayProperty(
            label: 'root',
            items: [$leaf],
        );

        $expected = [
            'class' => ClassWithArrayProperty::class,
            'properties' => [
                'items' => [
                    [
                        'class' => ClassWithArrayProperty::class,
                        'properties' => [
                            'items' => ['x', 'y'],
                            'label' => 'leaf',
                        ],
                    ],
                ],
                'label' => 'root',
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($root);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // NormalisesForComparison interface handling
    //
    // ----------------------------------------------------------------

    #[TestDox('uses the canonical form verbatim when the object implements NormalisesForComparison')]
    public function test_normalises_for_comparison_interface_is_honoured_verbatim(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when an object opts into the NormalisesForComparison
        // contract, it takes full responsibility for producing its
        // canonical form. The normaliser must:
        //
        //  1. CALL `getNormalisedForComparison()` - so the contract
        //     fires at all;
        //  2. SKIP the reflection walk - so a regular property like
        //     `$shouldNotAppear` does NOT leak into the output;
        //  3. USE the return value verbatim - so any structure the
        //     implementor chose (e.g. int-keyed dict shape) is
        //     preserved exactly, not put back through the list/map
        //     heuristic that the contract exists to bypass;
        //  4. WRAP with the class name - so two different classes
        //     that happen to return the same canonical body still
        //     compare unequal.
        //
        // The fixture returns `[42 => 'alice', 7 => 'bob']` - an
        // int-keyed dict that the standard pipeline would treat as
        // a list and renumber from zero. Pinning that the original
        // int keys survive proves all four behaviours at once.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithIntKeyedCanonicalForm();

        $expected = [
            'class' => ClassWithIntKeyedCanonicalForm::class,
            'canonical' => [42 => 'alice', 7 => 'bob'],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNormalisedForComparison::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('a CollectionsKit DictOfIntegers preserves its int keys through the full pipeline')]
    public function test_dict_of_integers_keys_survive_full_pipeline(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // This is the headline guarantee the NormalisesForComparison
        // contract was added to deliver: a DictOfIntegers with int
        // keys (e.g. user IDs) must canonicalise to a form that
        // PRESERVES those keys, so two dicts that hold the same
        // values under different keys do NOT compare equal.
        //
        // Before the contract, the reflection walk would see the
        // backing array `[42 => 'alice', 7 => 'bob']`, conclude
        // (correctly for a raw array, wrongly for a dict) that it
        // was a list, and drop the int keys - reducing two
        // semantically-different dicts to the same canonical form.
        // The contract gives the dict the final word on its
        // canonical shape; this test pins that the full pipeline
        // honours it end-to-end.

        // ----------------------------------------------------------------
        // setup your test

        $sameValuesDifferentKeysA = new DictOfIntegers([42 => 1, 7 => 2]);
        $sameValuesDifferentKeysB = new DictOfIntegers([99 => 1, 3 => 2]);

        // ----------------------------------------------------------------
        // perform the change

        $normalisedA = GetNormalisedForComparison::from($sameValuesDifferentKeysA);
        $normalisedB = GetNormalisedForComparison::from($sameValuesDifferentKeysB);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($normalisedA, $normalisedB);
    }
}
