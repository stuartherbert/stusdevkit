# Json->validate()

> `public validate(string $input, int $depth = self::DEFAULT_DEPTH, int $flags = self::DEFAULT_VALIDATE_FLAGS): array`

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
    /**
     * @param int<1,max> $depth
     *
     * @return list<JsonValidationError>
     *   empty list on success; on failure, the list of errors
     *   found in `$input`.
     */
    public function validate(
        string $input,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_VALIDATE_FLAGS,
    ): array
}
```

## Description

`validate()` reports whether `$input` is a well-formed JSON document.
Unlike [`Json::encode()`](encode.md) and [`Json::decode()`](decode.md),
this method never throws on a malformed document: it reports the
verdict in the return value instead, so callers can branch on
validity without setting up a `try`/`catch`. An empty list means the
input is valid; a non-empty list carries the errors that were found,
each one a
[`JsonValidationError`](../JsonValidationError/README.md) with a
`getCode()` and a `getMessage()`.

The recommended idiom is to compare the return value explicitly:

```php
if ($json->validate($input) === []) {
    // $input is valid JSON
}
```

`validate()` is one of three siblings on [`Json`](README.md):

- [`Json::encode()`](encode.md) — produce JSON from a PHP value
- [`Json::decode()`](decode.md) — read JSON back into a PHP value

## Parameters

**`$input`** (`string`)

The string to inspect.

**`$depth`** (`int<1,max>`)

Maximum nesting depth. A document that nests deeper than this is
reported as invalid (not thrown).

**`$flags`** (`int`)

Bitmask of [`JSON_*`](https://www.php.net/manual/en/json.constants.php)
validate flags.

## Return Values

Returns `list<`[`JsonValidationError`](../JsonValidationError/README.md)`>`.

- On success: an empty list (`[]`).
- On failure: a list with one entry per error. Each entry is a
  [`JsonValidationError`](../JsonValidationError/README.md) carrying
  the corresponding
  [`json_last_error()`](https://www.php.net/manual/en/function.json-last-error.php)
  code and
  [`json_last_error_msg()`](https://www.php.net/manual/en/function.json-last-error-msg.php)
  message.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ ->validate() declares $input, $depth and $flags as parameters in that order
 ✔ ->validate() returns an empty array for valid JSON
 ✔ ->validate() accepts valid JSON primitive [1, 2, 3]
 ✔ ->validate() accepts valid JSON primitive "hello"
 ✔ ->validate() accepts valid JSON primitive 42
 ✔ ->validate() accepts valid JSON primitive -17
 ✔ ->validate() accepts valid JSON primitive 3.14
 ✔ ->validate() accepts valid JSON primitive true
 ✔ ->validate() accepts valid JSON primitive false
 ✔ ->validate() accepts valid JSON primitive null
 ✔ ->validate() accepts valid JSON primitive []
 ✔ ->validate() accepts valid JSON primitive {}
 ✔ ->validate() accepts valid JSON with surrounding whitespace
 ✔ ->validate() accepts JSON within the custom depth limit
 ✔ ->validate() returns a JsonValidationError for invalid JSON
 ✔ ->validate() rejects invalid JSON: {"unclosed brace
 ✔ ->validate() rejects invalid JSON: [1, 2, 3,]
 ✔ ->validate() rejects invalid JSON: {key: "value"}
 ✔ ->validate() rejects invalid JSON: {"key": value}
 ✔ ->validate() rejects invalid JSON: tru
 ✔ ->validate() rejects invalid JSON: nul
 ✔ ->validate() rejects invalid JSON: {"a": 1} {"b": 2}
 ✔ ->validate() rejects invalid JSON: <xml>not json</xml>
 ✔ ->validate() rejects invalid JSON: {"escaped": "bad\n'}
 ✔ ->validate() rejects invalid JSON: ''
 ✔ ->validate() rejects invalid JSON:    
 ✔ ->validate() rejects JSON exceeding the custom depth limit
```

## Source

[`kits/missingbitskit/src/Json/Json.php:228`](../../../../kits/missingbitskit/src/Json/Json.php#L228)

## Changelog

_No tagged releases yet._

## See Also

- [`JsonValidationError`](../JsonValidationError/README.md) — the
  value object returned for each error in a malformed document
- [`Json::decode()`](decode.md) — when you need the decoded value
  and not just a verdict
- [`Json::encode()`](encode.md) — the matching encoder

## Issues

- [Open issues mentioning `Json->validate()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Json%3A%3Avalidate%28%29%22)
- [Closed issues mentioning `Json->validate()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Json%3A%3Avalidate%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Json%3A%3Avalidate%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
