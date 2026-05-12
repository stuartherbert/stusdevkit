# ClassInstantiability

A string-backed enum describing whether a given class-string can be instantiated
via `new`, and — if not — which single reason disqualifies it.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:**

- [`BackedEnum`](https://www.php.net/manual/en/enum.backedenum.php) (PHP built-in)
- [`StaticallyArrayable`](../../Arrays/StaticallyArrayable/README.md)

**Uses:**

- [`EnumToArray`](../../Enums/EnumToArray/README.md)

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

/**
 * @implements StaticallyArrayable<string,string>
 */
enum ClassInstantiability: string implements StaticallyArrayable
{
    /** @use EnumToArray<string> */
    use EnumToArray;

    case INSTANTIABLE = 'instantiable';
    case CLASS_DOES_NOT_EXIST = 'class does not exist';
    case IS_INTERFACE = 'is an interface';
    case IS_TRAIT = 'is a trait';
    case IS_ENUM = 'is an enum';
    case IS_ABSTRACT = 'is an abstract class';
    case CONSTRUCTOR_NOT_PUBLIC = 'constructor is not public';

    public function isInstantiable(): bool;

    // --- EnumToArray ---

    public static function toArray(): array;
}
```

## Description

`ClassInstantiability` is an enum describing whether a given class-string
can be instantiated via `new`, and — if not — which single reason disqualifies
it.

Each case carries a short, self-describing string value suitable for dropping
straight into error messages (e.g. "cannot use Foo: is an interface").

Create instances of this enum via
[`GetClassInstantiability::from()`](../GetClassInstantiability/README.md). This enum
does not create itself.

The enum implements `StaticallyArrayable` so its case set is exposed as a
`name => value` map via `toArray()`, useful for data-provider tests, config
rendering, and similar consumers.

## Cases

| Case | Backing value | Description |
|------|---------------|-------------|
| `INSTANTIABLE` | `'instantiable'` | A concrete class with a public zero-arg constructor (default or explicit) |
| `CLASS_DOES_NOT_EXIST` | `'class does not exist'` | No symbol with this name is loaded: not a class, interface, trait, or enum |
| `IS_INTERFACE` | `'is an interface'` | The symbol is an interface — no runtime representation `new` can produce |
| `IS_TRAIT` | `'is a trait'` | The symbol is a trait — compile-time composition unit, `new` on one is a fatal error |
| `IS_ENUM` | `'is an enum'` | The symbol is a pure or backed enum — `class_exists()` returns true but PHP forbids `new` on enums |
| `IS_ABSTRACT` | `'is an abstract class'` | The symbol is an abstract class — `class_exists()` returns true and constructor may be public, but PHP forbids direct instantiation |
| `CONSTRUCTOR_NOT_PUBLIC` | `'constructor is not public'` | The class has an explicit constructor that is `private` or `protected` (e.g. singleton, factory-only) |

## Methods

**From ClassInstantiability**

- [`->isInstantiable()`](isInstantiable.md) — Convenience predicate returning `true` only for `INSTANTIABLE`

**From EnumToArray**

- [`::toArray()`](../../Enums/EnumToArray/toArray.md) — Returns a map of every case name to its backing value

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\ClassInstantiability
 ✔ is declared as an enum
 ✔ lives in the StusDevKit\MissingBitsKit\Reflection namespace
 ✔ is a string-backed enum
 ✔ implements StaticallyArrayable
 ✔ has case INSTANTIABLE
 ✔ has case CLASS_DOES_NOT_EXIST
 ✔ has case IS_INTERFACE
 ✔ has case IS_TRAIT
 ✔ has case IS_ENUM
 ✔ has case IS_ABSTRACT
 ✔ has case CONSTRUCTOR_NOT_PUBLIC
 ✔ INSTANTIABLE has backing value "instantiable"
 ✔ CLASS_DOES_NOT_EXIST has backing value "class does not exist"
 ✔ IS_INTERFACE has backing value "is an interface"
 ✔ IS_TRAIT has backing value "is a trait"
 ✔ IS_ENUM has backing value "is an enum"
 ✔ IS_ABSTRACT has backing value "is an abstract class"
 ✔ CONSTRUCTOR_NOT_PUBLIC has backing value "constructor is not public"
```

## Source

[`kits/missingbitskit/src/Reflection/ClassInstantiability.php:62`](../src/Reflection/ClassInstantiability.php#L62)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassInstantiability`](../GetClassInstantiability/README.md) — factory that produces ClassInstantiability values
- [`EnumToArray`](../Enums/EnumToArray/README.md) — trait used to implement StaticallyArrayable

## Issues

- [Open issues mentioning `ClassInstantiability`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ClassInstantiability%22)
- [Closed issues mentioning `ClassInstantiability`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ClassInstantiability%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ClassInstantiability%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
