# GetClassTraits::from()

> `public static function from(string $item): array`

Static factory; accepts a class-string or trait-string and returns its
trait list.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetClassTraits`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetClassTraits
{
    /**
     * @param  class-string $item
     *         the item to examine
     * @return array<class-string,class-string>
     *         the class's traits list
     */
    public static function from(string $item): array
}
```

## Description

`from()` accepts a PHP class or trait name and returns an associative array
mapping each used trait to its fully qualified name (FQCN). This is the fast
path for callers that already know the input is a valid class-string or
trait-string — it skips the [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php)
/ [`trait_exists()`](https://www.php.net/manual/en/function.trait-exists.php)
guard that [`->__invoke()`](__invoke.md) performs.

The method walks the entire class hierarchy using
[`GetClassHierarchy::from()`](../GetClassHierarchy/README.md) to ensure traits
inherited from parent classes are included. It then recursively follows trait
dependencies so that if a class uses `OuterTrait` and `OuterTrait` itself uses
`NestedTrait`, both traits appear in the result.

Each entry maps the trait FQCN to itself (`$trait => $trait`), producing an
`array<class-string, class-string>` shape. The order matches what PHP reports
via [`class_uses()`](https://www.php.net/manual/en/function.class-uses.php).

**Siblings:**

- [`GetClassHierarchy`](../GetClassHierarchy/README.md) — returns parent classes instead of traits
- [`GetClassInterfaces`](../GetClassInterfaces/README.md) — returns interfaces instead of traits
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level type inspection

## Parameters

**`$item`** (`class-string`)

The fully qualified name of the class or trait to examine. Unlike
[`->__invoke()`](__invoke.md), this method does not validate the input — it
expects a valid class-string or trait-string and delegates directly to
`class_uses()` after walking the hierarchy.

## Return Values

Returns an associative array mapping trait names to their FQCN:

- For a class using no traits, returns an empty array `[]`
- For a class using one or more traits, returns entries keyed by each trait's
  FQCN, with the FQCN as the value

The result includes:

- Traits declared directly on the class via `use`
- Traits inherited from parent classes
- Traits transitively used by other traits (recursive walk)

The order matches what PHP's [`class_uses()`](https://www.php.net/manual/en/function.class-uses.php) reports.
The shape is `array<class-string, class-string>`.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassTraits
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns empty array for a class that uses no traits
 ✔ ::from() returns a directly used trait
 ✔ ::from() returns a trait inherited from a parent class
 ✔ ::from() walks traits used by other traits
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassTraits.php:81`](../src/TypeInspectors/GetClassTraits.php#L81)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetClassHierarchy`](../GetClassHierarchy/README.md) — returns parent classes instead of traits
- [`GetClassInterfaces`](../GetClassInterfaces/README.md) — returns interfaces instead of traits

## Issues

- [Open issues mentioning `GetClassTraits::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassTraits%3A%3Afrom()%22)
- [Closed issues mentioning `GetClassTraits::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassTraits%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassTraits%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
