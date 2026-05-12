# DictOfUuids::set()

> `public function set(string $key, UuidInterface $value): static`

Store a UUID in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfUuids extends DictOfObjects
{
    /**
     * @param string $key
     * @param UuidInterface $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(string $key, UuidInterface $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> narrowed `string` key and `UuidInterface` value types shown above are bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Stores a [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored UUID is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfUuids::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfUuids` specifically a `null` value would already be a type error at the PHP signature level (`UuidInterface $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`string`)

The key under which to store the UUID. The PHP signature accepts `mixed`; the class's `@extends DictOfObjects<string, UuidInterface>` binding narrows this to `string`.

**`$value`** ([`UuidInterface`](https://uuid.ramsey.dev/en/stable/))

The UUID to store. The PHP signature accepts `mixed`; the class's template binding narrows this to `UuidInterface`. Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names `DictOfUuids` as the offending collection type.

## Here Be Dragons

**UUIDs are stored by reference**, inherited from [`DictOfObjects`](../DictOfObjects/README.md). The dict holds a pointer to the `UuidInterface` instance, not a clone. In practice this is rarely a concern because `UuidInterface` implementations are conventionally immutable.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->set() stores a UUID with a string key
 ✔ ->set() returns $this for method chaining
 ✔ ->set() overwrites existing UUID at same key
 ✔ ->set() adds to existing data
 ✔ ->set() supports fluent chaining
 ✔ ->set() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:105`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L105)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::get()`](get.md) — return a UUID from the dict, throwing if absent
- [`DictOfUuids::maybeGet()`](maybeGet.md) — return a UUID from the dict, or `null` if absent
- [`DictOfUuids::has()`](has.md) — check whether a UUID exists for the given key
- [`CollectionAsDict::set()`](../CollectionAsDict/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::set()%22)
- [Closed issues mentioning `DictOfUuids::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
