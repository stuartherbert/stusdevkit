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
use StusDevKit\MissingBitsKit\Json\Json;

$json = new Json();

// encode a PHP value to a JSON string
$encoded = $json->encode(value: ['name' => 'Alice', 'age' => 30]);

// decode a JSON string back to a PHP value
$decoded = $json->decode(input: $encoded, associative: true);

// validate a JSON string without decoding it
// returns [] on success, or a list<JsonValidationError> on failure
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

### Reflection

Helpers for working with PHP's `ReflectionType` hierarchy.

#### GetReflectionTypes

`ReflectionType` is abstract: the real payload lives on three unrelated child classes (`ReflectionNamedType`, `ReflectionUnionType`, `ReflectionIntersectionType`), each with its own shape. `GetReflectionTypes::from()` papers over that by returning a flat `ReflectionType[]` regardless of which child class you were handed.

```php
use StusDevKit\MissingBitsKit\Reflection\GetReflectionTypes;

// given a named type (e.g. `int`), you get it back wrapped in an array
$types = GetReflectionTypes::from($namedType);
// => [ReflectionNamedType]

// given a union (e.g. `int|string`), you get the member types
$types = GetReflectionTypes::from($unionType);
// => [ReflectionNamedType, ReflectionNamedType]

// given an intersection (e.g. `Countable&Traversable`), same
$types = GetReflectionTypes::from($intersectionType);
// => [ReflectionNamedType, ReflectionNamedType]
```

`from()` performs a **one-level unwrap only**. For a DNF type such as `(Countable&Traversable)|int`, the returned array contains the intersection as a `ReflectionIntersectionType` — callers that need a fully-flattened leaf list should use `FlattenReflectionType::from()` instead.

Throws `UnsupportedReflectionTypeException` if given a `ReflectionType` subclass it does not recognise (future-proofing against new PHP additions).

#### FlattenReflectionType

`FlattenReflectionType::from()` goes one step further than `GetReflectionTypes`: it recurses all the way to the leaves and returns the **unique set of PHP type-name strings** the position can satisfy. It's aimed at callers that want to ask *"which named types does this parameter accept?"* without caring about the `ReflectionType` hierarchy — a reflection-based DI container is the motivating use case.

```php
use StusDevKit\MissingBitsKit\Reflection\FlattenReflectionType;

// a named type
FlattenReflectionType::from($namedIntType);
// => ['int']

// a nullable named type - split into base type plus 'null', so it
// looks the same as an explicit `int|null` union
FlattenReflectionType::from($nullableIntType);
// => ['int', 'null']

// a union
FlattenReflectionType::from($intOrStringType);
// => ['int', 'string']

// an intersection
FlattenReflectionType::from($countableAndTraversableType);
// => ['Countable', 'Traversable']

// a DNF type - fully flattened to leaves, with duplicates collapsed
FlattenReflectionType::from($aAndBOrAAndCType);
// => ['A', 'B', 'C']     (not ['A', 'B', 'A', 'C'])
```

Three pieces of processing happen together:

- **recursion** — union and intersection members are walked to the leaf level, including DNF types like `(A&B)|C`.
- **nullable split** — a `?Foo` named type is emitted as two leaves, `'Foo'` and `'null'`, so the caller sees the same shape as an explicit `Foo|null` union. (`mixed` and an explicit `null` return type are already whole leaves and are not split further.)
- **deduplication** — each distinct leaf appears once. The reachable case is a DNF like `(A&B)|(A&C)`, where `A` would otherwise appear twice.

The returned list is **unordered**. PHP normalises union and intersection members at parse time, so source/declaration order is not preserved — reflection never sees it. Callers that need a predictable order should sort the result themselves.

Throws `UnsupportedReflectionTypeException` if given a `ReflectionType` subclass it does not recognise (surfaced from the `GetReflectionTypes` delegation).

#### GetClassInstantiability

`GetClassInstantiability::from()` reports whether a given class-string names something PHP will let you `new` — and, if not, the single reason that disqualifies it. It's aimed at callers that want to guard a `new $classname(...)` call, such as a reflection-based DI container.

The inspector returns a `ClassInstantiability` enum. Each case carries a short string value suitable for dropping straight into an error message.

```php
use StusDevKit\MissingBitsKit\Reflection\ClassInstantiability;
use StusDevKit\MissingBitsKit\Reflection\GetClassInstantiability;

// happy path - a plain class with a public constructor
GetClassInstantiability::from(MyService::class);
// => ClassInstantiability::INSTANTIABLE

// interfaces cannot be `new`'d
GetClassInstantiability::from(MyInterface::class);
// => ClassInstantiability::IS_INTERFACE

// enums are classes at runtime, but PHP forbids `new` on them
GetClassInstantiability::from(MyEnum::class);
// => ClassInstantiability::IS_ENUM

// a singleton / factory-only class
GetClassInstantiability::from(SingletonClass::class);
// => ClassInstantiability::CONSTRUCTOR_NOT_PUBLIC

// unknown name - not a class, interface, trait, or enum
GetClassInstantiability::from('DoesNotExist');
// => ClassInstantiability::CLASS_DOES_NOT_EXIST
```

Callers that only care about a yes/no answer use the `isInstantiable()` helper on the result:

```php
if (! GetClassInstantiability::from($classname)->isInstantiable()) {
    throw new \RuntimeException("cannot build {$classname}");
}
```

Callers that want a reason in their error message use the backing string:

```php
$result = GetClassInstantiability::from($classname);
if (! $result->isInstantiable()) {
    throw new \RuntimeException("cannot build {$classname}: {$result->value}");
    // e.g. "cannot build MyThing: is an abstract class"
}
```

The inspector reports the **first** disqualifying reason it finds, in this order: existence → interface → trait → enum → abstract → non-public constructor. A class that fails more than one check (e.g. abstract class with a private constructor) reports only the first one seen.

### Arrays

#### StaticallyArrayable

`StaticallyArrayable` is a type-level counterpart to the
instance-level `Arrayable`. It declares a single static method that
returns the implementing type's data as a PHP array:

```php
namespace StusDevKit\MissingBitsKit\Arrays;

/**
 * @template TKey of array-key
 * @template TValue of mixed
 */
interface StaticallyArrayable
{
    /** @return array<TKey, TValue> */
    public static function toArray(): array;
}
```

Originally added for **backed enums**: the set of cases is a
property of the type, not of any individual case. Implementers
(e.g. `ClassInstantiability`) advertise that their case set can be
rendered as an array for data-provider tests, config rendering, JSON
output, and similar consumers.

See [`docs/01-Engineering-Standards/Enums.md`](../../docs/01-Engineering-Standards/Enums.md) — every backed
enum in the project is required to implement this interface.

### Enums

#### EnumToArray

`EnumToArray` is the canonical implementation of
`StaticallyArrayable::toArray()` for backed enums. It walks
`self::cases()` and returns a `name => value` map:

```php
use StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable;
use StusDevKit\MissingBitsKit\Enums\EnumToArray;

/**
 * @implements StaticallyArrayable<string, string>
 */
enum MyEnum: string implements StaticallyArrayable
{
    /** @use EnumToArray<string> */
    use EnumToArray;

    case FIRST = 'first';
    case SECOND = 'second';
}

MyEnum::toArray();
// => ['FIRST' => 'first', 'SECOND' => 'second']
```

The trait is **generic** over the backing type. Bind the type
parameter at the use site with `@use EnumToArray<string>` for
string-backed enums, or `@use EnumToArray<int>` for int-backed
enums. Without the binding the return type widens to
`array<string, string|int>`, leaking a union the using enum
promised to avoid.

PHPStan enforces that the trait is only used inside a backed enum
via `@phpstan-require-implements \BackedEnum`. Using it in a pure
enum, a regular class, or an interface is a static-analysis error.

### Functions

| Function | Purpose |
|----------|---------|
| `get_class_basename()` | Return the short class name without the namespace |
| `object_merge()` | Copy all properties from source object(s) onto a target object |
| `uri_resolve_reference()` | Resolve a URI reference against a base URI per RFC 3986 Section 5 |
