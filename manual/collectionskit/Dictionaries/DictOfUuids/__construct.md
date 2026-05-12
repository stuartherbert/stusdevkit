# DictOfUuids::__construct()

> `public function __construct(array $data = [])`

Create a new dict of UUIDs, optionally seeded with data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfUuids extends DictOfObjects
{
    /**
     * @param array<string, UuidInterface> $data
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
> The narrowed `array<string, UuidInterface>` shown above is bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Creates a new `DictOfUuids`, optionally seeded with an array of UUIDs keyed by string.

The constructor accepts an associative array of `string` keys to [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) values. The array is stored as the dict's initial contents.

The constructor rejects any array containing a `null` value. This is enforced by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md), which throws a `NullValueNotAllowedException` on violation. The null prohibition is a hard invariant across all CollectionsKit collection types — it lets `maybeGet()` and `maybeFirst()` use `null` unambiguously to mean "absent" or "empty".

## Parameters

**`$data`** (`array<string, UuidInterface>`, optional, default: `[]`)

The initial contents of the dict. Keys are strings (typically the UUID's own string representation, but any string is accepted); values must implement [`UuidInterface`](https://uuid.ramsey.dev/en/stable/). The PHP parameter type is `array`; the class's `@extends DictOfObjects<string, UuidInterface>` binding narrows this to `array<string, UuidInterface>`.

## Return Values

_None._ The constructor does not return a value; it initialises the dict instance.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$data` is `null`. The exception message names `DictOfUuids` as the offending collection type.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ::__construct() creates an empty dict
 ✔ ::__construct() accepts initial data
 ✔ ::__construct() preserves string keys
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:150`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::set()`](set.md) — store a UUID in the dict
- [`DictOfUuids::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::__construct()%22)
- [Closed issues mentioning `DictOfUuids::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
