# DictOfFloats::set()

> `public function set(int|string $key, float $value): static`

Store a float in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfFloats extends DictOfNumbers
{
    /**
     * @param array-key $key
     * @param float $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(int|string $key, float $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> narrowed `array-key` key and `float` value types shown above are bound by
> `@template-extends DictOfNumbers<array-key, float>` on [`DictOfFloats`](README.md).

## Description

Stores a float in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored float is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfFloats::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfFloats` specifically, a `null` value would already be a type error at the PHP signature level (`float $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key under which to store the float. The PHP signature accepts `mixed`; the class's `@template-extends DictOfNumbers<array-key, float>` binding narrows this to `int|string`.

**`$value`** (`float`)

The float to store. The PHP signature accepts `mixed`; the class's template binding narrows this to `float`. Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names `DictOfFloats` as the offending collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfFloats
 ✔ ->set() stores a value with a string key
 ✔ ->set() stores a value with an integer key
 ✔ ->set() overwrites existing value at same key
 ✔ ->set() adds to existing data
 ✔ ->set() returns $this for method chaining
 ✔ ->set() supports fluent chaining
 ✔ ->set() accepts various float values with data set "positive float"
 ✔ ->set() accepts various float values with data set "negative float"
 ✔ ->set() accepts various float values with data set "zero"
 ✔ ->set() accepts various float values with data set "very small positive"
 ✔ ->set() accepts various float values with data set "very large positive"
 ✔ ->set() accepts various float values with data set "very small negative"
 ✔ ->set() accepts various float values with data set "very large negative"
 ✔ ->set() accepts various float values with data set "one third"
 ✔ ->set() accepts various float values with data set "pi approximation"
 ✔ ->set() accepts various float values with data set "euler number"
 ✔ ->set() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:105`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L105)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfFloats`](README.md) — where the `<array-key, float>` template parameters are bound
- [`DictOfFloats::get()`](get.md) — return a float from the dict, throwing if absent
- [`DictOfFloats::maybeGet()`](maybeGet.md) — return a float from the dict, or `null` if absent
- [`DictOfFloats::has()`](has.md) — check whether a float exists for the given key
- [`DictOfNumbers::set()`](../DictOfNumbers/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfFloats::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfFloats::set()%22)
- [Closed issues mentioning `DictOfFloats::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfFloats::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
