# UnsupportedReflectionTypeException::__construct()

> `public __construct(ReflectionType $refType)`

Accepts a ReflectionType and pre-fills the parent's `type`, `status`,
`title`, and `extra` fields to signal a library-maintenance gap.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Reflection\UnsupportedReflectionTypeException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionType;

class UnsupportedReflectionTypeException
{
    public function __construct(
        ReflectionType $refType,
    ) {}
}
```

## Description

Constructs a new `UnsupportedReflectionTypeException` that signals a
**library-maintenance gap**: the caller fed in a perfectly valid
`ReflectionType` subclass, but this library does not yet know how to
handle it.

The constructor pre-fills the parent's fields with fixed values:

- `type` → `"http://github.com/stuartherbert/stusdevkit/"` (a fixed
  documentation URI)
- `status` → `500` (RFC-correct status for a server-side problem)
- `title` → `"Unsupported PHP ReflectionType child class"` (human-readable summary)
- `extra` → `['class_name' => $refType::class]` (the concrete subclass name)

No `detail` is supplied, so `maybeGetDetail()` returns null and
`getMessage()` falls back to the fixed title.

**FORWARDS-COMPATIBILITY NOTE:** A future release may accept a more
specific type than `ReflectionType` once the flattener gains support
for additional ReflectionType subclasses.

## Parameters

**`$refType`** (`ReflectionType`)

The ReflectionType subclass instance that the flattener does not yet
recognise. The concrete subclass name (e.g. `'ReflectionNamedType'`)
is stored in `extra['class_name']`.

## Source

[`kits/missingbitskit/src/Reflection/UnsupportedReflectionTypeException.php:42`](../src/Reflection/UnsupportedReflectionTypeException.php#L42)

## Changelog

_No tagged releases yet._

## See Also

- [`IntersectionTypesNotSupportedException::__construct()`](../IntersectionTypesNotSupportedException/__construct.md) — for intersection type errors (caller contract violation, status 422)
- [`hasExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasExtra.md) — check if extra data is available
- [`getExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getExtra.md) — retrieve extra data array

## Issues

- [Open issues mentioning `UnsupportedReflectionTypeException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UnsupportedReflectionTypeException::__construct()%22)
- [Closed issues mentioning `UnsupportedReflectionTypeException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UnsupportedReflectionTypeException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UnsupportedReflectionTypeException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
