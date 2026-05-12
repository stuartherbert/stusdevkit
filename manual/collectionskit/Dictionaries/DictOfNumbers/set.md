# DictOfNumbers::set()

> `public function set(int|string $key, int|float $value): static`

Store a number in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @param int|float $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(int|string $key, int|float $value): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> value type `int|float` comes from `@template TValue of int|float` declared on
> [`DictOfNumbers`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Stores a number in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored value is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfNumbers::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfNumbers` specifically, a `null` value would already be a type error at the PHPStan level (`int|float $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key under which to store the number. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

**`$value`** (`int|float`)

The number to store. The PHP signature accepts `mixed`; the `@template TValue of int|float` binding on `DictOfNumbers` narrows this to `int|float`. Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names the runtime collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->set() stores an integer with a string key
 ✔ ->set() stores a float with a string key
 ✔ ->set() stores a value with an integer key
 ✔ ->set() overwrites existing value at same key
 ✔ ->set() can overwrite an integer with a float
 ✔ ->set() can overwrite a float with an integer
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

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::get()`](get.md) — return a number from the dict, throwing if absent
- [`DictOfNumbers::maybeGet()`](maybeGet.md) — return a number from the dict, or `null` if absent
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`CollectionAsDict::set()`](../CollectionAsDict/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::set()%22)
- [Closed issues mentioning `DictOfNumbers::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
