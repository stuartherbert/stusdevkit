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

use Exception;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DependencyKit\Container;

/**
 * Contract tests for Container.
 *
 * Container is DependencyKit's PSR-11 concrete container.
 *
 * Here Be Dragons: at the time these tests were written the class
 * body is a stub - `get()` and `has()` both `throw new
 * Exception('Not implemented')`. The tests below therefore pin the
 * *contract* (namespace, implements ContainerInterface, published
 * method set, parameter shapes) rather than trying to lock down
 * behaviour that has not been designed yet. The "stub throws" tests
 * document the current state so that the day a real implementation
 * lands it is obvious which tests need to evolve into real behaviour
 * tests.
 */
#[TestDox(Container::class)]
class ContainerTest extends TestCase
{
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
        // import Container by FQN, so moving it is a breaking change
        // that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DependencyKit';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(Container::class))
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

        // Container is a concrete instantiable class - not a trait,
        // not an interface, not an enum. Pinning this prevents a
        // silent reshape from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Container::class);

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

        // Container is PSR-11 by design - any framework code that
        // accepts a ContainerInterface must be able to use a
        // DependencyKit Container interchangeably. Dropping the
        // implements clause would silently break every framework
        // integration that relies on structural PSR-11 typing.
        //
        // we check the implemented-interface list via reflection
        // rather than `instanceof` so that PHPStan cannot statically
        // narrow the assertion - the regression we want to catch is
        // a future removal of `implements ContainerInterface`.

        // ----------------------------------------------------------------
        // setup your test

        $interfaces = (new ReflectionClass(Container::class))
            ->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains(ContainerInterface::class, $interfaces);
    }

    #[TestDox('exposes only __construct(), get() and has() as public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Container's public surface is the PSR-11 pair plus the
        // constructor. Any new public method is a surface-area
        // expansion the PSR contract does not cover, so the method
        // set is pinned by enumeration - an addition fails with a
        // diff that names the new method rather than a cryptic count
        // mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct', 'get', 'has'];
        $reflection = new ReflectionClass(Container::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === Container::class,
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

        // an explicit constructor is pinned because Container will
        // grow wiring parameters (registrations, a parent container,
        // ...) over time. Losing the declared constructor would
        // silently re-expose the compiler-synthesised default.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Container::class);

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
        // Container(...)`. A protected or private constructor would
        // make the class uninstantiable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(Container::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares no parameters')]
    public function test_construct_declares_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the current stub constructor takes no parameters. Pinning
        // this as the empty enumeration means the day a real parameter
        // is added - registrations, a parent container, a binding
        // list - this test fails with a named diff that forces the
        // change to be reviewed rather than slipping in silently.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $method = (new ReflectionClass(Container::class))
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
        // guards against an accidental rename or removal that would
        // silently break every consumer of the PSR contract.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Container::class);

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

        // PSR-11 mandates `public function get(string $id)`. A silent
        // visibility downgrade would break every framework
        // integration.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(Container::class))->getMethod('get');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->get() is an instance method')]
    public function test_get_is_an_instance_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11's `get` is defined on the interface as an instance
        // method. Promoting it to `static` would break interface
        // conformance and structural typing.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(Container::class))->getMethod('get');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->get() declares $id as its only parameter')]
    public function test_get_declares_id_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins the parameter name as `$id`. Some PHP tooling
        // (and named-argument call sites in userland) relies on the
        // exact name matching the interface, so this is pinned by
        // enumeration.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['id'];
        $method = (new ReflectionClass(Container::class))->getMethod('get');

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

        // PSR-11 pins `$id` as string. Widening or narrowing this
        // would break structural conformance against
        // ContainerInterface.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(Container::class))
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
        // declaration guards against an accidental rename or removal.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Container::class);

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

        // PSR-11 mandates `public function has(string $id): bool`.
        // A silent visibility downgrade would break every framework
        // integration.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(Container::class))->getMethod('has');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->has() is an instance method')]
    public function test_has_is_an_instance_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11's `has` is defined on the interface as an instance
        // method. Promoting it to `static` would break interface
        // conformance.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(Container::class))->getMethod('has');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->has() declares $id as its only parameter')]
    public function test_has_declares_id_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11 pins the parameter name as `$id`. Pinning by
        // enumeration keeps it aligned with the interface and with
        // ->get().

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['id'];
        $method = (new ReflectionClass(Container::class))->getMethod('has');

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

        // PSR-11 pins `$id` as string on `has()` too - same type as
        // on `get()` so the pair stays symmetrical.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(Container::class))
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
        // type would break structural conformance and confuse callers
        // that rely on the PSR contract.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'bool';
        $method = (new ReflectionClass(Container::class))->getMethod('has');
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

    #[TestDox('::__construct() accepts no arguments and returns an instance')]
    public function test_construct_returns_an_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the zero-argument constructor is the entry point - pinning
        // instantiation as its own test means a regression in the
        // constructor body (e.g. a required dependency added later)
        // surfaces here rather than only in downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Container();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Container::class, $unit);
    }

    // ================================================================
    //
    // ->get() behaviour (stub - see class-level Here Be Dragons)
    //
    // ----------------------------------------------------------------

    #[TestDox('->get() throws an Exception while the class is a stub')]
    public function test_get_throws_while_stub(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Here Be Dragons: the production source currently throws a
        // plain `\Exception('Not implemented')` from ->get(). This
        // test documents that fact so the stub is visible in the
        // TestDox spec. The day a real implementation lands, this
        // test is the first one that must be rewritten into a proper
        // behaviour suite (happy path lookup, missing-id throws
        // DependencyNotFoundException, ...).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Container();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not implemented');

        // ----------------------------------------------------------------
        // perform the change

        $unit->get('anything');
    }

    // ================================================================
    //
    // ->has() behaviour (stub - see class-level Here Be Dragons)
    //
    // ----------------------------------------------------------------

    #[TestDox('->has() throws an Exception while the class is a stub')]
    public function test_has_throws_while_stub(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Here Be Dragons: the production source currently throws a
        // plain `\Exception('Not implemented')` from ->has() - which
        // itself violates PSR-11's contract (which requires ->has()
        // to always return a bool and never throw). Documenting this
        // explicitly in TestDox means the violation is impossible to
        // overlook when implementation lands.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Container();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not implemented');

        // ----------------------------------------------------------------
        // perform the change

        $unit->has('anything');
    }
}
