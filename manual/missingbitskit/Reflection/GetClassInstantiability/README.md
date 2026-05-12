# GetClassInstantiability

Reports whether a given class-string can be instantiated via `new`, and —
if not — which single reason disqualifies it.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionClass;

class GetClassInstantiability
{
    /**
     * Inspects a class-string and returns the single reason that
     * disqualifies it from instantiation, or `INSTANTIABLE` if PHP
     * will let you `new` it.
     */
    public static function from(string $classname): ClassInstantiability;
}
```

## Description

`GetClassInstantiability` is a stateless utility that inspects a class-string
and returns a single `ClassInstantiability` enum value describing whether PHP
will let you `new` it.

Use this as a guard in front of code that will call `new $classname(...)`.
Callers who only care about a yes/no answer can use
`GetClassInstantiability::from($classname)->isInstantiable()`.

The inspector reports the **first** disqualifying reason it finds; it does
not enumerate every problem. Checks run in this order:

1. The symbol must exist at all (class, interface, trait, or enum).
2. It must not be an interface.
3. It must not be a trait.
4. It must not be an enum.
5. It must not be an abstract class.
6. If it declares a constructor, that constructor must be public.

The ordering means, for example, that an abstract class that also declares
a private constructor reports as `IS_ABSTRACT`, not `CONSTRUCTOR_NOT_PUBLIC`.

## Methods

- [`::from()`](./from.md) — Inspects a class-string and returns the single
  reason that disqualifies it from instantiation, or `INSTANTIABLE` if PHP
  will let you `new` it.

## Here Be Dragons

- **Autoloading runs.** `new ReflectionClass($classname)` triggers the
  autoloader. Passing a class-string you don't trust will load that
  class's file, with all the top-level side effects that implies. Only
  pass class-strings you were ready to `new` anyway.

- **Anonymous classes are out of scope.** They have mangled runtime names
  and no meaningful class-string caller. If you somehow pass one in, the
  result is whatever the reflection layer happens to say — not pinned.

- **No `__construct` via `__callStatic` / magic.** If the class resolves
  construction through magic, reflection won't see it and this inspector
  will report whichever static fact actually holds (typically
  `CONSTRUCTOR_NOT_PUBLIC` or `INSTANTIABLE` depending on what's
  declared). Magic construction is outside the scope of "can PHP `new`
  this directly?".

- **Engine-restricted internal classes slip past.** A handful of PHP built-ins
  refuse direct `new` via engine-level flags that reflection does not expose —
  `Generator` and `WeakReference` are the obvious examples (`WeakReference`
  requires `::create()`, `Generator` is reserved for internal use). For these,
  every reflection signal says "instantiable" (`isInstantiable()` lies and
  returns true, `isAbstract()` is false, there is no non-public constructor),
  so this inspector returns `INSTANTIABLE` — but the caller's `new $classname(...)`
  will still fail with a clear PHP error at that point ("class is reserved for
  internal use and cannot be manually instantiated"). Trying to detect these
  ahead of time would require a hardcoded allow/deny list that silently rots
  between PHP versions; the trade-off is worse than letting the runtime error
  surface at the `new` site.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability
 ✔ lives in the StusDevKit\MissingBitsKit\Reflection namespace
```

## Source

[`kits/missingbitskit/src/Reflection/GetClassInstantiability.php:105`](../src/Reflection/GetClassInstantiability.php#L105)

## Changelog

_No tagged releases yet._

## See Also

- [`ClassInstantiability`](../ClassInstantiability/README.md) — the enum returned by `from()`
- [`GetReflectionTypes`](../GetReflectionTypes/README.md) — the flattener that uses this inspector
- [`IntersectionTypesNotSupportedException`](../IntersectionTypesNotSupportedException/README.md) — raised when intersection types cannot be flattened

## Issues

- [Open issues mentioning `GetClassInstantiability`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassInstantiability%22)
- [Closed issues mentioning `GetClassInstantiability`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassInstantiability%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassInstantiability%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
