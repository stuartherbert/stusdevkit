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

### TypeInspectors

TypeInspectors is a family of classes that answer the question *"what types can this value satisfy?"*. They return PHP parameter-type-hint spellings (`int`, `float`, `bool`, `null`) rather than `gettype()`'s long form, so results can be fed straight into error messages, validators, or type-hint generators.

Every inspector follows the same shape:

- `public function __invoke(mixed $input): array<string,string>` — call-as-function style; returns `[]` if the input is not of the inspector's expected type.
- `public static function from(...)` — direct call, strictly type-hinted to the concrete input, returns a `type-name => type-name` lookup map.

The returned map is ordered from most-specific to most-general, and always ends with `mixed` (plus `object` for any object input). Keys are unique, so callers can test for a type with `isset()`.

#### The inspectors

| Class | Purpose |
|-------|---------|
| `GetDuckTypes` | Entry point — accepts any PHP value and dispatches to the right inspector below |
| `GetArrayTypes` | Types an array satisfies (adds `callable` when the array is a `[Class, method]` callable) |
| `GetBooleanTypes` | `true` / `false` / `bool` / `mixed` |
| `GetFloatTypes` | `numeric`, `float`, `mixed` |
| `GetIntegerTypes` | `numeric`, `int`, `mixed` |
| `GetNumericType` | Shared helper used by other inspectors for `is_numeric()` values |
| `GetStringTypes` | Adds `callable` / `numeric` where the string also satisfies those shapes |
| `GetObjectTypes` | Types an object satisfies — thin wrapper around `GetClassTypes` |
| `GetClassTypes` | Full type surface for a class-string — parents, interfaces, traits, `callable` (if `__invoke` is defined), `object`, `mixed` |
| `GetClassHierarchy` | The class itself and its parent classes |
| `GetClassInterfaces` | Interfaces implemented (direct and inherited, walking interface hierarchies) |
| `GetClassTraits` | All traits used by the class and its parents, including trait-of-trait usage |
| `GetNamespace` | The namespace part of a class-string or an object's class |
| `GetPrintableType` | A human-readable descriptor **string** (e.g. `int<42>`, `object<MyClass>`, `callable<Closure>`) — handy for error messages |

#### Example — duck-typing dispatch

```php
use StusDevKit\MissingBitsKit\TypeInspectors\GetDuckTypes;

GetDuckTypes::from(42);
// => ['numeric' => 'numeric', 'int' => 'int', 'mixed' => 'mixed']

GetDuckTypes::from('strlen');
// => ['callable' => 'callable', 'string' => 'string', 'mixed' => 'mixed']

GetDuckTypes::from(new stdClass());
// => ['stdClass' => 'stdClass', 'object' => 'object', 'mixed' => 'mixed']

$types = GetDuckTypes::from($value);
if (isset($types['callable'])) {
    // ...
}
```

#### Example — printable type descriptor

`GetPrintableType::from()` returns a string, and takes an optional bitmask of `FLAG_*` class constants controlling how much detail the string includes.

| Flag | Effect |
|------|--------|
| `FLAG_NONE` | Minimum output — just the type token |
| `FLAG_CLASSNAME` | Include the class name for non-Closure objects |
| `FLAG_CALLABLE_DETAILS` | Include the callable shape — `<function>`, `<Class::method>`, `<Closure>` |
| `FLAG_SCALAR_VALUE` | Include the scalar value — `int<42>`, `bool<true>`, `string<hello>` |
| `FLAG_DEFAULTS` | All three detail flags set (the default if `$options` is omitted) |

```php
use StusDevKit\MissingBitsKit\TypeInspectors\GetPrintableType;

GetPrintableType::from(42);                                         // 'int<42>'
GetPrintableType::from(fn() => 1);                                  // 'callable<Closure>'
GetPrintableType::from(['a' => 1]);                                 // 'array'
GetPrintableType::from(null);                                       // 'null'

GetPrintableType::from(42, GetPrintableType::FLAG_NONE);            // 'int'
GetPrintableType::from(42, GetPrintableType::FLAG_SCALAR_VALUE);    // 'int<42>'

$options = GetPrintableType::FLAG_CLASSNAME
    | GetPrintableType::FLAG_SCALAR_VALUE;
GetPrintableType::from(new stdClass(), $options);                   // 'object<stdClass>'
```

### Functions

| Function | Purpose |
|----------|---------|
| `get_class_basename()` | Return the short class name without the namespace |
| `object_merge()` | Copy all properties from source object(s) onto a target object |
| `uri_resolve_reference()` | Resolve a URI reference against a base URI per RFC 3986 Section 5 |
