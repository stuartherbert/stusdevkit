# UnseenObjectException::__construct()

> `public __construct(object $obj)`

Build an exception describing an unseen-object misuse of [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md).

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\DataInspectors\UnseenObjectException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\DataInspectors;

class UnseenObjectException
{
    /**
     * @param object $obj
     *      the object that `visitIndexOf()` was called with, but
     *      which had never been recorded via
     *      NormalisationContext::markSeen()
     */
    public function __construct(
        object $obj,
    ) {}
}
```

## Description

Constructs a new `UnseenObjectException`. The constructor pre-fills the
parent [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md)'s
fields with fixed values:

- `type` → `"http://github.com/stuartherbert/stusdevkit/"` (a fixed
  documentation URI)
- `status` → `500` (RFC-correct status for a programmer error — a
  server-side fault, not a bad caller input)
- `title` → `"visitIndexOf() called on an object that NormalisationContext has not seen"`
  (human-readable summary)
- `extra` → `['class_name' => $obj::class]` (the offending object's
  class name)

No `detail` is supplied, so [`maybeGetDetail()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/maybeGetDetail.md)
returns `null` and [`getMessage()`](https://www.php.net/manual/en/exception.getMessage)
falls back to the fixed title.

## Parameters

**`$obj`** (`object`)

The object that [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md)
was called with, but which had never been recorded via
[`NormalisationContext::markSeen()`](../NormalisationContext/markSeen.md).
Its class name is stored in `extra['class_name']`.

## Source

[`kits/missingbitskit/src/DataInspectors/UnseenObjectException.php:68`](../src/DataInspectors/UnseenObjectException.php#L68)

## Changelog

_No tagged releases yet._

## See Also

- [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md) — the method that constructs and throws this exception
- [`getExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getExtra.md) — retrieve the offending object's class name
- [`getStatus()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getStatus.md) — retrieve the HTTP 500 status

## Issues

- [Open issues mentioning `UnseenObjectException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UnseenObjectException::__construct()%22)
- [Closed issues mentioning `UnseenObjectException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UnseenObjectException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UnseenObjectException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
