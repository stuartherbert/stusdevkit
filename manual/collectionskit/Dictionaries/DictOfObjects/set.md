# DictOfObjects::set()

> `public function set(int|string $key, object $value): static`

Store an object in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @param object $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(int|string $key, object $value): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> value type `object` comes from `@template TValue of object` declared on
> [`DictOfObjects`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Stores an object in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored object is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfObjects::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfObjects` specifically, a `null` value would already be a type error at the PHPStan level (`object $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key under which to store the object. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

**`$value`** (`object`)

The object to store. The PHP signature accepts `mixed`; the `@template TValue of object` binding on `DictOfObjects` narrows this to `object`. Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names the runtime collection type.

## Here Be Dragons

**Objects are stored by reference**, inherited from `DictOfObjects`. The dict holds a pointer to the object instance, not a clone. Mutating a retrieved object mutates the dict's copy too — `$dict->get('key')->name = 'new'` is not a footgun, it's working as designed. If you need value semantics, clone before storing.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->set() stores an object with a string key
 ✔ ->set() stores an object with an integer key
 ✔ ->set() overwrites existing object at same key
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

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::get()`](get.md) — return an object from the dict, throwing if absent
- [`DictOfObjects::maybeGet()`](maybeGet.md) — return an object from the dict, or `null` if absent
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`CollectionAsDict::set()`](../CollectionAsDict/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::set()%22)
- [Closed issues mentioning `DictOfObjects::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
