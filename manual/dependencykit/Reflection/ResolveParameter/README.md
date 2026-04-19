# ResolveParameter

Turns a single [`ReflectionParameter`](https://www.php.net/manual/en/class.reflectionparameter.php)
into the value that should be passed to that parameter, by consulting a
[PSR-11](https://www.php-fig.org/psr/psr-11/) container and falling back
to whatever the parameter declaration allows.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\DependencyKit\Reflection;

use Psr\Container\ContainerInterface;
use ReflectionParameter;

class ResolveParameter
{
    /**
     * Retrieve a value for the given parameter, using its type, from
     * the given PSR-11 DI container.
     */
    public static for(
        ReflectionParameter $refParam,
        ContainerInterface $container,
    ): mixed;
}
```

## Description

`ResolveParameter` turns a single
[`ReflectionParameter`](https://www.php.net/manual/en/class.reflectionparameter.php)
into the value that should be passed to that parameter, by consulting a
[PSR-11](https://www.php-fig.org/psr/psr-11/) dependency container and
falling back to whatever the parameter declaration allows (a declared
default, `null` for a nullable parameter, or a thrown exception if
neither applies).

## Methods

Exposes one static method:

- [`ResolveParameter::for()`](for.md) — resolve a single
  [`ReflectionParameter`](https://www.php.net/manual/en/class.reflectionparameter.php)
  to a value drawn from the container or the parameter's own fallback

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\DependencyKit\Reflection\ResolveParameter
 ✔ lives in the StusDevKit\DependencyKit\Reflection namespace
 ✔ is declared as a class
 ✔ exposes only a for() public method
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameter.php:59`](../../../../kits/dependencykit/src/Reflection/ResolveParameter.php#L59)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameters`](../ResolveParameters/README.md) — the
  per-callable factory that builds on `ResolveParameter::for()`

## Issues

- [Open issues mentioning `ResolveParameter`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameter%22)
- [Closed issues mentioning `ResolveParameter`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameter%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameter%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
