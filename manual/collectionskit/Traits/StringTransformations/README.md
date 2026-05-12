# StringTransformations

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Traits;

trait StringTransformations
{
    /**
     * Trims all strings in the collection using PHP's trim() function.
     */
    public function applyTrim(
        string $characters = " \n\r\t\v\0",
    ): static;

    /**
     * Left-trims all strings in the collection using PHP's ltrim() function.
     */
    public function applyLtrim(
        string $characters = " \n\r\t\v\0",
    ): static;

    /**
     * Right-trims all strings in the collection using PHP's rtrim() function.
     */
    public function applyRtrim(
        string $characters = " \n\r\t\v\0",
    ): static;
}
```

## Description

`StringTransformations` provides in-place string transformation methods for collections that hold string values.

Use this trait in any collection class that extends [`CollectionOfAnything`](../../CollectionOfAnything/README.md) and stores strings — for example [`DictOfStrings`](../../Dictionaries/DictOfStrings/README.md).

Each method mutates the underlying `$data` array in place via `array_map()`, replacing every stored string with its trimmed counterpart. The methods return `$this` so calls can be chained fluently.

## Methods

**From StringTransformations**

- [`->applyTrim()`](applyTrim.md) — trims all strings in the collection using PHP's `trim()` function
- [`->applyLtrim()`](applyLtrim.md) — left-trims all strings in the collection using PHP's `ltrim()` function
- [`->applyRtrim()`](applyRtrim.md) — right-trims all strings in the collection using PHP's `rtrim()` function

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Traits\StringTransformations
 ✔ is declared as a trait
 ✔ lives in the StusDevKit\CollectionsKit\Traits namespace
 ✔ exposes only applyTrim, applyLtrim, and applyRtrim methods
```

## Source

[`kits/collectionskit/src/Traits/StringTransformations.php:51`](../../../../kits/collectionskit/src/Traits/StringTransformations.php#L51)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](../../Dictionaries/DictOfStrings/README.md) — uses this trait
- [`CollectionOfAnything`](../../CollectionOfAnything/README.md) — root base class for every collection type

## Issues

- [Open issues mentioning `StringTransformations`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22StringTransformations%22)
- [Closed issues mentioning `StringTransformations`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22StringTransformations%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=StringTransformations%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
