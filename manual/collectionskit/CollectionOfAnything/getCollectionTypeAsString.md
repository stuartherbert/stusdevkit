# CollectionOfAnything::getCollectionTypeAsString()

> `public function getCollectionTypeAsString(): string`

Return the unqualified class name of this collection type.

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
     * @return non-empty-string the class basename, e.g. `ListOfStrings` or `CollectionOfAnything`.
     */
    public function getCollectionTypeAsString(): string
}
```

## Description

Return the unqualified class name of this collection type. Uses late-static binding (`static::class`) so that on a subclass the returned name is the subclass's basename, not `CollectionOfAnything`. This is primarily used by the null-value validator to build human-readable error messages that name the offending collection type.

## Parameters

_None._

## Return Values

A `non-empty-string` containing the class basename. For example, `ListOfStrings` or `CollectionOfAnything`. On a subclass, returns the subclass's basename.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
CollectionOfAnything
 ✔ ->getCollectionTypeAsString() returns the unqualified class name
 ✔ ->getCollectionTypeAsString() resolves via late-static binding on subclasses
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:272`](../../../kits/collectionskit/src/CollectionOfAnything.php#L272)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::__construct()`](__construct.md) — creates a collection, optionally seeded with data
- [`CollectionOfAnything::toArray()`](toArray.md) — returns the stored data as a plain PHP array
- [`CollectionOfAnything::count()`](count.md) — returns the number of elements stored in the collection

## Issues

- [Open issues mentioning `CollectionOfAnything::getCollectionTypeAsString()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::getCollectionTypeAsString()%22)
- [Closed issues mentioning `CollectionOfAnything::getCollectionTypeAsString()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::getCollectionTypeAsString()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
