# Project Context

## Project Overview

This is StusDevKit, a mono-repo of PHP libraries to achieve this goal: Building blocks for assembling the things you need to build, in a way that will last.

The primary goal of this project is to provide a PHP framework that will be stable for many years.

- re-use PHP PSR standards where available
- explicit code over magic code (avoid __call, __get, __set)
- readability over conciseness
  - method names are `verbNoun` or `nounVerb`
  - detailed docblocks
  - detailed inline comments
- minimal third-party dependencies
- simple, solid solutions over clever, fragile code
- usage-driven automated regression tests

## Engineering Standards — Always Consult First

A compiled knowledge base covers every subsystem. Before working on,
analyzing, or reviewing any subsystem, read its article — it contains
cross-file coupling, threading invariants, and gotchas you cannot derive
from reading individual source files.

The full index is imported below.

@docs/01-Engineering-Standards/README.md

Read all engineering standards in `docs/01-Engineering-Standards` at the start of every session.

Read any relevant engineering standard file before working on related topics. The directory contents may grow over time — always discover rather than assume a fixed set of documents.

## Architecture & Structure

### Concept

- Each PHP library in the project is known as a 'kit'.
- Each kit has a clear scope, defined in its README.md file.
- Kits share a single set of dependencies, listed in `composer.json`.
- Kits can import and use other kits (be careful about circular dependencies).
- All kits are tracked by the parent mono-repo's git history. There are no separate git repos for individual kits.

### Kit Index

| Kit | Namespace | Purpose |
|-----|-----------|---------|
| assertionskit | `StusDevKit\AssertionsKit` | Interfaces (contracts) describing assertion methods |
| collectionskit | `StusDevKit\CollectionsKit` | Type-safe data collection classes (lists, dictionaries, indexes) |
| datetimekit | `StusDevKit\DateTimeKit` | DateTime utilities (When, Now) for CLI and HTTP apps |
| dependencykit | `StusDevKit\DependencyKit` | PSR-compatible dependency injection solution |
| exceptionskit | `StusDevKit\ExceptionsKit` | RFC 9457 problem details exceptions |
| missingbitskit | `StusDevKit\MissingBitsKit` | Standalone functions that fill gaps in PHP's standard library |
| validationkit | `StusDevKit\ValidationKit` | Validation library |

### Namespace Convention

Kits follow PSR-4 autoloading: `StusDevKit\<KitName>\` maps to `kits/<kitname>/src/`. Test namespaces follow the same pattern:
- `StusDevKit\<KitName>\Tests\Unit\` → `kits/<kitname>/tests/unit/src/`
- `StusDevKit\<KitName>\Tests\Fixtures\` → `kits/<kitname>/tests/fixtures/src/`

All namespace mappings must be registered in `composer.json` under `autoload` and `autoload-dev`.

### Inter-Kit Dependencies

To check which kits depend on other kits, search for cross-kit `use` statements (e.g. `use StusDevKit\CollectionsKit\...` appearing in another kit's source). Circular dependencies between kits are not allowed.

### Adding a New Kit

1. Create the directory structure: `kits/<kitname>/src/`, `kits/<kitname>/tests/unit/src/`, `kits/<kitname>/tests/fixtures/src/`
2. Create `kits/<kitname>/README.md` defining the kit's scope and purpose
3. Add PSR-4 autoload entries to `composer.json` (both `autoload` and `autoload-dev`)
4. Add a test suite entry to `phpunit.xml.dist` and a source include directory
5. Add the kit's `src` and `tests` paths to `phpstan.neon`
6. Add a `unit-<kitname>` target to the `Makefile` and include it in the `unit` target
7. Update the Kit Index table in this file
8. Run `make composer-install` to regenerate the autoloader

### Before Working on a Kit

- **Read the kit's README.md first** to understand its scope and what belongs there
- Check for inter-kit dependencies before adding cross-kit imports

### Git Hooks
- `.githooks/` - version-controlled git hooks directory (configured via `git config core.hooksPath .githooks`, set up by `make init`)
- `commit-msg` - validates Conventional Commits format
- `post-commit` - rebuilds CHANGELOG.md on main branch and amends the commit to include it (has re-entrancy guard)

### Directory Organization
- `kits/*` - root directory for each PHP library
- `kits/*/README.md` - Kit scope and purpose documentation
- `kits/*/src/` - Root source directory for each PHP library
- `kits/*/src/Contracts/` - Interfaces and contracts
- `kits/*/src/Dictionaries/` - Collections that have a given identity / primary key
- `kits/*/src/Exceptions/` - Exceptions thrown by this code library
- `kits/*/src/Indexes/` - Collections where the stored data provides its own identity
- `kits/*/src/Stacks/` - LIFO stack collections
- `kits/*/src/Lists/` - Collections that have no obvious identity / primary key
- `kits/*/src/Traits/` - Code that's shared across multiple classes
- `kits/*/src/Validators/` - Validation helpers
- `kits/*/tests/fixtures/src/` - test fixtures
- `kits/*/tests/unit/src/` - PHPUnit unit tests (mirrors src/ structure)

## Working Practices

### Discuss Before Making Large Changes
- When a fix requires the same workaround in many places (e.g. adding `@var` annotations to 20+ test files), **stop and discuss** — this is a strong signal that the root cause is elsewhere.
- Present the problem and possible approaches before editing files. Walk through one concrete example together.
- Prefer fixing the source design over patching symptoms in consuming code.
- When making the same change across many files or locations, apply one representative example first and show it for approval before applying the rest.

### Work in Small, Commitable Chunks
Break work into small, focused pieces that can each be committed separately with a clear commit message. Each chunk should be one logical change — a single bug fix, a single refactoring step, or a single new feature. Do not combine unrelated changes.

**After completing each chunk:**
1. Run `make all-checks` to verify the chunk is green.
2. **Stop and tell the user** what was done, so they can review and commit before the next chunk begins.
3. Do not start the next chunk until the user says to continue.

**What constitutes a chunk:**
- A bug fix with its tests (write the failing test first, then the fix).
- A refactoring that changes internal structure without changing behaviour, with any test updates needed.
- A new feature with its tests (write the failing tests first, then the implementation).
- An importer/exporter change for a new keyword, with round-trip tests.

**What is NOT a chunk:**
- Multiple unrelated bug fixes combined together.
- A refactoring bundled with a new feature that depends on it — these are two separate chunks (refactor first, then feature).
- Implementation without tests, followed by tests as a separate chunk — tests and implementation belong together.

### Before Writing Code
1. **Always ask about file placement** - Don't assume directory structure
2. **Ask about naming conventions** - Follow project patterns
3. **Check existing implementations** - Use similar classes as templates
4. **Understand the domain** - Read entity docblocks and comments
5. **Ask for further advice** - If you are not confident about your understanding, ask for more information

### Code Standards
- **PHP 8.5+** with `declare(strict_types=1)`
- **License header** - All PHP files must include the BSD-3-Clause license header (copy from `kits/collectionskit/src/Lists/ListOfStrings.php`)
- **Detailed docblocks** with purpose and usage examples
- **Docblocks: motivation, not speculation** - Don't claim what's
  "primary", "main", or "most common" — you don't have that
  information, and speculation ages badly. DO record what drove the
  code into existence: phrase motivation as **"Originally added
  for ..."**. That's factual information the author always has, and
  it remains useful even once other uses emerge. Applies to docblocks,
  inline comments, and README sections written alongside new code.
- **Method docblocks are load-bearing on utility classes.** For
  classes consumed through their static methods, the caller's IDE
  hover shows only the method docblock — the class-level docblock
  is rarely read. Every piece of contract the caller needs (return
  shape, hazards, `@throws`) must live in the method docblock,
  repeated across siblings if necessary. The class-level docblock
  carries origin, navigation, and shared terms only. Authoritative
  write-up:
  [docs/01-Engineering-Standards/Docblocks.md](docs/01-Engineering-Standards/Docblocks.md).
- **Inline comments are essential, not optional.** This project
  writes code for decades. Inline comments inside functions and
  methods are load-bearing: code tells you *what*, comments tell
  you *why*. Don't default to writing none — default to writing
  enough that the method reads as its own design document.
  Authoritative write-up:
  [docs/01-Engineering-Standards/Inline-Comments.md](docs/01-Engineering-Standards/Inline-Comments.md).
  Key points summarised below.
- **Comments first, then implementation.** Write the comments
  before the code. The comments become the method's outline; the
  code fills them in. A wrong comment is a design bug, not a doc
  bug.
- **Narrative shape.** A method body reads top-to-bottom as
  *declare → guard → discriminate → summarise → confirm → return*.
  Markers below make each beat visible.
- **Marker vocabulary** (apply as short tags above the code they
  describe):
  - `// our return value` — declares the accumulator at the top
  - `// robustness!` — guard rejects invalid input
  - `// correctness!` — guard ensures correct output for supported
    input (robustness and correctness are the two first-class
    engineering pillars — never strip these markers, never critique
    them as "what-comments")
  - `// general case` / `// special case` — branch discrimination
  - `// step 1: XXX` / `// step 2: XXX` — numbered steps in
    multi-step methods, drafted before the implementation
  - `// if we get here ...` — short one-liner summarising what the
    early-returns/throws above have proven
  - `// all done` — confirms work is complete before the final
    return
  - `// keep phpstan happy` — classifies a statement as existing
    for the static analyser's benefit, not runtime correctness
    (the invariant already holds from earlier code). Use the
    two-line structure: invariant line, blank `//`, then the marker.
  - `// shorthand` — variable is a readability rename (not
    computation or state); can license short names when they mirror
    external notation
- **What NOT to comment.** Don't restate WHAT the code does; don't
  reference the current task, fix, or ticket (belongs in commit
  message / PR); don't reference callers (git history is
  authoritative).
- **Constructor validation** with meaningful assertions
- **Line length** - PHP docblocks and comments should word wrap at column 79. Code does not have to word wrap. Markdown files do not need to word wrap.
- **Named method parameters** - always use named method parameters when a
  method call passes more than one parameter
- **Coding standard** - Uses `LaminasCodingStandard` (see phpcs.xml.dist)
- **No naming prefixes/suffixes** - Don't use `Abstract` prefix, `Interface`
  or `Trait` suffixes (PHPStan catches misuse). Exception classes **must** end
  with the `Exception` suffix (e.g. `NullValueNotAllowedException`).
- **`get` / `Get*` prefix is a scope marker** - the prefix signals that the
  thing only returns values and does nothing else. On a **class** name (e.g.
  `GetClassTraits`, `GetPrintableType`), it means the class is a pure value
  producer: no mutation, no side effects, no orchestration. If a class grows
  beyond returning values, the prefix is wrong and the class needs renaming
  or splitting. On a **method**, prefer `get` for getters unless the method
  does complex work. (The full method-level convention is still being
  formalised — apply incrementally, don't retroactively rename without
  discussion.)
- **No PHPStan ignoreErrors** - Never use `ignoreErrors` in `phpstan.neon` to suppress warnings. Fix the code or type annotations instead. The project runs PHPStan at max level specifically to catch type errors; suppressing them defeats that goal.
- **Heredoc for multiline strings** - JSON examples and any strings that don't fit comfortably on a single line must use heredoc format. Use single-quoted heredoc (`<<<'JSON'`) for strings that don't need variable interpolation.

### Code Patterns

#### Section Separators
Use this pattern to organize code sections within classes:
```php
// ================================================================
//
// Section Name
//
// ----------------------------------------------------------------
```

#### Accessor Pattern (Maybe/Definite)
Use paired methods for nullable vs throwing accessors:
- `maybeFirst()` / `first()` - returns null vs throws on empty collection
- `maybeGet($key)` / `get($key)` - returns null vs throws on missing key

#### Data Transformation Methods (`apply` prefix)
Methods that transform stored data in-place must be prefixed with `apply` (e.g. `applyTrim()`, `applyLtrim()`, `applyRtrim()`). This distinguishes them from accessors and other operations. They return `static` for fluent chaining.

#### Method Chaining
Data modification methods should return `$this` or `static` for fluent chaining.

#### PHPDoc Generics
Heavy use of generics for type safety:
- `@template TKey of array-key`
- `@template TValue of mixed`
- `@extends ParentClass<TKey, TValue>`
- `@phpstan-consistent-constructor`

### Testing Practices
- **Test-driven development** - Always write failing tests before implementing changes:
  - For **bug fixes**: write a test that reproduces the bug first. It must fail against the current code, proving the bug exists. Then implement the fix and verify the test passes.
  - For **new features**: write tests that exercise the new behaviour first. They must fail before the implementation and pass after.
  - For **pre-existing bugs** discovered during other work: write the failing test to prove the bug exists before fixing it. The failing test is the evidence.
  - This applies to every change, no exceptions. Tests written after implementation only prove the code works as written — they don't prove the bug existed or that the fix solved it.
- **Follow ListOfStringsTest style** - Use `kits/collectionskit/tests/unit/src/Lists/ListOfStringsTest.php` as template for new tests
- **Test method naming** - Use snake_case: `test_can_instantiate_empty_list()`
- **Test structure**: explain test → setup → perform change → test results
- **Cover ALL public methods** including inherited ones from parent classes
- **Use TestDox attributes** for clear test descriptions
- **Tests are documentation** - The TestDox output is the specification.
  A reader who runs `make test` should learn what the code does without
  reading the code. This shapes how tests are written:
  - Each `#[TestDox('...')]` description is a **complete factual sentence**
    about the code, not test jargon. Prefer `toArray() is static` over
    `ensures that toArray is static`. Prefer `returns a name-to-value
    map for a string-backed enum` over `tests the string-backed enum
    case`. No "ensures", "verifies", "tests that", "checks if" — state
    the fact directly.
  - Group tests into sections using `// ===` separators, and order the
    sections so the TestDox output reads top-down as a coherent story
    (identity first, then shape, then behaviour).
  - Contract tests (reflection-based lockdowns of the published API
    shape) belong alongside behaviour tests: the combination reads as
    "here's what it IS, here's what it DOES".
  - On every `#[DataProvider(...)]` test, use `#[TestDox('... $paramName ...')]`
    placeholders so the varying value lands **inside** the sentence
    rather than as a trailing `with data set "X"` suffix. Prefer
    scalar (`string`/`int`/`bool`) parameter types because they
    interpolate cleanly; look up rich objects inside the test body
    from a scalar name (e.g. `constant(Enum::class . '::' . $name)`
    + `assertInstanceOf` for enum cases).
  - **Pin sets, not counts.** When locking down an API surface (enum
    cases, class methods, parameters), assert the enumerated list,
    not its cardinality. `assertSame(['toArray'], $methodNames)` names
    the offender on failure; `assertSame(1, count(...))` just says
    "expected 1, got 2".
- **Comprehensive coverage** - test happy path, edge cases, and error conditions
- **Never** use for() loops in tests - always create PHPUnit data providers instead
- **Test explanations** - Keep concise, avoid redundant phrases. Word wrap at column 79.
- **Test comments** - Word wrap all test explanation comments at column 79 for readability
- **One condition per test** - Each test method must test one main condition. Do not combine "accepts valid input" and "rejects invalid input" in a single test. Write separate methods (e.g. `test_string_email_accepts_valid` and `test_string_email_rejects_invalid`).
- **Expected values must be literal** - The `$expected` value (or equivalent) in a unit test must always be written as a literal, never computed. Do not derive it by calling another inspector, running a helper, or transforming the input at test time — that turns the test into a tautology (it passes whenever the computation agrees with itself, even if both are wrong). Type each expected value out by hand. Class-string constants (`SomeClass::class`) are fine because they resolve at compile time and give rename safety, but anything that runs code to produce the expected value is not.

#### CRITICAL Testing Anti-Patterns to Avoid
- **NEVER assume the method under test works correctly** - Write tests based on what the method SHOULD do according to business requirements, not what the current implementation happens to return. Tests should reveal bugs, not encode them.
- **ALWAYS set up complete, realistic test data** - Don't use minimal parameters or default values. Understand what fields are required for valid business states and set them properly in test setup. Incomplete test objects lead to unrealistic test scenarios.
- **Define expected behavior FIRST** - Before examining the implementation,understand the business rules and define what the correct behavior should be. Then let the tests fail if the implementation is wrong, rather than tracing through buggy code to calculate test expectations.

### Task Management
- **Use TodoWrite tool** for tracking progress on multi-step tasks
- Use 'bd' for task tracking
- **Mark tasks as in_progress** when starting work
- **Mark tasks as completed** immediately when finished
- **Only one task in_progress** at a time

## Build & Test Commands

All commands run inside Docker containers. Use `DEBUG=1` prefix to enable
Xdebug.

### Setup Commands
- **Initialize environment**: `make init`
- **Open container shell**: `make shell`
- **Install dependencies**: `make composer-install`
- **Update dependencies**: `make composer-update`

### Quality Commands
- **Code formatting**: `make cs-fix`
- **Syntax checking**: `make syntax-check`
- **Static analysis**: `make phpstan` (level 10)
- **All linting**: `make static-checks` (runs syntax-check, cs-fix, phpstan)
- **All tests**: `make all-checks` (always use this for verification, not individual commands)

### Test Commands
- **Full test suite**: `make test`
- **All unit tests**: `make unit`
- **Per-kit unit tests**: `make unit-collectionskit`, `make unit-datetimekit`
- **Unit tests with filter**: `make unit OPTS="specific/test/path"`
- **Code coverage**: `make coverage`

### Notes
- **Import cleanup**: Always run `make cs-fix` after import cleanup to double-check the work

## Dependencies & External Systems
- **PHPUnit 13** - testing framework
- **PHPStan** - static analysis at level 10 (strictest)
- **Laminas Coding Standard** - code style enforcement
- **Ramsey UUID** - for UUID v7 generation
