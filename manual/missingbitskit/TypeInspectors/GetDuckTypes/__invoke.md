# GetDuckTypes->__invoke()

> `public function __invoke(mixed $item): array`

Call the class as an invokable object; accepts any input and returns the full
type list.

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
     *
     * @return array<string,string>
     *         a list of type(s) that this item can be
     */
    public function __invoke(mixed $item): array;
}
```

## Description

`__invoke()` accepts any value and returns the full type list — the union of all
per-type inspectors plus the universal `mixed` marker. It is a thin wrapper that
delegates directly to [`::from()`](from.md).

The method performs no validation or coercion — every PHP value is accepted and
dispatched through the same `is_*()` chain as `from()`.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetObjectTypes::from()`](../GetObjectTypes/from.md) — static factory for object type inspection

## Parameters

**`$item`** (`mixed`)

The item to examine. Accepts any PHP value — scalars, objects, callables,
arrays, null, or resources.

## Return Values

Returns an associative array mapping type names to their spelling — the same
result as [`::from()`](from.md). The shape is `array<string, string>`.

Every returned array includes `'mixed' => 'mixed'` because every PHP value
satisfies the `mixed` type hint.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
$duck = new GetDuckTypes();

// Integer input
$duck(42);
// Returns: ['numeric' => 'numeric', 'int' => 'int', 'mixed' => 'mixed']

// String input
$duck('hello');
// Returns: ['string' => 'string', 'mixed' => 'mixed']

// Object input
$duck(new stdClass());
// Returns: [stdClass::class => stdClass::class, 'object' => 'object', 'mixed' => 'mixed']
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetDuckTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() and ::from() return the same result
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetDuckTypes.php:52`](../src/TypeInspectors/GetDuckTypes.php#L52)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts any value, returns the full type list
- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetObjectTypes::from()`](../GetObjectTypes/from.md) — static factory for object type inspection

## Issues

- [Open issues mentioning `GetDuckTypes->__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetDuckTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetDuckTypes->__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetDuckTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetDuckTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
