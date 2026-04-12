# MissingBitsKit

## Scope

MissingBitsKit provides standalone functions and lightweight classes that fill gaps in PHP's standard library. These are utilities that are generally useful but not provided by PHP itself.

## Namespace

`StusDevKit\MissingBitsKit`

Standalone functions are autoloaded via Composer's `files` directive. Classes follow PSR-4 autoloading.

## Contents

### Classes

#### Json

Provides safe wrappers around PHP's `json_encode()`, `json_decode()`, and `json_validate()`. All encode/decode calls force `JSON_THROW_ON_ERROR` so that failures always throw `\JsonException` instead of returning `false`.

```php
use StusDevKit\MissingBitsKit\Json;

$json = new Json();

// encode a PHP value to a JSON string
$encoded = $json->encode(value: ['name' => 'Alice', 'age' => 30]);

// decode a JSON string back to a PHP value
$decoded = $json->decode(input: $encoded, associative: true);

// validate a JSON string without decoding it
// returns [] on success, or [errorCode, errorMessage] on failure
$errors = $json->validate($encoded);
```

### Functions

| Function | Purpose |
|----------|---------|
| `get_class_basename()` | Return the short class name without the namespace |
| `object_merge()` | Copy all properties from source object(s) onto a target object |
| `uri_resolve_reference()` | Resolve a URI reference against a base URI per RFC 3986 Section 5 |
