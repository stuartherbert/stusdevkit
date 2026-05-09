# Rfc9457ProblemDetailsException::maybeGetInstanceAsString()

> `public maybeGetInstanceAsString(): ?string`

Returns the instance URI as a string if one was provided, or `null` otherwise.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class Rfc9457ProblemDetailsException
{
    /**
     * Return the `instance` URI as a string (if we have one).
     *
     * - absolute URI to a resource specific to this occurrence of the problem
     * - for example, if the user needs to perform a manual step to solve
     *   this problem, $instance could be a link to the web page for that
     *   action
     *
     * @return non-empty-string|null
     */
    public function maybeGetInstanceAsString(): ?string {}
}
```

## Description

Returns the absolute URI to a resource specific to this occurrence of
the problem, if one was provided at construction time. Returns `null`
if no instance URI was set.

For example, if the user needs to perform a manual step to solve this
problem, `$instance` could be a link to the web page for that action.

This method is the counterpart to [`hasInstance()`](./hasInstance.md):
when `hasInstance()` returns `true`, this method is guaranteed to return
a non-empty string.

**FORWARDS-COMPATIBILITY NOTE:** A future release will return a `Uri`
instance instead of a string, once PHP 8.5's `Uri` extension is
documented and IDE-supported.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:168`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L168)

## Changelog

_No tagged releases yet._

## See Also

- [`hasInstance()`](./hasInstance.md) — check if instance URI was provided
- [`maybeGetDetail()`](./maybeGetDetail.md) — retrieve detail if set

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::maybeGetInstanceAsString()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::maybeGetInstanceAsString()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::maybeGetInstanceAsString()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::maybeGetInstanceAsString()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
