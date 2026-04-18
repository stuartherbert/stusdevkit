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

use Closure;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidClassException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidMethodException;

/**
 * ResolveParameters is a **permissive raw-reflection utility**.
 * Give it a function, method, constructor, or callable and a PSR-11
 * container, and it returns the values to pass in. Nothing more.
 *
 * Originally added as a helper for a reflection-based DI factory.
 *
 * Permissive means the factories only check what they need to in
 * order to run reflection: that the function/method/class exists
 * and that `method_exists()` / `class_exists()` say yes. They do
 * **not** decide whether the target is *appropriate* to resolve
 * against — that's the caller's context, not the utility's.
 *
 * The most common "is this appropriate" check a DI layer wants is
 * "can this class be instantiated at all?" For that, call
 * {@see \StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability::from}
 * before {@see forConstructor}. This utility stays out of that
 * decision so it can be re-used by callers whose context is not
 * DI-shaped.
 *
 * Exposes four static factories, each with its own narrow contract:
 *
 * - {@see forFunction} - global function name or `Closure`
 * - {@see forMethod} - object-or-class-string plus method name
 * - {@see forConstructor} - class-string for a class's constructor
 * - {@see forCallable} - any PHP `callable`, dispatching to the
 *   appropriate sibling above
 */
class ResolveParameters
{
    // ================================================================
    //
    // forFunction()
    //
    // ----------------------------------------------------------------

    /**
     * return the parameters that need to be passed into the given `$func`
     *
     * `forFunction` has a deliberately narrow contract: it accepts a
     * global function name (a plain `string` that satisfies
     * `function_exists()`) or a `Closure` (including a first-class
     * callable produced via `Foo::bar(...)` syntax, which PHP
     * materialises as a `Closure`). Nothing else.
     *
     * It does NOT accept:
     *
     * - `'Class::method'` string callables
     * - `[$target, 'method']` array callables
     * - invokable objects (objects with `__invoke`)
     *
     * All three are rejected with `InvalidFunctionException` because
     * `function_exists()` returns false for them. This is intentional:
     * `forFunction` is the "I know I have a function" entry point, and
     * silently re-routing non-function callables to `forMethod` would
     * turn a caller mistake into a quiet success.
     *
     * Use {@see forCallable} for mixed callable-shape dispatch - it
     * will route each shape to the correct factory for you.
     *
     * Here Be Dragons
     * ===============
     *
     * **Bound `Closure`s go through, but the binding is invisible
     * to reflection.**
     *
     * `ReflectionFunction` sees only the parameter list. Whatever
     * `$this` scope the closure was bound to doesn't reach the
     * resolver. If the parameter types reference services scoped
     * to that binding, the container still needs them registered
     * under their type name — being bound buys nothing on the
     * resolution side.
     *
     * First-class callables produced via `Foo::bar(...)` hit the
     * same path, since PHP materialises them as `Closure`
     * instances.
     *
     * **Inherited footguns from {@see ResolveParameter::for}** —
     * three to know before wiring this up:
     *
     * - **Union-type resolution order is best-effort.** PHP
     *   normalises the member order before the resolver sees it,
     *   so what you get isn't always what you wrote.
     * - **The literal container key `'object'` is a universal
     *   class-type fallback.** Register anything under it and
     *   every otherwise-unmatched class-typed parameter silently
     *   resolves to that service.
     * - **PSR-11 `NotFoundExceptionInterface` from a mid-resolve
     *   container failure is shape-identical to this resolver's
     *   own `UnresolvedParameterException`.** Catch the broad
     *   type first and you'll be chasing ghosts — catch
     *   `UnresolvedParameterException` first.
     *
     * Full treatment in {@see ResolveParameter}'s own
     * `Here Be Dragons`.
     *
     * @param Closure|string $func
     *   the function that you want to resolve parameters for
     * @param ContainerInterface $container
     *   the DI container to retrieve parameter values from
     * @return array<string, mixed>
     *   indexed by parameter name (as declared, no `$` prefix), in
     *   declaration order. Splat-ready with `...` and compatible
     *   with named-argument invocation. Keys are always parameter
     *   names, even when every parameter is positional.
     *
     * @throws InvalidFunctionException
     *   when `$func` is a string that does not name a declared
     *   function.
     * @throws UntypedParameterException
     *   when one of `$func`'s parameters has no declared type.
     * @throws UnsupportedParameterTypeException
     *   when one of `$func`'s parameters uses a variadic or
     *   intersection type that this resolver cannot satisfy.
     * @throws UnresolvedParameterException
     *   when the container has no match for one of `$func`'s
     *   parameters and the parameter has no default and is not
     *   nullable.
     */
    public static function forFunction(
        Closure|string $func,
        ContainerInterface $container,
    ): array
    {
        // robustness!
        if (is_string($func) && !function_exists($func)) {
            throw new InvalidFunctionException($func);
        }

        return self::forReflection(new ReflectionFunction($func), $container);
    }

    // ================================================================
    //
    // forCallable()
    //
    // ----------------------------------------------------------------

    /**
     * return the parameters that need to be passed into the given `$callable`
     *
     * `forCallable` is the entry point for mixed callable-shape input.
     * PHP's `callable` pseudo-type accepts seven shapes; this method
     * inspects the runtime value and dispatches to the appropriate
     * factory:
     *
     * - Closure (including first-class callables like `Foo::bar(...)`)
     *   → {@see forFunction}
     * - invokable object (has `__invoke`) → {@see forMethod} with
     *   method `'__invoke'`
     * - `[$target, 'method']` array (instance or static) → {@see forMethod}
     * - `'Class::method'` string → {@see forMethod} (split on `::`)
     * - plain `'function_name'` string → {@see forFunction}
     *
     * Originally added so callers holding a PHP `callable` can resolve
     * its parameters in one call, without open-coding the dispatch
     * logic at every call site. PSR-11 containers accepting factory
     * callables in multiple shapes are the motivating use case.
     *
     * Here Be Dragons
     * ===============
     *
     * **PHP's `callable` type hint is stricter than `forMethod`'s
     * signature.**
     *
     * `is_callable()` returns false for private and protected
     * methods, so this entry point only reaches public methods.
     * Callers who need to reflect on non-public methods must use
     * {@see forMethod} directly.
     *
     * **Inherited footguns from {@see ResolveParameter::for}** —
     * three to know before wiring this up:
     *
     * - **Union-type resolution order is best-effort.** PHP
     *   normalises the member order before the resolver sees it,
     *   so what you get isn't always what you wrote.
     * - **The literal container key `'object'` is a universal
     *   class-type fallback.** Register anything under it and
     *   every otherwise-unmatched class-typed parameter silently
     *   resolves to that service.
     * - **PSR-11 `NotFoundExceptionInterface` from a mid-resolve
     *   container failure is shape-identical to this resolver's
     *   own `UnresolvedParameterException`.** Catch the broad
     *   type first and you'll be chasing ghosts — catch
     *   `UnresolvedParameterException` first.
     *
     * Full treatment in {@see ResolveParameter}'s own
     * `Here Be Dragons`.
     *
     * @param callable $callable
     *   the callable that you want to resolve parameters for
     * @param ContainerInterface $container
     *   the DI container to retrieve parameter values from
     *
     * @return array<string, mixed>
     *   indexed by parameter name (as declared, no `$` prefix), in
     *   declaration order. Splat-ready with `...` and compatible
     *   with named-argument invocation. Keys are always parameter
     *   names, even when every parameter is positional.
     *
     * @throws InvalidFunctionException
     *   when `$callable` dispatches to {@see forFunction} and the
     *   string does not name a declared function.
     * @throws InvalidMethodException
     *   when `$callable` dispatches to {@see forMethod} and the
     *   target does not declare the named method.
     * @throws UntypedParameterException
     *   when the resolved target has a parameter with no declared
     *   type.
     * @throws UnsupportedParameterTypeException
     *   when the resolved target has a parameter with a variadic or
     *   intersection type that this resolver cannot satisfy.
     * @throws UnresolvedParameterException
     *   when the container has no match for one of the resolved
     *   target's parameters and the parameter has no default and
     *   is not nullable.
     */
    public static function forCallable(
        callable $callable,
        ContainerInterface $container,
    ): array
    {
        // general case - Closure (covers first-class callables too)
        if ($callable instanceof Closure) {
            return self::forFunction($callable, $container);
        }

        // special case - invokable object (has __invoke)
        if (is_object($callable)) {
            return self::forMethod($callable, '__invoke', $container);
        }

        // special case - [$target, 'method'] array (instance or static)
        if (is_array($callable)) {
            /** @var array{0: object|string, 1: string} $callable */
            [$target, $method] = $callable;
            return self::forMethod($target, $method, $container);
        }

        // if we get here, we've already ruled out every other shape for
        // `callable`
        //
        // keep phpstan happy
        assert(is_string($callable));

        // special case - 'Class::method' string (static method by name)
        if (str_contains($callable, '::')) {
            [$class, $method] = explode('::', $callable, 2);
            return self::forMethod($class, $method, $container);
        }

        // if we get here, the callable is a plain function name
        return self::forFunction($callable, $container);
    }

    // ================================================================
    //
    // forMethod()
    //
    // ----------------------------------------------------------------

    /**
     * return the parameters that need to be passed into the given
     * `$method` of `$target`. The parameters come from the given
     * PSR-11 DI `$container`.
     *
     * This inspects the parameters accepted by the given method,
     * and uses their types to find matching values in the given
     * DI container.
     *
     * Here Be Dragons
     * ===============
     *
     * **`forMethod` is visibility-blind — on purpose.**
     *
     * `method_exists()` returns true for public, protected, and
     * private methods alike, and `ReflectionMethod` reflects any
     * of them. That's deliberate: callers writing factories for
     * their own classes sometimes need to resolve a private
     * constructor-helper.
     *
     * The cost: a typo that happens to collide with a private
     * method on the target gets silently accepted here, when the
     * caller almost certainly meant a public one. If you want
     * visibility enforcement, route through {@see forCallable}
     * instead — PHP's `callable` type hint rejects non-public
     * methods at the boundary.
     *
     * **`__call` / `__callStatic` virtual methods hit a mismatch.**
     *
     * `method_exists()` can't see through `__call`, so this
     * factory throws `InvalidMethodException` with "method does
     * not exist" — right from reflection's perspective, unhelpful
     * from the caller's. Magic-method dispatch is out of scope
     * for reflection-based parameter resolution: there's nothing
     * to inspect until PHP conjures the method at call time.
     *
     * **Inherited footguns from {@see ResolveParameter::for}** —
     * three to know before wiring this up:
     *
     * - **Union-type resolution order is best-effort.** PHP
     *   normalises the member order before the resolver sees it,
     *   so what you get isn't always what you wrote.
     * - **The literal container key `'object'` is a universal
     *   class-type fallback.** Register anything under it and
     *   every otherwise-unmatched class-typed parameter silently
     *   resolves to that service.
     * - **PSR-11 `NotFoundExceptionInterface` from a mid-resolve
     *   container failure is shape-identical to this resolver's
     *   own `UnresolvedParameterException`.** Catch the broad
     *   type first and you'll be chasing ghosts — catch
     *   `UnresolvedParameterException` first.
     *
     * Full treatment in {@see ResolveParameter}'s own
     * `Here Be Dragons`.
     *
     * @param object|string $target
     *   the object or class where the method is defined
     * @param string $method
     *   the method on $target that you want to resolve parameters for
     * @param ContainerInterface $container
     *   the DI container to retrieve parameter values from
     *
     * @return array<string, mixed>
     *   indexed by parameter name (as declared, no `$` prefix), in
     *   declaration order. Splat-ready with `...` and compatible
     *   with named-argument invocation. Keys are always parameter
     *   names, even when every parameter is positional.
     *
     * @throws InvalidMethodException
     *   when `$target` (or its class) does not declare `$method`
     *   (including `__call`-dispatched virtual methods — see
     *   `Here Be Dragons`).
     * @throws UntypedParameterException
     *   when one of `$method`'s parameters has no declared type.
     * @throws UnsupportedParameterTypeException
     *   when one of `$method`'s parameters uses a variadic or
     *   intersection type that this resolver cannot satisfy.
     * @throws UnresolvedParameterException
     *   when the container has no match for one of `$method`'s
     *   parameters and the parameter has no default and is not
     *   nullable.
     */
    public static function forMethod(
        object|string $target,
        string $method,
        ContainerInterface $container,
    ): array
    {
        // robustness!
        if (!method_exists($target, $method)) {
            $className = is_string($target) ? $target : $target::class;
            throw new InvalidMethodException($className, $method);
        }

        return self::forReflection(new ReflectionMethod($target, $method), $container);
    }

    // ================================================================
    //
    // forConstructor()
    //
    // ----------------------------------------------------------------

    /**
     * return the parameters that need to be passed into the constructor of
     * `$class`
     *
     * Inspects the parameters accepted by the given class's constructor,
     * and uses their types to find values in the given DI container.
     *
     * Here Be Dragons
     * ===============
     *
     * **`forConstructor` doesn't check whether the ctor is
     * callable.**
     *
     * The class may be:
     *
     * - abstract (cannot instantiate)
     * - an enum (no public constructor)
     * - a class with a private constructor
     *
     * This factory reflects the ctor parameters of any of the
     * above and hands back a perfectly good splat-ready array.
     * The splat works. It's the `new` that trips over the
     * engine's restriction, with a raw PHP `Error` ("Cannot
     * instantiate abstract class …", "Call to private … from
     * global scope", etc.). The stack trace points at the
     * caller's `new` line, not this factory — easy to miss when
     * triaging something else.
     *
     * Call
     * {@see \StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability::from}
     * first to see if there's any point in calling
     * `forConstructor`.
     *
     * **Empty-array return is ambiguous.**
     *
     * `[]` can mean either:
     *
     * - the class declares no explicit constructor at all (PHP
     *   supplies an implicit zero-arg ctor), or
     * - the class declares a constructor that takes zero
     *   parameters.
     *
     * Both are "nothing to inject" from the resolver's point of
     * view, and both produce `[]`. Callers that need to
     * distinguish the two must reflect on the class directly.
     *
     * **Inherited footguns from {@see ResolveParameter::for}** —
     * three to know before wiring this up:
     *
     * - **Union-type resolution order is best-effort.** PHP
     *   normalises the member order before the resolver sees it,
     *   so what you get isn't always what you wrote.
     * - **The literal container key `'object'` is a universal
     *   class-type fallback.** Register anything under it and
     *   every otherwise-unmatched class-typed parameter silently
     *   resolves to that service.
     * - **PSR-11 `NotFoundExceptionInterface` from a mid-resolve
     *   container failure is shape-identical to this resolver's
     *   own `UnresolvedParameterException`.** Catch the broad
     *   type first and you'll be chasing ghosts — catch
     *   `UnresolvedParameterException` first.
     *
     * Full treatment in {@see ResolveParameter}'s own
     * `Here Be Dragons`.
     *
     * @param string $class
     *   any string; the factory reports `InvalidClassException` for
     *   strings that do not name a declared class. PHPStan callers
     *   will usually be handing in a `class-string`, but a plain
     *   `string` is accepted by the docblock to match the runtime
     *   behaviour and permit "is this a class at all?" checks.
     * @param ContainerInterface $container
     *   the DI container to retrieve parameter values from
     * @return array<string, mixed>
     *   indexed by parameter name (as declared, no `$` prefix), in
     *   declaration order. Splat-ready with `...` and compatible
     *   with named-argument invocation. Keys are always parameter
     *   names, even when every parameter is positional.
     *
     * @throws InvalidClassException
     *   when `$class` does not name a declared class.
     * @throws UntypedParameterException
     *   when one of the constructor's parameters has no declared
     *   type.
     * @throws UnsupportedParameterTypeException
     *   when one of the constructor's parameters uses a variadic or
     *   intersection type that this resolver cannot satisfy.
     * @throws UnresolvedParameterException
     *   when the container has no match for one of the constructor's
     *   parameters and the parameter has no default and is not
     *   nullable.
     */
    public static function forConstructor(
        string $class,
        ContainerInterface $container,
    ): array
    {
        // robustness!
        if (!class_exists($class)) {
            throw new InvalidClassException($class);
        }

        $ctor = (new ReflectionClass($class))->getConstructor();
        if ($ctor === null) {
            return [];
        }

        return self::forReflection($ctor, $container);
    }

    // ================================================================
    //
    // Internals
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, mixed>
     */
    private static function forReflection(
        ReflectionFunctionAbstract $ref,
        ContainerInterface $container,
    ): array
    {
        // our return value
        $retval = [];

        // find the params for our function or method
        $refParams = $ref->getParameters();

        // get the value for each param from the given DI container
        foreach ($refParams as $refParam) {
            $paramName = $refParam->getName();
            $retval[$paramName] = ResolveParameter::for($refParam, $container);
        }

        // all done
        return $retval;
    }
}
