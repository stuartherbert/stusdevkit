# Json

Error-safe wrappers around PHP's
[`json_encode()`](https://www.php.net/manual/en/function.json-encode.php),
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php)
and [`json_validate()`](https://www.php.net/manual/en/function.json-validate.php)
builtins.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Json;

class Json
{
    public const DEFAULT_DECODE_FLAGS = 0;
    public const DEFAULT_ENCODE_FLAGS = 0;
    public const DEFAULT_VALIDATE_FLAGS = 0;
    public const DEFAULT_DEPTH = 512;

    /**
     * convert the given `$input` to a JSON-format string.
     */
    public function encode(
        mixed $input,
        int $flags = self::DEFAULT_ENCODE_FLAGS,
        int $depth = self::DEFAULT_DEPTH,
    ): string;

    /**
     * convert the given `$input` JSON string into a PHP value.
     */
    public function decode(
        string $input,
        ?bool $associative = null,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_DECODE_FLAGS,
    ): mixed;

    /**
     * validate the given `$input` to see if it really is a
     * JSON-format string.
     */
    public function validate(
        string $input,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_VALIDATE_FLAGS,
    ): array;
}
```

## Description

`Json` provides safer versions of three PHP builtins:
[`json_encode()`](https://www.php.net/manual/en/function.json-encode.php),
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php)
and [`json_validate()`](https://www.php.net/manual/en/function.json-validate.php).

Originally added to ensure that any
[`json_encode()`](https://www.php.net/manual/en/function.json-encode.php)
or [`json_decode()`](https://www.php.net/manual/en/function.json-decode.php)
failure always throws a
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php)
instead of silently returning `false` or `null`. Both
[`Json::encode()`](encode.md) and [`Json::decode()`](decode.md) always
OR [`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
into the caller's flags, so error handling is consistent regardless
of what flags the caller passes.

The four `DEFAULT_*` class constants name the library's defaults so
callers can pass them through (or override them) without sprinkling
magic numbers through their own code.

## Methods

- [`Json::encode()`](encode.md) — convert the given `$input` to a
  JSON-format string
- [`Json::decode()`](decode.md) — convert the given `$input` JSON
  string into a PHP value
- [`Json::validate()`](validate.md) — validate the given `$input` to
  see if it really is a JSON-format string

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ lives in the StusDevKit\MissingBitsKit\Json namespace
 ✔ is a concrete class
 ✔ exposes only encode(), decode() and validate() as public methods
 ✔ exposes only the expected public class constants
 ✔ DEFAULT_DECODE_FLAGS has the value 0
 ✔ DEFAULT_ENCODE_FLAGS has the value 0
 ✔ DEFAULT_VALIDATE_FLAGS has the value 0
 ✔ DEFAULT_DEPTH has the value 512
```

## Source

[`kits/missingbitskit/src/Json/Json.php:58`](../../../../kits/missingbitskit/src/Json/Json.php#L58)

## Changelog

_No tagged releases yet._

## See Also

_None._

## Issues

- [Open issues mentioning `Json`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Json%22)
- [Closed issues mentioning `Json`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Json%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Json%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
