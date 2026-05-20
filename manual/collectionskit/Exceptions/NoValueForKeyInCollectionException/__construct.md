# NoValueForKeyInCollectionException::__construct()

> `public function __construct(string $collectionType, int|string $key)`

Create an exception for a missing key.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Exceptions;

class NoValueForKeyInCollectionException
{
    /**
     * @param non-empty-string $collectionType
     *     the human-readable name of the collection (e.g.
     *     "CollectionAsDict", "DictOfStrings")
     * @param int|string $key
     *     the key the caller asked for
     */
    public function __construct(
        string $collectionType,
        int|string $key,
    )
}
```

## Description

Constructs the exception with two RFC 9457 fields fixed by the class and a third composed from the caller's input. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://stusdevkit.dev/errors/collections/no-value-for-key`
- **status**: `500` (Internal Server Error)
- **title**: `$collectionType`, then `" does not contain "`, then `$key`

No `detail` and no `extra` are supplied. Because the parent uses `detail` as the exception message when present and falls back to the title otherwise, the built-in `Exception::getMessage()` returns the composed `"<collectionType> does not contain <key>"` string.

The narrower API surface is the point: callers raising "this key is not in the collection" name the collection and the key, and the exception fills in the type URI and status.

```php
throw new NoValueForKeyInCollectionException(
    collectionType: 'DictOfStrings',
    key: 'user-42',
);
```

## Parameters

**`$collectionType`** (`non-empty-string`)

The human-readable name of the collection — for example, `"CollectionAsDict"` or `"DictOfStrings"`. It becomes the first part of the composed problem title.

**`$key`** (`int|string`)

The key the caller asked for. It is appended to the title after `" does not contain "`, so the failure message names exactly which key was missing. Both integer and string keys are accepted, matching the key types a CollectionsKit dictionary or index can hold.

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
StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $collectionType and $key as its only parameters
 ✔ ::__construct() declares $collectionType as string
 ✔ ::__construct() declares $key as int|string
 ✔ ::__construct() accepts a collection type and a string key
 ✔ ::__construct() accepts an integer key
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 500
 ✔ ->getTitle() composes "{type} does not contain {key}" for a string key
 ✔ ->getTitle() composes "{type} does not contain {key}" for an integer key
 ✔ ->getMessage() falls back to the title when no detail is set
```

## Source

[`kits/collectionskit/src/Exceptions/NoValueForKeyInCollectionException.php:66`](../../../../kits/collectionskit/src/Exceptions/NoValueForKeyInCollectionException.php#L66)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`NoValueForKeyInCollectionException`](README.md) — the exception class this constructor builds
- [`CollectionAsDict::get()`](../../Dictionaries/CollectionAsDict/get.md) — the dictionary accessor that constructs and throws this exception

## Issues

- [Open issues mentioning `NoValueForKeyInCollectionException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NoValueForKeyInCollectionException::__construct()%22)
- [Closed issues mentioning `NoValueForKeyInCollectionException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NoValueForKeyInCollectionException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NoValueForKeyInCollectionException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
