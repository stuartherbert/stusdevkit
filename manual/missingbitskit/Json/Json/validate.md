# Json::validate()

> `public static validate(string $input, int $depth = self::DEFAULT_DEPTH, int $flags = self::DEFAULT_VALIDATE_FLAGS): ?JsonValidationError`

Validate the given `$input` to see if it really is a JSON-format
string.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Json\Json`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Json;

class Json
{
    public const DEFAULT_VALIDATE_FLAGS = 0;
    public const DEFAULT_DEPTH = 512;

    /**
     * @param int<1,max> $depth
     * @param 0|JSON_INVALID_UTF8_IGNORE $flags
     */
    public static function validate(
        string $input,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_VALIDATE_FLAGS,
    ): ?JsonValidationError
}
```

## Description

`validate()` reports whether `$input` is a syntactically valid
JSON document without allocating the decoded value. It wraps PHP's
[`json_validate()`](https://www.php.net/manual/en/function.json-validate.php)
builtin.

Unlike [`Json::encode()`](encode.md) and [`Json::decode()`](decode.md),
this method never throws on a malformed document — it reports the
verdict in the return value instead, so callers can branch on
validity without setting up a `try`/`catch`. `null` means the
input is valid; a
[`JsonValidationError`](../JsonValidationError/README.md) carries
the failure reason.

The recommended idiom is to compare the return value explicitly:

```php
if (Json::validate($input) === null) {
    // $input is valid JSON
}
```

It is one of three siblings on [`Json`](README.md):

- [`Json::encode()`](encode.md) — write a PHP value out as JSON
- [`Json::decode()`](decode.md) — read JSON back into a PHP value

## Parameters

**`$input`** (`string`)

The string to inspect.

**`$depth`** (`int<1,max>`, optional, default: `self::DEFAULT_DEPTH`)

Maximum nesting depth. A document that nests deeper than this is
reported as invalid (no exception is thrown — see
[Return Values](#return-values)).

**`$flags`** (`0|JSON_INVALID_UTF8_IGNORE`, optional, default: `self::DEFAULT_VALIDATE_FLAGS`)

Either `0` (the default) or
[`JSON_INVALID_UTF8_IGNORE`](https://www.php.net/manual/en/json.constants.php).
No other [`JSON_*`](https://www.php.net/manual/en/json.constants.php)
constant is accepted by the underlying
[`json_validate()`](https://www.php.net/manual/en/function.json-validate.php)
builtin — see the [Here Be Dragons](#here-be-dragons) note below.

## Return Values

Returns `null` when `$input` is valid JSON. Returns a
[`JsonValidationError`](../JsonValidationError/README.md)
describing the failure otherwise. Validation failures never surface
as thrown exceptions — they always travel back through the return
value.

## Errors/Exceptions

_None._ Validation failures are reported through the return value,
not through thrown exceptions.

## Here Be Dragons

**`$flags` is NOT a general
[`JSON_*`](https://www.php.net/manual/en/json.constants.php)
bitmask.**

Unlike [`Json::encode()`](encode.md) and [`Json::decode()`](decode.md),
[`json_validate()`](https://www.php.net/manual/en/function.json-validate.php)
only understands `0` or
[`JSON_INVALID_UTF8_IGNORE`](https://www.php.net/manual/en/json.constants.php).
Passing any other
[`JSON_*`](https://www.php.net/manual/en/json.constants.php)
constant is a silent no-op at best and a type error at
static-analysis time — the narrow type on the parameter is what
the PHP builtin actually accepts.

## Examples

_None yet._

## Contract (from tests)

```
Json (StusDevKit\MissingBitsKit\Tests\Unit\Json\Json)
 ✔ ::validate() declares $input, $depth and $flags as parameters in that order
 ✔ ::validate() returns null for valid JSON
 ✔ ::validate() accepts valid JSON primitive [1, 2, 3]
 ✔ ::validate() accepts valid JSON primitive "hello"
 ✔ ::validate() accepts valid JSON primitive 42
 ✔ ::validate() accepts valid JSON primitive -17
 ✔ ::validate() accepts valid JSON primitive 3.14
 ✔ ::validate() accepts valid JSON primitive true
 ✔ ::validate() accepts valid JSON primitive false
 ✔ ::validate() accepts valid JSON primitive null
 ✔ ::validate() accepts valid JSON primitive []
 ✔ ::validate() accepts valid JSON primitive {}
 ✔ ::validate() accepts valid JSON with surrounding whitespace
 ✔ ::validate() accepts JSON within the custom depth limit
 ✔ ::validate() returns a JsonValidationError for invalid JSON
 ✔ ::validate() rejects invalid JSON: {"unclosed brace
 ✔ ::validate() rejects invalid JSON: [1, 2, 3,]
 ✔ ::validate() rejects invalid JSON: {key: "value"}
 ✔ ::validate() rejects invalid JSON: {"key": value}
 ✔ ::validate() rejects invalid JSON: tru
 ✔ ::validate() rejects invalid JSON: nul
 ✔ ::validate() rejects invalid JSON: {"a": 1} {"b": 2}
 ✔ ::validate() rejects invalid JSON: <xml>not json</xml>
 ✔ ::validate() rejects invalid JSON: {"escaped": "bad\n'}
 ✔ ::validate() rejects invalid JSON: ''
 ✔ ::validate() rejects invalid JSON:    
	  
 ✔ ::validate() rejects JSON exceeding the custom depth limit
```

## Source

[`kits/missingbitskit/src/Json/Json.php:245`](../../../../kits/missingbitskit/src/Json/Json.php#L245)

## Changelog

_No tagged releases yet._

## See Also

- [`JsonValidationError`](../JsonValidationError/README.md) — the
  value object returned on validation failure
- [`Json::encode()`](encode.md) — write a PHP value out as JSON
- [`Json::decode()`](decode.md) — decode JSON to a PHP value
  (throws on invalid input instead of returning an error)

## Issues

- [Open issues mentioning `Json::validate()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Json%3A%3Avalidate%28%29%22)
- [Closed issues mentioning `Json::validate()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Json%3A%3Avalidate%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Json%3A%3Avalidate%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
