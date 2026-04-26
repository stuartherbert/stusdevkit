# GetClassTypes::__invoke()

> `public __invoke(mixed $input): array`

Returns the set of types for an invokable class, or empty array if input is not a type name.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetClassTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

/**
 * do we have a PHP class / interface / trait name?
 */
public function __invoke(mixed $input): array
```

## Description

Call the class as an invokable object. Accepts any input and returns empty array for unknown names. For invocation convenience on any input type, not just known FQCN strings. Delegates to `::from()` when a valid class/interface/trait name is provided.

## Parameters

**`$input`** (`mixed`)

The value to check. May be a string containing a fully qualified class, interface, or trait name (FQCN). Non-string inputs are rejected immediately with an empty array return.

## Return Values

Returns an associative array where keys and values are identical — each entry represents one type hint that the named element satisfies. The shape is `array<string, string>`. Keys include:
- The original class/interface/trait name (as a fully qualified string)
- All parent classes in the inheritance hierarchy
- All implemented interfaces
- All used traits
- `object` — the universal object type hint for every class

If input is not a known FQCN, an empty array is returned.

## Errors/Exceptions

_None._

## Here Be Dragons

None documented here. (See TODO below.)

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassTypes
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "int"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "float"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "true"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "false"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "null"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "array"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "object"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "unknown class name"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface/trait name with data set "empty string"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassTypes.php:57`](../src/TypeInspectors/GetClassTypes.php#L57)

## Changelog

_No tagged releases yet._

## TODO

This method page shares the same source docblock gaps as the class page (see [README](README.md) → **TODO** section):
- `@param $input` description incomplete — add prose explaining it accepts a class/interface/trait FQCN string
- Return value prose missing for `array<string, string>` shape
- "Here Be Dragons" section needs content from source inline comments

## See Also

- [`::from()`](from.md) — accepts a string FQCN and returns the type set
- `GetDuckTypes` — alternative approach using duck-typing
- `GetObjectTypes` — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetClassTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetClassTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
