# Json::encode()

> `public encode(mixed $input, int $flags = self::DEFAULT_ENCODE_FLAGS, int $depth = self::DEFAULT_DEPTH): string`

Convert the given `$input` to a JSON-format string.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Json\Json`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Json;

use JsonException;

class Json
{
    /**
     * @param int<1,max> $depth
     *
     * @throws JsonException
     */
    public function encode(
        mixed $input,
        int $flags = self::DEFAULT_ENCODE_FLAGS,
        int $depth = self::DEFAULT_DEPTH,
    ): string
}
```

## Description

`encode()` converts any PHP value that
[`json_encode()`](https://www.php.net/manual/en/function.json-encode.php)
can represent into a JSON-format string. If the value cannot be
represented (a resource, a circular reference, a nesting depth that
exceeds `$depth`), the method throws a
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php)
instead of returning `false`.

It is one of three siblings on [`Json`](README.md):

- [`Json::decode()`](decode.md) — read JSON back into a PHP value
- [`Json::validate()`](validate.md) — check a string is valid JSON
  without decoding it

## Parameters

**`$input`** (`mixed`)

The PHP value to encode.

**`$flags`** (`int`)

Bitmask of [`JSON_*`](https://www.php.net/manual/en/json.constants.php)
encode flags (for example
[`JSON_PRETTY_PRINT`](https://www.php.net/manual/en/json.constants.php),
[`JSON_UNESCAPED_SLASHES`](https://www.php.net/manual/en/json.constants.php)).
[`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
is always added on top, whether the caller passes it or not.

**`$depth`** (`int<1,max>`)

Maximum nesting depth. Exceeding it throws
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php).

## Return Values

Returns the JSON-format string representation of `$input`. The method
never returns `false` — any failure is raised as a
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php).

## Errors/Exceptions

- **[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php)**
  — when `$input` cannot be encoded (for example it contains a
  resource or a circular reference), or when nesting exceeds
  `$depth`.

## Here Be Dragons

**[`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
is always on — and you cannot turn it off.**

Every call ORs
[`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
into whatever `$flags` the caller passed, so `encode(..., flags: 0)`
is **not** a way back to the PHP-builtin "return `false` on failure"
behaviour. That is the whole point of the wrapper: failures always
throw, never silently sneak a `false` through into downstream
string-typed code. Callers who want the raw
[`json_encode()`](https://www.php.net/manual/en/function.json-encode.php)
semantics should call
[`json_encode()`](https://www.php.net/manual/en/function.json-encode.php)
directly and accept the type-hint fight with static analysis.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ encodes an associative array as a JSON object
 ✔ encodes an empty array as []
 ✔ encodes an empty stdClass as {}
 ✔ encodes null as the root value
 ✔ encodes boolean true as the root value
 ✔ encodes boolean false as the root value
 ✔ encodes an integer as the root value
 ✔ encodes a string as the root value
 ✔ encodes PHP_INT_MAX without precision loss
 ✔ escapes special characters in strings and round-trips cleanly
 ✔ preserves unicode characters through a round-trip
 ✔ encodes deeply nested arrays
 ✔ encodes nested stdClass objects
 ✔ respects JSON_PRETTY_PRINT
 ✔ respects JSON_UNESCAPED_SLASHES
 ✔ respects JSON_UNESCAPED_UNICODE
 ✔ accepts combined flags via bitwise OR
 ✔ accepts JSON_THROW_ON_ERROR from the caller as a no-op
 ✔ encode() accepts named parameters
 ✔ throws JsonException on a circular reference
 ✔ throws JsonException when depth is exceeded
 ✔ throws JsonException when given a resource
 ✔ encode() and decode() are inverse operations
```

## Source

[`kits/missingbitskit/src/Json/Json.php:118`](../../../../kits/missingbitskit/src/Json/Json.php#L118)

## Changelog

_No tagged releases yet._

## See Also

- [`Json::decode()`](decode.md) — the inverse operation
- [`Json::validate()`](validate.md) — pre-check a string before
  attempting to decode it

## Issues

- [Open issues mentioning `Json::encode()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Json%3A%3Aencode%28%29%22)
- [Closed issues mentioning `Json::encode()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Json%3A%3Aencode%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Json%3A%3Aencode%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
