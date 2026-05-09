# Rfc9457ProblemDetailsException::__construct()

> `public __construct(string $type, int $status, string $title, array $extra = [], ?string $detail = null, ?string $instance = null, ?Throwable $previous = null)`

Initialise the problem report fields and forward detail/title to `Exception`.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

use Throwable;

class Rfc9457ProblemDetailsException
{
    /**
     * @param non-empty-string $type
     * - URI to a page that documents this class of problem
     * - for example, link to a Github wiki page explaining the error
     * @param int $status
     * - HTTP status code that describes the nature of this problem
     * - recommend sticking to 4xx and 5xx codes
     * - does not stop you using 3xx or other HTTP status codes
     * @param non-empty-string $title
     * - short, human-readable summary of the problem type
     * - used as the exception message if `$detail` is empty
     * @param non-empty-string|null $detail
     * - human-readable explanation specific to this occurrence of the problem
     * - used as the exception message unless empty
     * @param ProblemReportExtra $extra
     * - array of additional information specific to this occurrence of the
     *   problem
     * - think of these as additional fields to add to your application
     *   logs
     * @param non-empty-string|null $instance
     * - absolute URI to a resource specific to this occurrence of the problem
     * - for example, if the user needs to perform a manual step to solve
     *   this problem, $instance could be a link to the web page for that
     *   action
     * @param ?Throwable $previous
     * - was this exception caused by another exception?
     */
    public function __construct(
        protected string $type,
        protected int $status,
        protected string $title,
        protected array $extra = [],
        protected ?string $detail = null,
        protected ?string $instance = null,
        protected ?Throwable $previous = null,
    ) {}
}
```

## Description

Constructs a new `Rfc9457ProblemDetailsException` with all fields needed
for an RFC 9457 problem report.

The `$type` parameter is a URI pointing to documentation for this class
of problem (e.g. a GitHub wiki page). The `$status` parameter is an HTTP
status code describing the nature of the problem (4xx or 5xx recommended).
The `$title` is a short, human-readable summary.

Optional parameters include `$detail` (a more detailed explanation),
`$instance` (an absolute URI to a resource specific to this occurrence),
and `$extra` (arbitrary additional data for application logs).

The `$previous` exception is forwarded to `Exception::__construct()`
to support exception chaining.

## Parameters

**`$type`** (`non-empty-string`)

URI to a page that documents this class of problem. For example,
a link to a GitHub wiki page explaining the error.

**`$status`** (`int`)

HTTP status code that describes the nature of this problem.
Sticking to 4xx and 5xx codes is recommended, though other HTTP
status codes are allowed.

**`$title`** (`non-empty-string`)

Short, human-readable summary of the problem type. Used as the
exception message if `$detail` is empty.

**`$extra`** (`array`, optional, default: `[]`)

Array of additional information specific to this occurrence of the
problem. Think of these as additional fields to add to your
application logs.

**`$detail`** (`string`, optional, default: `null`)

Human-readable explanation specific to this occurrence of the problem.
Used as the exception message unless empty.

**`$instance`** (`string`, optional, default: `null`)

Absolute URI to a resource specific to this occurrence of the problem.
For example, if the user needs to perform a manual step to solve
this problem, `$instance` could be a link to the web page for that action.

**`$previous`** (`Throwable`, optional, default: `null`)

Was this exception caused by another exception? Forwarded to
`Exception::__construct()` for chaining.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:83`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L83)

## Changelog

_No tagged releases yet._

## See Also

- [`hasDetail()`](./hasDetail.md) — check if detail was provided
- [`maybeGetDetail()`](./maybeGetDetail.md) — retrieve the detail if set
- [`hasExtra()`](./hasExtra.md) — check if extra data was provided
- [`getExtra()`](./getExtra.md) — retrieve the extra data array
- [`hasInstance()`](./hasInstance.md) — check if instance URI was provided
- [`maybeGetInstanceAsString()`](./maybeGetInstanceAsString.md) — retrieve the instance URI

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::__construct()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
