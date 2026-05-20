# IsClosedResource

Predicate. Use this to determine whether a value is a resource
that has been closed.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class IsClosedResource
{
    /**
     * Predicate. Is the given `$value` a closed resource?
     */
    public function __invoke(mixed $value): bool;

    /**
     * Predicate. Is the given `$value` a closed resource?
     */
    public static function check(mixed $value): bool;
}
```

## Description

`IsClosedResource` is a predicate. It answers a single yes/no
question: is a value a resource that has been closed?

The predicate can be used in two ways:

- [`::check()`](check.md) — the static entry point. Use it inline
  when the call site already has a value in hand and does not
  need to allocate.
- [`->__invoke()`](__invoke.md) — the invokable entry point. Use
  it when the predicate has to be stored in a `callable` slot,
  passed around as a value, or composed with higher-order helpers.

Both methods apply the same rule, and return `true` only for a
resource that has been closed — for example, a file handle after
[`fclose()`](https://www.php.net/manual/en/function.fclose.php).
An open resource returns `false`, and so does every non-resource
value. PHP's own
[`is_resource()`](https://www.php.net/manual/en/function.is-resource.php)
cannot fill this role: it returns `false` for a closed resource
*and* for a non-resource, so it cannot tell the two apart.

`IsClosedResource` was originally added to give
[`AssertApi`](../../../assertionskit/Contracts/AssertApi/README.md)'s
closed-resource assertions a single, robust place to make this
check, instead of each one comparing the output of a debug-only
function against a hard-coded string. See the Here Be Dragons
section below for why the check is necessarily indirect.

## Methods

**From `IsClosedResource`**

- [`->__invoke()`](__invoke.md) — predicate. Is the given `$value` a closed resource?
- [`::check()`](check.md) — predicate. Is the given `$value` a closed resource?

## Here Be Dragons

PHP has no native closed-resource predicate, so this check is
necessarily indirect. It returns `true` ONLY for a resource
that has been closed: an OPEN resource returns `false` (reach
for `is_resource()` if that is what you want), and so does
every non-resource value.

The check deliberately keys on the stable `resource` prefix
that `gettype()` reports, not on the exact `resource (closed)`
wording - the parenthetical text is a description PHP could
reword, whereas the resource type itself is a frozen, legacy
part of the language.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\IsClosedResource
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes only __invoke() and check() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/IsClosedResource.php:73`](../../../../kits/missingbitskit/src/TypeInspectors/IsClosedResource.php#L73)

## Changelog

_No tagged releases yet._

## See Also

- [`IsComparablePair`](../IsComparablePair/README.md) — predicate
  sibling in the same directory; shares the static `check()` plus
  invokable `__invoke()` shape, returning `bool`.
- [`IsArrayKey`](../../Arrays/IsArrayKey/README.md) — another
  predicate of the same shape in MissingBitsKit.
- [`AssertApi`](../../../assertionskit/Contracts/AssertApi/README.md) —
  the closed-resource assertions that originally motivated this
  predicate.

## Issues

- [Open issues mentioning `IsClosedResource`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsClosedResource%22)
- [Closed issues mentioning `IsClosedResource`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsClosedResource%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsClosedResource%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
