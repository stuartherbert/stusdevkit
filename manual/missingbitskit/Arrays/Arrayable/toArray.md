# Arrayable::toArray()

> `public toArray(): array`

Exports the contents of this object as an array.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Arrays\Arrayable`](README.md)

## Signature

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
     * @return array<TKey, TValue>
     */
    public function toArray(): array;
}
```

## Description

Exports the contents of this object as an array.

Any class that implements [`Arrayable`](README.md) should add its
own docblock on this method, explaining exactly what data the
method exports for that particular class — which fields are
included, which are withheld, and what the keys and values
represent.

It is up to each implementing class to determine (and to
document!) exactly what data it will return. Classes are not
required to return any hidden internal data.

## Parameters

_None._

## Return Values

Returns an `array<TKey, TValue>`. The specific shape — which keys
are present, which values are included, and their ordering — is
up to the implementing class to define and document. The returned
array may be empty.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\Arrayable
 ✔ exposes only a toArray() method
 ✔ declares a toArray() method
 ✔ toArray() is public
 ✔ toArray() is an instance method
 ✔ toArray() takes no parameters
 ✔ toArray() declares an `array` return type
```

## Source

[`kits/missingbitskit/src/Arrays/Arrayable.php:75`](../../../../kits/missingbitskit/src/Arrays/Arrayable.php#L75)

## Changelog

_No tagged releases yet._

## See Also

- [`StaticallyArrayable::toArray()`](../StaticallyArrayable/toArray.md)
  — the static counterpart for data bound to the type rather than
  to an instance

## Issues

- [Open issues mentioning `Arrayable::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Arrayable%3A%3AtoArray%28%29%22)
- [Closed issues mentioning `Arrayable::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Arrayable%3A%3AtoArray%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Arrayable%3A%3AtoArray%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
