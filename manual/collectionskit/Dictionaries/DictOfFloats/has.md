# DictOfFloats::has()

> `public function has(int|string $key): bool`

Check to see if we have a float for the given `$key` in this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfFloats extends DictOfNumbers
{
    /**
     * @param array-key $key
     * @return bool
     * - `true` if we have a float for `$key`
     * - `false` otherwise
     */
    public function has(int|string $key): bool
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function has($key): bool`, inherited from
> [`CollectionAsDict::has()`](../CollectionAsDict/has.md). The narrowed
> `array-key` key type shown above is bound by
> `@template-extends DictOfNumbers<array-key, float>` on [`DictOfFloats`](README.md).

## Description

Reports whether a float is stored for the given `$key` in this dict.

The implementation uses PHP's `isset()` over the underlying array, so an empty dict always returns `false`. Combined with the no-null invariant enforced by [`DictOfFloats::set()`](set.md) and the constructor, `has()` and [`DictOfFloats::maybeGet()`](maybeGet.md) agree: `has()` returning `false` is equivalent to `maybeGet()` returning `null`.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose presence is being tested. The PHP signature accepts `mixed`; the class's `@template-extends DictOfNumbers<array-key, float>` binding narrows this to `int|string`.

## Return Values

- `true` if we have a float for `$key`
- `false` otherwise

An empty dict always returns `false`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfFloats
 ✔ ->has() returns true for existing string key
 ✔ ->has() returns true for key with zero value
 ✔ ->has() returns false for missing key
 ✔ ->has() returns false for empty dict
 ✔ ->has() returns true for key added via set()
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:177`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L177)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfFloats`](README.md) — where the `<array-key, float>` template parameters are bound
- [`DictOfFloats::get()`](get.md) — return a float from the dict, throwing if absent
- [`DictOfFloats::maybeGet()`](maybeGet.md) — return a float from the dict, or `null` if absent
- [`DictOfFloats::set()`](set.md) — store a float in the dict
- [`CollectionAsDict::has()`](../CollectionAsDict/has.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfFloats::has()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfFloats::has()%22)
- [Closed issues mentioning `DictOfFloats::has()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfFloats::has()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
