# JsonValidationError

Describes a single JSON validation failure reported by
[`Json::validate()`](../Json/validate.md).

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Json;

class JsonValidationError
{
    /**
     * @param int $code
     *   pass in the result from `json_last_error()`
     * @param string $message
     *   pass in the result from `json_last_error_msg()`
     */
    public function __construct(
        private int $code,
        private string $message,
    );

    /**
     * Describes what kind of JSON validation error was found.
     */
    public function getCode(): int;

    /**
     * A description of the JSON validation error that was found.
     */
    public function getMessage(): string;
}
```

## Description

`JsonValidationError` is the value object
[`Json::validate()`](../Json/validate.md) returns for each error it
finds in a malformed document. It carries two fields:

- the numeric error code from
  [`json_last_error()`](https://www.php.net/manual/en/function.json-last-error.php)
  (one of the [`JSON_ERROR_*`](https://www.php.net/manual/en/json.constants.php)
  constants)
- the human-readable message from
  [`json_last_error_msg()`](https://www.php.net/manual/en/function.json-last-error-msg.php)

Originally added to replace the `[code, message]` two-tuple that
[`Json::validate()`](../Json/validate.md) used to return, so that
callers can address fields by name rather than by position, and so
the method's return type (`list<JsonValidationError>`) carries the
semantic role of the data on its face.

## Methods

- [`JsonValidationError::getCode()`](getCode.md) — describes what
  kind of JSON validation error was found
- [`JsonValidationError::getMessage()`](getMessage.md) — a
  description of the JSON validation error that was found

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\JsonValidationError
 ✔ lives in the StusDevKit\MissingBitsKit\Json namespace
 ✔ exposes only __construct(), getCode() and getMessage() as public methods
 ✔ ::__construct() declares $code and $message as parameters in that order
```

## Source

[`kits/missingbitskit/src/Json/JsonValidationError.php:52`](../../../../kits/missingbitskit/src/Json/JsonValidationError.php#L52)

## Changelog

_No tagged releases yet._

## See Also

- [`Json::validate()`](../Json/validate.md) — produces a
  `list<JsonValidationError>` describing every error found in a
  malformed document

## Issues

- [Open issues mentioning `JsonValidationError`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22JsonValidationError%22)
- [Closed issues mentioning `JsonValidationError`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22JsonValidationError%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=JsonValidationError%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
