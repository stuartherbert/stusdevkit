# DictOfBooleans::isTrue()

> `public function isTrue(mixed $name): bool`

is the named flag set to `true`?

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @param TKey $name
     * @return bool
     * - true if the named flag exists, and is set to `true`
     * - false if the named flag exists, and is set to `false`
     * - false if the named flag does not exist
     */
    public function isTrue(mixed $name): bool
}
```

## Description

Reports whether the named flag is currently set to `true`.

The method folds the "absent" case into a `false` answer so callers do not have to test for presence separately. If you need to tell "absent" apart from "present and false", call [`CollectionAsDict::has()`](../CollectionAsDict/has.md) first.

## Parameters

**`$name`** (`mixed`)

The name of the flag to check. The native parameter type is `mixed`; the docblock narrows it to `TKey` (`array-key`), so callers must pass a string or an integer.

## Return Values

- `true` if the named flag exists, and is set to `true`
- `false` if the named flag exists, and is set to `false`
- `false` if the named flag does not exist

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ::isTrue() signature: isTrue(mixed $name): bool
 ✔ ->isTrue() returns true for flag set to true
 ✔ ->isTrue() returns false for flag set to false
 ✔ ->isTrue() returns false for non-existent flag
 ✔ ->isTrue() returns false for empty dict
 ✔ ->isTrue() reflects value set via set()
 ✔ ->isTrue() reflects overwritten value
 ✔ ->isTrue() and ->isFalse() are opposites for existing flags
 ✔ ->isTrue() and ->isFalse() both return false for missing flags
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfBooleans.php:90`](../../../../kits/collectionskit/src/Dictionaries/DictOfBooleans.php#L90)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans::isFalse()`](isFalse.md) — is the named flag set to `false`?
- [`CollectionAsDict::has()`](../CollectionAsDict/has.md) — check whether a value exists for the given key
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — return a value from the collection, throwing if absent

## Issues

- [Open issues mentioning `DictOfBooleans::isTrue()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::isTrue()%22)
- [Closed issues mentioning `DictOfBooleans::isTrue()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::isTrue()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
