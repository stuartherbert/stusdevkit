# Enums

## Introduction

Enums in this project are **value objects**. They represent a fixed set of
named values, nothing more.

## Case names are UPPER_SNAKE_CASE

Enum cases are named constants. PHP exposes them as class-level
constants (`MyEnum::MY_CASE`), so they follow the same convention as any
other constant in the project: `UPPER_SNAKE_CASE`.

**Why:** cases are values, not types or methods. Naming them like
constants makes the call site read correctly (`ClassInstantiability::IS_ABSTRACT`
reads as a constant lookup, which is exactly what it is) and keeps them
visually distinct from class names and method calls. It also removes any
ambiguity when a case name would otherwise collide with a PascalCase
class name in the same namespace.

### Allowed

```php
enum ClassInstantiability: string
{
    case INSTANTIABLE = 'instantiable';
    case IS_INTERFACE = 'is an interface';
    case CONSTRUCTOR_NOT_PUBLIC = 'constructor is not public';
}
```

### Not allowed

```php
enum ClassInstantiability: string
{
    case Instantiable = 'instantiable';            // PascalCase - no
    case isInterface = 'is an interface';          // camelCase - no
    case constructor_not_public = 'constructor is not public'; // lowercase - no
}
```

The backing value (the string to the right of `=`) is free-form — it
exists for human consumption (error messages, logs, UI labels) and is
not bound by this rule.

## Enums never create themselves

Enums must not contain static factory methods that inspect external state
(class-strings, arrays, objects, database rows, etc.) to decide which case
to return.

Creating an enum value is someone else's job. A dedicated inspector,
parser, or mapper class takes the input, does the work, and returns the
enum. The enum itself stays focused on representing the value.

**Why:** it keeps the enum small, focused, and easy to reason about. The
inspection logic lives in a class that can be named for what it does,
tested in isolation, and replaced or extended without touching the enum.
Enums that reach out to reflect on classes, parse strings, or query
services end up as god-objects that tangle value representation with
business logic.

### Allowed

```php
// a dedicated inspector class creates the enum value
final class GetClassInstantiability
{
    public static function from(string $classname): ClassInstantiability
    {
        // ... inspect the class-string, return the right case ...
    }
}

enum ClassInstantiability
{
    case INSTANTIABLE;
    case CLASS_DOES_NOT_EXIST;
    case IS_INTERFACE;
    // ...
}
```

### Not allowed

```php
enum ClassInstantiability
{
    case INSTANTIABLE;
    case CLASS_DOES_NOT_EXIST;
    // ...

    // enum reaches out and inspects external state - wrong
    public static function forClass(string $classname): self
    {
        // ...
    }
}
```

PHP's built-in `BackedEnum::from()` / `tryFrom()` are the one exception:
they map a scalar backing value to its case, which is part of the enum's
own identity, not external inspection.

## Backed enums must expose their cases as an array

Every **backed enum** (string-backed or int-backed) must:

- `implements StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable`, and
- provide `public static function toArray(): array` returning a
  `name => value` map of every case.

The canonical implementation is the
`StusDevKit\MissingBitsKit\Enums\EnumToArray` trait. Use it rather
than writing the method by hand:

```php
use StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable;
use StusDevKit\MissingBitsKit\Enums\EnumToArray;

/**
 * @implements StaticallyArrayable<string, string>
 */
enum ClassInstantiability: string implements StaticallyArrayable
{
    /** @use EnumToArray<string> */
    use EnumToArray;

    case INSTANTIABLE = 'instantiable';
    case IS_INTERFACE = 'is an interface';
    // ...
}
```

The `@use EnumToArray<string>` tag binds the trait's generic
`TValue` to `string` (use `<int>` for int-backed enums). This
keeps `toArray()`'s return type narrow enough to satisfy the
enum's `@implements StaticallyArrayable<string, TValue>`
promise — without it, the trait would return `array<string,
string|int>` and leak the wider union to callers.

Callers then have a uniform, predictable way to get the full case set:

```php
ClassInstantiability::toArray();
// => [
//     'INSTANTIABLE' => 'instantiable',
//     'IS_INTERFACE' => 'is an interface',
//     // ...
// ]
```

**Why:** the set of cases is a property of the **type**, not of any
individual case, so `toArray()` is static. Having one canonical
name-to-value map everywhere makes data-provider driven tests, config
rendering, and JSON output trivial — every backed enum works the same
way.

### Pure enums are exempt

Pure enums have no backing value, so there's nothing to map names to.
A pure enum that needs array-like exposure should be promoted to a
backed enum first; don't invent a fake value just to satisfy the
interface.

### Why static, not instance

`ClassInstantiability::IS_ENUM->toArray()` would return the same map
as `ClassInstantiability::INSTANTIABLE->toArray()` — the value is a
property of the type, not the instance. Exposing it as static makes
this explicit, and removes the odd "every case returns the same
thing" shape that an instance method would have.

### Why a separate `StaticallyArrayable` interface

`Arrayable` is shaped for instance-level state: the same collection
can hold different data at different moments, so `toArray()` is an
instance method. `StaticallyArrayable`
covers the other case — the data is bound to the type, not the
instance — which is what a backed enum is.

## Convenience methods are fine

Enums may carry methods that help callers work with the value they
already hold. These are read-only and operate on `$this` alone.

Typical shapes:

- predicates: `isInstantiable(): bool`, `isTerminal(): bool`
- descriptions: `label(): string`, `describe(): string`
- comparisons / groupings: `isOneOf(self ...$others): bool`

**Rule of thumb:** if the method needs to look outside `$this` to
answer, it does not belong on the enum.
