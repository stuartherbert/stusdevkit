# GetNormalisedForComparison::from()

> `public static from(mixed $input): mixed`

Normalise a value so it can be used in structural comparison.

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
     * @return mixed
     *      the normalised representation of `$input`
     */
    public static function from(
        mixed $input,
    ): mixed
}
```

## Description

`::from()` is the casual top-level entry point for normalisation. It
walks `$input` recursively and rewrites it into a canonical form:

- **list-shaped arrays** (every key an int) drop their keys and
  re-index from zero, preserving value iteration order;
- **map-shaped arrays** (any string key present) preserve all keys
  and are sorted lexicographically by key;
- **objects implementing
  [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)**
  return their class name plus the canonical form they publish — the
  return value is used verbatim, with no further normalisation;
- **other objects** return an array of all properties (public,
  protected and private, including those inherited from parent
  classes), along with their class name;
- **enums** return their class plus the case name — and, for backed
  enums, the backing value;
- **cyclic object graphs** emit a back-reference marker rather than
  recursing forever.

Every call to `from()` starts a **fresh** cycle-detection walk: it
builds its own [`NormalisationContext`](../NormalisationContext/README.md)
internally, so cycle detection is scoped to that single top-level
call and two independent `from()` calls never share state.

Implementors of
[`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
must **not** call `from()` from inside `getNormalisedForComparison()`
— use [`fromNested()`](fromNested.md) instead, so the parent walk's
cycle-detection state is preserved.

## Parameters

**`$input`** (`mixed`)

The value to normalise. Any PHP value is accepted — scalars, `null`,
arrays, objects, and enums.

## Return Values

The normalised representation of `$input`. The shape depends on the
input: scalars and `null` round-trip unchanged, arrays come back as
arrays (possibly re-indexed or re-ordered), and objects always come
back as arrays. Treat any non-scalar result as opaque — compare it,
do not introspect it.

## Errors/Exceptions

_None._

## Here Be Dragons

- **Return type may differ from input type.** A scalar comes back as
  a scalar, but an array can come back re-indexed, and an object
  always comes back as an array. Anything non-scalar should be
  treated as opaque — compare it, do not introspect it.

- **Use [`fromNested()`](fromNested.md) when implementing the
  [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
  contract.** Calling `from()` from inside
  `getNormalisedForComparison()` starts a fresh cycle-detection walk,
  which footguns into an infinite loop on self-referencing graphs.
  See [`fromNested()`](fromNested.md).

- **Closures collapse together.** Reflection sees no user-declared
  properties on a [`Closure`](https://www.php.net/manual/en/class.closure.php),
  so every closure canonicalises to `['class' => 'Closure',
  'properties' => []]`. Two closures with completely different bodies
  silently compare equal. If your state holds callables, normalise
  them yourself (e.g. via a stable identifier) before handing them
  in.

- **PHP `resource` values pass through verbatim.** File handles, curl
  handles, and any other `resource` are neither arrays nor objects,
  so the fallback returns them unchanged. Two `fopen()` calls for the
  same path produce different resource IDs and canonicalise unequal
  even though they "mean" the same thing — a footgun if you compare
  state that carries handles.

- **[`DateTime`](https://www.php.net/manual/en/class.datetime.php) /
  [`DateTimeImmutable`](https://www.php.net/manual/en/class.datetimeimmutable.php)
  expose raw internals.** These have no
  [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
  implementation here, so they fall through to the reflection walk
  and surface their private `date`, `timezone_type`, and `timezone`
  fields. Two datetimes that represent the same instant in different
  timezones canonicalise differently. Wrap datetime-bearing state in
  a value object that implements `NormalisesForComparison` rather
  than relying on raw normalisation.

- **`UNINITIALISED_PROPERTY` sentinel can collide with real string
  values.** See [`UNINITIALISED_PROPERTY`](README.md#here-be-dragons)
  for the full hazard. Short version: a string property whose value
  is the literal `'__uninitialised__'` is indistinguishable in the
  canonical form from a property that was never initialised.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\GetNormalisedForComparison
 ✔ returns the int 42 unchanged
 ✔ returns the float 3.14 unchanged
 ✔ returns the string "hello" unchanged
 ✔ returns the empty string unchanged
 ✔ returns the boolean true unchanged
 ✔ returns the boolean false unchanged
 ✔ returns null unchanged
 ✔ represents an uninitialised typed property with the UNINITIALISED_PROPERTY sentinel
 ✔ does not throw when an uninitialised typed property is encountered
 ✔ represents a self-referencing object with the __cycle_ref marker
 ✔ represents a two-node cycle with sequential visit indexes
 ✔ detects a cycle through a NormalisesForComparison implementor that passes the context
 ✔ a shared (non-cyclic) child is still normalised in full at each occurrence
 ✔ represents a pure enum case as an enum/case pair
 ✔ represents a backed enum case as an enum/case/value triple
 ✔ distinct pure enum cases normalise to distinct values
 ✔ includes private properties declared on a parent class, qualified by their declaring class
 ✔ excludes static properties from the normalised output
 ✔ normalises an empty array to an empty list
 ✔ drops integer keys from an all-int-keyed array and re-indexes from zero
 ✔ preserves the value order of an int-keyed array (values are never sorted)
 ✔ two int-keyed arrays with the same value sequence normalise identically regardless of key gaps
 ✔ preserves all keys when at least one key is a string, even mixed int+string
 ✔ sorts map keys lexicographically (SORT_STRING) so numeric-looking strings stay in lex order
 ✔ recurses into a map nested inside a list
 ✔ recurses into a list nested inside a map
 ✔ recurses into an array held in an object property
 ✔ recurses through several alternating layers of object and array
 ✔ uses the canonical form verbatim when the object implements NormalisesForComparison
 ✔ a CollectionsKit DictOfIntegers preserves its int keys through the full pipeline
```

## Source

[`kits/missingbitskit/src/DataInspectors/GetNormalisedForComparison.php:166`](../../../../kits/missingbitskit/src/DataInspectors/GetNormalisedForComparison.php#L166)

## Changelog

_No tagged releases yet._

## See Also

- [`fromNested()`](fromNested.md) — the protocol-internal entry point for implementors continuing an existing walk
- [`NormalisationContext`](../NormalisationContext/README.md) — the per-walk cycle-detection state `from()` builds internally
- [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md) — the contract `from()` honours verbatim when an object implements it
- [`GetNormalisedForComparison`](README.md) — the class this method belongs to

## Issues

- [Open issues mentioning `GetNormalisedForComparison::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNormalisedForComparison%3A%3Afrom%28%29%22)
- [Closed issues mentioning `GetNormalisedForComparison::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNormalisedForComparison%3A%3Afrom%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNormalisedForComparison%3A%3Afrom%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
