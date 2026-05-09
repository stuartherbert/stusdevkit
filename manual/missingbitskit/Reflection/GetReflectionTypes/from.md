# GetReflectionTypes::from()

> `public static function from(ReflectionType $refType): array`

Returns a normalised list of `ReflectionType` instances from any child of
`ReflectionType`.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Reflection\GetReflectionTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

class GetReflectionTypes
{
    /**
     * @return ReflectionType[]
     */
    public static function from(ReflectionType $refType): array;
}
```

## Description

`from()` accepts any `ReflectionType` and returns a normalised list of
`ReflectionType` instances — regardless of whether the input is a named type,
union, or intersection.

The method dispatches via `instanceof` checks:

1. **ReflectionNamedType** — wraps in a single-item array (it is the leaf node
   and has no `getTypes()` method).

2. **ReflectionUnionType** — delegates to `$refType->getTypes()`, returning the
   member types in the order PHP reports them.

3. **ReflectionIntersectionType** — delegates to `$refType->getTypes()`,
   returning the member types in the order PHP reports them.

4. **Unknown child** — throws `UnsupportedReflectionTypeException`. This is a
   forward-compatibility guard: if PHP adds new `ReflectionType` child classes,
   the method fails loudly rather than silently accepting them.

This is a one-level unwrap only — it does not recurse into compound members of
a union (DNF types). For callers that need leaf-only results, use
`FlattenReflectionType::from()`.

**Siblings:**

- [`FlattenReflectionType`](../FlattenReflectionTypes/README.md) — flattens compound types to leaf nodes

## Parameters

**`$refType`** (`ReflectionType`)

The reflection type to unwrap. Accepts any child of `ReflectionType`:
`ReflectionNamedType`, `ReflectionUnionType`, or
`ReflectionIntersectionType`.

## Return Values

Returns an array of `ReflectionType` instances. The shape is
`array<int, ReflectionType>`.

**For a named type (e.g. `int`, `string`):**

- A single-item array containing the input unchanged:
  `[ReflectionNamedType]`

**For a nullable named type (e.g. `?int`):**

- A single-item array containing the input unchanged:
  `[ReflectionNamedType]` (where `allowsNull()` returns `true`)

**For a union type (e.g. `int|string`):**

- An array of the member types in PHP's reported order:
  `[ReflectionNamedType(int), ReflectionNamedType(string)]`

**For a DNF type (e.g. `(Countable&Traversable)|int`):**

- An array of the top-level members, with intersection types preserved as
  `ReflectionIntersectionType` instances (not flattened):
  `[ReflectionIntersectionType, ReflectionNamedType(int)]`

**For an intersection type (e.g. `Countable&Traversable`):**

- An array of the member types in PHP's reported order:
  `[ReflectionNamedType(Countable), ReflectionNamedType(Traversable)]`

## Errors/Exceptions

Throws `UnsupportedReflectionTypeException` if the input is a child of
`ReflectionType` that this method does not recognise. This is a forward-
compatibility guard for future PHP versions that may add new `ReflectionType`
child classes.

## Here Be Dragons

None yet.

## Examples

```php
// Named type (int)
GetReflectionTypes::from($refIntType);
// Returns: [ReflectionNamedType(int)]

// Nullable named type (?int)
GetReflectionTypes::from($refNullableIntType);
// Returns: [ReflectionNamedType(?int)]

// Union type (int|string)
GetReflectionTypes::from($refUnionType);
// Returns: [ReflectionNamedType(int), ReflectionNamedType(string)]

// DNF type ((Countable&Traversable)|int)
GetReflectionTypes::from($refDnfType);
// Returns: [ReflectionIntersectionType, ReflectionNamedType(int)]

// Intersection type (Countable&Traversable)
GetReflectionTypes::from($refIntersectionType);
// Returns: [ReflectionNamedType(Countable), ReflectionNamedType(Traversable)]
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\GetReflectionTypes
 ✔ ::from() is public
 ✔ ::from() is static
 ✔ ::from() takes exactly one parameter
 ✔ ::from()'s parameter has a ReflectionType type
 ✔ ::from() declares an array return type
 ✔ ::from() wraps a ReflectionNamedType in a single-item array
 ✔ ::from() wraps a nullable ReflectionNamedType in a single-item array
 ✔ ::from() returns the member types of a ReflectionUnionType
 ✔ ::from() does not recurse into compound members of a union (DNF)
 ✔ ::from() returns the member types of a ReflectionIntersectionType
 ✔ ::from() throws UnsupportedReflectionTypeException for an unknown ReflectionType subclass
```

## Source

[`kits/missingbitskit/src/Reflection/GetReflectionTypes.php:77`](../src/Reflection/GetReflectionTypes.php#L77)

## Changelog

_No tagged releases yet._

## See Also

- [`FlattenReflectionType`](../FlattenReflectionTypes/README.md) — flattens compound types to leaf nodes
- [`GetClassHierarchy`](../../TypeInspectors/GetClassHierarchy/README.md) — returns the full class hierarchy for a class-string

## Issues

- [Open issues mentioning `GetReflectionTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetReflectionTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetReflectionTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetReflectionTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetReflectionTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
