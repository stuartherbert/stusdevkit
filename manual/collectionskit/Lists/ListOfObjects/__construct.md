# ListOfObjects::__construct()

> `public function __construct(array $data = [])`

Create a new `ListOfObjects`, optionally seeded with objects.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfObjects extends CollectionAsList
{
    /**
     * @param array<int, object> $data
     * @throws NullValueNotAllowedException
     */
    public function __construct(
        protected array $data = [],
    )
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function __construct(protected array $data = [])`,
> inherited from [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md).
> The narrowed `array<int, object>` parameter type shown above is bound by
> `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Constructs a new `ListOfObjects`. When `$data` is supplied, every entry is stored as the list's initial contents, in the order given; sequential integer keys are preserved. When `$data` is omitted, the list starts empty.

The constructor rejects any array containing a `null` value. The no-null invariant is enforced for every collection in CollectionsKit so that the maybe-* accessors ([`maybeFirst()`](maybeFirst.md), [`maybeLast()`](maybeLast.md)) can use `null` to mean "the list is empty" without ambiguity.

## Parameters

**`$data`** (`array<int, object>`, optional, default: `[]`)

The initial objects to seed the list with. Each value must be an `object` (instance of any class) and must not be `null`. Keys are preserved as supplied, so callers can pass a list-shaped array or rely on the default of an empty list.

The PHP signature accepts `array`, but the class's `@extends CollectionAsList<object>` binding narrows the value type to `object`.

## Return Values

_None._ (Constructors do not return.)

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$data` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ ::__construct() creates an empty list
 ✔ ::__construct() accepts initial objects
 ✔ ::__construct() preserves sequential integer keys
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:150`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md) — the generic implementation this page specialises
- [`ListOfObjects::add()`](add.md) — append a single object after construction

## Issues

- [Open issues mentioning `ListOfObjects::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3A__construct()%22)
- [Closed issues mentioning `ListOfObjects::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3A__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
