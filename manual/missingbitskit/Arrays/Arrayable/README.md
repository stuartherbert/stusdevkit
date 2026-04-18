# Arrayable

Any class that implements `Arrayable` can return its internal state
as a PHP array.

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
interface Arrayable
{
    /**
     * Exports the contents of this object as an array.
     */
    public function toArray(): array;
}
```

## Description

`Arrayable` is our equivalent of PHP's built-in
[`Stringable`](https://www.php.net/manual/en/class.stringable.php)
interface, only for arrays. It declares a single instance method
that returns the implementing object's internal state as a PHP
array.

Originally added to standardise "convert-to-PHP-array" behaviour
for the CollectionsKit classes.

It is up to each class to determine (and to document!) exactly
what data it will return. Classes are not required to return any
hidden internal data (for example).

Use [`StaticallyArrayable`](../StaticallyArrayable/README.md) if
you want a `static toArray()` method instead — one that exposes
data bound to the type rather than to an instance.

## Methods

- [`Arrayable::toArray()`](toArray.md) — exports the contents of
  this object as an array

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\Arrayable
 ✔ is declared as an interface
 ✔ lives in the StusDevKit\MissingBitsKit\Arrays namespace
```

## Source

[`kits/missingbitskit/src/Arrays/Arrayable.php:64`](../../../../kits/missingbitskit/src/Arrays/Arrayable.php#L64)

## Changelog

_No tagged releases yet._

## See Also

- [`StaticallyArrayable`](../StaticallyArrayable/README.md) —
  type-level counterpart for data bound to the type rather than
  to an instance

## Issues

- [Open issues mentioning `Arrayable`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Arrayable%22)
- [Closed issues mentioning `Arrayable`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Arrayable%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Arrayable%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
