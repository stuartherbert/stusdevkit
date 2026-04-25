# Assertions in StusDevKit

## Purpose of Assert Statements

Assertions serve three distinct purposes in this codebase. Understanding which applies to a given `Assert::*` call is critical for reviewing and maintaining the code.

### 1. Regression Guard for Opaque APIs

When calling **opaque functions** — PHP builtins, C-extension functions, or any code whose implementation cannot be inspected by static analysis tools like PHPStan — assertions document and verify expected behavior at runtime.

PHPStan can type-narrow opaque function return values using casts (e.g., `(string) $result`), but a cast is purely static: it tells the analyzer to trust you without enforcing anything at runtime. An assertion, conversely, is both documentation **and** a regression test for hypothetical future changes in the external code.

```php
// This comment explains why the assert exists and would catch what
// the cast alone cannot: a PHP implementation change that violates
// the documented contract.
Assert::assertIsString($retval, "json_encode() failed to return a string");
```

**When to prefer Assert over cast:** When the function has a documented contract (e.g., "returns X on success") and you want an explicit runtime check if that contract is ever violated. This applies primarily to PHP builtins and C extensions.

### 2. Contract Documentation

Assertions make **invariants visible as code**, not buried in comments or docblocks. An assertion like:

```php
Assert::assertNotSame(0, $error->getCode(), "empty error list means success");
```

is clearer than a comment saying "the method never returns zero" because the assertion is both a statement of fact and an enforcement mechanism — it fires if anyone later introduces code that violates the invariant.

This is especially important for **business logic invariants** (e.g., "a valid email cannot be empty") where the assert serves as executable documentation of what constitutes correctness.

### 3. Developer-Facing Error Messages

Assertions provide better error messages than exceptions thrown from production paths. When an assertion fails:
- The message is visible immediately during development
- It includes file/line context from the calling code
- It does not require a full exception stack trace to understand what went wrong

This makes assertions valuable for **preconditions** — validating that caller-provided arguments satisfy expected invariants before any work begins.

## When NOT to Use Asserts

### Production-Critical Logic

Do **not** use asserts as the sole guard for production-critical paths (e.g., authentication checks, database writes). Assertions can be disabled via `zend.assertions = 0` or `-1`. Production failures must be caught through normal control flow.

### Redundant Checks

An assertion is redundant if:
- The code path before it already guarantees the condition (and no opaque function lies between)
- A type cast already expresses the same intent for PHPStan purposes
- A docblock or inline comment conveys the same invariant more clearly

## Commenting Assertions

Every assert that serves purpose #1 or #2 should have a **one-line explanation** on the line above it. The comment should state what behavior is being guarded, not restate the assertion itself.

```php
// json_encode() with JSON_THROW_ON_ERROR returns a string or throws.
// This assertion documents that invariant (the contract for opaque
// C-built-in functions is not statically provable) and would catch
// a PHP regression if the implementation changed.
Assert::assertIsString($retval, "json_encode() failed to return a string");

// Bad: redundant comment
$retval = json_encode($input);
// Assert that retval is a string
Assert::assertIsString($retval);
```
