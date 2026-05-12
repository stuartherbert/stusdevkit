# DictOfObjects::__construct()

> `public function __construct(array $data = [])`

Create a new dict of objects, optionally seeded with data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @param array<array-key, object> $data
     *
     * @throws NullValueNotAllowedException
     */
    public function __construct(
        protected array $data = [],
    )
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function __construct(array $data = [])`,
> inherited from [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Creates a new `DictOfObjects`, optionally seeded with an array of objects keyed by `array-key` (`int` or `string`).

The constructor accepts an associative array whose values are objects. The array is stored as the dict's initial contents.

The constructor rejects any array containing a `null` value. This is enforced by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md), which throws a `NullValueNotAllowedException` on violation. The null prohibition is a hard invariant across all CollectionsKit collection types — it lets `maybeGet()` and `maybeFirst()` use `null` unambiguously to mean "absent" or "empty".

## Parameters

**`$data`** (`array<array-key, object>`, optional, default: `[]`)

The initial contents of the dict. Keys are integers or strings (the `array-key` bound inherited from [`CollectionAsDict`](../CollectionAsDict/README.md)); values must be objects (the bound declared on `DictOfObjects`). The PHP parameter type is `array`; the class's template binding narrows it to `array<array-key, object>`.

## Return Values

_None._ The constructor does not return a value; it initialises the dict instance.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$data` is `null`. The exception message names the runtime collection type as the offending collection — for a direct `DictOfObjects`, that is `DictOfObjects`; for a subclass such as `DictOfUuids`, the subclass's name appears instead.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
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

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::set()`](set.md) — store an object in the dict
- [`DictOfObjects::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::__construct()%22)
- [Closed issues mentioning `DictOfObjects::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
