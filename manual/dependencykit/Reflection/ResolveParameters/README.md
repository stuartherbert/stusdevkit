# ResolveParameters

A permissive raw-reflection utility. Give it a function, method,
constructor, or callable and a PSR-11 container, and it returns the
values to pass in. Nothing more.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\DependencyKit\Reflection;

use Closure;
use Psr\Container\ContainerInterface;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidClassException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidMethodException;

class ResolveParameters
{
    /**
     * Resolve, from the given PSR-11 container, the parameters that
     * need to be passed into the given `$func`.
     */
    public static forFunction(
        Closure|string $func,
        ContainerInterface $container,
    ): array;

    /**
     * Resolve, from the given PSR-11 container, the parameters that
     * need to be passed into the given `$callable`.
     */
    public static forCallable(
        callable $callable,
        ContainerInterface $container,
    ): array;

    /**
     * Resolve, from the given PSR-11 container, the parameters that
     * need to be passed into the given `$method` of `$target`.
     */
    public static forMethod(
        object|string $target,
        string $method,
        ContainerInterface $container,
    ): array;

    /**
     * Resolve, from the given PSR-11 container, the parameters that
     * need to be passed into the constructor of `$class`.
     */
    public static forConstructor(
        string $class,
        ContainerInterface $container,
    ): array;
}
```

## Description

`ResolveParameters` is a **permissive raw-reflection utility**. Give it
a function, method, constructor, or callable and a PSR-11 container,
and it returns the values to pass in. Nothing more.

Originally added as a helper for a reflection-based DI factory.

Permissive means the factories only check what they need to in order to
run reflection: that the function/method/class exists and that
[`method_exists()`](https://www.php.net/manual/en/function.method-exists.php)
/ [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php)
say yes. They do **not** decide whether the target is *appropriate* to
resolve against — that's the caller's context, not the utility's.

The most common "is this appropriate" check a DI layer wants is "can
this class be instantiated at all?" For that, call
[`GetClassInstantiability::from()`](../../../missingbitskit/Reflection/GetClassInstantiability/from.md)
before [`ResolveParameters::forConstructor()`](forConstructor.md). This
utility stays out of that decision so it can be re-used by callers
whose context is not DI-shaped.

## Methods

Exposes four static factories, each with its own narrow contract:

- [`ResolveParameters::forFunction()`](forFunction.md) — global
  function name or [`Closure`](https://www.php.net/manual/en/class.closure.php)
- [`ResolveParameters::forMethod()`](forMethod.md) —
  object-or-class-string plus method name
- [`ResolveParameters::forConstructor()`](forConstructor.md) —
  class-string for a class's constructor
- [`ResolveParameters::forCallable()`](forCallable.md) — any PHP
  `callable`, dispatching to the appropriate sibling above

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\DependencyKit\Reflection\ResolveParameters
 ✔ lives in the StusDevKit\DependencyKit\Reflection namespace
 ✔ declares a forCallable() method
 ✔ declares a forFunction() method
 ✔ declares a forMethod() method
 ✔ declares a forConstructor() method
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameters.php:84`](../../../../kits/dependencykit/src/Reflection/ResolveParameters.php#L84)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameter::for()`](../ResolveParameter/for.md) — the
  per-parameter resolver `ResolveParameters` builds on
- [`GetClassInstantiability::from()`](../../../missingbitskit/Reflection/GetClassInstantiability/from.md)
  — check whether a class can actually be instantiated before calling
  [`ResolveParameters::forConstructor()`](forConstructor.md)

## Issues

- [Open issues mentioning `ResolveParameters`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameters%22)
- [Closed issues mentioning `ResolveParameters`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameters%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameters%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
