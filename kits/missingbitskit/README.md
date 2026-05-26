# MissingBitsKit

## Scope

MissingBitsKit provides standalone functions and lightweight classes that fill gaps in PHP's standard library. These are utilities that are generally useful but not provided by PHP itself.

## Namespace

`StusDevKit\MissingBitsKit`

Standalone functions are autoloaded via Composer's `files` directive. Classes follow PSR-4 autoloading.

## Contents

Each entry below links to its manual page, which carries the full contract, examples, and caveats. Entries are grouped by source namespace, alphabetical within each group.

### Arrays

Contracts and helpers for working with values that can be projected into PHP arrays, and for the keys those arrays use.

- [`Arrayable`](../../manual/missingbitskit/Arrays/Arrayable/README.md) — instance-level contract: any class that returns its internal state as a PHP array
- [`ArrayShape`](../../manual/missingbitskit/Arrays/ArrayShape/README.md) — value object describing whether an array is being used as an ordered list or as a key/value map
- [`GetArrayShape`](../../manual/missingbitskit/Arrays/GetArrayShape/README.md) — classifies a PHP array as list-shaped or map-shaped
- [`IsArrayKey`](../../manual/missingbitskit/Arrays/IsArrayKey/README.md) — type guard: reports whether a value can be used as a PHP array key
- [`RequireArrayKey`](../../manual/missingbitskit/Arrays/RequireArrayKey/README.md) — type guarantee: proves a value can be used as a PHP array key, throwing otherwise
- [`StaticallyArrayable`](../../manual/missingbitskit/Arrays/StaticallyArrayable/README.md) — type-level counterpart to `Arrayable`: any type that returns its data as a PHP array via a static method

### Contracts

Interfaces that describe behaviour expected of values handed to MissingBitsKit's inspectors.

- [`NormalisesForComparison`](../../manual/missingbitskit/Contracts/NormalisesForComparison/README.md) — contract for objects that produce their own canonical form for structural comparison

### DataInspectors

Inspectors that derive a normalised projection from a runtime value, suitable for downstream comparison or hashing.

- [`GetNormalisedForComparison`](../../manual/missingbitskit/DataInspectors/GetNormalisedForComparison/README.md) — produce a normalised version of a value, designed for use in structural comparisons

### Enums

Helpers for the enum side of the type system.

- [`EnumToArray`](../../manual/missingbitskit/Enums/EnumToArray/README.md) — canonical `StaticallyArrayable::toArray()` implementation for backed enums; returns a `name => value` map

### Json

Error-safe wrappers around PHP's JSON builtins.

- [`Json`](../../manual/missingbitskit/Json/Json/README.md) — wrappers for `json_encode()`, `json_decode()`, and `json_validate()`; encode and decode always throw `\JsonException` on failure
- [`JsonValidationError`](../../manual/missingbitskit/Json/JsonValidationError/README.md) — value object returned by `Json::validate()` on failure

### Reflection

Helpers for working with PHP's `ReflectionType` hierarchy and for guarding `new $classname(...)` calls.

- [`ClassInstantiability`](../../manual/missingbitskit/Reflection/ClassInstantiability/README.md) — enum of reasons a class-string can or cannot be `new`'d
- [`FlattenReflectionType`](src/Reflection/FlattenReflectionType.php) — recursive leaf-flatten of a `ReflectionType` into the unique set of PHP type-name strings it can satisfy (no manual page yet)
- [`GetClassInstantiability`](../../manual/missingbitskit/Reflection/GetClassInstantiability/README.md) — reports whether a class-string can be `new`'d, and the first disqualifying reason if not
- [`GetReflectionTypes`](../../manual/missingbitskit/Reflection/GetReflectionTypes/README.md) — one-level unwrap of a `ReflectionType` into a flat `ReflectionType[]`
- [`IntersectionTypesNotSupportedException`](../../manual/missingbitskit/Reflection/IntersectionTypesNotSupportedException/README.md) — thrown when an intersection type is passed where only named or union types are supported
- [`UnsupportedReflectionTypeException`](../../manual/missingbitskit/Reflection/UnsupportedReflectionTypeException/README.md) — thrown when a `ReflectionType` subclass is not recognised

### TypeInspectors

A family of classes that answer the question *"what types can this value satisfy?"*. Type-map inspectors return a `type-name => type-name` map ordered most-specific to most-general; `Is*` predicates return a `bool`.

- [`FlattenClassTypes`](src/TypeInspectors/FlattenClassTypes.php) — expand a list of class/interface names into a deduplicated leaf-list of every type each can satisfy (no manual page yet)
- [`GetArrayTypes`](../../manual/missingbitskit/TypeInspectors/GetArrayTypes/README.md) — types an array satisfies (adds `callable` for `[Class, method]` callables)
- [`GetBooleanTypes`](../../manual/missingbitskit/TypeInspectors/GetBooleanTypes/README.md) — `true` / `false` / `bool` / `mixed`
- [`GetClassHierarchy`](../../manual/missingbitskit/TypeInspectors/GetClassHierarchy/README.md) — the class itself and its parent classes
- [`GetClassInterfaces`](../../manual/missingbitskit/TypeInspectors/GetClassInterfaces/README.md) — interfaces implemented, walking interface hierarchies
- [`GetClassTraits`](../../manual/missingbitskit/TypeInspectors/GetClassTraits/README.md) — all traits used by a class and its parents, including trait-of-trait usage
- [`GetClassTypes`](../../manual/missingbitskit/TypeInspectors/GetClassTypes/README.md) — full type surface for a class-string: parents, interfaces, traits, `callable` (if `__invoke` is defined), `object`, `mixed`
- [`GetDuckTypes`](../../manual/missingbitskit/TypeInspectors/GetDuckTypes/README.md) — entry point that accepts any PHP value and dispatches to the right inspector below
- [`GetFloatTypes`](../../manual/missingbitskit/TypeInspectors/GetFloatTypes/README.md) — `numeric`, `float`, `mixed`
- [`GetIntegerTypes`](../../manual/missingbitskit/TypeInspectors/GetIntegerTypes/README.md) — `numeric`, `int`, `mixed`
- [`GetNamespace`](../../manual/missingbitskit/TypeInspectors/GetNamespace/README.md) — the namespace part of a class-string or an object's class
- [`GetNumericTypes`](../../manual/missingbitskit/TypeInspectors/GetNumericTypes/README.md) — shared helper used by other inspectors for `is_numeric()` values
- [`GetObjectTypes`](../../manual/missingbitskit/TypeInspectors/GetObjectTypes/README.md) — types an object satisfies; thin wrapper around `GetClassTypes`
- [`GetPrintableType`](../../manual/missingbitskit/TypeInspectors/GetPrintableType/README.md) — human-readable type descriptor string for error messages (e.g. `int<42>`, `object<MyClass>`, `callable<Closure>`)
- [`GetStringTypes`](../../manual/missingbitskit/TypeInspectors/GetStringTypes/README.md) — adds `callable` / `numeric` where the string also satisfies those shapes
- [`IsClosedResource`](../../manual/missingbitskit/TypeInspectors/IsClosedResource/README.md) — predicate: reports whether a value is a resource that has been closed
- [`IsComparablePair`](../../manual/missingbitskit/TypeInspectors/IsComparablePair/README.md) — predicate: reports whether two values can be meaningfully ordered against each other with `<`, `>`, `<=`, `>=`

### Standalone functions

Functions autoloaded via Composer's `files` directive; import with `use function StusDevKit\MissingBitsKit\<name>`.

- [`get_class_basename()`](src/get_class_basename.php) — return the short class name without the namespace
- [`object_merge()`](src/object_merge.php) — copy all public properties from one or more source objects onto a target object (the object equivalent of `array_merge()`)
- [`uri_resolve_reference()`](src/uri_resolve_reference.php) — resolve a URI reference against a base URI per RFC 3986 Section 5
