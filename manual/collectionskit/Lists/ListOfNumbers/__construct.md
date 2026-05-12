# ListOfNumbers::__construct()

> `public function __construct(array $data = [])`

Create a new `ListOfNumbers`, optionally seeded with numbers.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfNumbers extends CollectionAsList
{
    /**
     * @param array<int, int|float> $data
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
> The narrowed types shown above come from the `@template TValue of int|float = int|float`
> on [`ListOfNumbers`](README.md); subclasses can pin these further (e.g.
> [`ListOfIntegers`](../ListOfIntegers/README.md) pins `TValue` to `int`,
> [`ListOfFloats`](../ListOfFloats/README.md) pins `TValue` to `float`).

## Description

Constructs a new `ListOfNumbers`. When `$data` is supplied, every entry is stored as the list's initial contents, in the order given; sequential integer keys are preserved. When `$data` is omitted, the list starts empty.

The constructor rejects any array containing a `null` value. The no-null invariant is enforced for every collection in CollectionsKit so that the maybe-* accessors ([`maybeFirst()`](maybeFirst.md), [`maybeLast()`](maybeLast.md)) can use `null` to mean "the list is empty" without ambiguity.

## Parameters

**`$data`** (`array<int, int|float>`, optional, default: `[]`)

The initial numbers to seed the list with. Each value must be an `int` or a `float`, and must not be `null`. Keys are preserved as supplied, so callers can pass a list-shaped array or rely on the default of an empty list. Mixed `int`/`float` arrays are accepted; each value's PHP type is preserved on the way in.

The PHP signature accepts `array`, but the class's `@template TValue of int|float = int|float` re-bound narrows the value type to `int|float`.

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
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ ::__construct() creates an empty list
 ✔ ::__construct() accepts initial integers
 ✔ ::__construct() accepts initial floats
 ✔ ::__construct() accepts mixed int and float values
 ✔ ::__construct() preserves sequential integer keys
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:150`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](README.md) — where the `<int|float>` template parameter is re-bounded
- [`CollectionOfAnything::__construct()`](../../CollectionOfAnything/__construct.md) — the generic implementation this page specialises
- [`ListOfNumbers::add()`](add.md) — append a single number after construction

## Issues

- [Open issues mentioning `ListOfNumbers::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%3A%3A__construct()%22)
- [Closed issues mentioning `ListOfNumbers::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%3A%3A__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
