# InvalidClassException::__construct()

> `public function __construct(string $className)`

Build an `InvalidClassException` carrying the caller-supplied class name in the `extra` slot under the `class_name` key.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\InvalidClassException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class InvalidClassException
{
    /**
     * @param string $className
     *     the class name that cannot be found / resolved
     */
    public function __construct(
        string $className,
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus the caller-supplied class name stashed in the parent's [`extra`](../Rfc9457ProblemDetailsException/getExtra.md) slot. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/invalid-class` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Content)
- **title**: `"Class name does not refer to a loadable class"`
- **extra**: `['class_name' => $className]`

The narrower API surface is the point: callers don't have to remember the type URI, the status code, the title, or the wire-format key every time they want to raise "the string isn't a loadable class name". Hand in the offending class name string, and the exception fills in the rest.

```php
throw new InvalidClassException(
    className: 'Missing\\ClassName',
);
```

Unlike its sibling [`InvalidArgumentException`](../InvalidArgumentException/README.md), this constructor takes no `$detail` and no `$extra` — the class name is the entire payload, and it is carried in `extra` (not `detail`) so the value is machine-readable rather than embedded in prose.

## Parameters

**`$className`** (`string`)

The class name that cannot be found / resolved. Stored verbatim in the parent's `extra` slot under the snake_case key `class_name`, and surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and from [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md). The parameter type is bare `string` — not `class-string` — because the whole reason this exception exists is that the string did NOT identify a loadable class.

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

**The PHP parameter name and the wire-format key are different.** The constructor accepts `$className` (camelCase), but the value lands in `getExtra()` under `class_name` (snake_case). Callers reaching into `getExtra()` from a `catch` block must use the snake_case key — `getExtra()['className']` will silently return `null`.

**No `getClassName()` helper exists.** To recover the offending class name, read `$e->getExtra()['class_name']`.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\InvalidClassException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $className as its only parameter
 ✔ ::__construct() declares $className as string
 ✔ ::__construct() accepts a class name string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the class name is stored in extra
 ✔ ->getExtra() carries the class name under the "class_name" key
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/exceptionskit/src/Exceptions/InvalidClassException.php:60`](../../../../kits/exceptionskit/src/Exceptions/InvalidClassException.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — inherited accessor that surfaces the `class_name` payload back to the caller
- [`InvalidArgumentException::__construct()`](../InvalidArgumentException/__construct.md) — sibling constructor that uses `detail` + `extra` instead of pinning the payload to a fixed `extra` shape

## Issues

- [Open issues mentioning `InvalidClassException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22InvalidClassException::__construct()%22)
- [Closed issues mentioning `InvalidClassException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22InvalidClassException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=InvalidClassException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
