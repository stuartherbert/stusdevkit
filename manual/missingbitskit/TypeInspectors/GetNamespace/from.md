# GetNamespace::from()

> `public static function from(string|object $item): string`

Extracts the namespace portion from a class-string or object instance.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetNamespace`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetNamespace
{
    /**
     * @param  string|object $item
     *         the item to examine
     * @return string
     *         the class's namespace
     *
     *         returns an empty string if the class is in the
     *         global namespace
     */
    public static function from(string|object $item): string;
}
```

## Description

`from()` accepts a fully-qualified class name (as a `class-string`) or an object
instance, and returns the namespace portion — everything up to (but not including)
the final backslash separator.

The method performs two steps:

1. If the input is an object, it resolves to a class-string via
   [`get_class()`](https://www.php.net/manual/en/function.get-class.php).

2. It finds the last backslash in the class-string using
   [`strrpos()`](https://www.php.net/manual/en/function.strrpos.php) and extracts
   the namespace prefix using [`substr()`](https://www.php.net/manual/en/function.substr.php).

If no backslash is found (the class lives in the global namespace), the method
returns an empty string `''`.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance

## Parameters

**`$item`** (`string|object`)

The item to examine. Accepts either:

- A fully-qualified class name as a `class-string` (e.g.
  `'StusDevKit\MissingBitsKit\TypeInspectors\GetNamespace'`)
- An object instance (resolved to its class name via `get_class()`)

## Return Values

Returns a string containing the namespace portion of the class name:

- For `SampleClass::class` (`'StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass'`),
  returns `'StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors'`
- For `stdClass::class` (`'stdClass'`, no backslash), returns `''` (empty string)
- For an object of a namespaced class, returns the same namespace as its class-string equivalent

The return type is always `string`. An empty string indicates the class lives in
the global namespace.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
// Namespaced class-string
GetNamespace::from(SampleClass::class);
// Returns: "StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors"

// Global namespace class-string
GetNamespace::from(stdClass::class);
// Returns: ""

// Object instance (namespaced)
GetNamespace::from(new SampleClass());
// Returns: "StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors"

// Object instance (global namespace)
GetNamespace::from(new stdClass());
// Returns: ""
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetNamespace
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns string
 ✔ ::from() returns the namespace part of a fully-qualified class name
 ✔ ::from() returns empty string for a class name without a namespace
 ✔ ::from() returns the namespace for a namespaced object instance
 ✔ ::from() returns empty string for an object of a global-namespace class
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetNamespace.php:60`](../src/TypeInspectors/GetNamespace.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance

## Issues

- [Open issues mentioning `GetNamespace::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNamespace%3A%3Afrom()%22)
- [Closed issues mentioning `GetNamespace::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNamespace%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNamespace%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
