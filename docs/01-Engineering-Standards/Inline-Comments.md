# Inline Comments

## Introduction

Code tells you **what** it does. Inline comments tell you **why** it
was written that way. For code that will live for many years,
reconstructing "why" from "what" is a lossy operation — eventually
some future reader will get it wrong and "clean up" a load-bearing
decision.

This standard is therefore strict: inline comments inside functions
and methods are **essential** for maintainable code, not optional
garnish. Don't default to writing none — default to writing enough
that the method reads as its own design document.

## Core principle

- **Code tells you WHAT.** Well-named identifiers and structured
  control flow handle this. If you need a comment to explain *what*
  a line does, the identifiers are probably wrong; rename first.
- **Comments tell you WHY.** Why is this guard here? Why this
  branch? Why this ordering? Why this apparently odd variable name?
  None of these are derivable from the code; all of them are
  deliberate engineering decisions.

## Comments first, then implementation

Write the comments **before** writing the implementation. The
comments become the method's outline; the code fills them in.

Consequences:

- **Comments are the primary artifact.** A wrong comment is a design
  bug, not a doc bug.
- **The method's skeleton is visible at a glance.** A reader can
  collapse the code and still read the algorithm.
- **Design happens in prose first.** You commit to an approach in
  English before you commit to syntax, which is cheaper to revise.

## The narrative shape

A well-written method body reads top-to-bottom as a narrative:

1. **Declare** what the method will produce (`// our return value`)
2. **Guard** the inputs and preconditions (`// robustness!`,
   `// correctness!`)
3. **Discriminate** the cases (`// general case`,
   `// special case`, numbered `// step N:`)
4. **Summarise** what's been proven above (`// if we get here ...`)
5. **Confirm** the work is complete (`// all done`)
6. Return

Not every method uses every beat, but the ordering is consistent
across the codebase. A reader who has read any one method can skim
the next one faster.

## Marker vocabulary

The project uses a small set of inline-comment markers with specific
meanings. New markers may be added over time; the current set is
below. Apply them as short tags above the code they describe — the
tag classifies the *purpose category*, and the code itself (plus any
adjacent description) covers the details.

### `// robustness!`

The guard on the next line(s) rejects invalid input. Classifies the
guard as deliberate input validation, not defensive dead weight.

```php
// robustness!
if (!class_exists($class)) {
    throw new InvalidClassException($class);
}
```

### `// correctness!`

The guard ensures correct output for supported input. Similar shape
to `// robustness!`, but distinguishes "we're validating our own
output" from "we're validating the caller's input". Robustness and
correctness together are the two first-class engineering pillars
of this project.

### `// special case`

The branch that follows handles an unusual scenario, separate from
the default path.

### `// general case`

The branch that follows handles the default/typical scenario. Pairs
with `// special case` to make the discrimination explicit.

```php
// general case - named type
if ($refType instanceof ReflectionNamedType) {
    return [ $refType ];
}

// special case - supports multiple types
if ($refType instanceof ReflectionUnionType) {
    return $refType->getTypes();
}
```

*Example: [kits/missingbitskit/src/Reflection/GetReflectionTypes.php](../../kits/missingbitskit/src/Reflection/GetReflectionTypes.php)*

### `// our return value`

Declares and names the accumulator the method will populate and
return. Signals to the reader: "this is what we're building; the
rest of the method fills it in."

```php
public static function toArray(): array
{
    // our return value
    $retval = [];

    foreach (self::cases() as $case) {
        $retval[$case->name] = $case->value;
    }

    return $retval;
}
```

*Example: [kits/missingbitskit/src/Enums/EnumToArray.php](../../kits/missingbitskit/src/Enums/EnumToArray.php)*

### `// if we get here ...`

A short, snappy one-liner summarising what the early-returns and
throws above have proven. Gives the reader the current invariants
before the method's final block. Keep it tight.

```php
// if we get here, we've run out of reasons why the given
// class name cannot be instantiated
return ClassInstantiability::INSTANTIABLE;
```

*Example: [kits/missingbitskit/src/Reflection/GetClassInstantiability.php](../../kits/missingbitskit/src/Reflection/GetClassInstantiability.php)*

### `// all done`

Placed immediately before a final `return`, confirms all expected
work is complete. Useful in longer methods where the reader may
have lost track of whether every step has run.

### `// step 1: XXX`, `// step 2: XXX`

Numbered steps in a method that does several distinct pieces of
work. Drafted first, implementation fills each step in.

```php
// step 1: does the symbol exist at all?
if (!class_exists($classname) && ...) { ... }

// step 2-4: rule out the non-class-shaped symbols first.
if ($refClass->isInterface()) { ... }
// ...

// step 5: abstract classes cannot be `new`'d directly ...
if ($refClass->isAbstract()) { ... }

// step 6: an explicit constructor must be public ...
$ctor = $refClass->getConstructor();
```

*Example: [kits/missingbitskit/src/Reflection/GetClassInstantiability.php](../../kits/missingbitskit/src/Reflection/GetClassInstantiability.php)*

### `// keep phpstan happy`

Classifies a statement as existing for the **static analyser's
benefit**, not for runtime correctness. The invariant the statement
asserts is already guaranteed by earlier code (a typehint, a series
of narrowing checks, an interface contract) — the line is there
only because PHPStan cannot follow the inference itself.

Without the marker, a future reader seeing an `assert()` or a
narrowing check might think it's a runtime guard and try to add
error handling, throw clauses, or test cases around it. The marker
tells them: "this is a static-analysis aid, not a correctness
check; the invariant already holds."

Use the two-line structure: **invariant line**, blank `//`, then
the marker:

```php
// if we get here, we've already ruled out every other shape for `callable`
//
// keep phpstan happy
assert(is_string($callable));
```

*Example: [kits/dependencykit/src/Reflection/ResolveParameters.php](../../kits/dependencykit/src/Reflection/ResolveParameters.php)*

### `// shorthand`

Declares a variable whose purpose is **readability only** — a rename
of an expression that would be too long or too chained to sit
inline in the next `if` / `while` / `match` / assignment. Signals to
the reader: "don't speculate about why this variable exists, it's
just the next expression with a name."

A secondary use: licensing short variable names that would
otherwise look sloppy, by explaining that their shortness is
load-bearing — typically because they mirror variable names from an
external spec or algorithm pseudocode.

```php
// shorthand - names mirror RFC 3986 Section 5.2.2 (R, B)
$r = uri_parse($ref);
$b = uri_parse($base);
```

*Provisional — this marker's guidance may refine as more real
examples appear in the kits.*

## What NOT to comment

- **Don't restate WHAT the code does.** If a comment says the same
  thing the code says (`// increment the counter` next to `$i++`),
  delete it. Rename identifiers or restructure if the WHAT isn't
  already clear.
- **Don't reference the current task, fix, or ticket.**
  `// added for issue #123`, `// part of the Y migration` — these
  belong in the commit message and the PR description. They rot
  fast: the issue closes, the migration completes, and the comment
  becomes archaeology.
- **Don't reference callers.** `// used by FooService` — callers
  change; git history is authoritative for who-uses-what.

## Relationship to docblocks

Docblocks and inline comments serve different scopes:

- **Docblocks** describe a class or method's external contract:
  what it does, what it takes, what it returns, what it throws,
  what its hazards are (`Here Be Dragons`). Read by callers who
  don't want to open the body.
- **Inline comments** describe the implementation's internal
  reasoning: why this branch, why this guard, why this ordering.
  Read by maintainers inside the body.

The "tell them WHY" rule applies to both, but the WHY is at
different levels: docblocks document the WHY of the API shape;
inline comments document the WHY of the implementation.

## Canonical examples

Current best examples of this standard in the codebase:

- [kits/missingbitskit/src/Reflection/GetClassInstantiability.php](../../kits/missingbitskit/src/Reflection/GetClassInstantiability.php)
  — numbered steps, `// if we get here`, narrative shape end-to-end
- [kits/missingbitskit/src/Enums/EnumToArray.php](../../kits/missingbitskit/src/Enums/EnumToArray.php)
  — `// our return value` accumulator pattern
- [kits/missingbitskit/src/Reflection/GetReflectionTypes.php](../../kits/missingbitskit/src/Reflection/GetReflectionTypes.php)
  — `// general case` / `// special case` discrimination

This is an evolving standard. When new marker vocabulary emerges,
or existing guidance sharpens based on review comments, update this
document and flag the change in commit history.
