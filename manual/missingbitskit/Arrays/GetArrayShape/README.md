# GetArrayShape

Reports whether a PHP array is being used as a list (every key is
an int) or as a map (at least one key is a string).

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

class GetArrayShape
{
    /**
     * inspect an array and return whether it is being used as a
     * list or a map.
     */
    public static function from(array $input): ArrayShape;
}
```

## Description

`GetArrayShape` answers a single question: *is this PHP array being
used as an ordered list of values, or as a key/value map?* The
result is returned as an [`ArrayShape`](../ArrayShape/README.md)
enum value, which the caller can `match` over.

The classification rule is by **runtime key type**, not by source
appearance:

- every key is an `int` → [`ArrayShape::LIST`](../ArrayShape/README.md#cases)
- at least one key is a `string` → [`ArrayShape::MAP`](../ArrayShape/README.md#cases)
- empty array → [`ArrayShape::LIST`](../ArrayShape/README.md#cases) (see Here Be Dragons below)

Originally added so the helper class
[`GetNormalisedForComparison`](../../GetNormalisedForComparison/README.md)
could handle list-shaped inputs (whose int keys are mere positions
and can be dropped) differently from map-shaped inputs (whose
keys ARE the identity and must be preserved).

`GetArrayShape` extends PHP's built-in
[`array_is_list()`](https://www.php.net/manual/en/function.array-is-list.php).
That function returns `true` only for keys exactly `0..n-1` with
no gaps — which is the wrong intuition when the caller wants to
know *"is this a sequence of values, or a key/value map?"*.
`GetArrayShape` accepts everything `array_is_list()` accepts AND
treats gappy / non-zero-start int-keyed arrays (e.g. the
leftovers from
[`array_filter()`](https://www.php.net/manual/en/function.array-filter.php))
as lists too.

The implementation defers to
[`array_is_list()`](https://www.php.net/manual/en/function.array-is-list.php)
on the fast path — packed PHP arrays are detected in O(1) via the
engine's internal `HASH_FLAG_PACKED` flag — and falls through to a
short-circuiting key walk for the looser shapes that `array_is_list()`
rejects.

## Methods

**From `GetArrayShape`**

- [`::from()`](from.md) — inspect an array and return whether it is being used as a list or a map.

## Here Be Dragons

**`GetArrayShape` is providing an educated guess.** Native PHP
arrays are untyped at runtime, and their array keys can cause
surprises. For the strongest correctness guarantees, avoid passing
native PHP arrays through this class — use one of the typed
collections from [CollectionsKit](../../../collectionskit/) instead,
such as [`ListOfIntegers`](../../../collectionskit/Lists/ListOfIntegers/README.md)
or [`DictOfIntegers`](../../../collectionskit/Dictionaries/DictOfIntegers/README.md).

- **Empty array reports as LIST.** An empty array is technically
  both list and map. We pick LIST so callers don't need a third
  "neither" case, and to match PHP's own
  [`array_is_list([])`](https://www.php.net/manual/en/function.array-is-list.php)
  returning `true`.

- **PHP coerces canonical numeric-string keys to ints.** A literal
  `["10" => 'x']` is stored with the int key `10`. To
  `GetArrayShape` it is indistinguishable from `[10 => 'x']` —
  both report as LIST. If you need the source key type
  preserved, do not pass through a PHP array.

- **Non-canonical numeric-string keys stay as strings.** Strings
  like `"01"` (leading zero), `"1.5"` (decimal point), `"+1"`
  (leading plus), or `" 1"` (whitespace) are *not* coerced by PHP
  — they remain string keys at storage time, so `GetArrayShape`
  classifies the array as MAP. This is the same rule applied
  consistently, but it can surprise callers who read the source
  literal and assume every numeric-looking string is an int key.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\GetArrayShape
 ✔ lives in the StusDevKit\MissingBitsKit\Arrays namespace
 ✔ is declared as a class
 ✔ exposes only ::from() as its public method
```

## Source

[`kits/missingbitskit/src/Arrays/GetArrayShape.php:86`](../../../../kits/missingbitskit/src/Arrays/GetArrayShape.php#L86)

## Changelog

_No tagged releases yet._

## See Also

- [`ArrayShape`](../ArrayShape/README.md) — the enum returned by
  [`::from()`](from.md). Pattern-match on its cases at call sites.
- [`array_is_list()`](https://www.php.net/manual/en/function.array-is-list.php) —
  the PHP built-in `GetArrayShape` extends. Use `array_is_list()`
  directly when you specifically need the strict `0..n-1, no gaps`
  rule.

## Issues

- [Open issues mentioning `GetArrayShape`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetArrayShape%22)
- [Closed issues mentioning `GetArrayShape`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetArrayShape%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetArrayShape%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
