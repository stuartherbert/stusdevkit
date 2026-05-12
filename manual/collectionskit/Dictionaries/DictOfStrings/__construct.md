# DictOfStrings::__construct()

> `public function __construct(array $data = [])`

Create a new dict of strings, optionally seeded with data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @param array<array-key, string> $data
     *
     * @throws NullValueNotAllowedException
     */
    public function __construct(
        protected array $data = [],
    )
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function __construct(array $data = [])`,
> inherited from [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md).
> The narrowed `array<array-key, string>` shown above is bound by
> `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Creates a new `DictOfStrings`, optionally seeded with an array of strings keyed by `array-key` (`int` or `string`).

The constructor accepts an associative array whose values are strings. The array is stored as the dict's initial contents.

The constructor rejects any array containing a `null` value. This is enforced by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md), which throws a `NullValueNotAllowedException` on violation. The null prohibition is a hard invariant across all CollectionsKit collection types — it lets `maybeGet()` and `maybeFirst()` use `null` unambiguously to mean "absent" or "empty".

## Parameters

**`$data`** (`array<array-key, string>`, optional, default: `[]`)

The initial contents of the dict. Keys are integers or strings (the `array-key` bound); values must be strings. The PHP parameter type is `array`; the class's `@extends CollectionAsDict<array-key, string>` binding narrows it to `array<array-key, string>`.

## Return Values

_None._ The constructor does not return a value; it initialises the dict instance.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$data` is `null`. The exception message names `DictOfStrings` as the offending collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ ::__construct() creates an empty dict
 ✔ ::__construct() accepts initial data
 ✔ ::__construct() preserves string keys
 ✔ ::__construct() accepts integer keys
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:150`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::set()`](set.md) — store a string in the dict
- [`DictOfStrings::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::__construct()%22)
- [Closed issues mentioning `DictOfStrings::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
