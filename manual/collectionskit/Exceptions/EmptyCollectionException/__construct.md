# EmptyCollectionException::__construct()

> `public function __construct(string $collectionType)`

Create an exception for an empty collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Exceptions\EmptyCollectionException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Exceptions;

class EmptyCollectionException
{
    /**
     * @param non-empty-string $collectionType
     *     the human-readable name of the collection (e.g.
     *     "StackOfStrings")
     */
    public function __construct(
        string $collectionType,
    )
}
```

## Description

Constructs the exception with two RFC 9457 fields fixed by the class and a third composed from the caller's input. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://stusdevkit.dev/errors/collections/empty`
- **status**: `500` (Internal Server Error)
- **title**: `$collectionType` followed by `" is empty"`

No `detail` and no `extra` are supplied. Because the parent uses `detail` as the exception message when present and falls back to the title otherwise, the built-in `Exception::getMessage()` returns the composed `"<collectionType> is empty"` string.

The narrower API surface is the point: callers don't have to remember the type URI or the status code every time they need to raise "this collection was empty". Name the collection, and the exception fills in the rest.

```php
throw new EmptyCollectionException(
    collectionType: 'StackOfStrings',
);
```

## Parameters

**`$collectionType`** (`non-empty-string`)

The human-readable name of the collection — for example, `"StackOfStrings"`. It is appended with `" is empty"` to form the problem title, which then surfaces from [`Rfc9457ProblemDetailsException::getTitle()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTitle.md) and, since no detail is supplied, from the built-in `Exception::getMessage()`.

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Exceptions\EmptyCollectionException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $collectionType as its only parameter
 ✔ ::__construct() declares $collectionType as string
 ✔ ::__construct() accepts a collection type string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 500
 ✔ ->getTitle() interpolates the collection type into "{type} is empty"
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->jsonSerialize() returns the RFC 9457 problem-details payload
```

## Source

[`kits/collectionskit/src/Exceptions/EmptyCollectionException.php:66`](../../../../kits/collectionskit/src/Exceptions/EmptyCollectionException.php#L66)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`EmptyCollectionException`](README.md) — the exception class this constructor builds
- [`EmptyStackException`](../EmptyStackException/README.md) — subclass thrown when an operation needs a non-empty stack

## Issues

- [Open issues mentioning `EmptyCollectionException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22EmptyCollectionException::__construct()%22)
- [Closed issues mentioning `EmptyCollectionException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22EmptyCollectionException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=EmptyCollectionException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
