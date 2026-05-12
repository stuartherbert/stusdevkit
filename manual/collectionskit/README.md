# CollectionsKit Manual

API reference for the `collectionskit` package.

## Classes

### Root Base

- [`CollectionOfAnything`](CollectionOfAnything/README.md) — root base class for every collection type in CollectionsKit

### Accessible Collections

- [`AccessibleCollection`](AccessibleCollection/README.md) — extends CollectionOfAnything with methods for accessing arbitrary elements, merging collections, and copying

### Lists

- [`CollectionAsList`](Lists/CollectionAsList/README.md) — base class for all lists
- [`ListOfCallables`](Lists/ListOfCallables/README.md) — a list of PHP callables
- [`ListOfFloats`](Lists/ListOfFloats/README.md) — a list of `float` values
- [`ListOfIntegers`](Lists/ListOfIntegers/README.md) — a list of `int` values
- [`ListOfNumbers`](Lists/ListOfNumbers/README.md) — a list of numeric values (`int` or `float`)
- [`ListOfObjects`](Lists/ListOfObjects/README.md) — a list of PHP objects
- [`ListOfStrings`](Lists/ListOfStrings/README.md) — a list of string values
- [`ListOfUuids`](Lists/ListOfUuids/README.md) — a list of `UuidInterface` values

### Dictionaries

- [`CollectionAsDict`](Dictionaries/CollectionAsDict/README.md) — base class for all dictionaries
- [`DictOfBooleans`](Dictionaries/DictOfBooleans/README.md) — named boolean flags
- [`DictOfFloats`](Dictionaries/DictOfFloats/README.md) — float values with arbitrary keys
- [`DictOfIntegers`](Dictionaries/DictOfIntegers/README.md) — integer values with arbitrary keys
- [`DictOfNumbers`](Dictionaries/DictOfNumbers/README.md) — base class for numeric dictionaries
- [`DictOfObjects`](Dictionaries/DictOfObjects/README.md) — object values with arbitrary keys
- [`DictOfStrings`](Dictionaries/DictOfStrings/README.md) — string values with arbitrary keys
- [`DictOfUuids`](Dictionaries/DictOfUuids/README.md) — `UuidInterface` values with caller-provided keys

### Indexes

- [`IndexOfEntitiesWithStringIds`](Indexes/IndexOfEntitiesWithStringIds/README.md) — entities keyed by `getId()` string
- [`IndexOfEntitiesWithUuids`](Indexes/IndexOfEntitiesWithUuids/README.md) — entities keyed by `getId()` UUID
- [`IndexOfUuids`](Indexes/IndexOfUuids/README.md) — bare `UuidInterface` values keyed by string representation

### Stacks

- [`CollectionAsStack`](Stacks/CollectionAsStack/README.md) — base class for all stacks
- [`StackOfStrings`](Stacks/StackOfStrings/README.md) — a LIFO stack of string values

### Traits

- [`StringTransformations`](Traits/StringTransformations/README.md) — string transformation methods (`applyTrim`, `applyLtrim`, `applyRtrim`)
- [`UuidConversions`](Traits/UuidConversions/README.md) — UUID conversion helpers

### Validators

- [`RejectNullArrayValues`](Validators/RejectNullArrayValues/README.md) — validates that no array element is null
- [`RejectNullValue`](Validators/RejectNullValue/README.md) — validates that a single value is not null

### Exceptions

- [`EmptyCollectionException`](Exceptions/EmptyCollectionException/README.md) — thrown when accessing an empty collection
- [`EmptyStackException`](Exceptions/EmptyStackException/README.md) — thrown when accessing an empty stack

### Contracts (Interfaces)

- [`EntityWithStringId`](Contracts/EntityWithStringId/README.md) — interface for entities with string IDs
- [`EntityWithUuid`](Contracts/EntityWithUuid/README.md) — interface for entities with UUID IDs
