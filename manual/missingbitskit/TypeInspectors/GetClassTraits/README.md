# GetClassTraits

Returns the complete set of traits used by a PHP class or trait, including
traits inherited from parent classes and traits transitively used by other
traits.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetClassTraits
{
    /**
     * does this class use traits? if so, which ones does it use?
     */
    public function __invoke(mixed $input): array;

    /**
     * get a full list of traits used by a class
     */
    public static function from(string $item): array;
}
```

## Description

`GetClassTraits` maps a class or trait name to the full set of traits it
uses. It walks the entire class hierarchy and recursively follows trait
dependencies, so every trait in the chain is reported.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not a string naming a known class or trait. Uses
  [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php)
  and [`trait_exists()`](https://www.php.net/manual/en/function.trait-exists.php)
  to validate the name before delegating to `::from()`.
- [`::from()`](from.md) — accepts a class-string or trait-string directly
  and returns its trait list. This is the fast path when the caller already
  knows the input is a valid class or trait name.

The return map uses fully qualified trait names as both keys and values, in
the order PHP reports them via [`class_uses()`](https://www.php.net/manual/en/function.class-uses.php).
This includes traits declared directly on the class, traits inherited from
parent classes, and traits transitively used by other traits (e.g. if
`OuterTrait uses NestedTrait`, both appear in the result).

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-class-string values
- [`::from()`](from.md) — Static factory; accepts a class-string or trait-string and returns its trait list

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassTraits
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassTraits.php:60`](../src/TypeInspectors/GetClassTraits.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassHierarchy`](../GetClassHierarchy/README.md) — returns the full class hierarchy including parents
- [`GetClassInterfaces`](../GetClassInterfaces/README.md) — returns the interfaces implemented by a class
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level type inspection

## Issues

- [Open issues mentioning `GetClassTraits`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassTraits%22)
- [Closed issues mentioning `GetClassTraits`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassTraits%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassTraits%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
