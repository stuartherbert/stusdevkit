# CollectionOfAnything::__construct()

> `public function __construct(array $data = [])`

Create a new collection, optionally seeded with data.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\CollectionOfAnything`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Arrays\Arrayable;

class CollectionOfAnything implements Arrayable, Countable, IteratorAggregate
{
    /**
     * @param array<TKey, TValue> $data
     *
     * @throws NullValueNotAllowedException
     */
    public function __construct(
        protected array $data = [],
    )
}
```

## Description

Create a new collection, optionally seeded with data. The constructor accepts an array of key-value pairs and stores them as the collection's initial contents. The array may be indexed (integer keys) or associative (string keys); both are preserved as-is.

The constructor rejects any array containing a `null` value. This is enforced by the `RejectNullArrayValues` validator, which throws a `NullValueNotAllowedException` on violation. The null prohibition is a hard invariant across all CollectionsKit collection types.

## Parameters

**`$data`** (`array<TKey, TValue>`, optional, default: `[]`)

The initial contents of the collection. May be an indexed array (integer keys) or an associative array (string keys). Both key types are preserved as-is.

## Return Values

_None._ The constructor does not return a value; it initializes the collection instance.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$data` is null.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
CollectionOfAnything
 ✔ ::__construct() signature: __construct(array $data = [])
 ✔ ::__construct() creates an empty collection when called with no arguments
 ✔ ::__construct() seeds the collection from an indexed array
 ✔ ::__construct() seeds the collection from an associative array
 ✔ ::__construct() rejects an array containing a null value
 ✔ ::__construct() rejects a single null-only array
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:146`](../../../kits/collectionskit/src/CollectionOfAnything.php#L146)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::toArray()`](toArray.md) — returns the stored data as a plain PHP array
- [`CollectionOfAnything::count()`](count.md) — returns the number of elements stored in the collection
- [`CollectionOfAnything::empty()`](empty.md) — returns true if the collection contains no elements

## Issues

- [Open issues mentioning `CollectionOfAnything::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::__construct()%22)
- [Closed issues mentioning `CollectionOfAnything::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
