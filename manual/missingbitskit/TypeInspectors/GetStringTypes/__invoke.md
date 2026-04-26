# GetStringTypes->__invoke()

> `public function __invoke(mixed $input): array`

Call the class as an invokable object; accepts any input, coerces `Stringable`
objects to strings, and returns the type list.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetStringTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Stringable;

class GetStringTypes
{
    /**
     * @param  mixed $input
     *         the item to be inspected
     * @return array<string,string>
     *         the list of PHP types that `$input` can satisfy
     *
     *         returns an empty list if `$input` is not a string
     *         or not Stringable
     */
    public function __invoke(mixed $input): array;
}
```

## Description

`__invoke()` accepts any value and returns the full type list for a string — but
only when the input is actually a string or a `Stringable` object. For any other
input, it returns an empty array.

The method performs two validation/coercion steps before delegating to
[`::from()`](from.md):

1. If the input is an object that implements `Stringable`, it records
   `'Stringable'` and `'string'` in the result, then returns early — it does
   **not** coerce to string and process through `from()`.

2. The input must be a string (checked via `is_string()`). If it is not, the
   method returns `[]` immediately without further processing.

If we get here, `$input` is a string. The method merges the `Stringable` entry
(if any) with the result from `::from()`.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetObjectTypes::from()`](../GetObjectTypes/from.md) — static factory for object type inspection

## Parameters

**`$input`** (`mixed`)

The item to be inspected. Accepts any PHP value — strings, objects implementing
`Stringable`, or anything else (which will return an empty array).

## Return Values

Returns an associative array mapping type names to their spelling — the union
of `Stringable`/`string` (if coerced) and all types from [`::from()`](from.md).
The shape is `array<string, string>`.

For a plain string:

- `'string' => 'string'`

For a callable string (e.g. `'strlen'`):

- `'callable' => 'callable'`, `'string' => 'string'`

For an integer-shaped numeric string (e.g. `'123'`):

- `'numeric' => 'numeric'`, `'int' => 'int'`, `'string' => 'string'`

For a float-shaped numeric string (e.g. `'45.6'`):

- `'numeric' => 'numeric'`, `'float' => 'float'`, `'string' => 'string'`

For a `Stringable` object:

- `'Stringable' => 'Stringable'`, `'string' => 'string'` (returns early, does not process through `from()`)

For any non-string, non-`Stringable` input:

- `[]` (empty array)

The universal `'mixed'` type hint is deliberately **not** included here.
It is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
$getter = new GetStringTypes();

// Plain string
$getter('hello');
// Returns: ['string' => 'string']

// Callable string
$getter('strlen');
// Returns: ['callable' => 'callable', 'string' => 'string']

// Numeric string (int)
$getter('123');
// Returns: ['numeric' => 'numeric', 'int' => 'int', 'string' => 'string']

// Numeric string (float)
$getter('45.6');
// Returns: ['numeric' => 'numeric', 'float' => 'float', 'string' => 'string']

// Non-string input
$getter(42);
// Returns: []

// Stringable object (returns early, does not process through from())
$getter(new SampleToString());
// Returns: ['Stringable' => 'Stringable', 'string' => 'string']
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetStringTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "int"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "float"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "true"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "false"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "null"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "array"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "object without __toString"
 ✔ ->__invoke() coerces a Stringable object and returns the expected type list
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetStringTypes.php:61`](../src/TypeInspectors/GetStringTypes.php#L61)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts a string directly, returns the type list
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the union of all per-type inspectors plus `mixed`
- [`GetNumericTypes`](../GetNumericTypes/README.md) — returns types for values that `is_numeric()` accepts

## Issues

- [Open issues mentioning `GetStringTypes->__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetStringTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetStringTypes->__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetStringTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetStringTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
