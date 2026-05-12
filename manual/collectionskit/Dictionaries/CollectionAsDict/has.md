# CollectionAsDict::has()

> `public function has($key): bool`

Check to see if we have a value for the given `$key` in this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class CollectionAsDict extends AccessibleCollection
{
    /**
     * @param TKey $key
     * @return bool
     * - `true` if we have a value for `$key`
     * - `false` otherwise
     */
    public function has($key): bool
}
```

## Description

Reports whether a value is stored for the given `$key` in this collection.

The implementation uses PHP's `isset()` over the underlying array, so an empty dictionary always returns `false`. Combined with the no-null invariant enforced by [`CollectionAsDict::set()`](set.md) and the constructor, `has()` and [`CollectionAsDict::maybeGet()`](maybeGet.md) agree: `has()` returning `false` is equivalent to `maybeGet()` returning `null`.

## Parameters

**`$key`** (`mixed`)

The key whose presence is being tested. Narrowed to `TKey` (`array-key`) by the docblock, so callers must pass a string or an integer.

## Return Values

- `true` if we have a value for `$key`
- `false` otherwise

An empty dictionary always returns `false`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict
 ✔ ::has() signature: has($key): bool
 ✔ ->has() returns true for existing string key
 ✔ ->has() returns true for existing integer key
 ✔ ->has() returns false for missing key
 ✔ ->has() returns false for empty dict
 ✔ ->has() returns true for key added via set()
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:177`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L177)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict::get()`](get.md) — return a value from the collection, throwing if absent
- [`CollectionAsDict::maybeGet()`](maybeGet.md) — return a value from the collection, or `null` if absent
- [`CollectionAsDict::set()`](set.md) — store a value in the collection

## Issues

- [Open issues mentioning `CollectionAsDict::has()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsDict::has()%22)
- [Closed issues mentioning `CollectionAsDict::has()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsDict::has()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsDict%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
