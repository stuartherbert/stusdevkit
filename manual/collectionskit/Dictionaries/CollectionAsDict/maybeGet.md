# CollectionAsDict::maybeGet()

> `public function maybeGet($key): mixed`

Return a value from the collection.

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
     * @return TValue|null
     */
    public function maybeGet($key): mixed
}
```

## Description

Returns the value stored for the given `$key`.

If the collection has no value for `$key`, returns `null`. The dictionary's no-null invariant — enforced by [`CollectionAsDict::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null".

Use [`CollectionAsDict::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`mixed`)

The key whose value is being requested. Narrowed to `TKey` (`array-key`) by the docblock, so callers must pass a string or an integer.

## Return Values

The value stored at `$key` (typed as `TValue`), or `null` when the key is absent.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict
 ✔ ::maybeGet() signature: maybeGet($key): mixed
 ✔ ->maybeGet() returns value for existing key
 ✔ ->maybeGet() returns null for missing key
 ✔ ->maybeGet() returns null for empty dict
 ✔ ->maybeGet() returns value added via set()
 ✔ ->maybeGet() returns value with integer key
 ✔ ->maybeGet() returns the overwritten value after set()
 ✔ ->get() and ->maybeGet() return same value for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:131`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L131)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict::get()`](get.md) — return a value from the collection, throwing if absent
- [`CollectionAsDict::has()`](has.md) — check whether a value exists for the given key
- [`CollectionAsDict::set()`](set.md) — store a value in the collection

## Issues

- [Open issues mentioning `CollectionAsDict::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsDict::maybeGet()%22)
- [Closed issues mentioning `CollectionAsDict::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsDict::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsDict%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
