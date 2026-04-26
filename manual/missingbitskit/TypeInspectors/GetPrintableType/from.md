# GetPrintableType::from()

> `public static function from(mixed $item, int $options = self::FLAG_DEFAULTS): string`

Returns a human-readable descriptor string for any PHP value, controlled by
a bitmask of flag constants.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetPrintableType`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Closure;

class GetPrintableType
{
    public const int FLAG_DEFAULTS = self::FLAG_CLASSNAME
        | self::FLAG_CALLABLE_DETAILS
        | self::FLAG_SCALAR_VALUE;

    /**
     * @param  mixed $item
     *         the data to examine
     * @param  int $options
     *         a bitmask of `self::FLAG_*` constants - controls what extra
     *         detail (classnames, callable shape, scalar values) appears
     *         in the return value
     * @return string
     *         the data type of $item
     */
    public static function from(
        mixed $item,
        int $options = self::FLAG_DEFAULTS
    ): string;
}
```

## Description

`from()` accepts any PHP value and an optional flag bitmask, returning a
human-readable descriptor string. The default flags include classname detail,
callable shape, and scalar values — callers can reduce output by passing
`FLAG_NONE` or combining specific flags.

The method dispatches through four branches in order:

1. **Objects** — routed to `returnObjectType()`. Closures are handled
   specially: they always go through the callable formatter regardless of
   `FLAG_CLASSNAME`, so `FLAG_CALLABLE_DETAILS` alone reveals closure shape.
2. **Callables** — routed to `returnCallableType()`. Handles function strings,
   closures (already filtered), and `[object, method]` or `[Class, method]`
   arrays.
3. **Scalars** — routed to `returnScalarType()`. Handles `int`, `float`,
   `bool`, and `string` with PHP parameter-type-hint spellings (`'int'`,
   `'float'`, `'bool'`) rather than `gettype()` output (`'integer'`,
   `'double'`, `'boolean'`).
4. **Catch-all** — uses `gettype()` and normalises the result: `'NULL'`
   becomes `'null'`, `'resource (closed)'` becomes `'resource'`.

The descriptor format uses angle brackets for embedded details:

- `int<42>` — integer with value
- `bool<true>` / `bool<false>` — boolean with value (not `<1>`/`<>`)
- `object<ClassName>` — object with class name (when `FLAG_CLASSNAME` is set)
- `callable<Closure>` / `callable<strlen>` / `callable<Class::method>` —
  callable with shape detail (when `FLAG_CALLABLE_DETAILS` is set)

**Siblings:**

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance

## Parameters

**`$item`** (`mixed`)

The data to examine. Accepts any PHP value — scalars, objects, callables,
arrays, null, or resources.

**`$options`** (`int`, optional, default: `self::FLAG_DEFAULTS`)

A bitmask of flag constants that controls what extra detail appears in the
return value. Available flags:

- `FLAG_NONE` (`0`) — minimum output, just the base type name
- `FLAG_CLASSNAME` (`1`) — include class names for objects (e.g.
  `object<ClassName>`)
- `FLAG_CALLABLE_DETAILS` (`2`) — include callable shape (e.g.
  `callable<Closure>`, `callable<strlen>`, `callable<Class::method>`)
- `FLAG_SCALAR_VALUE` (`4`) — include scalar values (e.g. `int<42>`,
  `bool<true>`)

Combine flags with `|` (e.g. `FLAG_CLASSNAME | FLAG_SCALAR_VALUE`).

## Return Values

Returns a descriptor string describing the type of `$item`. The format depends
on the input value and the flags:

**Scalars:**

- `int` — integer without value detail
- `int<42>` — integer with value (when `FLAG_SCALAR_VALUE` is set)
- `float` / `float<1.5>` — float with optional value
- `bool` / `bool<true>` / `bool<false>` — boolean with optional value
  (booleans format as `<true>`/`<false>`, not `<1>`/`<>`)
- `string` / `string<hello>` — string with optional value

**Objects:**

- `object` — plain object without classname (when `FLAG_CLASSNAME` is not set)
- `object<ClassName>` — object with class name (when `FLAG_CLASSNAME` is set)
- Closures are always routed through the callable formatter: `callable` or
  `callable<Closure>` depending on `FLAG_CALLABLE_DETAILS`

**Callables:**

- `callable` — callable without shape detail
- `callable<strlen>` — function string with name
- `callable<Closure>` — closure (note: not the anonymous class name)
- `callable<Class::method>` — `[Class, method]` array callable

**Other types:**

- `null` — lowercase (normalised from `gettype()`'s `'NULL'`)
- `array` — plain array
- `resource` — open or closed resource (closed resources normalised from
  `'resource (closed)'`)

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
// Basic type names only
GetPrintableType::from(42, GetPrintableType::FLAG_NONE);        // "int"
GetPrintableType::from(1.5, GetPrintableType::FLAG_NONE);       // "float"
GetPrintableType::from(true, GetPrintableType::FLAG_NONE);      // "bool"
GetPrintableType::from("hello", GetPrintableType::FLAG_NONE);   // "string"

// With scalar values
GetPrintableType::from(42, GetPrintableType::FLAG_SCALAR_VALUE); // "int<42>"
GetPrintableType::from(true, GetPrintableType::FLAG_SCALAR_VALUE); // "bool<true>"

// With classnames
GetPrintableType::from(new stdClass(), GetPrintableType::FLAG_CLASSNAME); // "object<stdClass>"

// With callable details
GetPrintableType::from("strlen", GetPrintableType::FLAG_CALLABLE_DETAILS); // "callable<strlen>"
GetPrintableType::from(fn() => 1, GetPrintableType::FLAG_CALLABLE_DETAILS); // "callable<Closure>"

// Default flags (classname + callable details + scalar values)
GetPrintableType::from(42); // "int<42>"
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetPrintableType
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns string
 ✔ ::from() returns the expected descriptor for a scalar value with data set "int, FLAG_NONE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "int, FLAG_SCALAR_VALUE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "int, defaults"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "float, FLAG_NONE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "float, FLAG_SCALAR_VALUE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "true, FLAG_NONE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "true, FLAG_SCALAR_VALUE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "false, FLAG_NONE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "false, FLAG_SCALAR_VALUE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "plain string, FLAG_NONE"
 ✔ ::from() returns the expected descriptor for a scalar value with data set "plain string, FLAG_SCALAR_VALUE"
 ✔ ::from() returns just "object" for a plain object when FLAG_CLASSNAME is not set
 ✔ ::from() returns object<ClassName> when FLAG_CLASSNAME is set
 ✔ ::from() returns object<ClassName> for an invokable object with defaults
 ✔ ::from() returns "callable" for a Closure when FLAG_CALLABLE_DETAILS is not set
 ✔ ::from() returns "callable<Closure>" for a Closure when FLAG_CALLABLE_DETAILS alone is set
 ✔ ::from() returns "callable<Closure>" for a Closure with defaults
 ✔ ::from() returns "callable" for a callable string without FLAG_CALLABLE_DETAILS
 ✔ ::from() returns "callable<function>" for a callable string with FLAG_CALLABLE_DETAILS
 ✔ ::from() returns "callable<Class::method>" for a [Class, method] callable array with FLAG_CALLABLE_DETAILS
 ✔ ::from() returns "null" for a null input
 ✔ ::from() returns "array" for a non-callable array input
 ✔ ::from() returns "resource" for a resource input
 ✔ ::from() collapses a closed resource back to "resource"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetPrintableType.php:94`](../src/TypeInspectors/GetPrintableType.php#L94)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance
- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string

## Issues

- [Open issues mentioning `GetPrintableType::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetPrintableType%3A%3Afrom()%22)
- [Closed issues mentioning `GetPrintableType::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetPrintableType%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetPrintableType%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
