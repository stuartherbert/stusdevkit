# Json::validate()

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
     * @return array{int, string}|array{}
     *   empty array on success; on failure, a two-element list of
     *   `[json_last_error(), json_last_error_msg()]`.
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
validity without setting up a `try`/`catch`.

It is one of three siblings on [`Json`](README.md):

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

Returns `array{int, string}|array{}`.

- On success: an empty array (`[]`).
- On failure: a two-element list of
  `[`[`json_last_error()`](https://www.php.net/manual/en/function.json-last-error.php)`, `[`json_last_error_msg()`](https://www.php.net/manual/en/function.json-last-error-msg.php)`]`.

## Errors/Exceptions

_None._

## Here Be Dragons

**The return value's truthiness is the opposite of what you would
guess.**

Empty array on success, `[code, message]` on failure. In PHP, an
empty array is falsy and a non-empty array is truthy, so:

- `if ($json->validate($x)) { /* valid */ }` — the block runs on
  **FAILURE** (non-empty array is truthy)
- `if (! $json->validate($x)) { /* valid */ }` — the block runs on
  **SUCCESS** (empty array is falsy)

Read the return type as "the list of errors, which is empty when
there are none". Never eyeball the truthiness — compare explicitly:

```php
if ($json->validate($input) === []) {
    // valid
}
```

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ returns an empty array for valid JSON
 ✔ accepts valid JSON primitive [1, 2, 3]
 ✔ accepts valid JSON primitive "hello"
 ✔ accepts valid JSON primitive 42
 ✔ accepts valid JSON primitive -17
 ✔ accepts valid JSON primitive 3.14
 ✔ accepts valid JSON primitive true
 ✔ accepts valid JSON primitive false
 ✔ accepts valid JSON primitive null
 ✔ accepts valid JSON primitive []
 ✔ accepts valid JSON primitive {}
 ✔ accepts valid JSON with surrounding whitespace
 ✔ accepts JSON within the custom depth limit
 ✔ returns [errorCode, errorMessage] for invalid JSON
 ✔ rejects invalid JSON: {"unclosed brace
 ✔ rejects invalid JSON: [1, 2, 3,]
 ✔ rejects invalid JSON: {key: "value"}
 ✔ rejects invalid JSON: {"key": value}
 ✔ rejects invalid JSON: tru
 ✔ rejects invalid JSON: nul
 ✔ rejects invalid JSON: {"a": 1} {"b": 2}
 ✔ rejects invalid JSON: <xml>not json</xml>
 ✔ rejects invalid JSON: {"escaped": "bad\n'}
 ✔ rejects invalid JSON: ''
 ✔ rejects invalid JSON:    
 ✔ rejects JSON exceeding the custom depth limit
```

## Source

[`kits/missingbitskit/src/Json/Json.php:242`](../../../../kits/missingbitskit/src/Json/Json.php#L242)

## Changelog

_No tagged releases yet._

## See Also

- [`Json::decode()`](decode.md) — when you need the decoded value
  and not just a verdict
- [`Json::encode()`](encode.md) — the matching encoder

## Issues

- [Open issues mentioning `Json::validate()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Json%3A%3Avalidate%28%29%22)
- [Closed issues mentioning `Json::validate()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Json%3A%3Avalidate%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Json%3A%3Avalidate%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
