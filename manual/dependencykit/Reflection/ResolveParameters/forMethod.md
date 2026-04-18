# ResolveParameters::forMethod()

> `public static forMethod(object|string $target, string $method, ContainerInterface $container): array`

Resolve, from the given PSR-11 container, the parameters that need to be
passed into the given `$method` of `$target`.

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
     * @throws InvalidMethodException
     * @throws UntypedParameterException
     * @throws UnsupportedParameterTypeException
     * @throws UnresolvedParameterException
     */
    public static function forMethod(
        object|string $target,
        string $method,
        ContainerInterface $container,
    ): array
}
```

## Description

`forMethod()` inspects the parameters accepted by the given method,
then draws a value for each one from `$container` by type. The returned
array is indexed by parameter name, splat-ready with `...` and
compatible with named-argument invocation.

`forMethod()` is one of four sibling factories on
[`ResolveParameters`](README.md), each narrow to a single callable
shape. Use:

- [`ResolveParameters::forFunction()`](forFunction.md) — for functions and `Closure`s
- [`ResolveParameters::forCallable()`](forCallable.md) — for mixed callable-shape dispatch
- [`ResolveParameters::forConstructor()`](forConstructor.md) — for constructors

## Parameters

**`$target`** (`object|string`)

The object or class where the method is defined. Passing an object
reflects an instance method; passing a class-string reflects a static
method (or an instance method, for callers that only need parameter
types).

**`$method`** (`string`)

The name of the method on `$target` that you want to resolve parameters
for.

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

- **[`InvalidMethodException`](../../../exceptionskit/Exceptions/InvalidMethodException.md)**
  — when `$target` (or its class) does not declare `$method` (including
  [`__call`](https://www.php.net/manual/en/language.oop5.overloading.php#object.call)-dispatched
  virtual methods — see `Here Be Dragons`).
- **[`UntypedParameterException`](../../Exceptions/UntypedParameterException.md)**
  — when one of `$method`'s parameters has no declared type.
- **[`UnsupportedParameterTypeException`](../../Exceptions/UnsupportedParameterTypeException.md)**
  — when one of `$method`'s parameters uses a variadic or intersection
  type that this resolver cannot satisfy.
- **[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)**
  — when the container has no match for one of `$method`'s parameters
  and the parameter has no default and is not nullable.

## Here Be Dragons

**`forMethod()` is visibility-blind — on purpose.**

[`method_exists()`](https://www.php.net/manual/en/function.method-exists.php)
returns `true` for public, protected, and private methods alike, and
[`ReflectionMethod`](https://www.php.net/manual/en/class.reflectionmethod.php)
reflects any of them. That's deliberate: callers writing factories for
their own classes sometimes need to resolve a private
constructor-helper.

The cost: a typo that happens to collide with a private method on the
target gets silently accepted here, when the caller almost certainly
meant a public one. If you want visibility enforcement, route through
[`ResolveParameters::forCallable()`](forCallable.md) instead — PHP's
`callable` type hint rejects non-public methods at the boundary.

**[`__call`](https://www.php.net/manual/en/language.oop5.overloading.php#object.call)
/ [`__callStatic`](https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic)
virtual methods hit a mismatch.**

[`method_exists()`](https://www.php.net/manual/en/function.method-exists.php)
can't see through
[`__call`](https://www.php.net/manual/en/language.oop5.overloading.php#object.call),
so this factory throws
[`InvalidMethodException`](../../../exceptionskit/Exceptions/InvalidMethodException.md)
with "method does not exist" — right from reflection's perspective,
unhelpful from the caller's. Magic-method dispatch is out of scope for
reflection-based parameter resolution: there's nothing to inspect until
PHP conjures the method at call time.

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
 ✔ declares a forMethod() method
 ✔ ::forMethod() is public
 ✔ ::forMethod() is static
 ✔ ::forMethod() declares an array return type
 ✔ ::forMethod() resolves parameters for an instance method on an object
 ✔ ::forMethod() resolves parameters for a static method via class-string
 ✔ ::forMethod() reflects private methods (visibility-blind)
 ✔ ::forMethod() throws InvalidMethodException when the method does not exist
 ✔ ::forMethod() throws InvalidMethodException for __call-dispatched virtual methods
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameters.php:399`](../../../../kits/dependencykit/src/Reflection/ResolveParameters.php#L399)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameters::forFunction()`](forFunction.md) — reflect a
  function name or `Closure`
- [`ResolveParameters::forCallable()`](forCallable.md) — dispatch on any
  PHP `callable` shape
- [`ResolveParameters::forConstructor()`](forConstructor.md) — reflect a
  class's constructor
- [`ResolveParameter::for()`](../ResolveParameter/for.md) — the
  per-parameter resolver this method builds on

## Issues

- [Open issues mentioning `ResolveParameters::forMethod()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameters%3A%3AforMethod%22)
- [Closed issues mentioning `ResolveParameters::forMethod()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameters%3A%3AforMethod%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameters%3A%3AforMethod%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
