# GetArrayShape::from()

> `public static function from(array $input): ArrayShape`

Inspect an array and return whether it is being used as a list or
a map.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Arrays\GetArrayShape`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

class GetArrayShape
{
    /**
     * @param array<mixed> $input
     *      the array to inspect
     * @return ArrayShape
     *      how this shape is being used
     */
    public static function from(array $input): ArrayShape
}
```

## Description

`::from()` is the sole entry point for [`GetArrayShape`](README.md).
It classifies a PHP array into one of two
[`ArrayShape`](../ArrayShape/README.md) cases according to the
**runtime** type of its keys:

- if every key is an `int` →
  [`ArrayShape::LIST`](../ArrayShape/README.md#cases)
- if at least one key is a `string` →
  [`ArrayShape::MAP`](../ArrayShape/README.md#cases)
- if the array is empty →
  [`ArrayShape::LIST`](../ArrayShape/README.md#cases) (chosen so
  callers do not need a third "neither" case)

The classification is by the keys PHP actually stored, not by the
source-code appearance of the literal. PHP coerces canonical
numeric-string keys (e.g. `"10"`) to `int` at write time; those
arrays come out as LIST. Non-canonical numeric-looking strings
(e.g. `"01"`, `"1.5"`, `"+1"`) are kept as strings, so those
arrays come out as MAP. See the Here Be Dragons section on
[`GetArrayShape`](README.md#here-be-dragons) for the full surprise
list.

Internally, `::from()` defers to
[`array_is_list()`](https://www.php.net/manual/en/function.array-is-list.php)
on the fast path — the engine reports packed arrays in O(1) — and
falls through to a short-circuiting key walk for the looser
shapes (gappy, non-zero-start, post-[`array_filter()`](https://www.php.net/manual/en/function.array-filter.php))
that `array_is_list()` rejects but `GetArrayShape` still calls a
list. The walk stops on the first string key it sees, so the
typical map shape is detected in O(1) too.

## Parameters

**`$input`** (`array<mixed>`)

The PHP array to inspect. Any array value is accepted; the
method's job is to decide how the array is being used based on
its keys. Values are not examined.

## Return Values

Returns an [`ArrayShape`](../ArrayShape/README.md) enum value
describing how the input is being used. See the
[Cases section on `ArrayShape`](../ArrayShape/README.md#cases)
for what each case means and when it is returned.

## Errors/Exceptions

_None._

## Here Be Dragons

See [Here Be Dragons on `GetArrayShape`](README.md#here-be-dragons) —
the empty-array, coerced-numeric-string, and non-coerced-numeric-string
caveats all apply to this method's classification.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\GetArrayShape
 ✔ ::from() is declared
 ✔ ::from() is public static
 ✔ ::from() takes exactly one parameter typed as array
 ✔ ::from() returns an ArrayShape
 ✔ ::from() returns LIST for an empty array
 ✔ ::from() returns LIST for a zero-indexed sequential array
 ✔ ::from() returns LIST for an int-keyed array with gaps
 ✔ ::from() returns LIST for an int-keyed array that does not start at zero
 ✔ ::from() returns LIST when numeric-string keys are coerced to ints by PHP
 ✔ ::from() returns MAP for an all-string-keyed array
 ✔ ::from() returns MAP when at least one key is a string
 ✔ ::from() returns MAP when a numeric-looking string key is NOT coerced by PHP
 ✔ ::from() returns MAP for a single-entry string-keyed array
```

## Source

[`kits/missingbitskit/src/Arrays/GetArrayShape.php:97`](../../../../kits/missingbitskit/src/Arrays/GetArrayShape.php#L97)

## Changelog

_No tagged releases yet._

## See Also

- [`ArrayShape`](../ArrayShape/README.md) — the enum returned by
  this method. Pattern-match on its cases at call sites.
- [`array_is_list()`](https://www.php.net/manual/en/function.array-is-list.php) —
  the PHP built-in this method extends. Use it directly when you
  specifically need the strict `0..n-1, no gaps` rule, rather
  than `GetArrayShape`'s looser "are the keys positions or
  identities?" classification.

## Issues

- [Open issues mentioning `GetArrayShape::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetArrayShape%3A%3Afrom%28%29%22)
- [Closed issues mentioning `GetArrayShape::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetArrayShape%3A%3Afrom%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetArrayShape%3A%3Afrom%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
