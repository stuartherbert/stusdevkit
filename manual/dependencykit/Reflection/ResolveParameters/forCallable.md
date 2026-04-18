# ResolveParameters::forCallable()

> `public static forCallable(callable $callable, ContainerInterface $container): array`

Resolve, from the given PSR-11 container, the parameters that need to be
passed into the given `$callable`.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\DependencyKit\Reflection\ResolveParameters`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\DependencyKit\Reflection;

use Psr\Container\ContainerInterface;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidMethodException;

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
     * @throws InvalidMethodException
     * @throws UntypedParameterException
     * @throws UnsupportedParameterTypeException
     * @throws UnresolvedParameterException
     */
    public static function forCallable(
        callable $callable,
        ContainerInterface $container,
    ): array
}
```

## Description

`forCallable()` is the entry point for mixed callable-shape input. PHP's
`callable` pseudo-type accepts seven shapes; `forCallable()` inspects
the runtime value and dispatches to the appropriate factory:

- [`Closure`](https://www.php.net/manual/en/class.closure.php) (including
  first-class callables like `Foo::bar(...)`) →
  [`ResolveParameters::forFunction()`](forFunction.md)
- invokable object (has [`__invoke`](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke))
  → [`ResolveParameters::forMethod()`](forMethod.md) with method
  `'__invoke'`
- `[$target, 'method']` array (instance or static) →
  [`ResolveParameters::forMethod()`](forMethod.md)
- `'Class::method'` string →
  [`ResolveParameters::forMethod()`](forMethod.md) (split on `::`)
- plain `'function_name'` string →
  [`ResolveParameters::forFunction()`](forFunction.md)

Originally added so callers holding a PHP `callable` can resolve its
parameters in one call, without open-coding the dispatch logic at every
call site. PSR-11 containers accepting factory callables in multiple
shapes are the motivating use case.

## Parameters

**`$callable`** (`callable`)

Any value PHP's `callable` pseudo-type accepts. `forCallable()` inspects
the runtime shape and routes to the appropriate factory — see
Description for the full dispatch table.

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
  — when `$callable` dispatches to
  [`ResolveParameters::forFunction()`](forFunction.md) and the string
  does not name a declared function.
- **[`InvalidMethodException`](../../../exceptionskit/Exceptions/InvalidMethodException.md)**
  — when `$callable` dispatches to
  [`ResolveParameters::forMethod()`](forMethod.md) and the target does
  not declare the named method.
- **[`UntypedParameterException`](../../Exceptions/UntypedParameterException.md)**
  — when the resolved target has a parameter with no declared type.
- **[`UnsupportedParameterTypeException`](../../Exceptions/UnsupportedParameterTypeException.md)**
  — when the resolved target has a parameter with a variadic or
  intersection type that this resolver cannot satisfy.
- **[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)**
  — when the container has no match for one of the resolved target's
  parameters and the parameter has no default and is not nullable.

## Here Be Dragons

**PHP's `callable` type hint is stricter than
[`ResolveParameters::forMethod()`](forMethod.md)'s signature.**

[`is_callable()`](https://www.php.net/manual/en/function.is-callable.php)
returns `false` for private and protected methods, so this entry point
only reaches public methods. Callers who need to reflect on non-public
methods must use [`ResolveParameters::forMethod()`](forMethod.md)
directly.

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
 ✔ declares a forCallable() method
 ✔ ::forCallable() is public
 ✔ ::forCallable() is static
 ✔ ::forCallable() declares an array return type
 ✔ ::forCallable() resolves parameters for a Closure
 ✔ ::forCallable() resolves parameters for a first-class callable
 ✔ ::forCallable() resolves parameters for an invokable object
 ✔ ::forCallable() resolves parameters for an [object, method] array
 ✔ ::forCallable() resolves parameters for a [class-string, method] array
 ✔ ::forCallable() resolves parameters for a "Class::method" string
 ✔ ::forCallable() resolves parameters for a global function name
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameters.php:273`](../../../../kits/dependencykit/src/Reflection/ResolveParameters.php#L273)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameters::forFunction()`](forFunction.md) — reflect a
  function name or `Closure`
- [`ResolveParameters::forMethod()`](forMethod.md) — reflect a method on
  an object or class
- [`ResolveParameters::forConstructor()`](forConstructor.md) — reflect a
  class's constructor
- [`ResolveParameter::for()`](../ResolveParameter/for.md) — the
  per-parameter resolver this method builds on

## Issues

- [Open issues mentioning `ResolveParameters::forCallable()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameters%3A%3AforCallable%22)
- [Closed issues mentioning `ResolveParameters::forCallable()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameters%3A%3AforCallable%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameters%3A%3AforCallable%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
