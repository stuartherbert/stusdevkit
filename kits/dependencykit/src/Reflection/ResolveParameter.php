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
namespace StusDevKit\DependencyKit\Reflection;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\MissingBitsKit\Reflection\FlattenReflectionType;
use StusDevKit\MissingBitsKit\Reflection\IntersectionTypesNotSupportedException;
use StusDevKit\MissingBitsKit\TypeInspectors\FlattenClassTypes;

/**
 * ResolveParameter turns a single ReflectionParameter into the value
 * that should be passed to that parameter, by consulting a PSR-11
 * dependency container and falling back to whatever the parameter
 * declaration allows (a declared default, `null` for a nullable
 * parameter, or a thrown exception if neither applies).
 *
 * Here Be Dragons.
 * ================
 *
 * **Union-type resolution order is best-effort, not a contract.**
 *
 * When a parameter is declared with a union type such as `A|B`, the
 * obvious expectation is "try `A` first, then `B`". We do our best
 * to honour that: the resolver takes the member order from the text
 * representation PHP produces for the union, which - on current PHP
 * versions - preserves declaration order for **class-only** unions.
 *
 * However:
 *
 *  - For **scalar-only** unions (e.g. `int|string`) and **mixed**
 *    unions (e.g. `stdClass|int`), PHP normalises the order at
 *    parse time, so declaration order is already lost before we
 *    see it. The resolver picks whatever order PHP reports, which
 *    is deterministic on a given PHP version but does not match
 *    what the developer wrote.
 *  - That text-based ordering is **not a cross-version
 *    guarantee**. A future PHP release could in principle change
 *    how it canonicalises unions, and the resolution order would
 *    shift with it.
 *
 * If the resolution order of a union-typed parameter is
 * load-bearing for your application, **do not rely on this**.
 * Either avoid union types on DI-injected parameters, or write a
 * dedicated factory that makes the choice explicit in your own
 * code.
 *
 * **Registering a service under the key `'object'` is a universal
 * class-type fallback.**
 *
 * For any class-typed parameter that misses its own hierarchy
 * lookup, the resolver's second pass eventually probes the
 * container for the literal key `'object'` - because `object` is
 * a real PHP type and a class value legitimately satisfies it.
 * This means that if you register a service under
 * `$container->set('object', …)`, **every** class-typed DI
 * resolution in your codebase that otherwise would fail falls
 * back to that service. That may be exactly what you want (a
 * deliberate "default object" for `object $x` parameters) - or it
 * may be an accidental catch-all that silently swallows real
 * resolution bugs.
 *
 * Rule of thumb: do not register anything under the key `'object'`
 * unless you mean for it to act as a universal class-type
 * fallback, and you accept that any missed resolution elsewhere
 * in the codebase will silently pick it up.
 *
 * **`NotFoundExceptionInterface` from `$container->get()` is
 * indistinguishable from the resolver's own
 * `UnresolvedParameterException` at a PSR-11 catch site.**
 *
 * `UnresolvedParameterException` deliberately implements
 * `NotFoundExceptionInterface` so that callers who only speak
 * PSR-11 can treat "no fallback available" as the same kind of
 * failure as any other container miss. The cost of that choice
 * is that a PSR-11-compliant exception thrown by the container
 * while resolving a sub-dependency of a matched id will *also*
 * implement `NotFoundExceptionInterface`, and a naive
 * `catch (NotFoundExceptionInterface $e)` cannot tell the two
 * apart. One of them is "the resolver ran out of fallbacks";
 * the other is "the container blew up mid-resolve". Those are
 * very different bugs, and conflating them will have you
 * chasing ghosts.
 *
 * If you need to tell the two cases apart, catch
 * `UnresolvedParameterException` **first**, and only then fall
 * through to a broader `NotFoundExceptionInterface` handler.
 *
 * Deliberately Out Of Scope.
 * ==========================
 *
 * The following features are **not** part of this resolver's
 * contract, and PRs adding them should be discussed and
 * consciously accepted before being merged - not slipped in as
 * "obvious next steps". The resolver is intentionally minimal so
 * that its behaviour stays predictable for many years; each
 * feature below trades some of that predictability away.
 *
 *  - **Attribute-driven overrides (`#[Inject('foo')]` and
 *    friends).** The resolver consults the declared type and
 *    nothing else. It deliberately does not read attributes off
 *    the parameter, the method, or the declaring class. Callers
 *    who need attribute-driven behaviour should build it in a
 *    layer above this resolver, where the policy is explicit
 *    and inspectable.
 *  - **Named-parameter lookup.** The resolver looks up container
 *    ids by type name, never by parameter name. A parameter
 *    `string $dsn` is not resolved by asking the container for
 *    `"dsn"`. Name-based DI is powerful but couples the caller's
 *    local variable naming to the container's key namespace, and
 *    makes rename refactors quietly unsafe. If you need
 *    name-based lookup, write an explicit factory.
 *  - **Autowiring of unregistered classes.** If a class type is
 *    not registered in the container (directly or via one of its
 *    ancestors), the resolver does not attempt to recursively
 *    construct it. Implicit construction hides dependency graphs
 *    from the container configuration, which is exactly where
 *    they should be visible. Register the class, or write a
 *    factory.
 *  - **Caching of resolved values or reflection output.** The
 *    resolver re-runs reflection on every call and re-probes the
 *    container every time. Callers who need caching should cache
 *    the *result* of a full `ResolveParameters::for*()` pass at
 *    their own layer, where the cache key and invalidation
 *    strategy are explicit. Per-parameter caching inside the
 *    resolver would couple its lifetime to the container's and
 *    introduce subtle staleness bugs.
 *  - **Scalar-value resolution by convention (env vars,
 *    config keys, etc.).** A parameter `string $apiKey` is
 *    resolved by asking the container for the id `"string"`, not
 *    by looking up `$_ENV['API_KEY']` or reading a config file.
 *    Convention-based scalar resolution is a separate concern
 *    and belongs in a dedicated config-binding layer.
 *
 * If a use case here feels essential, the right move is to open
 * the discussion at the kit level before touching this class -
 * not to widen the resolver's behaviour and hope nobody notices.
 */
class ResolveParameter
{
    public static function for(
        ReflectionParameter $refParam,
        ContainerInterface $container,
    ): mixed
    {
        $paramName = $refParam->getName();
        $refType = $refParam->getType();

        // special case: no type in the PHP code
        if ($refType === null) {
            throw new UntypedParameterException($paramName);
        }

        // special case: variadic parameter
        //
        // `Svc ...$svcs` expresses zero-or-more, which PSR-11
        // containers cannot enumerate via has()/get(). Rather than
        // silently returning an empty collection and hiding the
        // limitation from the developer, refuse the parameter
        // explicitly so the mismatch is loud.
        //
        // The paramType is formatted with a trailing `...` (e.g.
        // `"Svc ..."`, `"int|string ..."`) to mirror PHP's own
        // syntax - `...` sits between the type and the variable,
        // never as a prefix. A developer skimming the error can
        // match the string back to the declaration in source.
        if ($refParam->isVariadic()) {
            throw new UnsupportedParameterTypeException(
                paramName: $paramName,
                paramType: (string)$refType . ' ...',
            );
        }

        // we need a list of types to resolve against
        //
        // FlattenReflectionType refuses intersection types (including
        // DNF branches) because it can't faithfully represent them
        // in a flat name list - see its docblock for the rationale.
        // That refusal happens to line up exactly with what the
        // resolver needs: an intersection `A&B` cannot be satisfied
        // by PSR-11's single-type has()/get() lookups anyway, so
        // receiving the exception from the flattener is our cue to
        // translate it into a DI-shaped UnsupportedParameterTypeException
        // at the boundary.
        try {
            $types = FlattenReflectionType::from($refType);
        } catch (IntersectionTypesNotSupportedException) {
            throw new UnsupportedParameterTypeException(
                paramName: $paramName,
                paramType: (string)$refType,
            );
        }

        // strip 'null' from the type list before probing the
        // container. FlattenReflectionType emits 'null' as a leaf
        // for nullable parameters (`?Foo`, `Foo|null`), which is
        // correct at its level ("what types does this parameter
        // accept?"). But the container has nothing meaningful to
        // offer against the literal key 'null' - nullability is
        // handled below via $refParam->allowsNull(), which returns
        // null on fallback. Worse, if someone ever registers
        // anything under 'null', it would silently become a
        // catch-all for every nullable parameter in the codebase.
        //
        // gate on in_array() first: most parameters are not
        // nullable, so the common case is a single short-circuit
        // C-level scan and no array rebuild at all.
        if (in_array('null', $types, true)) {
            $types = array_values(array_diff($types, ['null']));
        }

        // attempt a container match - tryResolveType returns a
        // [found, value] pair so that a "not found" never travels as
        // an exception and can't mask a real PSR-11 failure thrown by
        // the container while it was resolving a sub-dependency
        [$found, $value] = self::tryResolveType(
            container: $container,
            types: $types
        );
        if ($found) {
            return $value;
        }

        // the container did not match - fall back to whatever the
        // caller made available in the parameter declaration

        // declared default wins over nullability: the caller wrote
        // out the exact value they wanted, whereas `?Foo` only
        // declares permission to be null
        if ($refParam->isDefaultValueAvailable()) {
            return $refParam->getDefaultValue();
        }

        // nullable parameter - `?Foo` / `Foo|null` explicitly permits
        // null, so we return null instead of throwing
        if ($refParam->allowsNull()) {
            return null;
        }

        // no fallback available - include both the parameter name and
        // its declared type so the caller can identify the exact slot
        // that failed (matters when several parameters of the same
        // constructor share a type)
        throw new UnresolvedParameterException(
            paramName: $paramName,
            paramType: (string)$refType,
        );
    }

    /**
     * @param list<string> $types
     * @return array{0: bool, 1: mixed}
     *   [true, $value] on a container match, [false, null] on a miss
     */
    private static function tryResolveType(
        ContainerInterface $container,
        array $types,
    ): array
    {
        // first pass - go with the shallow types
        foreach ($types as $type) {
            if ($container->has($type)) {
                return [true, $container->get($type)];
            }
        }

        // second pass - expand any class-like leaves into their
        // parent classes, interfaces and traits, and look for a
        // container match against any of those. FlattenClassTypes
        // owns the deduplication, so union members sharing an
        // ancestor no longer produce duplicate container probes.
        //
        // the first pass already probed every entry in $types, so
        // we array_diff those out of the expansion - no point
        // asking the container about the same id twice in the
        // same resolve.
        $ancestors = array_diff(
            FlattenClassTypes::from($types),
            $types
        );
        foreach ($ancestors as $type) {
            if ($container->has($type)) {
                return [true, $container->get($type)];
            }
        }

        // no match
        return [false, null];
    }
}
