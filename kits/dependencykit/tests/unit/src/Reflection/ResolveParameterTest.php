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

namespace StusDevKit\DependencyKit\Tests\Unit\Reflection;

use ArrayObject;
use Closure;
use Countable;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;
use stdClass;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\DependencyKit\Reflection\ResolveParameter;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\CallCountingContainer;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\SampleBackedEnum;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\SelfReferencingBase;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\SelfReferencingChild;

#[TestDox(ResolveParameter::class)]
class ResolveParameterTest extends TestCase
{
    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * extract the first ReflectionParameter from a closure, so tests
     * can construct realistic parameter types without an external
     * fixture class per case
     */
    private function paramFor(Closure $fn): ReflectionParameter
    {
        return (new ReflectionFunction($fn))->getParameters()[0];
    }

    /**
     * minimal PSR-11 container backed by an array - enough for the
     * has() / get() pair that ResolveParameter actually uses
     *
     * @param array<string, mixed> $services
     */
    private function container(array $services = []): ContainerInterface
    {
        return new class ($services) implements ContainerInterface {
            /** @param array<string, mixed> $services */
            public function __construct(private array $services)
            {
            }

            public function has(string $id): bool
            {
                return array_key_exists($id, $this->services);
            }

            public function get(string $id): mixed
            {
                return $this->services[$id];
            }
        };
    }


    // ================================================================
    //
    // Untyped parameter
    //
    // ----------------------------------------------------------------

    #[TestDox('for() throws UntypedParameterException when the parameter has no declared type')]
    public function test_for_throws_when_parameter_is_untyped(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a parameter with no type hint cannot be resolved from a DI
        // container - there's nothing to match against. ResolveParameter
        // must surface this as UntypedParameterException so the caller
        // can react to the design problem rather than silently guessing.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn ($x) => $x);
        $container = $this->container();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(UntypedParameterException::class);

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameter::for($param, $container);
    }

    #[TestDox('for() prefers the untyped refusal over the variadic refusal when a parameter is both')]
    public function test_for_throws_untyped_before_variadic_when_parameter_is_both(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP allows a parameter to be both untyped and variadic
        // (`...$xs`). Two refusal conditions apply: no type to look
        // up, and variadic parameters are unsupported. The resolver
        // checks for the missing type first, so
        // UntypedParameterException wins.
        //
        // This test pins the precedence so a future refactor that
        // reshuffles the guard order can't silently flip the
        // exception class a caller sees.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (...$xs) => $xs);
        $container = $this->container();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(UntypedParameterException::class);

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameter::for($param, $container);
    }

    // ================================================================
    //
    // Variadic parameter
    //
    // ----------------------------------------------------------------

    #[TestDox('for() throws UnsupportedParameterTypeException for a variadic parameter, with paramType formatted as "Type ..."')]
    public function test_for_throws_for_variadic_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a variadic parameter `Svc ...$svcs` expresses zero-or-more,
        // which PSR-11 containers cannot enumerate (has()/get() know
        // nothing about collections). Returning an empty array
        // silently would hide the limitation from developers who
        // wrote a variadic expecting the container to populate it.
        // ResolveParameter refuses variadics explicitly instead.
        //
        // The paramType field is formatted as `"Type ..."` - the
        // type followed by a trailing `...` - so it reads like the
        // PHP declaration a developer would recognise. Earlier we
        // prefixed the ellipsis (`"...Type"`), which is not a valid
        // PHP syntax position.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (stdClass ...$svcs) => $svcs);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnsupportedParameterTypeException $e) {
            $this->assertSame(
                [
                    'paramName' => 'svcs',
                    'paramType' => 'stdClass ...',
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnsupportedParameterTypeException was not thrown');
    }

    #[TestDox('for() throws UnsupportedParameterTypeException for a variadic parameter with a union type')]
    public function test_for_throws_for_variadic_union_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // variadic parameters are legal PHP with any kind of type,
        // including unions. The refusal must still fire, and the
        // paramType payload must include the full union string
        // followed by the trailing `...` - so a developer seeing
        // the error can match it back to the exact declaration
        // that was rejected.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(
            static fn (stdClass|ArrayObject ...$svcs) => $svcs,
        );
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnsupportedParameterTypeException $e) {
            $this->assertSame(
                [
                    'paramName' => 'svcs',
                    'paramType' => 'stdClass|ArrayObject ...',
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnsupportedParameterTypeException was not thrown');
    }

    // ================================================================
    //
    // Container match (happy path)
    //
    // ----------------------------------------------------------------

    #[TestDox('for() returns the container-registered value when the type matches')]
    public function test_for_returns_container_value_on_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the primary job of ResolveParameter is to look up the
        // parameter's type in the container and return whatever is
        // registered.

        // ----------------------------------------------------------------
        // setup your test

        $service = new stdClass();
        $param = $this->paramFor(static fn (stdClass $x) => $x);
        $container = $this->container([stdClass::class => $service]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($service, $actual);
    }

    // ================================================================
    //
    // Universal / leaf type hints: mixed / object / callable /
    // iterable / true / false / null
    //
    // These type hints resolve only by exact-string container
    // lookup - there is no class hierarchy for the second pass to
    // walk, and the container cannot know "any object would do"
    // without the caller having explicitly registered something
    // under 'object' (or 'mixed', or 'callable', ...). `true`,
    // `false`, and `null` are PHP 8.2+ first-class literal types
    // that share the same no-hierarchy shape. The tests below pin
    // that exact-string behaviour, and the nullability of `mixed`
    // and standalone `null` (both of which are implicitly
    // nullable per PHP's type system).
    //
    // ----------------------------------------------------------------

    /**
     * each row is a closure with a universally-typed first
     * parameter plus the container key that the resolver is
     * expected to probe for that type
     *
     * @return array<string, array{Closure, string}>
     */
    public static function provideUniversalTypeParams(): array
    {
        return [
            'mixed'    => [static fn (mixed $service) => $service,    'mixed'],
            'object'   => [static fn (object $service) => $service,   'object'],
            'callable' => [static fn (callable $service) => $service, 'callable'],
            'iterable' => [static fn (iterable $service) => $service, 'iterable'],
            'true'     => [static fn (true $service) => $service,     'true'],
            'false'    => [static fn (false $service) => $service,    'false'],
        ];
    }

    #[TestDox('for() resolves universal / leaf type hints by exact-string container lookup')]
    #[DataProvider('provideUniversalTypeParams')]
    public function test_for_resolves_universal_type_via_direct_container_hit(
        Closure $fn,
        string $containerKey,
    ): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `mixed`, `object`, `callable`, `iterable`, `true`, and
        // `false` have no class hierarchy for the second-pass
        // expansion to walk - they're whole type-system leaves in
        // their own right (with `true` / `false` being PHP 8.2+
        // first-class literal types). The only way the resolver
        // can satisfy any of them is an exact-string first-pass
        // hit in the container. This test pins that behaviour
        // separately for each type so a future PHP release that
        // changes the reflection surface for any of them gets
        // caught here rather than as a mysterious DI failure.
        //
        // The registered `$service` is a stdClass regardless of
        // the parameter type - this test is about the resolver
        // mechanism, not whether PHP would type-check the returned
        // value at a call site.

        // ----------------------------------------------------------------
        // setup your test

        $service = new stdClass();
        $param = $this->paramFor($fn);
        $container = $this->container([$containerKey => $service]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($service, $actual);
    }

    #[TestDox('for() returns null for a `mixed` parameter when the container has no match, because mixed is implicitly nullable')]
    public function test_for_returns_null_for_mixed_when_container_has_no_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's `mixed` type explicitly includes `null` - a
        // `mixed $x` parameter reports allowsNull() = true even
        // though the declaration has no `?` prefix and no explicit
        // `|null` union member. When the container has no entry
        // for 'mixed' and no default is declared, the resolver
        // must therefore fall through to the nullable branch and
        // return null, not throw.
        //
        // Pinning this separately because it's a real semantic
        // quirk that a future refactor could easily get wrong -
        // for instance, by hard-coding a "mixed always throws on
        // miss" rule that overlooks the implicit nullability.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (mixed $service) => $service);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('for() returns null for a standalone `null` typed parameter without probing the container')]
    public function test_for_returns_null_for_standalone_null_typed_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP 8.2+ promoted `null` to a first-class standalone
        // type. A parameter typed `null $x` accepts only the value
        // null, and reports allowsNull() = true.
        //
        // Our resolver strips 'null' from the probe list before
        // asking the container anything, so this kind of parameter
        // cannot draw a value from container state at all. The
        // fallback chain takes over: no default, allowsNull() is
        // true, return null. This test pins both halves:
        //   1. the return value is null (correctness)
        //   2. the container's has() is never called for *any*
        //      key during this resolve (no stray probes for
        //      'null' or anything else)

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (null $service) => $service);
        $container = new CallCountingContainer();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
        $this->assertSame([], $container->hasCalls);
    }

    // ================================================================
    //
    // Exception propagation from $container->get()
    //
    // When the container's has() returns true but get() then throws
    // (for instance, because a sub-dependency's own constructor
    // blew up), the thrown exception must surface from
    // ResolveParameter::for() unchanged. The resolver's "not found"
    // handling uses a [found, value] tuple rather than a caught
    // exception precisely so that a real PSR-11 failure - raised
    // while the container is resolving further dependencies - is
    // never mistaken for a missing-entry signal.
    //
    // ----------------------------------------------------------------

    #[TestDox('for() lets a RuntimeException thrown by $container->get() propagate unchanged')]
    public function test_for_propagates_exception_from_container_get(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the container claims it has 'stdClass', but its get()
        // throws when actually asked. That exception must come
        // back through ResolveParameter::for() as-is - wrapping
        // it in UnresolvedParameterException (or swallowing it as
        // "not found") would hide the real failure from the
        // caller and make sub-dependency bugs near-impossible to
        // diagnose.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (stdClass $service) => $service);
        $container = new class () implements ContainerInterface {
            public function has(string $id): bool
            {
                return true;
            }

            public function get(string $id): mixed
            {
                throw new RuntimeException(
                    'sub-dependency constructor exploded',
                );
            }
        };

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('sub-dependency constructor exploded');

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameter::for($param, $container);
    }

    // ================================================================
    //
    // No match - no fallback available
    //
    // ----------------------------------------------------------------

    #[TestDox('for() throws UnresolvedParameterException carrying paramName and paramType for an unresolved class type')]
    public function test_for_throws_when_no_match_and_no_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // if the container has nothing for the type, and the caller
        // offered no fallback (neither nullable nor a default value),
        // the only correct response is to fail loudly. The exception
        // is specific to parameter resolution (not a raw PSR-11 miss)
        // so it carries both the parameter name and the declared type
        // for diagnostics.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (stdClass $service) => $service);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnresolvedParameterException $e) {
            $this->assertSame(
                [
                    'paramName' => 'service',
                    'paramType' => 'stdClass',
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnresolvedParameterException was not thrown');
    }

    // ================================================================
    //
    // Unresolved scalar parameter
    //
    // ----------------------------------------------------------------

    /**
     * each row models one PHP scalar type as the first parameter of a
     * closure, along with the paramName/paramType that the exception
     * must surface
     *
     * @return array<string, array{Closure, string, string}>
     */
    public static function provideScalarParams(): array
    {
        return [
            'int'      => [static fn (int $count) => $count,         'count',   'int'],
            'string'   => [static fn (string $name) => $name,        'name',    'string'],
            'float'    => [static fn (float $ratio) => $ratio,       'ratio',   'float'],
            'bool'     => [static fn (bool $enabled) => $enabled,    'enabled', 'bool'],
            'true'     => [static fn (true $flag) => $flag,          'flag',    'true'],
            'false'    => [static fn (false $flag) => $flag,         'flag',    'false'],
            'iterable' => [static fn (iterable $items) => $items,    'items',   'iterable'],
        ];
    }

    #[TestDox('for() throws UnresolvedParameterException carrying paramName and paramType for an unresolved scalar / leaf type')]
    #[DataProvider('provideScalarParams')]
    public function test_for_throws_for_unresolved_scalar(
        Closure $fn,
        string $expectedParamName,
        string $expectedParamType,
    ): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // scalars and other non-nullable leaf types have no class
        // hierarchy for the second-pass expansion to walk - the
        // resolver's only shot is a literal container match against
        // the type name ('int', 'string', 'true', 'iterable', ...).
        // When that misses and the caller left no default and no
        // nullable, ResolveParameter must throw an
        // UnresolvedParameterException that carries the paramName
        // and the literal type name as paramType.
        //
        // `true`, `false`, and `iterable` are included alongside
        // the traditional scalars because they share the same
        // resolver behaviour: no hierarchy, non-nullable, miss =
        // throw. Their presence here pins PHP 8.2+ first-class
        // type behaviour for DI.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor($fn);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnresolvedParameterException $e) {
            $this->assertSame(
                [
                    'paramName' => $expectedParamName,
                    'paramType' => $expectedParamType,
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnresolvedParameterException was not thrown');
    }

    // ================================================================
    //
    // Unsupported: intersection types
    //
    // An intersection type `A&B` requires a value that satisfies both
    // A and B simultaneously. A PSR-11 container's has()/get() API
    // only speaks about single-type lookups, so the resolver has no
    // way to guarantee that any registered value actually satisfies
    // every member of the intersection. Rather than silently return
    // whatever is registered under one member (which may not satisfy
    // the others, causing a TypeError at the call site), the resolver
    // refuses intersection types explicitly. Callers who need an
    // intersection-typed dependency should write an explicit factory.
    //
    // ----------------------------------------------------------------

    #[TestDox('for() throws UnsupportedParameterTypeException for an intersection type')]
    public function test_for_throws_for_intersection_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a bare intersection type `A&B` cannot be resolved safely by
        // a PSR-11 driven resolver (see section comment above). The
        // resolver must refuse it explicitly, and the exception
        // payload must carry the paramName and the full intersection
        // string as paramType so the caller can see exactly which
        // slot was refused.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(
            static fn (Countable&Iterator $probe) => $probe,
        );
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnsupportedParameterTypeException $e) {
            $this->assertSame(
                [
                    'paramName' => 'probe',
                    'paramType' => 'Countable&Iterator',
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnsupportedParameterTypeException was not thrown');
    }

    #[TestDox('for() throws UnsupportedParameterTypeException for a DNF type with any intersection branch')]
    public function test_for_throws_for_dnf_type_with_intersection_branch(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a DNF type such as `(A&B)|C` mixes a union and an
        // intersection. Even though one branch (`C`) could be
        // resolved by the normal single-type path, the resolver
        // refuses the whole declaration rather than silently ignoring
        // the intersection branch - that way the caller always gets
        // the same diagnostic for any intersection use, and never
        // sees a parameter partially respected. The whole DNF string
        // (including the parentheses PHP reports) must appear in the
        // exception payload as paramType.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(
            static fn ((Countable&Iterator)|stdClass $probe) => $probe,
        );
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnsupportedParameterTypeException $e) {
            $this->assertSame(
                [
                    'paramName' => 'probe',
                    'paramType' => '(Countable&Iterator)|stdClass',
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnsupportedParameterTypeException was not thrown');
    }

    // ================================================================
    //
    // Unresolved union type
    //
    // ----------------------------------------------------------------

    #[TestDox('for() throws UnresolvedParameterException carrying paramName and paramType for an unresolved union type')]
    public function test_for_throws_for_unresolved_union_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a union type (A|B) invites the resolver to match either
        // member. When neither member nor any of their ancestors is in
        // the container, and no fallback is declared, the resolver
        // must report the whole union verbatim as paramType so the
        // caller can see every alternative that failed - not just one
        // of them.

        // stdClass|ArrayObject is chosen because both are concrete
        // classes whose ordering PHP preserves in the stringified
        // ReflectionUnionType, keeping the assertion deterministic.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(
            static fn (stdClass|ArrayObject $probe) => $probe,
        );
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnresolvedParameterException $e) {
            $this->assertSame(
                [
                    'paramName' => 'probe',
                    'paramType' => 'stdClass|ArrayObject',
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnresolvedParameterException was not thrown');
    }

    // ================================================================
    //
    // Enum-typed parameters
    //
    // A parameter typed as an enum class behaves, from the
    // resolver's perspective, like any other class-typed
    // parameter. Enums cannot extend other classes, so the only
    // class-hierarchy entries the second pass finds are
    // BackedEnum / UnitEnum - interfaces a user would almost
    // never register in a DI container. In practice this means
    // an enum-typed parameter without a default almost always
    // falls through to UnresolvedParameterException, carrying
    // the enum's FQCN as paramType.
    //
    // Pinned as a dedicated test because enums are a type the
    // resolver does not special-case, and a future change that
    // introduced special handling (e.g. to auto-inject the
    // enum's first case as a default) would quietly change
    // behaviour for every enum-typed constructor in a downstream
    // codebase. Better to catch that here.
    //
    // ----------------------------------------------------------------

    #[TestDox('for() throws UnresolvedParameterException carrying the enum FQCN for an enum-typed parameter with no container match')]
    public function test_for_throws_for_unresolved_enum_typed_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // SampleBackedEnum is a plain backed enum. When a
        // constructor parameter is typed to it and the container
        // has no registration under either the enum's FQCN or
        // any of its ancestor interfaces (BackedEnum, UnitEnum),
        // the resolver must throw UnresolvedParameterException
        // with the enum's fully-qualified name as paramType - so
        // the caller can see exactly which enum slot failed to
        // resolve.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(
            static fn (SampleBackedEnum $status) => $status,
        );
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change / test the results

        try {
            ResolveParameter::for($param, $container);
        } catch (UnresolvedParameterException $e) {
            $this->assertSame(
                [
                    'paramName' => 'status',
                    'paramType' => SampleBackedEnum::class,
                ],
                $e->getExtra(),
            );
            return;
        }

        $this->fail('UnresolvedParameterException was not thrown');
    }

    // ================================================================
    //
    // `self` / `parent` - PHP reflection pre-resolves these
    //
    // These tests pin PHP's current behaviour: when a parameter is
    // typed `self` or `parent`, reflection reports the resolved class
    // name (e.g. `Base`), not the literal keyword. ResolveParameter
    // therefore does not need its own self/parent resolution logic -
    // the container lookup and hierarchy expansion both receive a
    // real class name.
    //
    // If a future PHP release ever changes this (exceedingly
    // unlikely, but not impossible), these tests will fail and make
    // the regression obvious, rather than letting it surface as a
    // mysterious resolution failure in production code.
    //
    // ----------------------------------------------------------------

    #[TestDox('for() resolves a `self` typed parameter by looking up the declaring class in the container')]
    public function test_for_resolves_self_typed_parameter_via_declaring_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP reflection resolves `self` to the declaring class name
        // before ResolveParameter ever sees the ReflectionNamedType -
        // so `self $service` on SelfReferencingBase appears as the
        // leaf `SelfReferencingBase`. The container lookup therefore
        // succeeds against SelfReferencingBase::class and the
        // registered instance is returned unchanged.

        // ----------------------------------------------------------------
        // setup your test

        $service = new SelfReferencingBase();
        $param = (new ReflectionMethod(
            SelfReferencingBase::class,
            'takeSelf',
        ))->getParameters()[0];
        $container = $this->container([
            SelfReferencingBase::class => $service,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($service, $actual);
    }

    #[TestDox('for() resolves a `parent` typed parameter by looking up the parent class in the container')]
    public function test_for_resolves_parent_typed_parameter_via_parent_of_declaring_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP reflection resolves `parent` to the parent class name
        // before ResolveParameter ever sees the ReflectionNamedType -
        // so `parent $service` on SelfReferencingChild (which extends
        // SelfReferencingBase) appears as the leaf
        // `SelfReferencingBase`. The container lookup therefore
        // succeeds against the parent's class-string and the
        // registered instance is returned unchanged.

        // ----------------------------------------------------------------
        // setup your test

        $service = new SelfReferencingBase();
        $param = (new ReflectionMethod(
            SelfReferencingChild::class,
            'takeParent',
        ))->getParameters()[0];
        $container = $this->container([
            SelfReferencingBase::class => $service,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($service, $actual);
    }

    // ================================================================
    //
    // Second-pass probe behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('for() does not re-probe the declared type when falling through to the second-pass hierarchy walk')]
    public function test_for_does_not_re_probe_the_declared_type_in_the_second_pass(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the container has no match for the declared type but
        // does have one of its ancestors, the resolver falls through
        // from the first pass (shallow lookup) into a second pass
        // that walks the class hierarchy. The declared type was
        // already probed in the first pass - probing it again in
        // the second pass is a pointless container round-trip.
        //
        // this test pins both halves of the correct behaviour in
        // one go:
        //   1. the ancestor match is returned (proves the second
        //      pass fires at all)
        //   2. has(SelfReferencingChild::class) is called exactly
        //      once (proves the already-probed type is skipped
        //      when the second pass iterates the expanded list)
        //
        // SelfReferencingChild extends SelfReferencingBase so a
        // container registering only the parent exercises the
        // hierarchy walk directly.

        // ----------------------------------------------------------------
        // setup your test

        $service = new SelfReferencingBase();
        $param = $this->paramFor(
            static fn (SelfReferencingChild $svc) => $svc,
        );
        $container = new CallCountingContainer([
            SelfReferencingBase::class => $service,
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($service, $actual);
        $this->assertSame(
            1,
            $container->hasCalls[SelfReferencingChild::class] ?? 0,
        );
    }

    #[TestDox('for() never probes the container for the literal key "null" when resolving a nullable parameter')]
    public function test_for_does_not_probe_null_key_for_nullable_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // FlattenReflectionType faithfully emits 'null' as a leaf
        // for `?Foo` / `Foo|null` - that's correct at its level
        // (it's documenting "what types does this parameter
        // accept"). But ResolveParameter has no use for a container
        // key called 'null': nullability is handled later by
        // $refParam->allowsNull() which returns null on fallback.
        // Probing the container for 'null' is at best a pointless
        // round-trip; at worst, it becomes a silent catch-all for
        // every nullable parameter in the codebase if someone ever
        // registers something under that literal key.
        //
        // the resolver must skip 'null' in its first-pass probe.
        // This test pins that - and pins the correctness outcome
        // (a null return on fallback) at the same time, so a
        // future refactor that broke nullability altogether would
        // also fail this test.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (?stdClass $svc) => $svc);
        $container = new CallCountingContainer();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
        $this->assertSame(
            0,
            $container->hasCalls['null'] ?? 0,
        );
    }

    // ================================================================
    //
    // Nullable fallback
    //
    // ----------------------------------------------------------------

    #[TestDox('for() returns null when the parameter is nullable and the container has no match')]
    public function test_for_returns_null_for_nullable_when_no_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a nullable parameter (?Foo) is an explicit statement from
        // the caller: "null is an acceptable value for this slot".
        // When the container can't supply a Foo, ResolveParameter must
        // honour that permission by returning null rather than
        // throwing.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (?stdClass $x) => $x);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('for() prefers the container match over the null fallback')]
    public function test_for_prefers_container_match_over_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the null fallback is a last resort. When the container
        // actually has a value for a nullable parameter's type, that
        // value must win.

        // ----------------------------------------------------------------
        // setup your test

        $service = new stdClass();
        $param = $this->paramFor(static fn (?stdClass $x) => $x);
        $container = $this->container([stdClass::class => $service]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($service, $actual);
    }

    // ================================================================
    //
    // Default-value fallback
    //
    // ----------------------------------------------------------------

    #[TestDox('for() returns the declared default value when the container has no match')]
    public function test_for_returns_default_value_when_no_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // scalar parameters like `int $x = 42` are common for
        // configuration-style values that the container typically
        // won't supply. The caller's default is a deliberate fallback
        // and must be used when no container match exists.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (int $x = 42) => $x);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('for() prefers the container match over the declared default value')]
    public function test_for_prefers_container_match_over_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default value is a fallback. When the container has
        // something registered for the type, that container-registered
        // value must win - otherwise the container's configuration has
        // no effect.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (int $x = 42) => $x);
        $container = $this->container(['int' => 99]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(99, $actual);
    }

    #[TestDox('for() prefers the declared default value over the null fallback')]
    public function test_for_prefers_default_over_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when a parameter is both nullable AND has a default value
        // (e.g. `?int $x = 42`), the caller wrote two fallbacks but
        // only one can win. The default is the stronger signal -
        // the caller wrote out the value they wanted - so it must
        // take priority over the generic null fallback.

        // ----------------------------------------------------------------
        // setup your test

        $param = $this->paramFor(static fn (?int $x = 42) => $x);
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameter::for($param, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }
}
