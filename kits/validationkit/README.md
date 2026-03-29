# ValidationKit for Stu's Dev Kit

A validation library for PHP 8.5+, with full PHPStan max-level support.

Part of Stu's Dev Kit: building blocks for assembling the things you need to build, in a way that will last.

## Scope

TODO: Define the scope and purpose of this kit.

## Design Principles

### Accept the Native Type, Use coerce() for Conversions

Each schema validates its native PHP type. To accept other representations (e.g. strings from decoded JSON), call `coerce()` to opt in to type conversion.

| Schema | Native Type | coerce() Converts From |
|--------|-------------|------------------------|
| `Validate::boolean()` | `bool` | `"true"`, `"false"`, `0`, `1`, etc. |
| `Validate::dateTime()` | `DateTimeInterface` | ISO 8601 strings, Unix timestamps |
| `Validate::uuid()` | `UuidInterface` | UUID strings (standard and dashless) |

Without `coerce()`, non-native types are rejected. This keeps the boundary between validation and conversion explicit.

## License

BSD-3-Clause. See the license header in any source file for full terms.
