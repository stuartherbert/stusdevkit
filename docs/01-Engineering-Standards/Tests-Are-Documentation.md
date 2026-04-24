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

## Prefix method-specific tests with the method name

A flat TestDox listing mixes every class-level and method-level
test under one class heading. Without a prefix, a reader cannot
tell which method a given behaviour belongs to:

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ encodes an integer as the root value          ← which method?
 ✔ decodes a JSON array                           ← different method
 ✔ returns a JsonValidationError for invalid JSON ← third method
```

Prefix every method-specific test with the method name, using
PHP's own call syntax to signal what kind of member is being
tested:

- **`->method()`** — instance methods (the method is called
  through an object reference)
- **`::method()`** — static methods and the constructor (the
  method is called through the class name, or implicitly via
  `new`)

Class-level tests (namespace, class kind, published method set,
class constants) describe the class itself, not a specific
member — leave them **unprefixed**.

### Allowed

```php
// class-level - no prefix
#[TestDox('lives in the StusDevKit\\MissingBitsKit\\Json namespace')]
#[TestDox('exposes only encode(), decode() and validate() as public methods')]

// instance method - arrow prefix
#[TestDox('->encode() encodes an integer as the root value')]
#[TestDox('->getCode() returns the $code passed to the constructor')]

// static method - double-colon prefix
#[TestDox('::from() accepts any callable shape')]

// constructor - double-colon prefix (matches `new Foo(...)` syntax)
#[TestDox('::__construct() declares $code and $message as parameters in that order')]
```

### Not allowed

```php
#[TestDox('encodes an integer as the root value')]
// ↑ which method?

#[TestDox('encode() accepts named parameters')]
// ↑ bare form - is it static or instance?

#[TestDox('::encode() encodes an integer as the root value')]
// ↑ `::` on an instance method - the syntax is misleading
```

The output is then self-documenting — a reader scanning the
TestDox listing sees at a glance which member each line exercises:

```
StusDevKit\MissingBitsKit\Json\Json
 ✔ lives in the StusDevKit\MissingBitsKit\Json namespace
 ✔ exposes only encode(), decode() and validate() as public methods
 ✔ ->encode() encodes an integer as the root value
 ✔ ->decode() decodes a JSON array
 ✔ ->validate() returns a JsonValidationError for invalid JSON
```

### Tests that span multiple members

When a single test exercises two members together (typically a
round-trip or composed-call test), prefix with both:

```php
#[TestDox('->encode() and ->decode() are inverse operations')]
```

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

## Structure the test body as explain / setup / perform / test

Every test method body is divided into four labelled steps, in this
order, separated by a `// ---` divider line:

1. **explain your test** — prose explaining *why* this test exists.
   What contract is it pinning down? What would a failure mean?
   This is the test's docblock, lifted into the body so it reads
   inline with the code that proves the claim.
2. **setup your test** — build fixtures, expected values, input
   data, reflection handles. No calls to the code under test yet.
3. **perform the change** — exactly the call(s) under test.
   Separating this from setup means a reader can see the
   code-under-test at a glance, without hunting through fixture
   construction.
4. **test the results** — assertions. Everything above this line
   has built up the inputs and captured the outputs; this block
   converts them into pass/fail.

### Allowed

```php
#[TestDox('lives in the StusDevKit\\MissingBitsKit\\Json namespace')]
public function test_lives_in_the_expected_namespace(): void
{
    // ----------------------------------------------------------------
    // explain your test

    // the published namespace is part of the contract - callers
    // type-hint against the FQN, so moving it is a breaking
    // change that must go through a major version bump.

    // ----------------------------------------------------------------
    // setup your test

    $expected = 'StusDevKit\\MissingBitsKit\\Json';

    // ----------------------------------------------------------------
    // perform the change

    $actual = (new ReflectionClass(Json::class))->getNamespaceName();

    // ----------------------------------------------------------------
    // test the results

    $this->assertSame($expected, $actual);
}
```

A reader scanning the body sees the four beats as distinct visual
blocks. The "explain" block reads as the test's own docblock; the
"perform" block is the single call under test; the "test" block is
where the claim is enforced.

### Not allowed

```php
public function test_lives_in_the_expected_namespace(): void
{
    // the published namespace is part of the contract
    $expected = 'StusDevKit\\MissingBitsKit\\Json';
    $actual = (new ReflectionClass(Json::class))->getNamespaceName();
    $this->assertSame($expected, $actual);
}
```

The four beats are still there, but they're not visually separated.
A reader has to parse every line to work out which one is setup,
which one is the call under test, and which one is the assertion —
information the dividers make free.

### Exception-path tests

When the assertion is `$this->expectException(...)`, the call is
*setting an expectation* rather than *testing a result* — PHPUnit
checks the expectation when the test method returns. That means
the last block has a different label, and it lands **before**
"perform the change" because PHPUnit requires the expectation to
be registered before the call that throws:

```php
#[TestDox('::encode() throws JsonException on a circular reference')]
public function test_encode_throws_on_a_circular_reference(): void
{
    // ----------------------------------------------------------------
    // explain your test

    // json_encode() returns false (and sets an error) on a
    // circular reference. The wrapper forces JSON_THROW_ON_ERROR,
    // so the caller must see a JsonException instead of a
    // silently false-valued return.

    // ----------------------------------------------------------------
    // setup your test

    $input = [];
    $input['self'] = &$input;

    // ----------------------------------------------------------------
    // set test expectations

    $this->expectException(JsonException::class);

    // the exception message and code are produced by PHP's
    // json_encode() builtin - they are not part of our contract.

    // ----------------------------------------------------------------
    // perform the change

    Json::encode($input);
}
```

Use "set test expectations" for any assertion that registers a
check to be verified later (e.g. `expectException`,
`expectExceptionMessage`, `expectOutputString`). Use "test the
results" for assertions that inspect captured values directly
(`assertSame`, `assertNull`, `assertInstanceOf` on a `$actual`).

### Assert the exception message and code when they are ours

`expectException()` on its own only pins the exception class. The
message and code are part of the exception's contract too, and
an exception test must cover them **when they are ours**. The
decision hinges on who *wrote* the message and picked the code,
not on who declared the class:

**Ours — assert the full message AND the code.** Use
`expectExceptionMessage()` (full message, not a substring) and
`expectExceptionCode()` in this case.

- Our own exception class, thrown by our code
- A PHP / third-party exception class we throw ourselves with a
  message and code that we constructed (e.g. throwing
  `\InvalidArgumentException` with our own wording)
- An exception we caught from a foreign source and re-threw with
  our own message and code

**Not ours — assert the class only, and comment why.** Skip the
message and code assertions, and replace them with a short
inline comment in the "set test expectations" block naming the
foreign source and stating that the message and code are not
part of our contract. The comment makes the omission deliberate
rather than a gap.

- A PHP builtin that throws on its own (e.g. `\JsonException`
  from `json_encode()` / `json_decode()`)
- A third-party library exception we let propagate without
  decorating

The full-message match (not substring) for ours-category tests
means every wording change is visible in the diff. That is the
point — our wording is part of our contract, and silent drift in
the message our callers read is as much a regression as a silent
drift in a return value.

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
