# GetNormalisedForComparison::fromNested()

> `public static fromNested(mixed $input, NormalisationContext $context): mixed`

Normalise a value while continuing an existing walk's
cycle-detection state.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\DataInspectors\GetNormalisedForComparison`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\DataInspectors;

class GetNormalisedForComparison
{
    /**
     * @param mixed $input
     *      the value to normalise
     * @param NormalisationContext $context
     *      the parent walk's shared state, supplied to your
     *      `getNormalisedForComparison()` method - do not
     *      construct your own
     * @return mixed
     *      the normalised representation of `$input`
     */
    public static function fromNested(
        mixed $input,
        NormalisationContext $context,
    ): mixed
}
```

## Description

`::fromNested()` normalises `$input` while **continuing an existing
walk's cycle-detection state**, rather than starting a fresh one.

It is the entry point intended for implementors of
[`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md):
the implementor receives a
[`NormalisationContext`](../NormalisationContext/README.md) from the
parent walk and forwards it through every nested normalisation it
performs. Passing the context keeps the parent's visited-set alive
across the interface boundary, so self-references in the
implementor's own graph are detected and emit a `__cycle_ref` marker
instead of recursing forever.

The same type-dispatch sits behind `fromNested()` as behind
[`from()`](from.md) — the only difference is where the
[`NormalisationContext`](../NormalisationContext/README.md) comes
from: [`from()`](from.md) builds a fresh one, `fromNested()` requires
the caller to supply the parent walk's.

Casual top-level callers should use [`from()`](from.md) instead.
`fromNested()` is the protocol-internal entry point and deliberately
**requires** a context, so forgetting to pass it is a compile-time
error rather than a silent revert to the infinite-loop footgun.

## Parameters

**`$input`** (`mixed`)

The value to normalise. Any PHP value is accepted — scalars, `null`,
arrays, objects, and enums.

**`$context`** (`NormalisationContext`)

The parent walk's shared state, supplied to your
[`getNormalisedForComparison()`](../../Contracts/NormalisesForComparison/getNormalisedForComparison.md)
method. Pass through the context you were handed — do not construct
your own.

## Return Values

The normalised representation of `$input`. The shape depends on the
input: scalars and `null` round-trip unchanged, arrays come back as
arrays (possibly re-indexed or re-ordered), and objects always come
back as arrays. Treat any non-scalar result as opaque — compare it,
do not introspect it.

## Errors/Exceptions

_None._

## Here Be Dragons

The same value-shape hazards that apply to [`from()`](from.md) apply
to `fromNested()` — the same dispatch sits behind both. The
highlights, repeated here so the IDE hover on `fromNested()` carries
them too:

- **Return type may differ from input type.** Scalars round trip,
  arrays may be re-indexed, objects always come back as arrays. Treat
  the result as opaque.

- **Closures collapse together.** Every closure canonicalises to
  `['class' => 'Closure', 'properties' => []]`; two different
  closures silently compare equal. Normalise callables yourself (e.g.
  via a stable identifier) before handing them in.

- **PHP `resource` values pass through verbatim.** Two `fopen()`
  calls for the same path produce different resource IDs and
  canonicalise unequal even though they "mean" the same thing.

- **[`DateTime`](https://www.php.net/manual/en/class.datetime.php) /
  [`DateTimeImmutable`](https://www.php.net/manual/en/class.datetimeimmutable.php)
  expose raw internals.** No special-casing here; they fall through
  to reflection and surface their private `date`, `timezone_type`,
  and `timezone` fields. Two datetimes that represent the same
  instant in different timezones canonicalise differently. Wrap
  datetime-bearing state in a
  [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
  implementor instead.

- **`UNINITIALISED_PROPERTY` sentinel can collide with real string
  values.** See [`UNINITIALISED_PROPERTY`](README.md#here-be-dragons)
  for the full hazard.

## Examples

_None yet._

## Contract (from tests)

_None._

## Source

[`kits/missingbitskit/src/DataInspectors/GetNormalisedForComparison.php:238`](../../../../kits/missingbitskit/src/DataInspectors/GetNormalisedForComparison.php#L238)

## Changelog

_No tagged releases yet._

## See Also

- [`from()`](from.md) — the casual top-level entry point that builds a fresh context
- [`NormalisationContext`](../NormalisationContext/README.md) — the per-walk state `fromNested()` requires
- [`NormalisesForComparison::getNormalisedForComparison()`](../../Contracts/NormalisesForComparison/getNormalisedForComparison.md) — the implementor method that receives the context and passes it here
- [`GetNormalisedForComparison`](README.md) — the class this method belongs to

## Issues

- [Open issues mentioning `GetNormalisedForComparison::fromNested()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNormalisedForComparison%3A%3AfromNested%28%29%22)
- [Closed issues mentioning `GetNormalisedForComparison::fromNested()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNormalisedForComparison%3A%3AfromNested%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNormalisedForComparison%3A%3AfromNested%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
