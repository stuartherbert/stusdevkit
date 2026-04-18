# Json->decode()

> `public decode(string $input, ?bool $associative = null, int $depth = self::DEFAULT_DEPTH, int $flags = self::DEFAULT_DECODE_FLAGS): mixed`

Convert the given `$input` JSON string into a PHP value.

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
    public function decode(
        string $input,
        ?bool $associative = null,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_DECODE_FLAGS,
    ): mixed
}
```

## Description

`decode()` parses a JSON-format string back into a PHP value using
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php).
Anything that
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php)
would normally accept as input is accepted here; the difference is
that malformed JSON raises a
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php)
instead of silently returning `null`.

It is one of three siblings on [`Json`](README.md):

- [`Json->encode()`](encode.md) — the inverse operation
- [`Json::validate()`](validate.md) — check a string is valid JSON
  without decoding it

## Parameters

**`$input`** (`string`)

The JSON string to decode.

**`$associative`** (`?bool`)

`null` (the default) returns JSON objects as
[`\stdClass`](https://www.php.net/manual/en/class.stdclass.php)
instances, matching
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php).
`true` returns JSON objects as associative arrays. `false` is
equivalent to `null`.

**`$depth`** (`int<1,max>`)

Maximum nesting depth. Exceeding it throws
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php).

**`$flags`** (`int`)

Bitmask of [`JSON_*`](https://www.php.net/manual/en/json.constants.php)
decode flags (for example
[`JSON_BIGINT_AS_STRING`](https://www.php.net/manual/en/json.constants.php),
[`JSON_OBJECT_AS_ARRAY`](https://www.php.net/manual/en/json.constants.php)).
[`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
is always added on top, whether the caller passes it or not.

## Return Values

Returns the decoded PHP value. The return type is `mixed` because
any valid JSON document decodes to one of: a scalar, `null`, a PHP
array (when `$associative` is `true` or the root is a JSON array),
or a [`\stdClass`](https://www.php.net/manual/en/class.stdclass.php)
(when `$associative` is `null`/`false` and the root is a JSON
object). The method never returns `null` to signal failure — a
malformed document is raised as a
[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php).

## Errors/Exceptions

- **[`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php)**
  — when `$input` is not a valid JSON document, or when nesting
  exceeds `$depth`.

## Here Be Dragons

**[`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
is always on — and you cannot turn it off.**

Every call ORs
[`JSON_THROW_ON_ERROR`](https://www.php.net/manual/en/json.constants.php)
into whatever `$flags` the caller passed. Invalid JSON surfaces as a
thrown [`\JsonException`](https://www.php.net/manual/en/class.jsonexception.php),
not as a silent `null` return — a deliberate departure from the raw
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php)
contract that catches callers used to the builtin's behaviour.

**`$associative = null` means "JSON objects become
[`\stdClass`](https://www.php.net/manual/en/class.stdclass.php)".**

`null` here is not "no preference" — PHP treats it as "use the
default", and that default is
[`\stdClass`](https://www.php.net/manual/en/class.stdclass.php),
not an associative array. Pass `true` explicitly when you want
associative-array output. The wrapper does not flip this default,
because doing so would put it out of sync with
[`json_decode()`](https://www.php.net/manual/en/function.json-decode.php).

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ ->decode() declares $input, $associative, $depth and $flags as parameters in that order
 ✔ ->decode() decodes a JSON object as stdClass by default
 ✔ ->decode() decodes a JSON object as an associative array when associative is true
 ✔ ->decode() decodes a JSON array
 ✔ ->decode() decodes root-level JSON string primitive
 ✔ ->decode() decodes root-level JSON integer primitive
 ✔ ->decode() decodes root-level JSON negative integer primitive
 ✔ ->decode() decodes root-level JSON float primitive
 ✔ ->decode() decodes root-level JSON boolean true primitive
 ✔ ->decode() decodes root-level JSON boolean false primitive
 ✔ ->decode() decodes root-level JSON null primitive
 ✔ ->decode() respects JSON_BIGINT_AS_STRING
 ✔ ->decode() succeeds when nesting is within the custom depth limit
 ✔ ->decode() throws JsonException on syntactically invalid JSON
 ✔ ->decode() throws JsonException on an empty string
 ✔ ->decode() throws JsonException on a whitespace-only string
 ✔ ->decode() throws JsonException when depth is exceeded
 ✔ ->encode() and ->decode() are inverse operations
```

## Source

[`kits/missingbitskit/src/Json/Json.php:182`](../../../../kits/missingbitskit/src/Json/Json.php#L182)

## Changelog

_No tagged releases yet._

## See Also

- [`Json->encode()`](encode.md) — the inverse operation
- [`Json::validate()`](validate.md) — check a string is valid JSON
  without paying the cost of decoding it

## Issues

- [Open issues mentioning `Json->decode()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Json%3A%3Adecode%28%29%22)
- [Closed issues mentioning `Json->decode()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Json%3A%3Adecode%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Json%3A%3Adecode%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
