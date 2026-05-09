# GetReflectionTypes

Returns a normalised list of `ReflectionType` instances from any child of
`ReflectionType`, regardless of whether it is a named type, union, or
intersection.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

class GetReflectionTypes
{
    /**
     * Returns the list of ReflectionType that a $refType can be.
     */
    public static function from(ReflectionType $refType): array;
}
```

## Description

`GetReflectionTypes` is a helper that returns the list of types that a
`ReflectionType` can be referring to, no matter which child of `ReflectionType`
you have.

`ReflectionType` represents the type of a function/method parameter or return
type. This can be a simple type, or a compound type. Unfortunately, there is no
`getTypes()` method on `ReflectionType` itself to return a normalised list for
callers to consume.

Instead, each child class defines its own method. Some return a list of more
`ReflectionType`s. One child class (`ReflectionNamedType`) *is* the leaf node in
its own right, and has no `getTypes()` or equivalent.

`GetReflectionTypes::from()` dispatches via `instanceof` checks to handle each
child type uniformly:

- **ReflectionNamedType** — wraps in a single-item array (it is the leaf node)
- **ReflectionUnionType** — delegates to `getTypes()` (returns member types)
- **ReflectionIntersectionType** — delegates to `getTypes()` (returns member types)
- **Unknown child** — throws `UnsupportedReflectionTypeException`

If you want a flat list with everything resolved to leaf
`ReflectionNamedType` instances, use `FlattenReflectionType::from()` instead.

## Methods

- [`::from()`](from.md) — Static factory; accepts any ReflectionType, returns a normalised list

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\GetReflectionTypes
 ✔ is declared as a class
 ✔ lives in the StusDevKit\MissingBitsKit\Reflection namespace
 ✔ publishes exactly the expected set of public methods
```

## Source

[`kits/missingbitskit/src/Reflection/GetReflectionTypes.php:70`](../src/Reflection/GetReflectionTypes.php#L70)

## Changelog

_No tagged releases yet._

## See Also

- [`FlattenReflectionType`](../FlattenReflectionTypes/README.md) — flattens compound types to leaf nodes
- [`GetClassHierarchy`](../../TypeInspectors/GetClassHierarchy/README.md) — returns the full class hierarchy for a class-string

## Issues

- [Open issues mentioning `GetReflectionTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetReflectionTypes%22)
- [Closed issues mentioning `GetReflectionTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetReflectionTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetReflectionTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
