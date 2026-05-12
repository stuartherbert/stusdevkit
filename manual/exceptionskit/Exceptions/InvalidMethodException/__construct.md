# InvalidMethodException::__construct()

> `public function __construct(string $className, string $methodName)`

Build an `InvalidMethodException` carrying the caller-supplied class name and method name in the `extra` slot under the `class_name` and `method_name` keys.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\InvalidMethodException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class InvalidMethodException
{
    /**
     * @param string $className
     *     the class where we expect the given `$methodName` to
     *     exist
     * @param string $methodName
     *     the method that does not exist on the given `$className`
     *     (also does not exist on any parent classes or on any
     *     traits used by the given `$className` or on any traits
     *     used by any parent classes)
     */
    public function __construct(
        string $className,
        string $methodName,
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus the caller-supplied class+method pair stashed in the parent's [`extra`](../Rfc9457ProblemDetailsException/getExtra.md) slot. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/invalid-method` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Content)
- **title**: `"Method does not exist on the given class"`
- **extra**: `['class_name' => $className, 'method_name' => $methodName]`

The narrower API surface is the point: callers don't have to remember the type URI, the status code, the title, or the wire-format keys every time they want to raise "the named method does not exist on this class". Hand in the class name and the method name, and the exception fills in the rest.

```php
throw new InvalidMethodException(
    className: MyClass::class,
    methodName: 'missingMethod',
);
```

Unlike its sibling [`InvalidArgumentException`](../InvalidArgumentException/README.md), this constructor takes no `$detail` and no `$extra` — the class+method pair is the entire payload, and it is carried in `extra` (not `detail`) so the values are machine-readable rather than embedded in prose.

## Parameters

**`$className`** (`string`)

The class where the given `$methodName` is expected to exist. Stored verbatim in the parent's `extra` slot under the snake_case key `class_name`, and surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and from [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md). The parameter type is bare `string` — not `class-string` — because the source contract pins the native type to plain `string`, and the test suite locks that choice down explicitly so callers cannot be required to satisfy a tighter PHPStan type.

**`$methodName`** (`string`)

The method that does not exist on the given `$className` (also does not exist on any parent classes, or on any traits used by the given `$className`, or on any traits used by any parent classes). Stored verbatim in the parent's `extra` slot under the snake_case key `method_name`, and surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and from [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md).

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

**The PHP parameter names and the wire-format keys are different.** The constructor accepts `$className` and `$methodName` (camelCase), but the values land in `getExtra()` under `class_name` and `method_name` (snake_case). Callers reaching into `getExtra()` from a `catch` block must use the snake_case keys — `getExtra()['className']` or `getExtra()['methodName']` will silently return `null`.

**No `getClassName()` / `getMethodName()` helpers exist.** To recover the offending pair, read `$e->getExtra()['class_name']` and `$e->getExtra()['method_name']`.

**Wire-format key order is pinned.** `class_name` comes before `method_name` in the serialised `extra` array (the contract tests assert this exact order). Reordering them would be a wire-format change.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\InvalidMethodException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares its parameters in the expected order
 ✔ ::__construct() declares $className as string
 ✔ ::__construct() declares $methodName as string
 ✔ ::__construct() accepts a class name and method name
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the class and method names are stored in extra
 ✔ ->getExtra() carries both the class name and method name
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/exceptionskit/src/Exceptions/InvalidMethodException.php:68`](../../../../kits/exceptionskit/src/Exceptions/InvalidMethodException.php#L68)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — inherited accessor that surfaces the `class_name` and `method_name` payload back to the caller
- [`InvalidClassException::__construct()`](../InvalidClassException/__construct.md) — sibling constructor with the same `extra`-payload shape, for unloadable class names
- [`InvalidFunctionException::__construct()`](../InvalidFunctionException/__construct.md) — sibling constructor with the same `extra`-payload shape, for undefined global function names
- [`InvalidArgumentException::__construct()`](../InvalidArgumentException/__construct.md) — sibling constructor that uses `detail` + `extra` instead of pinning the payload to a fixed `extra` shape

## Issues

- [Open issues mentioning `InvalidMethodException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22InvalidMethodException::__construct()%22)
- [Closed issues mentioning `InvalidMethodException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22InvalidMethodException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=InvalidMethodException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
