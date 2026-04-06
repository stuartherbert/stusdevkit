# CollectionsKit for Stu's Dev Kit

Type-safe data collection classes for PHP 8.5+, with full PHPStan max-level support.

Part of Stu's Dev Kit: building blocks for assembling the things you need to build, in a way that will last.

## Installation

```bash
composer require stusdevkit/collectionskit
```

## What Problem Are We Solving?

PHP's arrays are one of the language's secret weapons:

- they're incredibly fast
- they're do the work of several data structures from most other languages

Unfortunately, if you're trying to build code that will last for years, they have two major disadvantages:

- they can't be typed at all
- they can't be extended (ie, can't add additional methods)

To a fair extent, you _can_ get around the first one by using PHPStan. But that only gives you limited protection: there are no runtime type checks to catch mistakes.

The second one is more about developer experience preference.

- With a collections class, we can colocate all the relevant, repeated operations throughout our code, and add them as methods. They're easy to discover, and gaps are easy to spot.
- We can also pick a type of collection (dictionary, list, index) that puts restrictions on how that collection works. This can add convenience (see index collections), and also avoid bugs by making sure the collection is used consistently throughout your libraries and apps.

## Types of Collection

This library provides the following types of collection, each suited to a different relationship between keys and values.

### Lists

A **List** is a sequential collection with automatic integer keys. The caller provides only values; keys are assigned automatically starting from 0.

Use a List when your data has no natural identity or primary key.

```php
use StusDevKit\CollectionsKit\Lists\ListOfStrings;

$tags = new ListOfStrings();
$tags->add('php')
     ->add('collections')
     ->add('typesafe');

$tags->count();      // 3
$tags->first();      // 'php'
$tags->toArray();    // [0 => 'php', 1 => 'collections', 2 => 'typesafe']
```

**Available classes:**

| Class | Description |
|-------|-------------|
| `CollectionAsList` | Base class for all lists. Extend this to create your own. |
| `ListOfCallables` | A list of PHP callables (closures, function names, method arrays, invokable objects). |
| `ListOfFloats` | A list of `float` values. Extends `ListOfNumbers`. |
| `ListOfIntegers` | A list of `int` values. Extends `ListOfNumbers`. |
| `ListOfNumbers` | A list of numeric values (`int` or `float`). |
| `ListOfObjects` | A list of PHP objects. |
| `ListOfStrings` | A list of string values. |
| `ListOfUuids` | A list of `UuidInterface` values. |

### Dictionaries

A **Dictionary** is a key-value collection where the caller provides both the key and the value. Use a Dictionary when your data has an external identity or when you need to control the keys.

```php
use StusDevKit\CollectionsKit\Dictionaries\DictOfStrings;

$config = new DictOfStrings();
$config->set('host', 'localhost')
       ->set('port', '3306');

$config->get('host');      // 'localhost'
$config->has('port');      // true
$config->maybeGet('tls');  // null
```

**Available classes:**

| Class | Description |
|-------|-------------|
| `CollectionAsDict` | Base class for all dictionaries. Extend this to create your own. |
| `DictOfStrings` | String values with arbitrary keys. |
| `DictOfBooleans` | Named boolean flags, with `isTrue()` and `isFalse()` helpers. |
| `DictOfIntegers` | Integer values with arbitrary keys. |
| `DictOfFloats` | Float values with arbitrary keys. |
| `DictOfNumbers` | Base class for numeric dictionaries. |
| `DictOfObjects` | Object values with arbitrary keys. |
| `DictOfUuids` | `UuidInterface` values with caller-provided keys. |

### Indexes

An **Index** is a key-value collection where the key is derived from the value itself. The caller provides only the value; the collection extracts the key automatically (typically from an `getId()` method).

Use an Index when your data has an inherent identity that should serve as its lookup key.

```php
use StusDevKit\CollectionsKit\Indexes\IndexOfEntitiesWithUuids;

$users = new IndexOfEntitiesWithUuids();
$users->add($alice);  // key derived from $alice->getId()
$users->add($bob);    // key derived from $bob->getId()

$users->get((string) $alice->getId());  // returns $alice
$users->getIds();                        // array of UuidInterface objects
```

**Available classes:**

| Class | Description |
|-------|-------------|
| `IndexOfUuids` | Bare `UuidInterface` values, keyed by their string representation. |
| `IndexOfEntitiesWithStringIds` | Entities implementing `EntityWithStringId`, keyed by `getId()`. |
| `IndexOfEntitiesWithUuids` | Entities implementing `EntityWithUuid`, keyed by `getId()`. |

### Stacks

A **Stack** is a last-in-first-out (LIFO) collection. Items are added with `push()` and removed with `pop()`. Only the top element is accessible — there is no random access or iteration.

Use a Stack when you need to track nested scopes, undo history, or any situation where you process items in reverse order.

```php
use StusDevKit\CollectionsKit\Stacks\StackOfStrings;

$stack = new StackOfStrings();
$stack->push('first');
$stack->push('second');
$stack->push('third');

$stack->peek();  // 'third' (does not remove)
$stack->pop();   // 'third'
$stack->pop();   // 'second'
$stack->pop();   // 'first'
```

**Available classes:**

| Class | Description |
|-------|-------------|
| `CollectionAsStack` | Base class for all stacks. Extend this to create your own. |
| `StackOfStrings` | A LIFO stack of string values. |

**Stack API:**

| Method | Description |
|--------|-------------|
| `push($value)` | Add a value to the top of the stack. Returns `$this` for chaining. |
| `pop()` | Remove and return the top value, or throw if empty. |
| `maybePop()` | Remove and return the top value, or `null` if empty. |
| `peek()` | Return the top value without removing it, or throw if empty. |
| `maybePeek()` | Return the top value without removing it, or `null` if empty. |
| `count()` | Number of items in the stack. |
| `empty()` | Returns `true` if the stack has no items. |

## Choosing the Right Type

| Question | Use |
|----------|-----|
| Does the data have no natural key? | **List** |
| Do you want to control the key yourself? | **Dictionary** |
| Should the key come from the data itself? | **Index** |
| Do you need last-in-first-out access? | **Stack** |

## Common API

All three collection types share a common base (`CollectionOfAnything`) that provides:

| Method | Description |
|--------|-------------|
| `count()` | Number of items in the collection. Also works with PHP's `count()` function. |
| `empty()` | Returns `true` if the collection has no items. |
| `first()` | Returns the first item, or throws if empty. |
| `maybeFirst()` | Returns the first item, or `null` if empty. |
| `last()` | Returns the last item, or throws if empty. |
| `maybeLast()` | Returns the last item, or `null` if empty. |
| `merge()` | Merges another collection or array into this one. |
| `copy()` | Returns a new collection with the same data. |
| `toArray()` | Returns the underlying data as a plain PHP array. |
| `getIterator()` | Makes the collection iterable with `foreach`. |

Dictionaries and Indexes additionally provide:

| Method | Description |
|--------|-------------|
| `get($key)` | Returns the value for `$key`, or throws if not found. |
| `maybeGet($key)` | Returns the value for `$key`, or `null` if not found. |
| `has($key)` | Returns `true` if the key exists. |

## Data Transformation Methods

Some collections provide methods that transform the stored data in-place. These methods are all prefixed with `apply` to clearly distinguish them from accessors and other operations.

| Class | Method | Description |
|-------|--------|-------------|
| `DictOfStrings` | `applyTrim()` | Trims whitespace (or custom characters) from all strings using PHP's `trim()`. |
| `DictOfStrings` | `applyLtrim()` | Left-trims whitespace (or custom characters) from all strings using PHP's `ltrim()`. |
| `DictOfStrings` | `applyRtrim()` | Right-trims whitespace (or custom characters) from all strings using PHP's `rtrim()`. |
| `ListOfStrings` | `applyTrim()` | Trims whitespace (or custom characters) from all strings using PHP's `trim()`. |
| `ListOfStrings` | `applyLtrim()` | Left-trims whitespace (or custom characters) from all strings using PHP's `ltrim()`. |
| `ListOfStrings` | `applyRtrim()` | Right-trims whitespace (or custom characters) from all strings using PHP's `rtrim()`. |

All `apply*` methods return `$this` for fluent chaining:

```php
$list = new ListOfStrings(['  /hello/  ', '  /world/  ']);
$list->applyTrim()->applyTrim(characters: '/');
// ['hello', 'world']
```

## Contracts

The library provides interfaces for entities that can be stored in Indexes:

| Interface | Method | Use with |
|-----------|--------|----------|
| `EntityWithStringId` | `getId(): string\|Stringable` | `IndexOfEntitiesWithStringIds` |
| `EntityWithUuid` | `getId(): UuidInterface` | `IndexOfEntitiesWithUuids` |

## Extending the Library

Create your own type-safe collection by extending the appropriate base class:

```php
use StusDevKit\CollectionsKit\Lists\CollectionAsList;

/**
 * @extends CollectionAsList<MyValueObject>
 */
class ListOfMyValueObjects extends CollectionAsList
{
}
```

```php
use StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict;

/**
 * @extends CollectionAsDict<string, MyEntity>
 */
class DictOfMyEntities extends CollectionAsDict
{
}
```

```php
use StusDevKit\CollectionsKit\Stacks\CollectionAsStack;

/**
 * @extends CollectionAsStack<MyUri>
 */
class StackOfUris extends CollectionAsStack
{
}
```

## License

BSD-3-Clause. See the license header in any source file for full terms.
