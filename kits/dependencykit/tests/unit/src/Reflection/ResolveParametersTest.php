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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use StusDevKit\DependencyKit\Reflection\ResolveParameters;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\AbstractClassFixture;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\CallableTargetClass;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\ClassWithoutConstructor;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\ClassWithTypedConstructor;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\ClassWithZeroArgConstructor;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\InvokableFixture;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\MagicMethodFixture;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\PrivateConstructorFixture;
use StusDevKit\DependencyKit\Tests\Fixtures\Reflection\PrivateMethodFixture;
use StusDevKit\ExceptionsKit\Exceptions\InvalidClassException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidMethodException;

/**
 * Tests for the ResolveParameters utility.
 */
#[TestDox(ResolveParameters::class)]
class ResolveParametersTest extends TestCase
{
    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * minimal PSR-11 container backed by an array. ResolveParameter
     * only touches has() / get(), so this inline fixture is enough
     * for every test in this file.
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

    #[TestDox('lives in the StusDevKit\\DependencyKit\\Reflection namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import by FQN, so moving the class is a breaking change
        // that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DependencyKit\\Reflection';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(ResolveParameters::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // forCallable() method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares a forCallable() method')]
    public function test_declares_a_forCallable_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `forCallable` is the entry point for mixed-shape callable
        // input. Renaming it is a breaking change for every caller
        // that relies on it.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ResolveParameters::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('forCallable');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forCallable() is public')]
    public function test_forCallable_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public for callers to reach it. A
        // silent downgrade would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forCallable');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forCallable() is static')]
    public function test_forCallable_is_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ResolveParameters is a stateless utility; all its
        // factories are static. Silently dropping `static` would
        // force every call site to instantiate.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forCallable');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forCallable() declares an array return type')]
    public function test_forCallable_declares_an_array_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the runtime return type is `array`. The richer
        // `array<string, mixed>` shape (string-keyed by parameter
        // name, in declaration order) lives in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forCallable');
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
    // forCallable() behaviour - dispatch by callable shape
    //
    // ----------------------------------------------------------------

    /**
     * every row exercises one branch of forCallable's shape-based
     * dispatch. The callable always has a single `int $x` parameter;
     * the container always has `'int' => 42` registered; the
     * expected result is therefore always `['x' => 42]`, except for
     * the `strlen` case which has a single `string $string`
     * parameter.
     *
     * @return iterable<string, array{
     *   0: string,
     *   1: callable,
     *   2: array<string, mixed>,
     *   3: array<string, mixed>
     * }>
     */
    public static function callableShapeProvider(): iterable
    {
        yield 'a Closure' => [
            'a Closure',
            static fn (int $x): int => $x,
            ['int' => 42],
            ['x' => 42],
        ];

        yield 'a first-class callable' => [
            'a first-class callable',
            CallableTargetClass::staticMethod(...),
            ['int' => 42],
            ['x' => 42],
        ];

        yield 'an invokable object' => [
            'an invokable object',
            new InvokableFixture(),
            ['int' => 42],
            ['x' => 42],
        ];

        yield 'an [object, method] array' => [
            'an [object, method] array',
            [new CallableTargetClass(), 'instanceMethod'],
            ['int' => 42],
            ['x' => 42],
        ];

        yield 'a [class-string, method] array' => [
            'a [class-string, method] array',
            [CallableTargetClass::class, 'staticMethod'],
            ['int' => 42],
            ['x' => 42],
        ];

        yield 'a "Class::method" string' => [
            'a "Class::method" string',
            CallableTargetClass::class . '::staticMethod',
            ['int' => 42],
            ['x' => 42],
        ];

        yield 'a global function name' => [
            'a global function name',
            'strlen',
            ['string' => 'hello'],
            ['string' => 'hello'],
        ];
    }

    /**
     * @param array<string, mixed> $services
     * @param array<string, mixed> $expected
     */
    #[TestDox('::forCallable() resolves parameters for $shapeDescription')]
    #[DataProvider('callableShapeProvider')]
    public function test_forCallable_resolves_parameters_for_shape(
        string $shapeDescription,
        callable $callable,
        array $services,
        array $expected,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // each row exercises one branch of forCallable's shape-based
        // dispatch (Closure, invokable, array, 'Class::method'
        // string, function name). The assertion pins that dispatch
        // reaches the right underlying factory and the resolver
        // produces the expected parameter map, keyed by parameter
        // name in declaration order.

        // ----------------------------------------------------------------
        // setup your test

        // `$shapeDescription` is carried for TestDox interpolation;
        // it does not drive the assertion.
        unset($shapeDescription);

        $container = $this->container($services);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forCallable($callable, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // forFunction() method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares a forFunction() method')]
    public function test_declares_a_forFunction_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `forFunction` is one of the four published factories.
        // Renaming or removing it is a breaking change for every
        // caller that relies on it.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ResolveParameters::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('forFunction');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forFunction() is public')]
    public function test_forFunction_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public for callers to reach it. A
        // silent downgrade would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forFunction');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forFunction() is static')]
    public function test_forFunction_is_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ResolveParameters is a stateless utility; all its
        // factories are static. Silently dropping `static` would
        // force every call site to instantiate.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forFunction');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forFunction() declares an array return type')]
    public function test_forFunction_declares_an_array_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the runtime return type is `array`. The richer
        // `array<string, mixed>` shape lives in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forFunction');
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
    // forFunction() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::forFunction() resolves parameters for a Closure')]
    public function test_forFunction_resolves_parameters_for_a_Closure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path for a Closure: the resolver walks the
        // parameter list, consults the container by type name, and
        // returns a name-keyed map in declaration order.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $func = static fn (int $x): int => $x;
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forFunction($func, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forFunction() resolves parameters for a global function name')]
    public function test_forFunction_resolves_parameters_for_a_global_function_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path for a string-shape function callable:
        // `strlen` is a PHP built-in with a single `string $string`
        // parameter, so the resolver produces a single-entry map.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['string' => 'hello'];
        $container = $this->container(['string' => 'hello']);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forFunction('strlen', $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forFunction() returns an empty array for a zero-parameter Closure')]
    public function test_forFunction_returns_an_empty_array_for_a_zero_parameter_Closure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a Closure that takes no parameters produces an empty map.
        // The container has no entries to walk and no entries to
        // return.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $func = static fn (): int => 42;
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forFunction($func, $container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forFunction() throws InvalidFunctionException when the string does not name a declared function')]
    public function test_forFunction_throws_when_the_string_does_not_name_a_declared_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the factory is a "I know I have a function" entry point.
        // Passing a string that `function_exists()` rejects is a
        // caller mistake that must be surfaced loudly, not quietly
        // re-routed to forMethod.

        // ----------------------------------------------------------------
        // setup your test

        $container = $this->container();
        $this->expectException(InvalidFunctionException::class);

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameters::forFunction('this_function_does_not_exist', $container);
    }

    // ================================================================
    //
    // forMethod() method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares a forMethod() method')]
    public function test_declares_a_forMethod_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `forMethod` is one of the four published factories.
        // Renaming or removing it is a breaking change for every
        // caller that relies on it.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ResolveParameters::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('forMethod');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forMethod() is public')]
    public function test_forMethod_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public for callers to reach it. A
        // silent downgrade would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forMethod');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forMethod() is static')]
    public function test_forMethod_is_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ResolveParameters is a stateless utility; all its
        // factories are static. Silently dropping `static` would
        // force every call site to instantiate.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forMethod');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forMethod() declares an array return type')]
    public function test_forMethod_declares_an_array_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the runtime return type is `array`. The richer
        // `array<string, mixed>` shape lives in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forMethod');
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
    // forMethod() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::forMethod() resolves parameters for an instance method on an object')]
    public function test_forMethod_resolves_parameters_for_an_instance_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path for an instance method: passing the object
        // itself as `$target` plus the method name by string, and
        // letting the resolver walk its parameter list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $target = new CallableTargetClass();
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forMethod(
            $target,
            'instanceMethod',
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forMethod() resolves parameters for a static method via class-string')]
    public function test_forMethod_resolves_parameters_for_a_static_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path for a static method: passing the
        // class-string as `$target` plus the method name by string.
        // `method_exists()` accepts both class-strings and objects,
        // so `forMethod` handles both with one signature.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forMethod(
            CallableTargetClass::class,
            'staticMethod',
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forMethod() reflects private methods (visibility-blind)')]
    public function test_forMethod_reflects_private_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `forMethod` is documented as visibility-blind -
        // `method_exists()` returns true for private methods, and
        // `ReflectionMethod` reflects them. Callers who want
        // visibility enforcement must route through `forCallable`,
        // which rejects non-public methods at the PHP `callable`
        // type boundary. Pin that permissive behaviour here so a
        // future "tighten up" attempt reveals itself as a test
        // failure.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forMethod(
            PrivateMethodFixture::class,
            'privateMethod',
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forMethod() throws InvalidMethodException when the method does not exist')]
    public function test_forMethod_throws_when_the_method_does_not_exist(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a typo that doesn't match any declared method is a caller
        // mistake. The factory must surface it loudly.

        // ----------------------------------------------------------------
        // setup your test

        $container = $this->container();
        $this->expectException(InvalidMethodException::class);

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameters::forMethod(
            CallableTargetClass::class,
            'noSuchMethod',
            $container,
        );
    }

    #[TestDox('::forMethod() throws InvalidMethodException for __call-dispatched virtual methods')]
    public function test_forMethod_throws_for_magic_call_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the documented footgun: `method_exists()` returns
        // false for methods that only exist via `__call`, so
        // `forMethod` throws even though the call would succeed at
        // runtime. Magic-method dispatch is out of scope for
        // reflection-based parameter resolution - we cannot inspect
        // parameters on a method PHP only conjures at call time.

        // ----------------------------------------------------------------
        // setup your test

        $target = new MagicMethodFixture();
        $container = $this->container();
        $this->expectException(InvalidMethodException::class);

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameters::forMethod($target, 'anyVirtualMethod', $container);
    }

    // ================================================================
    //
    // forConstructor() method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares a forConstructor() method')]
    public function test_declares_a_forConstructor_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `forConstructor` is one of the four published factories.
        // Renaming or removing it is a breaking change for every
        // caller that relies on it.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ResolveParameters::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('forConstructor');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forConstructor() is public')]
    public function test_forConstructor_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public for callers to reach it. A
        // silent downgrade would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forConstructor');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forConstructor() is static')]
    public function test_forConstructor_is_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ResolveParameters is a stateless utility; all its
        // factories are static. Silently dropping `static` would
        // force every call site to instantiate.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forConstructor');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::forConstructor() declares an array return type')]
    public function test_forConstructor_declares_an_array_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the runtime return type is `array`. The richer
        // `array<string, mixed>` shape lives in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $method = (new ReflectionClass(ResolveParameters::class))
            ->getMethod('forConstructor');
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
    // forConstructor() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::forConstructor() resolves parameters for a class with a typed constructor')]
    public function test_forConstructor_resolves_parameters_for_a_typed_constructor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path: a class with a typed ctor produces a
        // name-keyed parameter map the caller can splat into
        // `new $class(...)`.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forConstructor(
            ClassWithTypedConstructor::class,
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forConstructor() returns an empty array when the class has no explicit constructor')]
    public function test_forConstructor_returns_empty_array_when_class_has_no_explicit_constructor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the first limb of the documented empty-array
        // ambiguity: a class with no explicit ctor produces `[]`.
        // PHP supplies an implicit zero-arg ctor; the resolver sees
        // nothing to walk.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forConstructor(
            ClassWithoutConstructor::class,
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forConstructor() returns an empty array when the class has a zero-argument constructor')]
    public function test_forConstructor_returns_empty_array_when_class_has_zero_arg_constructor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the second limb of the documented empty-array
        // ambiguity: a class with an explicit zero-arg ctor also
        // produces `[]`, indistinguishable from the "no explicit
        // ctor" case at this factory's return.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $container = $this->container();

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forConstructor(
            ClassWithZeroArgConstructor::class,
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forConstructor() resolves parameters for an abstract class (permissive)')]
    public function test_forConstructor_resolves_parameters_for_an_abstract_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the documented permissive contract: the factory
        // reflects an abstract class's ctor parameters, even though
        // the caller cannot `new` the class. Deciding whether a
        // class is instantiable is the caller's job; this utility
        // just runs reflection.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forConstructor(
            AbstractClassFixture::class,
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forConstructor() resolves parameters for a class with a private constructor (permissive)')]
    public function test_forConstructor_resolves_parameters_for_a_private_constructor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the documented permissive contract: the factory
        // reflects a private ctor's parameters. Checking whether
        // the ctor is actually callable is the caller's job, not
        // the utility's.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['x' => 42];
        $container = $this->container(['int' => 42]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = ResolveParameters::forConstructor(
            PrivateConstructorFixture::class,
            $container,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::forConstructor() throws InvalidClassException when the string does not name a declared class')]
    public function test_forConstructor_throws_when_the_string_does_not_name_a_declared_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a typo that doesn't match any declared class is a caller
        // mistake. The factory must surface it loudly.

        // ----------------------------------------------------------------
        // setup your test

        // a namespaced string that looks like a class-string
        // satisfies PHPStan's `class-string` type check while
        // pointing at a class that genuinely does not exist at
        // runtime.
        $unknownClassString = 'StusDevKit\\DependencyKit\\Tests\\Fixtures'
            . '\\Reflection\\ThisClassDoesNotExist';
        $container = $this->container();
        $this->expectException(InvalidClassException::class);

        // ----------------------------------------------------------------
        // perform the change

        ResolveParameters::forConstructor($unknownClassString, $container);
    }
}
