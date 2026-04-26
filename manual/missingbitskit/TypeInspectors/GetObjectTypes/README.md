# GetObjectTypes

Returns the complete set of types that a PHP object satisfies, including its
class name, parent classes, interfaces, traits, and the universal `object`
type hint.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetObjectTypes
{
    /**
     * get a full list of types that an object can satisfy
     */
    public function __invoke(mixed $item): array;

    /**
     * get a full list of types that an object can satisfy
     */
    public static function from(object $item): array;
}
```

## Description

`GetObjectTypes` maps a PHP object instance to the full set of types it
satisfies. It is designed for use in type-inspection pipelines where a caller
needs to know which declared types a given object value could match.

This class is a thin wrapper around [`GetClassTypes`](../GetClassTypes/README.md)
that operates at the instance level. It delegates to `GetClassTypes::from()`
after extracting the class name via [`get_class()`](https://www.php.net/manual/en/function.get-class.php).

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not an object. Uses [`is_object()`](https://www.php.net/manual/en/function.is-object.php)
  to validate the type before delegating to `::from()`.
- [`::from()`](from.md) — accepts an object instance directly and returns
  its type map. This is the fast path when the caller already knows the input
  is an object.

The return map includes:

- The class name itself (the object is always an instance of its own class)
- All parent classes in the inheritance hierarchy
- All implemented interfaces
- All used traits
- The universal `object` type hint

Instance-level callable detection (Closures, objects with `__invoke()`) is
handled by `GetClassTypes` via `method_exists($className, '__invoke')`, so no
additional logic is needed here.

The universal `'mixed'` type hint is deliberately **not** included here;
it is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-object values
- [`::from()`](from.md) — Static factory; accepts an object and returns its type map

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetObjectTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetObjectTypes.php:55`](../src/TypeInspectors/GetObjectTypes.php#L55)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetObjectTypes::from()`](from.md) — instance-level callable detection

## Issues

- [Open issues mentioning `GetObjectTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetObjectTypes%22)
- [Closed issues mentioning `GetObjectTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetObjectTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetObjectTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
