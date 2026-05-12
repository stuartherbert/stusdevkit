# CollectionAsDict::set()

> `public function set(mixed $key, mixed $value): static`

Store a value in the collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class CollectionAsDict extends AccessibleCollection
{
    /**
     * @param TKey $key
     * @param TValue $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(mixed $key, mixed $value): static
}
```

## Description

Stores a value in the collection.

If the key already exists in this collection, its value is replaced by the given `$value`.

You cannot store `null` values in this collection — the value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. This lets [`CollectionAsDict::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent".

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`mixed`)

The key under which to store the value. The native parameter type is `mixed`; the docblock narrows it to `TKey` (`array-key`), so callers must pass a string or an integer.

**`$value`** (`mixed`)

The value to store. The native parameter type is `mixed`; the docblock narrows it to `TValue` (`array|bool|callable|float|int|object|string`). Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dictionary instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict
 ✔ ::set() signature: set(mixed $key, mixed $value): static
 ✔ ->set() stores a value with a string key
 ✔ ->set() stores a value with an integer key
 ✔ ->set() overwrites existing value at same key
 ✔ ->set() adds to existing data
 ✔ ->set() returns $this for method chaining
 ✔ ->set() supports fluent chaining
 ✔ ->set() can store values of different types
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:105`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L105)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict::get()`](get.md) — return a value from the collection, throwing if absent
- [`CollectionAsDict::maybeGet()`](maybeGet.md) — return a value from the collection, or `null` if absent
- [`CollectionAsDict::has()`](has.md) — check whether a value exists for the given key

## Issues

- [Open issues mentioning `CollectionAsDict::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsDict::set()%22)
- [Closed issues mentioning `CollectionAsDict::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsDict::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsDict%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
