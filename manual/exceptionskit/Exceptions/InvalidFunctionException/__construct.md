# InvalidFunctionException::__construct()

> `public function __construct(string $functionName)`

Build an `InvalidFunctionException` carrying the caller-supplied function name in the `extra` slot under the `function_name` key.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class InvalidFunctionException
{
    /**
     * @param string $functionName
     *     the function that cannot be found / resolved
     */
    public function __construct(
        string $functionName,
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus the caller-supplied function name stashed in the parent's [`extra`](../Rfc9457ProblemDetailsException/getExtra.md) slot. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/invalid-function` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Content)
- **title**: `"Function name does not refer to a defined function"`
- **extra**: `['function_name' => $functionName]`

The narrower API surface is the point: callers don't have to remember the type URI, the status code, the title, or the wire-format key every time they want to raise "the string isn't a defined function name". Hand in the offending function name string, and the exception fills in the rest.

```php
throw new InvalidFunctionException(
    functionName: 'my_function',
);
```

Unlike its sibling [`InvalidArgumentException`](../InvalidArgumentException/README.md), this constructor takes no `$detail` and no `$extra` — the function name is the entire payload, and it is carried in `extra` (not `detail`) so the value is machine-readable rather than embedded in prose.

## Parameters

**`$functionName`** (`string`)

The function that cannot be found / resolved. Stored verbatim in the parent's `extra` slot under the snake_case key `function_name`, and surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and from [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md). The parameter type is bare `string` — not `callable-string` — because the whole reason this exception exists is that the string did NOT name a defined function.

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

**The PHP parameter name and the wire-format key are different.** The constructor accepts `$functionName` (camelCase), but the value lands in `getExtra()` under `function_name` (snake_case). Callers reaching into `getExtra()` from a `catch` block must use the snake_case key — `getExtra()['functionName']` will silently return `null`.

**No `getFunctionName()` helper exists.** To recover the offending function name, read `$e->getExtra()['function_name']`.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $functionName as its only parameter
 ✔ ::__construct() declares $functionName as string
 ✔ ::__construct() accepts a function name string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the function name is stored in extra
 ✔ ->getExtra() carries the function name under the "function_name" key
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/exceptionskit/src/Exceptions/InvalidFunctionException.php:61`](../../../../kits/exceptionskit/src/Exceptions/InvalidFunctionException.php#L61)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — inherited accessor that surfaces the `function_name` payload back to the caller
- [`InvalidClassException::__construct()`](../InvalidClassException/__construct.md) — sibling constructor with the same `extra`-payload shape, for invalid class names
- [`InvalidArgumentException::__construct()`](../InvalidArgumentException/__construct.md) — sibling constructor that uses `detail` + `extra` instead of pinning the payload to a fixed `extra` shape

## Issues

- [Open issues mentioning `InvalidFunctionException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22InvalidFunctionException::__construct()%22)
- [Closed issues mentioning `InvalidFunctionException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22InvalidFunctionException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=InvalidFunctionException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
