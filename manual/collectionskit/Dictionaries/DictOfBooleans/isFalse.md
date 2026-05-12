# DictOfBooleans::isFalse()

> `public function isFalse(mixed $name): bool`

is the named flag set to `false`?

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
     * - true if the named flag exists, and is set to `false`
     * - false if the named flag exists, and is set to `true`
     * - false if the named flag does not exist
     */
    public function isFalse(mixed $name): bool
}
```

## Description

Reports whether the named flag is currently set to `false`.

The method folds the "absent" case into a `false` answer so callers do not have to test for presence separately. If you need to tell "absent" apart from "present and true", call [`CollectionAsDict::has()`](../CollectionAsDict/has.md) first.

## Parameters

**`$name`** (`mixed`)

The name of the flag to check. The native parameter type is `mixed`; the docblock narrows it to `TKey` (`array-key`), so callers must pass a string or an integer.

## Return Values

- `true` if the named flag exists, and is set to `false`
- `false` if the named flag exists, and is set to `true`
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
 ✔ ::isFalse() signature: isFalse(mixed $name): bool
 ✔ ->isFalse() returns true for flag set to false
 ✔ ->isFalse() returns false for flag set to true
 ✔ ->isFalse() returns false for non-existent flag
 ✔ ->isFalse() returns false for empty dict
 ✔ ->isFalse() reflects value set via set()
 ✔ ->isFalse() reflects overwritten value
 ✔ ->isTrue() and ->isFalse() are opposites for existing flags
 ✔ ->isTrue() and ->isFalse() both return false for missing flags
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfBooleans.php:108`](../../../../kits/collectionskit/src/Dictionaries/DictOfBooleans.php#L108)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans::isTrue()`](isTrue.md) — is the named flag set to `true`?
- [`CollectionAsDict::has()`](../CollectionAsDict/has.md) — check whether a value exists for the given key
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — return a value from the collection, throwing if absent

## Issues

- [Open issues mentioning `DictOfBooleans::isFalse()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::isFalse()%22)
- [Closed issues mentioning `DictOfBooleans::isFalse()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::isFalse()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
