# GetDuckTypes::from()

> `public static function from(mixed $item): array`

Returns a practical list of PHP types for any value — the union of all
per-type inspectors plus the universal `mixed` marker.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetDuckTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetDuckTypes
{
    /**
     * @param  mixed $item
     *         the item to examine
     * @return array<string,string>
     *         the list of type(s) that this item can be
     */
    public static function from(mixed $item): array;
}
```

## Description

`from()` accepts any PHP value and returns a practical list of types — the union
of all per-type inspectors plus the universal `mixed` marker. It dispatches via
`is_*()` checks rather than `gettype()` + match for two reasons:

1. `is_*()` avoids allocating the string that `gettype()` would have returned.
2. PHPStan narrows `mixed` on `is_*()` but not on a match-on-gettype(), so the
   concrete `from()` calls type-check without casts.

The method dispatches through seven branches in order:

1. **Strings** — delegated to [`GetStringTypes::from()`](../GetStringTypes/README.md)
2. **Integers** — delegated to [`GetIntegerTypes::from()`](../GetIntegerTypes/README.md)
3. **Floats** — delegated to [`GetFloatTypes::from()`](../GetFloatTypes/README.md)
4. **Arrays** — delegated to [`GetArrayTypes::from()`](../GetArrayTypes/README.md)
5. **Objects** — delegated to [`GetObjectTypes::from()`](../GetObjectTypes/README.md)
6. **Booleans** — delegated to [`GetBooleanTypes::from()`](../GetBooleanTypes/README.md)
7. **Null** — returns `['null' => 'null']` directly (lowercase to match PHP's
   keyword spelling)

Any value that falls through all branches (resources, closed resources) is
handled by the fallback: `gettype()` reports the raw type string, and closed
resources are collapsed from `'resource (closed)'` to `'resource'`.

Each per-type inspector returns only the types the value literally satisfies.
`from()` appends `'mixed' => 'mixed'` centrally because `mixed` is the
duck-type marker — "any value at all" — and every PHP value satisfies it.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetObjectTypes::__invoke()`](../GetObjectTypes/__invoke.md) — accepts any input; validates before delegating to `::from()`

## Parameters

**`$item`** (`mixed`)

The item to examine. Accepts any PHP value — scalars, objects, callables,
arrays, null, or resources.

## Return Values

Returns an associative array mapping type names to their spelling — the union
of all per-type inspectors plus `'mixed'`. The shape is `array<string, string>`.

**Integer inputs:**

- `'numeric' => 'numeric'`
- `'int' => 'int'`
- `'mixed' => 'mixed'`

**Float inputs:**

- `'numeric' => 'numeric'`
- `'float' => 'float'`
- `'mixed' => 'mixed'`

**String inputs:**

- Plain strings: `'string' => 'string'`, `'mixed' => 'mixed'`
- Numeric strings (int): adds `'numeric' => 'numeric'`, `'int' => 'int'`
- Numeric strings (float): adds `'numeric' => 'numeric'`, `'float' => 'float'`
- Callable strings: adds `'callable' => 'callable'`

**Array inputs:**

- Non-callable arrays: `'array' => 'array'`, `'mixed' => 'mixed'`
- Callable arrays: adds `'callable' => 'callable'`

**Object inputs:**

- Plain objects: class name, `'object' => 'object'`, `'mixed' => 'mixed'`
- Objects with parents, interfaces, traits: all of the above plus each ancestor
  and implemented interface/used trait
- Closures: `Closure::class`, `'callable' => 'callable'`, `'object' => 'object'`,
  `'mixed' => 'mixed'`
- Invokable objects: class name, `'callable' => 'callable'`, `'object' => 'object'`,
  `'mixed' => 'mixed'`
- Stringable objects: class name, `Stringable::class`, `'object' => 'object'`,
  `'mixed' => 'mixed'`

**Boolean inputs:**

- `true`: `'true' => 'true'`, `'bool' => 'bool'`, `'mixed' => 'mixed'`
- `false`: `'false' => 'false'`, `'bool' => 'bool'`, `'mixed' => 'mixed'`

**Null input:**

- `'null' => 'null'`, `'mixed' => 'mixed'`

**Resource input:**

- Open resources: `'resource' => 'resource'`, `'mixed' => 'mixed'`
- Closed resources: collapsed to `'resource' => 'resource'`, `'mixed' => 'mixed'`

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
// Integer input
GetDuckTypes::from(42);
// Returns: ['numeric' => 'numeric', 'int' => 'int', 'mixed' => 'mixed']

// Float input
GetDuckTypes::from(1.5);
// Returns: ['numeric' => 'numeric', 'float' => 'float', 'mixed' => 'mixed']

// String input
GetDuckTypes::from('hello');
// Returns: ['string' => 'string', 'mixed' => 'mixed']

GetDuckTypes::from('123');
// Returns: ['numeric' => 'numeric', 'int' => 'int', 'string' => 'string', 'mixed' => 'mixed']

// Object input
GetDuckTypes::from(new stdClass());
// Returns: [stdClass::class => stdClass::class, 'object' => 'object', 'mixed' => 'mixed']

GetDuckTypes::from(fn(): int => 1);
// Returns: [Closure::class => Closure::class, 'callable' => 'callable', 'object' => 'object', 'mixed' => 'mixed']

// Null input
GetDuckTypes::from(null);
// Returns: ['null' => 'null', 'mixed' => 'mixed']

// Resource input
GetDuckTypes::from(tmpfile());
// Returns: ['resource' => 'resource', 'mixed' => 'mixed']
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetDuckTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns numeric, int, and mixed for integer inputs with data set "zero"
 ✔ ::from() returns numeric, int, and mixed for integer inputs with data set "positive"
 ✔ ::from() returns numeric, int, and mixed for integer inputs with data set "negative"
 ✔ ::from() returns numeric, int, and mixed for integer inputs with data set "max"
 ✔ ::from() returns numeric, int, and mixed for integer inputs with data set "min"
 ✔ ::from() returns numeric, float, and mixed for float inputs with data set "zero"
 ✔ ::from() returns numeric, float, and mixed for float inputs with data set "positive"
 ✔ ::from() returns numeric, float, and mixed for float inputs with data set "negative"
 ✔ ::from() returns numeric, float, and mixed for float inputs with data set "very small"
 ✔ ::from() returns the expected type list for string inputs with data set "empty string"
 ✔ ::from() returns the expected type list for string inputs with data set "plain string"
 ✔ ::from() returns the expected type list for string inputs with data set "numeric string (int)"
 ✔ ::from() returns the expected type list for string inputs with data set "numeric string (float)"
 ✔ ::from() returns the expected type list for string inputs with data set "callable string"
 ✔ ::from() returns just array for non-callable array inputs with data set "empty"
 ✔ ::from() returns just array for non-callable array inputs with data set "list"
 ✔ ::from() returns just array for non-callable array inputs with data set "associative"
 ✔ ::from() returns callable and array for callable array inputs
 ✔ ::from() returns just the class name for a plain object
 ✔ ::from() returns all four as duck-types for an object with parent, interface, and trait
 ✔ ::from() returns Closure, callable, object, and mixed for Closure inputs
 ✔ ::from() returns the class name, callable, object, and mixed for an invokable object
 ✔ ::from() returns the class name and Stringable for a Stringable object
 ✔ ::from() returns the class name and Stringable for an object with __toString()
 ✔ ::from() surfaces parent and grandparent for a 3-deep class hierarchy
 ✔ ::from() surfaces an interface implemented on a parent class on the child
 ✔ ::from() surfaces the base interface for a 2-deep interface hierarchy
 ✔ ::from() surfaces a trait used by another trait transitively
 ✔ ::from() surfaces a trait used by a parent class on the child
 ✔ ::from() returns the literal type, bool, and mixed for boolean inputs with data set "true"
 ✔ ::from() returns the literal type, bool, and mixed for boolean inputs with data set "false"
 ✔ ::from() returns null and mixed for a null input
 ✔ ::from() returns resource and mixed for a resource input
 ✔ ::from() collapses a closed resource input to resource and mixed
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetDuckTypes.php:65`](../src/TypeInspectors/GetDuckTypes.php#L65)

## Changelog

_No tagged releases yet._

## See Also

- [`GetPrintableType`](../GetPrintableType/README.md) — returns a human-readable descriptor string
- [`GetObjectTypes::__invoke()`](../GetObjectTypes/__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection

## Issues

- [Open issues mentioning `GetDuckTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetDuckTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetDuckTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetDuckTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetDuckTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
