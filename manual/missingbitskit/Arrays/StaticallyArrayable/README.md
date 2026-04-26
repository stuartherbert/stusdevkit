# StaticallyArrayable

Any class that implements `StaticallyArrayable` can return its
type-level state as a PHP array via a static method call.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone interface._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

/**
 * @template TKey of array-key
 * @template TValue of mixed
 */
interface StaticallyArrayable
{
    /**
     * Exports the contents of this static class as an array.
     */
    public static function toArray(): array;
}
```

## Description

`StaticallyArrayable` is our equivalent of PHP's built-in
[`Stringable`](https://www.php.net/manual/en/class.stringable.php)
interface, only for arrays — but with a key difference: the `toArray()`
method is **static**, so it exposes data bound to the type rather than
to an instance.

Originally added for enums: the set of cases is a property of the type,
not of any individual case. A static `toArray()` exposes that set in a
form suitable for data-provider driven tests, config rendering, and
similar type-level consumers.

It is up to each class to determine (and to document!) exactly what
data it will return. Classes are not required to return any hidden
internal data (for example).

Use [`Arrayable`](../Arrayable/README.md) if you want an *instance*-level
array view instead — one that operates on `this` rather than on the type.

## Methods

- [`StaticallyArrayable::toArray()`](toArray.md) — exports the contents
  of this static class as an array

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable
 ✔ is declared as an interface
 ✔ lives in the StusDevKit\MissingBitsKit\Arrays namespace
 ✔ exposes only a toArray() method
 ✔ ::toArray() is declared
 ✔ ::toArray() is public
 ✔ ::toArray() is static
 ✔ ::toArray() takes no parameters
 ✔ ::toArray() declares an `array` return type
```

## Source

[`kits/missingbitskit/src/Arrays/StaticallyArrayable.php:66`](../../../../kits/missingbitskit/src/Arrays/StaticallyArrayable.php#L66)

## Changelog

_No tagged releases yet._

## See Also

- [`Arrayable`](../Arrayable/README.md) —
  instance-level counterpart for data bound to an object rather than
  to the type

## Issues

- [Open issues mentioning `StaticallyArrayable`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22StaticallyArrayable%22)
- [Closed issues mentioning `StaticallyArrayable`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22StaticallyArrayable%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=StaticallyArrayable%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
