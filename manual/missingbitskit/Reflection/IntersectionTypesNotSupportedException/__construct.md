# IntersectionTypesNotSupportedException::__construct()

> `public function __construct(ReflectionIntersectionType $refType)`

Pre-fills the parent's `type`, `status`, `title`, and `extra` fields for
an intersection type that cannot be flattened.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Reflection\IntersectionTypesNotSupportedException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionIntersectionType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

class IntersectionTypesNotSupportedException extends Rfc9457ProblemDetailsException
{
    public function __construct(ReflectionIntersectionType $refType);
}
```

## Description

`__construct()` accepts a `ReflectionIntersectionType` and pre-fills the parent's
RFC 9457 problem-details fields:

- **`type`** — fixed URI `https://github.com/stuartherbert/stusdevkit/`
- **`status`** — `422` (Unprocessable Content)
- **`title`** — `"Intersection types cannot be flattened to a list of names"`
- **`extra`** — `['type' => (string)$refType]`, carrying the intersection type's
  string form (e.g. `"Countable&ArrayAccess"`) under the key `type`.

An intersection `A&B` means "a value that satisfies both A and B simultaneously".
Collapsing that to `['A', 'B']` discards the "and" semantics — the flat list
becomes indistinguishable from a union `A|B` (which means "a value that
satisfies either A or B"). Callers reasoning from the flat list would draw
wrong conclusions. Rather than silently produce misleading output, this exception
refuses the input.

Distinct from `UnsupportedReflectionTypeException`: that one signals "this is a
ReflectionType subclass the flattener does not recognise" (a library maintenance
gap). This one signals "the input is known and well-formed, but deliberately
outside the flattener's contract" (a caller contract violation).

**Siblings:**

- [`UnsupportedReflectionTypeException`](../UnsupportedReflectionTypeException/README.md) — for unrecognised ReflectionType subclasses

## Parameters

**`$refType`** (`ReflectionIntersectionType`)

The intersection type that cannot be flattened. PHP's `ReflectionIntersectionType::__toString()`
renders it as `A&B` (e.g. `"Countable&ArrayAccess"`), which is stored in the
exception's `extra` slot under the key `type`.

## Return Values

This is a constructor — it returns nothing. However, it configures the following
parent class accessors:

- **`getTypeAsString()`** — returns `"https://github.com/stuartherbert/stusdevkit/"`
- **`getStatus()`** — returns `422` (Unprocessable Content)
- **`getTitle()`** — returns `"Intersection types cannot be flattened to a list of names"`
- **`getExtra()`** — returns `['type' => '<intersection type string>']`
- **`hasExtra()`** — returns `true` (extra is always populated)
- **`maybeGetDetail()`** — returns `null` (no detail is set)
- **`getMessage()`** — falls back to the title: `"Intersection types cannot be flattened to a list of names"`
- **`hasInstance()`** — returns `false` (no instance URI is set)

## Errors/Exceptions

_None._ This exception is thrown, not caught.

## Here Be Dragons

This exception is specific to intersection types — it does not apply to union
types, which can be safely flattened into a list.

## Examples

```php
// When a flattener encounters an intersection type:
throw new IntersectionTypesNotSupportedException($refType);

// The exception serialises to an RFC 9457 response body:
[
    'type'    => 'https://github.com/stuartherbert/stusdevkit/',
    'status'  => 422,
    'title'   => 'Intersection types cannot be flattened to a list of names',
    'extra'   => ['type' => 'Countable&ArrayAccess'],
];

// getMessage() falls back to the title:
$e->getMessage(); // "Intersection types cannot be flattened to a list of names"

// hasExtra() returns true:
$e->hasExtra(); // true

// getExtra() carries the intersection type string under "type":
$e->getExtra(); // ['type' => 'Countable&ArrayAccess']

// maybeGetDetail() returns null:
$e->maybeGetDetail(); // null

// hasInstance() returns false:
$e->hasInstance(); // false
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\IntersectionTypesNotSupportedException
 ✔ __construct() is declared
 ✔ __construct() is public
 ✔ __construct() declares $refType as its only parameter
 ✔ __construct() declares $refType as ReflectionIntersectionType
 ✔ __construct() accepts a ReflectionIntersectionType
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the intersection type is stored in extra
 ✔ ->getExtra() carries the intersection type string under the "type" key
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/missingbitskit/src/Reflection/IntersectionTypesNotSupportedException.php:67`](../src/Reflection/IntersectionTypesNotSupportedException.php#L67)

## Changelog

_No tagged releases yet._

## See Also

- [`UnsupportedReflectionTypeException`](../UnsupportedReflectionTypeException/README.md) — for unrecognised ReflectionType subclasses
- [`GetReflectionTypes`](../GetReflectionTypes/README.md) — the flattener that throws this exception

## Issues

- [Open issues mentioning `IntersectionTypesNotSupportedException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IntersectionTypesNotSupportedException%3A%3A__construct()%22)
- [Closed issues mentioning `IntersectionTypesNotSupportedException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IntersectionTypesNotSupportedException%3A%3A__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IntersectionTypesNotSupportedException%3A%3A__construct()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
