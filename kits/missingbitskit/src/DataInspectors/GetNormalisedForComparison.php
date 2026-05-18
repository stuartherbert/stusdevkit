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
namespace StusDevKit\MissingBitsKit\DataInspectors;

use BackedEnum;
use ReflectionObject;
use ReflectionProperty;
use StusDevKit\MissingBitsKit\Arrays\ArrayShape;
use StusDevKit\MissingBitsKit\Arrays\GetArrayShape;
use StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison;
use UnitEnum;

/**
 * Helper tool, for producing a normalised version of a given value.
 *
 * These "normalised" values are specifically designed to be used in
 * structural comparisons, where:
 *
 * - array keys order doesn't matter
 * - object property order doesn't matter
 *
 * Originally added to support the AssertApi's "Canonicalization"
 * methods.
 */
class GetNormalisedForComparison
{
    /**
     * Sentinel value used in place of a typed property's value when
     * the property has never been initialised.
     *
     * The raw underlying reflection call (`getRawValue()`) throws
     * on such a property, which would make any canonical comparison
     * of a two-phase-constructed value object impossible.
     * Substituting a fixed sentinel keeps the comparison usable
     * while still making the uninitialised state visible in a diff.
     */
    public const string UNINITIALISED_PROPERTY = '__uninitialised__';

    /**
     * normalise the given `$input`:
     *
     * - list-shaped arrays (every key is an int) drop their keys
     *   and re-index from zero, preserving value iteration order
     * - map-shaped arrays (any string key present) preserve all
     *   keys and are sorted lexicographically by key
     * - objects implementing `NormalisesForComparison` return
     *   their class name plus the canonical form they publish
     *   (the return value is used verbatim - no further
     *   normalisation is applied)
     * - other objects return an array of all properties (public,
     *   protected and private, including those inherited from
     *   parent classes), along with their class name
     * - enums return their class plus the case name (and, for
     *   backed enums, the backing value)
     * - cyclic object graphs emit a back-reference marker rather
     *   than recursing forever
     *
     * Here Be Dragons
     * ===============
     *
     * - **Return type may differ from input type.** A scalar comes
     *   back as a scalar, but an array can come back re-indexed,
     *   and an object always comes back as an array. Anything
     *   non-scalar should be treated as opaque - compare it, do
     *   not introspect it.
     *
     * - **Use `fromNested()` when implementing the
     *   `NormalisesForComparison` contract.** Calling `from()`
     *   from inside `getNormalisedForComparison()` starts a fresh
     *   cycle-detection walk, which footguns into an infinite
     *   loop on self-referencing graphs. See
     *   {@see fromNested}.
     *
     * @param mixed $input
     *      the value to normalise
     * @return mixed
     *      the normalised representation of `$input`
     */
    public static function from(mixed $input): mixed
    {
        // every top-level entry point starts a fresh context, so
        // cycle detection is scoped to a single top-level call -
        // two independent `from()` calls do not share state.
        return self::fromInternal($input, new NormalisationContext());
    }

    /**
     * normalise `$input` while continuing an existing walk's
     * cycle-detection state.
     *
     * Intended for implementors of
     * {@see \StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison}:
     * the implementor receives a `NormalisationContext` from the
     * parent walk and forwards it through every nested
     * normalisation it performs. Threading the context keeps the
     * parent's visited-set alive across the interface boundary,
     * so self-references in the implementor's own graph are
     * detected and emit `__cycle_ref` instead of recursing
     * forever.
     *
     * Casual top-level callers should use {@see from} instead -
     * `fromNested()` is the protocol-internal entry point and
     * deliberately requires a context, so forgetting to thread it
     * is a compile-time error rather than a silent revert to the
     * infinite-loop footgun.
     *
     * @param mixed $input
     *      the value to normalise
     * @param NormalisationContext $context
     *      the parent walk's shared state, supplied to your
     *      `getNormalisedForComparison()` method - do not
     *      construct your own
     * @return mixed
     *      the normalised representation of `$input`
     */
    public static function fromNested(
        mixed $input,
        NormalisationContext $context,
    ): mixed {
        return self::fromInternal($input, $context);
    }

    // ================================================================
    //
    // Internal helpers
    //
    // ----------------------------------------------------------------

    /**
     * recursive entry point - dispatches by type, threading the
     * shared context so cycles can be detected across a single
     * top-level call.
     */
    private static function fromInternal(
        mixed $input,
        NormalisationContext $context,
    ): mixed {
        // special case - do we have an array?
        if (is_array($input)) {
            return self::fromArrayInternal($input, $context);
        }

        // special case - do we have an object?
        if (is_object($input)) {
            return self::fromObjectInternal($input, $context);
        }

        // fallback - no normalisation rule
        return $input;
    }

    /**
     * @param array<mixed> $input
     * @return array<mixed>
     */
    private static function fromArrayInternal(
        array $input,
        NormalisationContext $context,
    ): array {
        // discriminate list-shaped from map-shaped arrays.
        //
        // PHP arrays serve two distinct roles, and canonical
        // comparison wants different treatment for each:
        //
        // - list-shaped (every key is an int): the values are an
        //   ordered sequence and the int keys are just positions.
        //   Two lists with the same value sequence should compare
        //   equal even if one has gaps from `array_filter` or
        //   `unset`. The int keys are dropped entirely.
        //
        // - map-shaped (at least one string key): the keys ARE the
        //   identity of each entry. They must survive into the
        //   normalised form, and they must arrive in a
        //   deterministic order independent of declaration order.
        //
        // The "any string key" rule lives in GetArrayShape so other
        // code can share the same intuition. PHP's built-in
        // `array_is_list()` is too strict for our purposes - it
        // only returns true for keys 0..n-1 with no gaps - so we
        // deliberately don't use it.
        return match(GetArrayShape::from($input)) {
            ArrayShape::LIST => self::fromArrayListInternal($input, $context),
            ArrayShape::MAP => self::fromArrayMapInternal($input, $context),
        };
    }

    /**
     * @param array<mixed> $input
     * @return array<mixed>
     */
    private static function fromArrayListInternal(
        array $input,
        NormalisationContext $context,
    ): array {
        // our return value
        $retval = [];

        // drop keys, preserve value iteration order.
        //
        // we deliberately do NOT sort the values: list order
        // usually carries meaning (event sequences, sorted
        // result sets, fixed display orders), and reordering
        // values would erase information the caller put there
        // on purpose.
        foreach ($input as $value) {
            $retval[] = self::fromInternal($value, $context);
        }

        // all done
        return $retval;
    }

    /**
     * @param array<mixed> $input
     * @return array<mixed>
     */
    private static function fromArrayMapInternal(
        array $input,
        NormalisationContext $context,
    ): array {
        // our return value
        $retval = [];

        // map - preserve keys, lex-sort for determinism.
        //
        // correctness!
        // SORT_STRING compares every key as a string, giving a
        // single predictable rule for any mix of int and
        // string keys. Without this flag, SORT_REGULAR's
        // loose-comparison rules sort ints numerically and
        // strings lexicographically, so two maps with the same
        // keys could canonicalise to different orderings
        // depending on the key types involved.
        //
        // ksort() is the in-place sort variant - the caller's
        // array is unaffected because PHP passes arrays by
        // value.
        ksort($input, SORT_STRING);

        foreach ($input as $key => $value) {
            $retval[$key] = self::fromInternal($value, $context);
        }

        // all done
        return $retval;
    }

    /**
     * @return array{class: class-string, properties: array<string,mixed>}
     *      | array{class: class-string, canonical: mixed}
     *      | array{enum: class-string, case: string}
     *      | array{enum: class-string, case: string, value: int|string}
     *      | array{__cycle_ref: int}
     */
    private static function fromObjectInternal(
        object $input,
        NormalisationContext $context,
    ): array {
        // special case - enums are objects but the reflection-based
        // walk below would collapse every case of a pure enum to
        // the same empty-properties shape. The case name (and the
        // backing value, for backed enums) IS the identity here -
        // surface it directly.
        //
        // enums are also exempt from the cycle-check below: they
        // are singletons, so two visits to the same case are not a
        // cycle, and emitting __cycle_ref on the second visit would
        // corrupt arrays-of-enum canonicalisation (e.g.
        // `[Colour::RED, Colour::RED]`).
        if ($input instanceof BackedEnum) {
            return [
                'enum' => $input::class,
                'case' => $input->name,
                'value' => $input->value,
            ];
        }
        if ($input instanceof UnitEnum) {
            return [
                'enum' => $input::class,
                'case' => $input->name,
            ];
        }

        // robustness!
        // if we have already walked this object during this call,
        // re-entering would recurse forever on cyclic graphs. Emit
        // a back-reference to the visit index of the first
        // encounter instead.
        //
        // visit indexes are assigned in order of first sight (0,
        // 1, 2, ...), which means structurally-equivalent cyclic
        // graphs normalise identically: the indexes depend on
        // traversal order, not on the runtime object identity.
        //
        // the cycle check sits BEFORE the NormalisesForComparison
        // branch so that an implementor whose canonical form
        // references itself (via `fromNested($self, $context)`)
        // gets the same __cycle_ref protection as a plain object.
        // Marking happens here too, so by the time the implementor
        // recurses into itself the seen-set already knows.
        if ($context->hasSeen($input)) {
            return ['__cycle_ref' => $context->visitIndexOf($input)];
        }
        $context->markSeen($input);

        // special case - the object publishes its own canonical
        // form via the NormalisesForComparison contract. The
        // return value is taken as final: we do NOT recurse into
        // it, do NOT apply list-vs-map heuristics, and do NOT
        // normalise its keys or values. The implementor has
        // already chosen a representation that is canonical for
        // their state - re-running our heuristic on top would
        // undo that work for the exact shapes the contract exists
        // to handle (int-keyed dicts, identity-bearing keys, etc.).
        //
        // we still wrap with `class` so two distinct classes that
        // happen to return the same canonical body remain
        // distinguishable.
        //
        // the implementor receives `$context` so its own nested
        // `fromNested()` calls extend the same visited-set,
        // closing the cycle-safety loop across the interface
        // boundary.
        if ($input instanceof NormalisesForComparison) {
            return [
                'class' => $input::class,
                'canonical' => $input->getNormalisedForComparison($context),
            ];
        }

        // general case - reflect over the declared properties
        $retval = [
            'class' => $input::class,
            'properties' => [],
        ];

        // step 1: walk the properties visible directly on the
        // runtime class. This covers the object's own properties
        // (all visibilities) plus public/protected properties
        // inherited from any ancestor class.
        $refObject = new ReflectionObject($input);
        foreach ($refObject->getProperties() as $refProperty) {
            // correctness!
            // static properties belong to the class, not the
            // instance, so they have no place in a per-instance
            // canonical form. Including them would let unrelated
            // code mutate the comparison key between the two
            // assertion-side normalisations.
            if ($refProperty->isStatic()) {
                continue;
            }
            $key = $refProperty->getName();
            $retval['properties'][$key] = self::readPropertyValue(
                $input,
                $refProperty,
                $context,
            );
        }

        // step 2: walk the parent chain to pick up private
        // properties declared higher up. Reflection does NOT report
        // inherited privates via getProperties() on the runtime
        // class - each ancestor's private slot is a separate field
        // that only that ancestor can see. Their keys are qualified
        // with the declaring class so a parent's private $secret
        // does not collide with a child's own private $secret.
        $refParent = $refObject->getParentClass();
        while ($refParent !== false) {
            $parentName = $refParent->getName();
            foreach ($refParent->getProperties(ReflectionProperty::IS_PRIVATE) as $refProperty) {
                // correctness!
                // getProperties(IS_PRIVATE) on an ancestor can also
                // surface privates declared higher up the chain
                // (PHP's implementation walks up); restrict to the
                // ones actually declared on this ancestor so we
                // count each inherited private exactly once.
                if ($refProperty->getDeclaringClass()->getName() !== $parentName) {
                    continue;
                }
                // statics are still skipped here for the same reason
                // as in step 1.
                if ($refProperty->isStatic()) {
                    continue;
                }
                $key = $refProperty->getName() . '@' . $parentName;
                $retval['properties'][$key] = self::readPropertyValue(
                    $input,
                    $refProperty,
                    $context,
                );
            }
            $refParent = $refParent->getParentClass();
        }

        ksort($retval['properties'], SORT_STRING);

        return $retval;
    }

    /**
     * read a single property off `$input` and return its normalised
     * value.
     *
     * Pulled out as a helper so the runtime-class walk and the
     * parent-chain walk share the same uninitialised-guard and
     * recursion logic.
     */
    private static function readPropertyValue(
        object $input,
        ReflectionProperty $refProperty,
        NormalisationContext $context,
    ): mixed {
        // robustness!
        // typed properties with no default and no assignment
        // throw on `getRawValue()` - substitute a fixed sentinel
        // so canonical comparison stays usable on two-phase-
        // constructed value objects.
        if (! $refProperty->isInitialized($input)) {
            return self::UNINITIALISED_PROPERTY;
        }

        return self::fromInternal(
            $refProperty->getRawValue($input),
            $context,
        );
    }
}
