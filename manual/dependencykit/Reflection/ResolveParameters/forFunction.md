# ResolveParameters::forFunction()

> `public static forFunction(Closure|string $func, ContainerInterface $container): array`

Resolve, from the given PSR-11 container, the parameters that need to be
passed into the given `$func`.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\DependencyKit\Reflection\ResolveParameters`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\DependencyKit\Reflection;

use Closure;
use Psr\Container\ContainerInterface;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException;

class ResolveParameters
{
    /**
     * @return array<string, mixed>
     *   indexed by parameter name (as declared, no `$` prefix), in
     *   declaration order. Splat-ready with `...` and compatible
     *   with named-argument invocation. Keys are always parameter
     *   names, even when every parameter is positional.
     *
     * @throws InvalidFunctionException
     * @throws UntypedParameterException
     * @throws UnsupportedParameterTypeException
     * @throws UnresolvedParameterException
     */
    public static function forFunction(
        Closure|string $func,
        ContainerInterface $container,
    ): array
}
```

## Description

`forFunction()` inspects the parameters declared by `$func` using PHP
reflection, then draws a value for each one from `$container` by type.
The returned array is indexed by parameter name, splat-ready with `...`
and compatible with named-argument invocation.

`forFunction()` is one of four sibling factories on
[`ResolveParameters`](README.md), each narrow to a single callable
shape. Use:

- [`ResolveParameters::forCallable()`](forCallable.md) — for mixed callable-shape dispatch
- [`ResolveParameters::forMethod()`](forMethod.md) — for methods
- [`ResolveParameters::forConstructor()`](forConstructor.md) — for constructors

## Parameters

**`$func`** (`Closure|string`)

A global function name (a plain `string` that satisfies
[`function_exists()`](https://www.php.net/manual/en/function.function-exists.php))
or a [`Closure`](https://www.php.net/manual/en/class.closure.php).
First-class callables produced via `Foo::bar(...)` syntax are
materialised by PHP as [`Closure`](https://www.php.net/manual/en/class.closure.php)
instances and are accepted here.

`forFunction()` has a deliberately narrow contract and does **not**
accept:

- `'Class::method'` string callables
- `[$target, 'method']` array callables
- invokable objects (objects with [`__invoke`](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke))

All three are rejected with
[`InvalidFunctionException`](../../../exceptionskit/Exceptions/InvalidFunctionException.md),
because [`function_exists()`](https://www.php.net/manual/en/function.function-exists.php)
returns `false` for them. This is intentional: silently re-routing
non-function callables to
[`ResolveParameters::forMethod()`](forMethod.md) would turn a caller
mistake into a quiet success. Use
[`ResolveParameters::forCallable()`](forCallable.md) for mixed
callable-shape dispatch.

**`$container`** (`ContainerInterface`)

A [`ContainerInterface`](https://www.php-fig.org/psr/psr-11/) (PSR-11)
container used to supply a value for each declared parameter, looked up
by the parameter's declared type.

## Return Values

Returns `array<string, mixed>`, indexed by parameter name (as declared,
no `$` prefix), in declaration order. The array is splat-ready with
`...` and compatible with named-argument invocation. Keys are always
parameter names, even when every parameter is positional.

## Errors/Exceptions

- **[`InvalidFunctionException`](../../../exceptionskit/Exceptions/InvalidFunctionException.md)**
  — when `$func` is a string that does not name a declared function.
- **[`UntypedParameterException`](../../Exceptions/UntypedParameterException.md)**
  — when one of `$func`'s parameters has no declared type.
- **[`UnsupportedParameterTypeException`](../../Exceptions/UnsupportedParameterTypeException.md)**
  — when one of `$func`'s parameters uses a variadic or intersection
  type that this resolver cannot satisfy.
- **[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)**
  — when the container has no match for one of `$func`'s parameters
  and the parameter has no default and is not nullable.

## Here Be Dragons

**Bound [`Closure`](https://www.php.net/manual/en/class.closure.php)s
go through, but the binding is invisible to reflection.**

[`ReflectionFunction`](https://www.php.net/manual/en/class.reflectionfunction.php)
sees only the parameter list. Whatever `$this` scope the closure was
bound to doesn't reach the resolver. If the parameter types reference
services scoped to that binding, the container still needs them
registered under their type name — being bound buys nothing on the
resolution side.

First-class callables produced via `Foo::bar(...)` hit the same path,
since PHP materialises them as
[`Closure`](https://www.php.net/manual/en/class.closure.php)
instances.

**Inherited footguns from [`ResolveParameter::for()`](../ResolveParameter/for.md)** — three to know before wiring this up:

- **Union-type resolution order is best-effort.** PHP normalises the
  member order before the resolver sees it, so what you get isn't
  always what you wrote.
- **The literal container key `'object'` is a universal class-type
  fallback.** Register anything under it and every otherwise-unmatched
  class-typed parameter silently resolves to that service.
- **PSR-11 [`NotFoundExceptionInterface`](https://www.php-fig.org/psr/psr-11/)
  from a mid-resolve container failure is shape-identical to this
  resolver's own
  [`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md).**
  Catch the broad type first and you'll be chasing ghosts — catch
  [`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)
  first.

Full treatment in
[`ResolveParameter::for()`](../ResolveParameter/for.md)'s own
`Here Be Dragons`.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\DependencyKit\Reflection\ResolveParameters
 ✔ declares a forFunction() method
 ✔ ::forFunction() is public
 ✔ ::forFunction() is static
 ✔ ::forFunction() declares an array return type
 ✔ ::forFunction() resolves parameters for a Closure
 ✔ ::forFunction() resolves parameters for a global function name
 ✔ ::forFunction() returns an empty array for a zero-parameter Closure
 ✔ ::forFunction() throws InvalidFunctionException when the string does not name a declared function
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameters.php:175`](../../../../kits/dependencykit/src/Reflection/ResolveParameters.php#L175)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameters::forCallable()`](forCallable.md) — dispatch on any
  PHP `callable` shape
- [`ResolveParameters::forMethod()`](forMethod.md) — reflect a method on
  an object or class
- [`ResolveParameters::forConstructor()`](forConstructor.md) — reflect a
  class's constructor
- [`ResolveParameter::for()`](../ResolveParameter/for.md) — the
  per-parameter resolver this method builds on

## Issues

- [Open issues mentioning `ResolveParameters::forFunction()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameters%3A%3AforFunction%22)
- [Closed issues mentioning `ResolveParameters::forFunction()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameters%3A%3AforFunction%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameters%3A%3AforFunction%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
