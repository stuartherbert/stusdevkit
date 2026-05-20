# IsComparablePair

Predicate. Use this to determine whether two values can be
meaningfully ordered against each other using PHP's relational
operators (`<`, `>`, `<=`, `>=`).

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class IsComparablePair
{
    /**
     * Predicate. Can `$first` and `$second` be meaningfully ordered
     * against each other using PHP's relational operators?
     */
    public function __invoke(mixed $first, mixed $second): bool;

    /**
     * Predicate. Can `$first` and `$second` be meaningfully ordered
     * against each other using PHP's relational operators?
     */
    public static function check(mixed $first, mixed $second): bool;
}
```

## Description

`IsComparablePair` is a predicate. It answers a single yes/no
question: can two values be ordered against each other using PHP's
relational operators (`<`, `>`, `<=`, `>=`)?

The predicate can be used in two ways:

- [`::check()`](check.md) — the static entry point. Use it inline
  when the call site already has both values in hand and does not
  need to allocate.
- [`->__invoke()`](__invoke.md) — the invokable entry point. Use
  it when the predicate has to be stored in a `callable` slot,
  passed around as a value, or composed with higher-order helpers.

Both methods apply the same rule: a pair is comparable when both
values are scalar, or when both values are
[`DateTimeInterface`](https://www.php.net/manual/en/class.datetimeinterface.php)
instances. Every other pairing — most importantly a scalar paired
with a `DateTimeInterface` — is reported as not comparable. The
check is symmetric: swapping the two arguments never changes the
result.

`IsComparablePair` was originally added for
[`AssertApi`](../../../assertionskit/Contracts/AssertApi/README.md)'s
comparison assertions, which accept an
`int|float|string|DateTimeInterface` on each side and must reject
a scalar paired with a DateTime before handing the two values to
`>`. See the Here Be Dragons section below for the footgun this
rules out.

## Methods

**From `IsComparablePair`**

- [`->__invoke()`](__invoke.md) — predicate. Can `$first` and `$second` be meaningfully ordered against each other using PHP's relational operators?
- [`::check()`](check.md) — predicate. Can `$first` and `$second` be meaningfully ordered against each other using PHP's relational operators?

## Here Be Dragons

This predicate rules out one specific footgun: PHP reports ANY
object as greater than ANY scalar, so `$dateTime > 5` is `true`
regardless of the date - the comparison is decided by type, not
by value. A scalar-vs-DateTime pair is therefore reported as
NOT comparable.

It does NOT police the subtler within-scalar surprises. Two
scalars always pass, even though PHP's relational operators
still apply their own coercion between them: a non-numeric
string compared against a number is byte-compared, and a `bool`
drags the other operand through truthiness. If those matter to
your caller, guard for them yourself.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\IsComparablePair
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes only __invoke() and check() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/IsComparablePair.php:78`](../../../../kits/missingbitskit/src/TypeInspectors/IsComparablePair.php#L78)

## Changelog

_No tagged releases yet._

## See Also

- [`IsArrayKey`](../../Arrays/IsArrayKey/README.md) — sibling
  predicate in MissingBitsKit; shares the static `check()` plus
  invokable `__invoke()` shape, returning `bool`.
- [`AssertApi`](../../../assertionskit/Contracts/AssertApi/README.md) —
  the comparison assertions that originally motivated this
  predicate.

## Issues

- [Open issues mentioning `IsComparablePair`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsComparablePair%22)
- [Closed issues mentioning `IsComparablePair`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsComparablePair%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsComparablePair%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
