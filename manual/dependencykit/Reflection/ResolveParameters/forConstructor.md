# ResolveParameters::forConstructor()

> `public static forConstructor(string $class, ContainerInterface $container): array`

Resolve, from the given PSR-11 container, the parameters that need to be
passed into the constructor of `$class`.

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
use StusDevKit\ExceptionsKit\Exceptions\InvalidClassException;

class ResolveParameters
{
    /**
     * @return array<string, mixed>
     *   indexed by parameter name (as declared, no `$` prefix), in
     *   declaration order. Splat-ready with `...` and compatible
     *   with named-argument invocation. Keys are always parameter
     *   names, even when every parameter is positional.
     *
     * @throws InvalidClassException
     * @throws UntypedParameterException
     * @throws UnsupportedParameterTypeException
     * @throws UnresolvedParameterException
     */
    public static function forConstructor(
        string $class,
        ContainerInterface $container,
    ): array
}
```

## Description

`forConstructor()` inspects the parameters accepted by the given
class's constructor, then draws a value for each one from `$container`
by type. The returned array is indexed by parameter name, splat-ready
with `...` and compatible with named-argument invocation.

`forConstructor()` is one of four sibling factories on
[`ResolveParameters`](README.md), each narrow to a single callable
shape. Use:

- [`ResolveParameters::forFunction()`](forFunction.md) — for functions and `Closure`s
- [`ResolveParameters::forCallable()`](forCallable.md) — for mixed callable-shape dispatch
- [`ResolveParameters::forMethod()`](forMethod.md) — for methods

## Parameters

**`$class`** (`string`)

Any string; the factory reports
[`InvalidClassException`](../../../exceptionskit/Exceptions/InvalidClassException.md)
for strings that do not name a declared class. PHPStan callers will
usually be handing in a `class-string`, but a plain `string` is
accepted by the docblock to match the runtime behaviour and permit
"is this a class at all?" checks.

**`$container`** (`ContainerInterface`)

A [`ContainerInterface`](https://www.php-fig.org/psr/psr-11/) (PSR-11)
container used to supply a value for each declared constructor
parameter, looked up by the parameter's declared type.

## Return Values

Returns `array<string, mixed>`, indexed by parameter name (as declared,
no `$` prefix), in declaration order. The array is splat-ready with
`...` and compatible with named-argument invocation. Keys are always
parameter names, even when every parameter is positional.

## Errors/Exceptions

- **[`InvalidClassException`](../../../exceptionskit/Exceptions/InvalidClassException.md)**
  — when `$class` does not name a declared class.
- **[`UntypedParameterException`](../../Exceptions/UntypedParameterException.md)**
  — when one of the constructor's parameters has no declared type.
- **[`UnsupportedParameterTypeException`](../../Exceptions/UnsupportedParameterTypeException.md)**
  — when one of the constructor's parameters uses a variadic or
  intersection type that this resolver cannot satisfy.
- **[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)**
  — when the container has no match for one of the constructor's
  parameters and the parameter has no default and is not nullable.

## Here Be Dragons

**`forConstructor()` doesn't check whether the ctor is callable.**

The class may be:

- abstract (cannot instantiate)
- an enum (no public constructor)
- a class with a private constructor

This factory reflects the ctor parameters of any of the above and hands
back a perfectly good splat-ready array. The splat works. It's the
`new` that trips over the engine's restriction, with a raw PHP `Error`
("Cannot instantiate abstract class …", "Call to private … from global
scope", etc.). The stack trace points at the caller's `new` line, not
this factory — easy to miss when triaging something else.

Call
[`GetClassInstantiability::from()`](../../../missingbitskit/Reflection/GetClassInstantiability/from.md)
first to see if there's any point in calling `forConstructor()`.

**Empty-array return is ambiguous.**

`[]` can mean either:

- the class declares no explicit constructor at all (PHP supplies an
  implicit zero-arg ctor), or
- the class declares a constructor that takes zero parameters.

Both are "nothing to inject" from the resolver's point of view, and
both produce `[]`. Callers that need to distinguish the two must
reflect on the class directly.

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
 ✔ declares a forConstructor() method
 ✔ forConstructor() is public
 ✔ forConstructor() is static
 ✔ forConstructor() declares an array return type
 ✔ forConstructor() resolves parameters for a class with a typed constructor
 ✔ forConstructor() returns an empty array when the class has no explicit constructor
 ✔ forConstructor() returns an empty array when the class has a zero-argument constructor
 ✔ forConstructor() resolves parameters for an abstract class (permissive)
 ✔ forConstructor() resolves parameters for a class with a private constructor (permissive)
 ✔ forConstructor() throws InvalidClassException when the string does not name a declared class
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameters.php:512`](../../../../kits/dependencykit/src/Reflection/ResolveParameters.php#L512)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameters::forFunction()`](forFunction.md) — reflect a
  function name or `Closure`
- [`ResolveParameters::forCallable()`](forCallable.md) — dispatch on any
  PHP `callable` shape
- [`ResolveParameters::forMethod()`](forMethod.md) — reflect a method on
  an object or class
- [`ResolveParameter::for()`](../ResolveParameter/for.md) — the
  per-parameter resolver this method builds on
- [`GetClassInstantiability::from()`](../../../missingbitskit/Reflection/GetClassInstantiability/from.md)
  — check whether a class can actually be instantiated before calling
  `forConstructor()`

## Issues

- [Open issues mentioning `ResolveParameters::forConstructor()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameters%3A%3AforConstructor%22)
- [Closed issues mentioning `ResolveParameters::forConstructor()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameters%3A%3AforConstructor%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameters%3A%3AforConstructor%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
