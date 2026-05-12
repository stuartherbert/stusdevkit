# DictOfNumbers::__construct()

> `public function __construct(array $data = [])`

Create a new dict of numbers, optionally seeded with data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @param array<array-key, int|float> $data
     *
     * @throws NullValueNotAllowedException
     */
    public function __construct(
        protected array $data = [],
    )
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function __construct(array $data = [])`,
> inherited from [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Creates a new `DictOfNumbers`, optionally seeded with an array of numbers keyed by `array-key` (`int` or `string`).

The constructor accepts an associative array whose values are `int` or `float`. The array is stored as the dict's initial contents.

The constructor rejects any array containing a `null` value. This is enforced by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md), which throws a `NullValueNotAllowedException` on violation. The null prohibition is a hard invariant across all CollectionsKit collection types — it lets `maybeGet()` and `maybeFirst()` use `null` unambiguously to mean "absent" or "empty".

## Parameters

**`$data`** (`array<array-key, int|float>`, optional, default: `[]`)

The initial contents of the dict. Keys are integers or strings (the `array-key` bound inherited from [`CollectionAsDict`](../CollectionAsDict/README.md)); values must be `int` or `float` (the bound declared on `DictOfNumbers`). The PHP parameter type is `array`; the class's template binding narrows it to `array<array-key, int|float>`.

## Return Values

_None._ The constructor does not return a value; it initialises the dict instance.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$data` is `null`. The exception message names the runtime collection type as the offending collection — for a direct `DictOfNumbers`, that is `DictOfNumbers`; for a subclass such as `DictOfIntegers`, the subclass's name appears instead.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ::__construct() creates an empty dict
 ✔ ::__construct() accepts initial integer data
 ✔ ::__construct() accepts initial float data
 ✔ ::__construct() accepts mixed integer and float data
 ✔ ::__construct() preserves string keys
 ✔ ::__construct() accepts integer keys
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:150`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::set()`](set.md) — store a number in the dict
- [`DictOfNumbers::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::__construct()%22)
- [Closed issues mentioning `DictOfNumbers::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
