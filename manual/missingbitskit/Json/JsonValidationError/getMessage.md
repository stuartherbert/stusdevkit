# JsonValidationError->getMessage()

> `public getMessage(): string`

A description of the JSON validation error that was found.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Json\JsonValidationError`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Json;

class JsonValidationError
{
    public function getMessage(): string
}
```

## Description

Returns the human-readable description of this validation failure.
The value is the string reported by
[`json_last_error_msg()`](https://www.php.net/manual/en/function.json-last-error-msg.php)
at the moment [`Json::validate()`](../Json/validate.md) detected the
failure.

## Parameters

_None._

## Return Values

Returns the `string` passed to the constructor's `$message`
parameter, verbatim.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\JsonValidationError
 ✔ ->getMessage() declares a string return type
 ✔ ->getMessage() returns the $message passed to the constructor
```

## Source

[`kits/missingbitskit/src/Json/JsonValidationError.php:81`](../../../../kits/missingbitskit/src/Json/JsonValidationError.php#L81)

## Changelog

_No tagged releases yet._

## See Also

- [`JsonValidationError::getCode()`](getCode.md) — the numeric
  counterpart to the error message
- [`Json::validate()`](../Json/validate.md) — the source of these
  error objects

## Issues

- [Open issues mentioning `JsonValidationError->getMessage()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22JsonValidationError%3A%3AgetMessage%28%29%22)
- [Closed issues mentioning `JsonValidationError->getMessage()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22JsonValidationError%3A%3AgetMessage%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=JsonValidationError%3A%3AgetMessage%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
