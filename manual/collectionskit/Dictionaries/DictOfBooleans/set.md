# DictOfBooleans::set()

> `public function set(int|string $key, bool $value): static`

Store a flag in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @param bool $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(int|string $key, bool $value): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md).

## Description

Stores a flag in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored flag is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfBooleans::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfBooleans` specifically, a `null` value would already be a type error at the PHP signature level (`bool $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key (flag name) under which to store the value. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

**`$value`** (`bool`)

The flag value to store. The PHP signature accepts `mixed`; the `@template-extends CollectionAsDict<TKey, bool>` binding on `DictOfBooleans` narrows this to `bool`. Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names `DictOfBooleans` as the offending collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->set() stores a value with a string key
 ✔ ->set() stores a value with an integer key
 ✔ ->set() overwrites existing value at same key
 ✔ ->set() adds to existing data
 ✔ ->set() returns $this for method chaining
 ✔ ->set() supports fluent chaining
 ✔ ->set() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:105`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L105)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::get()`](get.md) — return a flag from the dict, throwing if absent
- [`DictOfBooleans::maybeGet()`](maybeGet.md) — return a flag from the dict, or `null` if absent
- [`CollectionAsDict::set()`](../CollectionAsDict/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::set()%22)
- [Closed issues mentioning `DictOfBooleans::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
