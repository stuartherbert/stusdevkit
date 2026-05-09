# ClassInstantiability::isInstantiable()

> `public function isInstantiable(): bool`

Returns `true` if and only if this enum value represents an instantiable class.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Reflection\ClassInstantiability`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

enum ClassInstantiability: string implements StaticallyArrayable
{
    /**
     * convenience predicate for callers that only care whether the
     * class-string can be instantiated, not why.
     */
    public function isInstantiable(): bool;
}
```

## Description

`isInstantiable()` is a convenience predicate for callers that only care whether
the class-string can be instantiated, not why. It returns `true` when and only
when the enum value is `INSTANTIABLE`.

For all other cases (`CLASS_DOES_NOT_EXIST`, `IS_INTERFACE`, `IS_TRAIT`,
`IS_ENUM`, `IS_ABSTRACT`, `CONSTRUCTOR_NOT_PUBLIC`) it returns `false`.

**Siblings:**

- [`::toArray()`](../../Enums/EnumToArray/toArray.md) — returns a map of every case name to its backing value

## Parameters

_None._ This is an instance method on `ClassInstantiability` enum cases.

## Return Values

Returns `true` only for the `INSTANTIABLE` case:

```php
ClassInstantiability::INSTANTIABLE->isInstantiable(); // true
```

Returns `false` for all other cases:

```php
ClassInstantiability::CLASS_DOES_NOT_EXIST->isInstantiable(); // false
ClassInstantiability::IS_INTERFACE->isInstantiable();         // false
ClassInstantiability::IS_TRAIT->isInstantiable();             // false
ClassInstantiability::IS_ENUM->isInstantiable();              // false
ClassInstantiability::IS_ABSTRACT->isInstantiable();          // false
ClassInstantiability::CONSTRUCTOR_NOT_PUBLIC->isInstantiable(); // false
```

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
// For a concrete class with public constructor:
$result = ClassInstantiability::INSTANTIABLE;
$result->isInstantiable(); // true

// For any non-instantiable case:
ClassInstantiability::IS_INTERFACE->isInstantiable(); // false
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\ClassInstantiability
 ✔ ->isInstantiable() returns true for INSTANTIABLE
 ✔ ->isInstantiable() returns false for CLASS_DOES_NOT_EXIST
 ✔ ->isInstantiable() returns false for IS_INTERFACE
 ✔ ->isInstantiable() returns false for IS_TRAIT
 ✔ ->isInstantiable() returns false for IS_ENUM
 ✔ ->isInstantiable() returns false for IS_ABSTRACT
 ✔ ->isInstantiable() returns false for CONSTRUCTOR_NOT_PUBLIC
```

## Source

[`kits/missingbitskit/src/Reflection/ClassInstantiability.php:118`](../src/Reflection/ClassInstantiability.php#L118)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassInstantiability`](../GetClassInstantiability/README.md) — factory that produces ClassInstantiability values

## Issues

- [Open issues mentioning `ClassInstantiability::isInstantiable()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ClassInstantiability%3A%3AisInstantiable()%22)
- [Closed issues mentioning `ClassInstantiability::isInstantiable()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ClassInstantiability%3A%3AisInstantiable()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ClassInstantiability%3A%3AisInstantiable()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
