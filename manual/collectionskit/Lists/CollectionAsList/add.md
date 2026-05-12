# CollectionAsList::add()

> `public function add(mixed $value): static`

Add a new value to the end of the list.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Lists\CollectionAsList`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class CollectionAsList
{
    /**
     * @param TValue $value
     * @throws NullValueNotAllowedException
     */
    public function add(mixed $value): static
}
```

## Description

Adds `$value` to the end of the list. The value receives the next sequential integer key.

Duplicate values are not prevented — `add()` does not check whether `$value` already exists in the list. If you need uniqueness, enforce it at the caller, or use an [Index](../../../README.md#indexes) variant.

You cannot store `null` in the list — see the kit-level [no-null rule](../../../../kits/collectionskit/README.md). Passing `null` raises a `NullValueNotAllowedException` at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so subclasses preserve their own type when chaining.

## Parameters

**`$value`** (`mixed`)

The value to append. The PHPStan-narrowed type is the class's `TValue` template parameter (`array|bool|callable|float|int|object|string`); subclasses can pin it further. `null` is not permitted.

## Return Values

Returns `$this` — the same list instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\CollectionAsList
 ✔ ::add() is public and not static
 ✔ ::add() declares return type static
 ✔ ::add() accepts one parameter named $value
 ✔ ::add() declares $value as mixed
 ✔ ->add() appends a value to the list
 ✔ ->add() appends multiple values in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add duplicate values
 ✔ ->add() rejects null values
 ✔ ->add() can add values of different types
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict::set()`](../../Dictionaries/CollectionAsDict/set.md) — dictionary equivalent (caller provides the key)
- [`CollectionAsStack::push()`](../../Stacks/CollectionAsStack/push.md) — stack equivalent (LIFO storage)

## Issues

- [Open issues mentioning `CollectionAsList::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsList::add()%22)
- [Closed issues mentioning `CollectionAsList::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsList::add()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsList%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
