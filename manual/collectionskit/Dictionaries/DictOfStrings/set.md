# DictOfStrings::set()

> `public function set(int|string $key, string $value): static`

Store a string in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @param string $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(int|string $key, string $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> narrowed `array-key` key and `string` value types shown above are bound by
> `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Stores a string in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored string is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfStrings::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfStrings` specifically, a `null` value would already be a type error at the PHP signature level (`string $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key under which to store the string. The PHP signature accepts `mixed`; the class's `@extends CollectionAsDict<array-key, string>` binding narrows this to `int|string`.

**`$value`** (`string`)

The string to store. The PHP signature accepts `mixed`; the class's template binding narrows this to `string`. Passing `null` is rejected by the validator. An empty string (`""`) is permitted.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names `DictOfStrings` as the offending collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
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

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::get()`](get.md) — return a string from the dict, throwing if absent
- [`DictOfStrings::maybeGet()`](maybeGet.md) — return a string from the dict, or `null` if absent
- [`DictOfStrings::has()`](has.md) — check whether a string exists for the given key
- [`CollectionAsDict::set()`](../CollectionAsDict/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::set()%22)
- [Closed issues mentioning `DictOfStrings::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
