# GetObjectTypes::from()

> `public static function from(object $item): array`

Static factory; accepts an object and returns its type map.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetObjectTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetObjectTypes
{
    /**
     * @param  object $item
     *         the item to examine
     * @return array<string,string>
     *         the object's list of types
     */
    public static function from(object $item): array
}
```

## Description

`from()` accepts a PHP object instance and returns an associative array mapping
each type it satisfies to its spelling. This is the fast path for callers that
already know the input is an object — it skips the [`is_object()`](https://www.php.net/manual/en/function.is-object.php)
guard that [`->__invoke()`](__invoke.md) performs.

The method is a thin wrapper around [`GetClassTypes::from()`](../GetClassTypes/README.md).
It extracts the class name via [`get_class()`](https://www.php.net/manual/en/function.get-class.php)
and delegates to `GetClassTypes::from()`, which handles:

- The class name itself (the object is always an instance of its own class)
- All parent classes in the inheritance hierarchy
- All implemented interfaces
- All used traits
- Instance-level callable detection (Closures, objects with `__invoke()`) via
  `method_exists($className, '__invoke')`
- The universal `object` type hint

Each entry maps the type name to itself (`$type => $type`), producing an
`array<string, string>` shape. The order matches what `GetClassTypes::from()` reports.

The universal `'mixed'` type hint is deliberately **not** included here.
As documented in [`GetIntegerTypes::from()`](../GetIntegerTypes/README.md),
`mixed` is a duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetObjectTypes::__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`

## Parameters

**`$item`** (`object`)

The object instance to examine. Unlike [`->__invoke()`](__invoke.md), this
method does not validate the input — it expects a valid object and delegates
directly to `GetClassTypes::from()`.

## Return Values

Returns an associative array mapping type names to their spelling:

- An entry for the object's class name (FQCN)
- Entries for all parent classes in the inheritance hierarchy
- Entries for all implemented interfaces
- Entries for all used traits
- `'object'` — the universal object type hint

For a plain `stdClass` instance, returns:
```php
[
    stdClass::class => stdClass::class,
    'object' => 'object',
]
```

For an object with parents, interfaces, and traits (e.g. `SampleClass`),
returns all entries including the class name, parent classes, interfaces,
traits, and `'object'`.

For a `Closure` instance, returns the class name, `'callable'`, and
`'object'`.

For an object whose class declares `__invoke()` (e.g. `SampleInvokable`),
returns the class name, `'callable'`, and `'object'`.

The shape is `array<string, string>`. The order matches what
[`GetClassTypes::from()`](../GetClassTypes/README.md) reports.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetObjectTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns class name and object for a plain object
 ✔ ::from() returns full class surface for an object with parent, interface, and trait
 ✔ ::from() adds callable for a Closure instance
 ✔ ::from() adds callable for an object that defines __invoke()
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetObjectTypes.php:82`](../src/TypeInspectors/GetObjectTypes.php#L82)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint

## Issues

- [Open issues mentioning `GetObjectTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetObjectTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetObjectTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetObjectTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetObjectTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
