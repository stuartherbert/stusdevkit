# JsonValidationError->getCode()

> `public getCode(): int`

Describes what kind of JSON validation error was found.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Json\JsonValidationError`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Json;

class JsonValidationError
{
    public function getCode(): int
}
```

## Description

Returns the numeric error code for this validation failure. The
value is one of the
[`JSON_ERROR_*`](https://www.php.net/manual/en/json.constants.php)
constants, as reported by
[`json_last_error()`](https://www.php.net/manual/en/function.json-last-error.php)
at the moment [`Json::validate()`](../Json/validate.md) detected the
failure.

## Parameters

_None._

## Return Values

Returns the `int` passed to the constructor's `$code` parameter,
verbatim.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\JsonValidationError
 ✔ ->getCode() declares an int return type
 ✔ ->getCode() returns the $code passed to the constructor
```

## Source

[`kits/missingbitskit/src/Json/JsonValidationError.php:73`](../../../../kits/missingbitskit/src/Json/JsonValidationError.php#L73)

## Changelog

_No tagged releases yet._

## See Also

- [`JsonValidationError::getMessage()`](getMessage.md) — the
  human-readable counterpart to the error code
- [`Json::validate()`](../Json/validate.md) — the source of these
  error objects

## Issues

- [Open issues mentioning `JsonValidationError->getCode()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22JsonValidationError%3A%3AgetCode%28%29%22)
- [Closed issues mentioning `JsonValidationError->getCode()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22JsonValidationError%3A%3AgetCode%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=JsonValidationError%3A%3AgetCode%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
