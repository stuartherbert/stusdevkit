# GetClassInstantiability::from()

> `public static from(string $classname): ClassInstantiability`

Inspects a class-string and returns the single reason that disqualifies
it from instantiation, or `INSTANTIABLE` if PHP will let you `new` it.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionClass;

class GetClassInstantiability
{
    /**
     * inspect a string and return the single reason that disqualifies
     * it from instantiation, or `Instantiable` if PHP will let you
     * `new` it.
     *
     * The parameter is declared as plain `string` on purpose: the
     * whole point of this inspector is to tell callers whether the
     * name they hold actually resolves to an instantiable class.
     * Strings that do not name any loaded symbol return
     * `ClassDoesNotExist`.
     *
     * @param string $classname
     *     the fully-qualified name to inspect.
     */
    public static function from(string $classname): ClassInstantiability {}
}
```

## Description

Inspects a class-string and returns a single `ClassInstantiability` enum
value describing whether PHP will let you `new` it.

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

**Autoloading runs.** Calling this method triggers the autoloader for
the given class-string. Only pass names you were ready to `new` anyway.

## Parameters

**`$classname`** (`string`)

The fully-qualified name to inspect. Declared as plain `string` on
purpose: the whole point of this inspector is to tell callers whether
the name they hold actually resolves to an instantiable class. Strings
that do not name any loaded symbol return `CLASS_DOES_NOT_EXIST`.

## Return Values

A `ClassInstantiability` enum value. One of:

- `INSTANTIABLE` — a concrete class with a public zero-arg constructor
- `CLASS_DOES_NOT_EXIST` — no symbol with this name is loaded
- `IS_INTERFACE` — the symbol is an interface
- `IS_TRAIT` — the symbol is a trait
- `IS_ENUM` — the symbol is a pure or backed enum
- `IS_ABSTRACT` — the symbol is an abstract class
- `CONSTRUCTOR_NOT_PUBLIC` — the class has a private or protected constructor

## Errors/Exceptions

_None._

## Here Be Dragons

- **Anonymous classes are out of scope.** They have mangled runtime names
  and no meaningful class-string caller. If you somehow pass one in, the
  result is whatever the reflection layer happens to say — not pinned.

- **No `__construct` via `__callStatic` / magic.** If the class resolves
  construction through magic, reflection won't see it and this inspector
  will report whichever static fact actually holds (typically
  `CONSTRUCTOR_NOT_PUBLIC` or `INSTANTIABLE`).

- **Engine-restricted internal classes slip past.** `Generator` and
  `WeakReference` refuse direct `new` via engine-level flags that
  reflection does not expose. For these, this inspector returns
  `INSTANTIABLE` — but the caller's `new $classname(...)` will still
  fail with a clear PHP error at that point.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability
 ✔ ::from() returns INSTANTIABLE for a class with a public constructor
 ✔ ::from() returns INSTANTIABLE for a class with no declared constructor
 ✔ ::from() returns INSTANTIABLE for a readonly class
 ✔ ::from() returns INSTANTIABLE for a PHP-internal class
 ✔ ::from() returns INSTANTIABLE for an engine-restricted PHP-internal class (known reflection gap)
 ✔ ::from() returns CLASS_DOES_NOT_EXIST for an unknown class-string
 ✔ ::from() returns IS_INTERFACE for an interface
 ✔ ::from() returns IS_TRAIT for a trait
 ✔ ::from() returns IS_ENUM for an enum
 ✔ ::from() returns IS_ABSTRACT for an abstract class
 ✔ ::from() returns CONSTRUCTOR_NOT_PUBLIC for a class with a private constructor
 ✔ ::from() returns CONSTRUCTOR_NOT_PUBLIC for a class with a protected constructor
```

## Source

[`kits/missingbitskit/src/Reflection/GetClassInstantiability.php:121`](../src/Reflection/GetClassInstantiability.php#L121)

## Changelog

_No tagged releases yet._

## See Also

- [`ClassInstantiability`](../ClassInstantiability/README.md) — the enum returned by this method
- [`IntersectionTypesNotSupportedException`](../IntersectionTypesNotSupportedException/README.md) — raised when intersection types cannot be flattened

## Issues

- [Open issues mentioning `GetClassInstantiability::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassInstantiability::from()%22)
- [Closed issues mentioning `GetClassInstantiability::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassInstantiability::from()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassInstantiability%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
