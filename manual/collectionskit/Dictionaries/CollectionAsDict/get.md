# CollectionAsDict::get()

> `public function get($key): mixed`

Return a value from the collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class CollectionAsDict extends AccessibleCollection
{
    /**
     * @param TKey $key
     * @return TValue
     * @throws NoValueForKeyInCollectionException
     */
    public function get($key): mixed
}
```

## Description

Returns the value stored for the given `$key`.

If the collection has no value for `$key`, throws an exception. This is the throwing counterpart to [`CollectionAsDict::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`CollectionAsDict::has()`](has.md) to test for a key without retrieving the value, or [`CollectionAsDict::maybeGet()`](maybeGet.md) when you want a `null` rather than an exception for absent keys.

## Parameters

**`$key`** (`mixed`)

The key whose value is being requested. Narrowed to `TKey` (`array-key`) by the docblock, so callers must pass a string or an integer.

## Return Values

The value stored at `$key`, typed as `TValue`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dictionary. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `<CollectionType> does not contain <key>`, where `<CollectionType>` is the unqualified class name reported by [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) and `<key>` is the missing key.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict
 ✔ ::get() signature: get($key): mixed
 ✔ ->get() returns value for existing key
 ✔ ->get() throws NoValueForKeyInCollectionException for missing key
 ✔ ->get() throws NoValueForKeyInCollectionException for empty dict
 ✔ ->get() returns value added via set()
 ✔ ->get() returns value with integer key
 ✔ ->get() exception message includes the missing key
 ✔ ->get() and ->maybeGet() return same value for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:150`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict::maybeGet()`](maybeGet.md) — return a value from the collection, or `null` if absent
- [`CollectionAsDict::has()`](has.md) — check whether a value exists for the given key
- [`CollectionAsDict::set()`](set.md) — store a value in the collection

## Issues

- [Open issues mentioning `CollectionAsDict::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsDict::get()%22)
- [Closed issues mentioning `CollectionAsDict::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsDict::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsDict%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
