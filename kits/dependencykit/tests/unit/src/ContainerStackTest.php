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

namespace StusDevKit\DependencyKit\Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DependencyKit\ContainerStack;
use StusDevKit\DependencyKit\Exceptions\DependencyNotFoundException;

/**
 * Contract + behaviour tests for ContainerStack.
 *
 * ContainerStack is a PSR-11 container that delegates to a stack of
 * inner PSR-11 containers. Callers push a short-lived container on
 * top (test mocks, route model bindings, per-request overrides) and
 * the stack searches top-down on `has()` / `get()`, so overrides
 * win over long-lived bindings without anyone having to mutate the
 * underlying containers.
 *
 * These tests pin the class identity (namespace, kind, implements
 * ContainerInterface, published method set), the shape of each
 * public method, and the observable behaviour of push / pop / has /
 * get against a minimal in-memory PSR-11 container defined inline
 * below.
 */
#[TestDox(ContainerStack::class)]
class ContainerStackTest extends TestCase
{
    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * minimal PSR-11 container backed by an array. ContainerStack
     * only touches the `has()` / `get()` pair on its inner
     * containers, so this inline fixture is enough for every test in
     * this file.
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
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\DependencyKit namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import ContainerStack by FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DependencyKit';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(ContainerStack::class))
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

        // ContainerStack is a concrete instantiable class - not a
        // trait, not an interface, not an enum. Pinning this prevents
        // a silent reshape from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum())
            && (! $reflection->isAbstract());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('implements Psr\Container\ContainerInterface')]
    public function test_implements_psr_container_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ContainerStack is PSR-11 by design - framework code that
        // accepts a ContainerInterface must be able to use a
        // ContainerStack as a drop-in replacement for any other
        // PSR-11 container. Dropping the implements clause would
        // silently break every integration that relies on structural
        // PSR-11 typing.

        // ----------------------------------------------------------------
        // setup your test

        $interfaces = (new ReflectionClass(ContainerStack::class))
            ->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains(ContainerInterface::class, $interfaces);
    }

    #[TestDox('exposes only __construct(), push(), pop(), get() and has() as public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ContainerStack's public surface is the PSR-11 pair plus the
        // stack-management pair (push/pop) plus the constructor. Any
        // new public method is a surface-area expansion that every
        // caller inherits, so the method set is pinned by enumeration
        // - an addition fails with a diff that names the new method.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct', 'push', 'pop', 'get', 'has'];
        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === ContainerStack::class,
            ),
        ));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::__construct() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() is declared')]
    public function test_construct_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor is what wires the initial container list -
        // pinning its declaration guards against an accidental
        // removal that would silently fall back on the compiler
        // default.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('__construct');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() is public')]
    public function test_construct_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor must be public so callers can `new
        // ContainerStack([...])`. A protected or private constructor
        // would make the class uninstantiable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $containers as its only parameter')]
    public function test_construct_declares_containers_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every call site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['containers'];
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() declares $containers as array')]
    public function test_construct_declares_containers_as_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `$containers` is declared as `array` at PHP level; its
        // PHPDoc narrowing to `ContainerInterface[]` is carried
        // separately. Narrowing the PHP type would break callers who
        // already satisfy the PHPDoc but not a stricter native type.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $param = (new ReflectionClass(ContainerStack::class))
            ->getMethod('__construct')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->push() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->push() is declared')]
    public function test_push_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `push` is the entry point for adding a short-lived
        // container on top of the stack. Pinning its declaration
        // guards against an accidental rename or removal.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('push');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->push() is public')]
    public function test_push_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the stack-management API is callable from user code - a
        // visibility downgrade would silently break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('push');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->push() declares $container as its only parameter')]
    public function test_push_declares_container_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding or renaming parameters is a breaking change
        // for every named-argument call site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['container'];
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('push');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->push() declares $container as ContainerInterface')]
    public function test_push_declares_container_as_ContainerInterface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pinning the parameter type means the stack only ever holds
        // PSR-11 containers - any non-PSR-11 implementation is
        // rejected at call time rather than blowing up inside the
        // `has()` / `get()` search loop.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ContainerInterface::class;
        $param = (new ReflectionClass(ContainerStack::class))
            ->getMethod('push')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->push() declares a void return type')]
    public function test_push_declares_a_void_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `push` is a mutator - `void` makes it clear there is no
        // useful return value. Widening to `self` or `static` would
        // invite a fluent-chaining style that the method is not
        // designed to support.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'void';
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('push');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->pop() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->pop() is declared')]
    public function test_pop_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `pop` is the entry point for removing the top of the stack.
        // Pinning its declaration guards against accidental rename
        // or removal.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('pop');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->pop() is public')]
    public function test_pop_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the stack-management API is callable from user code - a
        // visibility downgrade would silently break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('pop');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->pop() declares no parameters')]
    public function test_pop_declares_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `pop` removes the top of the stack - the caller does not
        // pick which container to remove. Pinning the empty parameter
        // set means a later addition of a "which layer?" parameter
        // fails this test rather than silently changing the call
        // contract.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('pop');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->pop() declares a nullable ContainerInterface return type')]
    public function test_pop_declares_a_nullable_ContainerInterface_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `pop` returns the removed container, or null when the stack
        // was already empty. Callers rely on the `null` case to mean
        // "stack drained" - widening to non-nullable or narrowing to
        // a different type would break that signal.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('pop');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $returnType->getName();
        $actualNullable = $returnType->allowsNull();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(ContainerInterface::class, $actualName);
        $this->assertTrue($actualNullable);
    }

    // ================================================================
    //
    // ->get() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() is declared')]
    public function test_get_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `get` is half of the PSR-11 pair - pinning its declaration
        // guards against accidental rename or removal.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('get');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->get() is public')]
    public function test_get_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 mandates `public function get(string $id)` - a
        // visibility downgrade would break conformance.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('get');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->get() declares $id as its only parameter')]
    public function test_get_declares_id_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins the parameter name as `$id`. Keeping the exact
        // name matters for named-argument call sites that try to
        // stay PSR-compatible.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['id'];
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('get');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->get() declares $id as string')]
    public function test_get_declares_id_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins `$id` as string. Widening or narrowing would
        // break structural conformance against ContainerInterface.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(ContainerStack::class))
            ->getMethod('get')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->get() declares a mixed return type')]
    public function test_get_declares_a_mixed_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11's `get()` may return anything the container holds -
        // `mixed` is the widest honest return type. Narrowing it
        // would lie about the method's contract.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'mixed';
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('get');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->has() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() is declared')]
    public function test_has_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `has` is the other half of the PSR-11 pair - pinning its
        // declaration guards against accidental rename or removal.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ContainerStack::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('has');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->has() is public')]
    public function test_has_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 mandates `public function has(string $id): bool` -
        // a visibility downgrade would break conformance.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('has');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->has() declares $id as its only parameter')]
    public function test_has_declares_id_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins the parameter name as `$id`. Keeping the exact
        // name matters for named-argument call sites.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['id'];
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('has');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->has() declares $id as string')]
    public function test_has_declares_id_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins `$id` as string - same type as on `get()` so
        // the pair stays symmetrical.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(ContainerStack::class))
            ->getMethod('has')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->has() declares a bool return type')]
    public function test_has_declares_a_bool_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins `has()` as returning bool. Any other return
        // type would break structural conformance against
        // ContainerInterface.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'bool';
        $method = (new ReflectionClass(ContainerStack::class))
            ->getMethod('has');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts an empty container list')]
    public function test_construct_accepts_an_empty_container_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a zero-length stack is a legitimate starting state -
        // callers can build it up with push() calls later. The
        // constructor must not reject it.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ContainerStack([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ContainerStack::class, $unit);
    }

    #[TestDox('::__construct() accepts a non-empty container list')]
    public function test_construct_accepts_a_non_empty_container_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the common wiring path: one or more base containers passed
        // in up front. Pinning instantiation guards against a silent
        // regression (e.g. a required parameter added upstream).

        // ----------------------------------------------------------------
        // setup your test

        $inner = $this->container(['service.a' => 'value-a']);

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ContainerStack([$inner]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ContainerStack::class, $unit);
    }

    // ================================================================
    //
    // ->has() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() returns false when the stack is empty')]
    public function test_has_returns_false_when_stack_is_empty(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no inner containers, no id can ever be satisfied.
        // has() must report false for any id without raising any
        // warning or error.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('anything');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->has() returns true when the top container provides the id')]
    public function test_has_returns_true_when_top_container_provides_the_id(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy-path case: an id lives in the top (most recently
        // pushed) container. has() must walk the stack and return
        // true.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container(['service.a' => 'value-a']);
        $unit = new ContainerStack([$top]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->has() returns true when a lower container provides the id')]
    public function test_has_returns_true_when_lower_container_provides_the_id(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // if the top container does not know the id, the stack must
        // fall through to the next layer. This test proves the walk
        // continues past the first miss.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container([]);
        $bottom = $this->container(['service.a' => 'value-a']);
        $unit = new ContainerStack([$top, $bottom]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->has() returns false when no container in the stack provides the id')]
    public function test_has_returns_false_when_no_container_provides_the_id(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // if every inner container reports has() === false, the
        // stack itself must report has() === false. This is the
        // contract PSR-11 callers rely on to decide whether a
        // get() call is safe.

        // ----------------------------------------------------------------
        // setup your test

        $first = $this->container([]);
        $second = $this->container(['service.a' => 'value-a']);
        $unit = new ContainerStack([$first, $second]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('service.missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->has() returns the cached result on a repeat hit')]
    public function test_has_returns_cached_result_on_repeat_hit(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the stack remembers which layer satisfied each id, so a
        // second has() for the same id must return the same answer
        // without rewalking the stack. Observationally we prove this
        // by asserting the second call still returns true.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container(['service.a' => 'value-a']);
        $unit = new ContainerStack([$top]);
        $unit->has('service.a');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->has() returns false on a repeat miss for the same id')]
    public function test_has_returns_false_on_repeat_miss(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the stack also remembers which ids are definitively
        // unresolvable - the second has() for the same missing id
        // must still report false. This is the path that most
        // exercises the `unresolvedMap` short-circuit, and in
        // particular that the lookup tolerates "seen before" versus
        // "never seen" without raising warnings.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container([]);
        $unit = new ContainerStack([$top]);
        $unit->has('service.missing');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('service.missing');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    // ================================================================
    //
    // ->get() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() returns the value from the top container when the id lives there')]
    public function test_get_returns_value_from_top_container(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy-path case: id lives in the top container, so
        // get() must return that container's value.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container(['service.a' => 'value-a']);
        $unit = new ContainerStack([$top]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->get('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('value-a', $actual);
    }

    #[TestDox('->get() returns the top container\'s value when multiple layers provide the same id')]
    public function test_get_returns_top_value_when_layers_overlap(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the whole point of the stack is that later push() calls
        // override earlier bindings. When two layers provide the
        // same id, get() must return the top layer's value - that is
        // the override semantic test fixtures and route bindings
        // rely on.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container(['service.a' => 'override']);
        $bottom = $this->container(['service.a' => 'baseline']);
        $unit = new ContainerStack([$top, $bottom]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->get('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('override', $actual);
    }

    #[TestDox('->get() returns the value from a lower container when the top has no binding')]
    public function test_get_returns_value_from_lower_container(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the top container does not know an id, the stack must
        // fall through to the next layer and return whatever that
        // layer provides. Without this fall-through, short-lived
        // overrides would have to duplicate every base binding.

        // ----------------------------------------------------------------
        // setup your test

        $top = $this->container([]);
        $bottom = $this->container(['service.a' => 'value-a']);
        $unit = new ContainerStack([$top, $bottom]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->get('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('value-a', $actual);
    }

    #[TestDox('->get() throws DependencyNotFoundException when no container provides the id')]
    public function test_get_throws_when_id_is_unknown(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 requires `get($id)` to throw a
        // NotFoundExceptionInterface when the id is unknown.
        // ContainerStack specifically throws
        // DependencyNotFoundException (which implements the PSR
        // marker) so the caller gets a structured RFC 9457 diagnostic
        // carrying the requested id in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([$this->container([])]);
        $this->expectException(DependencyNotFoundException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->get('service.missing');
    }

    #[TestDox('->get() throws DependencyNotFoundException when the stack is empty')]
    public function test_get_throws_when_stack_is_empty(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a zero-length stack cannot resolve anything, so every
        // get() must throw. This is the degenerate case of the test
        // above - worth pinning separately because empty-stack
        // handling has historically been a footgun (off-by-one on
        // the search loop, uninitialised cache map, etc.).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([]);
        $this->expectException(DependencyNotFoundException::class);

        // ----------------------------------------------------------------
        // perform the change

        $unit->get('service.missing');
    }

    // ================================================================
    //
    // ->push() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->push() makes the newly-pushed container\'s bindings visible to ->has()')]
    public function test_push_makes_new_bindings_visible_to_has(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pushing a new container must immediately widen what the
        // stack reports as "known". Without this, short-lived
        // overrides pushed after construction would be invisible.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([$this->container([])]);
        $unit->push($this->container(['service.a' => 'value-a']));

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->has('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->push() makes the newly-pushed container override existing bindings on ->get()')]
    public function test_push_overrides_existing_bindings_on_get(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // push() places the new container on top of the stack. A
        // subsequent get() for an id that the new container provides
        // must return the new container's value, not a lower layer's.
        // This is the override semantic that test fixtures rely on.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([
            $this->container(['service.a' => 'baseline']),
        ]);
        $unit->push($this->container(['service.a' => 'override']));

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->get('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('override', $actual);
    }

    // ================================================================
    //
    // ->pop() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->pop() returns null when the stack is empty')]
    public function test_pop_returns_null_when_stack_is_empty(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the nullable return type exists specifically for this
        // case - a caller popping a drained stack gets null rather
        // than an exception. Pinning the null-on-empty behaviour
        // means any future change to "throw on empty" would be a
        // deliberate decision, not a silent drift.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->pop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('->pop() returns the most-recently-pushed container')]
    public function test_pop_returns_the_most_recently_pushed_container(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // LIFO semantic: the container returned by pop() is the one
        // most recently pushed. This is what lets test harnesses
        // restore the previous state after a scoped override.

        // ----------------------------------------------------------------
        // setup your test

        $baseline = $this->container(['service.a' => 'baseline']);
        $override = $this->container(['service.a' => 'override']);
        $unit = new ContainerStack([$baseline]);
        $unit->push($override);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->pop();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($override, $actual);
    }

    #[TestDox('->pop() removes the top container so ->get() falls through to the next layer')]
    public function test_pop_removes_the_top_container(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // after pop(), a subsequent get() for an id that the popped
        // container was overriding must fall through to the layer
        // underneath. Without this, "scoped override" would leak
        // past the pop - the exact bug that makes per-request
        // containers useless.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ContainerStack([
            $this->container(['service.a' => 'baseline']),
        ]);
        $unit->push($this->container(['service.a' => 'override']));
        $unit->pop();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->get('service.a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('baseline', $actual);
    }
}
