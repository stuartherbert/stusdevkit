# Tests Are Documentation

## Introduction

The TestDox output produced by `make test` is the **specification**
for this project. A developer who runs the test suite should be able
to learn what the code does without opening the source.

This treats the test suite as a live, always-truthful document —
truthful because it has to match the code to pass. Other documents
rot; TestDox output cannot, so long as we invest in making it
readable.

Every testing rule in [CLAUDE.md](../../CLAUDE.md) supports this
goal. The rules below are the ones specific to making the TestDox
output itself read as a spec.

## TestDox descriptions are complete factual sentences

Each `#[TestDox('...')]` attribute is a single factual sentence
about the code, in present tense. No test jargon.

### Allowed

```php
#[TestDox('toArray() is static')]
#[TestDox('returns a name-to-value map for a string-backed enum')]
#[TestDox('lives in the StusDevKit\\MissingBitsKit\\Enums namespace')]
#[TestDox('preserves case declaration order')]
```

These read as direct statements of fact. Strung together in the
test-runner output, they read as a specification.

### Not allowed

```php
#[TestDox('ensures that toArray is static')]
#[TestDox('verifies the namespace')]
#[TestDox('tests the string-backed enum case')]
#[TestDox('checks if declaration order is preserved')]
```

Banned phrases: **"ensures"**, **"verifies"**, **"tests that"**,
**"checks if"**, **"should"**. These are test-internal verbs that
describe what the test is doing rather than what the code does.
Remove them, and what's left is the fact worth documenting.

## DataProvider: use `$paramName` placeholders

PHPUnit's `#[TestDox(...)]` attribute supports parameter
interpolation using `$paramName` placeholders (and positional
`$0`, `$1`, ... alternatives). Use these on every data-provider
test so the varying value appears **inside** the sentence rather
than as a trailing `with data set "X"` suffix.

### Default (avoid)

```php
#[TestDox('each case has its expected backing value')]
#[DataProvider('caseBackingValueProvider')]
public function test_case_has_its_expected_backing_value(
    ClassInstantiability $case,
    string $expectedValue,
): void { /* ... */ }
```

TestDox output:

```
✔ each case has its expected backing value with data set "INSTANTIABLE"
✔ each case has its expected backing value with data set "CLASS_DOES_NOT_EXIST"
...
```

The case name lives in the suffix. A reader has to parse "each
case ... with data set INSTANTIABLE" to understand the claim is
about the INSTANTIABLE case specifically.

### With placeholders (preferred)

```php
#[TestDox('$caseName has backing value "$expectedValue"')]
#[DataProvider('caseBackingValueProvider')]
public function test_case_has_its_expected_backing_value(
    string $caseName,
    string $expectedValue,
): void {
    $case = constant(ClassInstantiability::class . '::' . $caseName);
    $this->assertInstanceOf(ClassInstantiability::class, $case);
    $this->assertSame($expectedValue, $case->value);
}
```

TestDox output:

```
✔ INSTANTIABLE has backing value "instantiable"
✔ CLASS_DOES_NOT_EXIST has backing value "class does not exist"
...
```

Each line is now a first-class factual statement — the shape
this standard is built around.

### Prefer scalar parameter types

Placeholders interpolate using PHP's string-cast rules. Scalar
types (`string`, `int`, `bool`) render cleanly. Objects render
via `__toString()` if defined, otherwise via a verbose class
descriptor — rarely useful in TestDox output.

If the test body needs a rich object (an enum case, a value
object, a domain entity), pass the **name** in the data provider
and look the object up inside the test. For enum cases,
`constant(SomeEnum::class . '::' . $name)` + `assertInstanceOf`
keeps PHPStan happy and reads cleanly.

## Pin sets, not counts

When locking down an API surface (the set of cases on an enum, the
set of methods on a class or interface, the set of parameters on a
method), assert the **enumerated set**, not its cardinality.

### Not allowed

```php
#[TestDox('declares exactly one method')]
public function test_declares_exactly_one_method(): void
{
    $expected = 1;
    $actual = count($reflection->getMethods());
    $this->assertSame($expected, $actual);
}
```

The count is incidental. A test failure says only "expected 1, got
2" — the reader has no idea which method was added. And if we ever
add a method, we're not really testing anything meaningful by
bumping the expected count to 2.

### Allowed

```php
#[TestDox('exposes only a toArray() method')]
public function test_exposes_only_a_toArray_method(): void
{
    $expected = ['toArray'];
    $actual = array_map(
        static fn ($method) => $method->getName(),
        $reflection->getMethods(),
    );
    $this->assertSame($expected, $actual);
}
```

The expected list of method names is explicit. A reader learns
*what* the surface is, not just *how big* it is. A test failure
prints a diff that names the offending addition or removal.

The same principle applies to enum case sets, property sets,
parameter sets, and any other "the API surface is exactly these
members" contract.

## Group tests into identity / shape / behaviour sections

Use `// ===` section separators to split a test file into sections,
and order the sections so the TestDox output reads top-down as a
coherent story:

1. **Identity** — what is it? Is it a trait, a class, an interface?
   What namespace does it live in? How many methods does it declare?
2. **Shape** — what does it look like? Is the method public, static?
   What parameters does it take? What's the return type?
3. **Behaviour** — what does it do? What are the happy-path outputs?
   The edge-case outputs?

A reader scanning the TestDox output sees *"here's what it IS, here's
what it LOOKS like, here's what it DOES"* — the same structure they'd
find in a well-written reference page.

## Contract tests sit alongside behaviour tests

A "contract test" uses reflection to pin down the published API shape
of the thing under test:

- it exists
- it lives in the expected namespace
- it declares the expected method set
- each method has the expected visibility, static-ness, parameter
  count, return type

These feel pedantic in isolation, but each one locks down a distinct
way the contract could drift. Renaming a method, removing `static`,
or moving the namespace would each make exactly one contract test
fail — pointing straight at the specific break.

Contract tests belong in the same file as behaviour tests, in the
"identity" and "shape" sections above. Keeping them together means
the TestDox output documents both *what the thing is* (contract)
and *what it does* (behaviour) in one pass.

### Canonical example

[kits/missingbitskit/tests/unit/src/Enums/EnumToArrayTest.php](../../kits/missingbitskit/tests/unit/src/Enums/EnumToArrayTest.php)
— 12 tests split into identity (3), shape (5), and behaviour (4).
The fixture `StringBackedSampleEnum` declares its cases in
non-alphabetical order on purpose, so the "preserves declaration
order" behaviour test cannot coincidentally pass under a hidden
alphabetic sort.
