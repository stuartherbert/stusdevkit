# DictOfIntegers::set()

> `public function set(int|string $key, int $value): static`

Store an integer in the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfIntegers extends DictOfNumbers
{
    /**
     * @param array-key $key
     * @param int $value
     *
     * @throws NullValueNotAllowedException
     */
    public function set(int|string $key, int $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function set(mixed $key, mixed $value): static`,
> inherited from [`CollectionAsDict::set()`](../CollectionAsDict/set.md). The
> narrowed `array-key` key and `int` value types shown above are bound by
> `@template-extends DictOfNumbers<array-key, int>` on [`DictOfIntegers`](README.md).

## Description

Stores an integer in the dict under the given `$key`.

If the key already exists in this dict, the previously-stored integer is replaced by `$value`.

The value passes through [`RejectNullValue`](../../Validators/RejectNullValue/README.md) before storage. The no-null invariant is what lets [`DictOfIntegers::maybeGet()`](maybeGet.md) use `null` unambiguously to mean "key absent" — but for `DictOfIntegers` specifically, a `null` value would already be a type error at the PHP signature level (`int $value`), so the runtime check rarely fires.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key under which to store the integer. The PHP signature accepts `mixed`; the class's `@template-extends DictOfNumbers<array-key, int>` binding narrows this to `int|string`.

**`$value`** (`int`)

The integer to store. The PHP signature accepts `mixed`; the class's template binding narrows this to `int`. Passing `null` is rejected by the validator.

## Return Values

Returns `$this` — the same dict instance, with `$key => $value` stored. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`. The exception message names `DictOfIntegers` as the offending collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers
 ✔ ->set() stores a value with a string key
 ✔ ->set() stores a value with an integer key
 ✔ ->set() overwrites existing value at same key
 ✔ ->set() adds to existing data
 ✔ ->set() returns $this for method chaining
 ✔ ->set() supports fluent chaining
 ✔ ->set() accepts various integer values with data set "positive integer"
 ✔ ->set() accepts various integer values with data set "negative integer"
 ✔ ->set() accepts various integer values with data set "zero"
 ✔ ->set() accepts various integer values with data set "one"
 ✔ ->set() accepts various integer values with data set "negative one"
 ✔ ->set() accepts various integer values with data set "large positive"
 ✔ ->set() accepts various integer values with data set "large negative"
 ✔ ->set() accepts various integer values with data set "power of two"
 ✔ ->set() accepts various integer values with data set "hex-friendly value"
 ✔ ->set() accepts various integer values with data set "small negative"
 ✔ ->set() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:105`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L105)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfIntegers`](README.md) — where the `<array-key, int>` template parameters are bound
- [`DictOfIntegers::get()`](get.md) — return an integer from the dict, throwing if absent
- [`DictOfIntegers::maybeGet()`](maybeGet.md) — return an integer from the dict, or `null` if absent
- [`DictOfIntegers::has()`](has.md) — check whether an integer exists for the given key
- [`DictOfNumbers::set()`](../DictOfNumbers/set.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfIntegers::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfIntegers::set()%22)
- [Closed issues mentioning `DictOfIntegers::set()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfIntegers::set()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
