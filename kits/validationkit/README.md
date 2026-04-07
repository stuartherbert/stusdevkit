# ValidationKit for Stu's Dev Kit

A validation library for PHP 8.5+, with full PHPStan max-level support.

Part of Stu's Dev Kit: building blocks for assembling the things you need to build, in a way that will last.

## What Problem Are We Solving?

Every application needs to validate data at its boundaries: HTTP requests, API responses, SDK clients, configuration files, and database results.

ValidationKit uses a fluent builder API inspired by [Zod](https://zod.dev/), structured errors following [RFC 9457](https://www.rfc-editor.org/rfc/rfc9457) (Problem Details), and provides JSON Schema Draft 2020-12 round-trip support.

- **Composable schemas** that are built from code, not strings and arrays
- **Structured errors** that promote documenting each error
- **Strong type safety** and **code discoverability / inspectability**
- **Codecs** to use the same validator in API handlers _and_ SDK clients
- **JSON Schema interop** for importing and exporting validation rules

## Quick Start

```php
use StusDevKit\ValidationKit\Validate;

// define a schema
$userSchema = Validate::object([
    'name'  => Validate::string()->min(1)->max(100),
    'email' => Validate::string()->email(),
    'age'   => Validate::optional(
        Validate::int()->gte(0),
    ),
]);

// validate data — throws on failure
$user = $userSchema->parse($requestData);

// or validate without throwing
$result = $userSchema->safeParse($requestData);
if ($result->failed()) {
    $errors = $result->error()->issues();
    // handle errors
}
```

## Table of Contents

- [Getting Started](#getting-started)
- [Core Validation API](#core-validation-api)
- [Primitive Schemas](#primitive-schemas)
- [Collection Schemas](#collection-schemas)
- [Composition](#composition)
- [Custom Validation](#custom-validation)
- [Error Handling](#error-handling)
- [Coercion](#coercion)
- [Codecs](#codecs)
- [JSON Schema Integration](#json-schema-integration)
- [Advanced Features](#advanced-features)
- [Design Principles](#design-principles)

## Getting Started

To create a validator for your API handler:

```php
use StusDevsKit\ValidationKit\Validate;

// create a validator
$validator = Validate::object([
    'customer_id' => Validate::uuid(),
    'name' => Validate::string()->min(1)->max(100),
    'age' => Validate::int()->gte(18),
]);

// use it
// $data is the decoded JSON from the HTTP request body
//
// throws an exception if the data fails validation
$validator->parse($data);
```

## Core Validation API

Every schema provides two pairs of validation methods — one for decoding (incoming data) and one for encoding (outgoing data):

```php
// decoding: validate incoming data
$data = $schema->parse($input);       // throws on failure
$result = $schema->safeParse($input); // returns ParseResult

// aliases for parse() and safeParse()
$data = $schema->decode($input);
$result = $schema->safeDecode($input);

// encoding: validate outgoing data
// (skips withDefault() and withCatch() — data should
// already be valid when encoding)
$data = $schema->encode($input);
$result = $schema->safeEncode($input);
```

The decode/encode distinction matters when using [Codecs](#codecs) for bidirectional validation between API handlers and SDK clients.

### ParseResult

`safeParse()` returns a `ParseResult` object:

```php
$result = $schema->safeParse($input);

$result->succeeded();    // bool
$result->failed();       // bool
$result->data();         // validated data (throws if failed)
$result->maybeData();    // validated data or null
$result->error();        // ValidationException (throws if succeeded)
$result->maybeError();   // ValidationException or null
```

## Primitive Schemas

### Strings

```php
$name = Validate::string()
    ->min(1)
    ->max(100);

$email = Validate::string()->email();
$url = Validate::string()->url();
$uuid = Validate::string()->uuid();
```

**Length constraints:**

| Method | Description |
|--------|-------------|
| `min($length)` | Minimum character length |
| `max($length)` | Maximum character length |
| `length($length)` | Exact character length |

**Format constraints:**

| Method | Description |
|--------|-------------|
| `email()` | Valid email address |
| `url()` | Valid URL |
| `uuid()` | UUID v1-v8 |
| `ipv4()` | IPv4 address |
| `ipv6()` | IPv6 address |
| `date()` | Date (YYYY-MM-DD) |
| `time()` | Time (RFC 3339) |
| `dateTime()` | Date-time (RFC 3339) |
| `duration()` | ISO 8601 duration |
| `hostname()` | RFC 1123 hostname |
| `uriReference()` | URI reference (RFC 3986) |
| `idnEmail()` | Internationalised email (RFC 6531) |
| `idnHostname()` | Internationalised hostname (RFC 5890) |
| `iri()` | Internationalised Resource Identifier |
| `iriReference()` | IRI reference |
| `uriTemplate()` | URI template (RFC 6570) |
| `jsonPointer()` | JSON Pointer (RFC 6901) |
| `relativeJsonPointer()` | Relative JSON Pointer |
| `isRegex()` | Valid PCRE pattern |
| `password()` | Password (UI hint only, no validation) |

**Content constraints:**

| Method | Description |
|--------|-------------|
| `regex($pattern)` | Match a PCRE pattern (including delimiters) |
| `includes($needle)` | Must contain substring |
| `startsWith($prefix)` | Must start with prefix |
| `endsWith($suffix)` | Must end with suffix |

**Transforms (applied before constraints):**

| Method | Description |
|--------|-------------|
| `applyTrim()` | Trim whitespace |
| `applyToLowerCase()` | Convert to lowercase |
| `applyToUpperCase()` | Convert to uppercase |

### Integers

```php
$age = Validate::int()->gte(0)->lte(150);
$port = Validate::int()->gt(0)->lt(65536);
```

| Method | Description |
|--------|-------------|
| `gt($value)` | Greater than |
| `gte($value)` | Greater than or equal |
| `lt($value)` | Less than |
| `lte($value)` | Less than or equal |
| `positive()` | Shorthand for `gt(0)` |
| `negative()` | Shorthand for `lt(0)` |
| `nonNegative()` | Shorthand for `gte(0)` |
| `nonPositive()` | Shorthand for `lte(0)` |
| `multipleOf($value)` | Must be a multiple of the given value |
| `int32()` | Must fit in a 32-bit signed integer |
| `int64()` | Must fit in a 64-bit signed integer (no-op on 64-bit PHP) |

### Numbers (int or float)

```php
$score = Validate::number()->gte(0)->lte(100);
```

Same constraint methods as integers, plus:

| Method | Description |
|--------|-------------|
| `finite()` | Must not be INF or NAN |
| `float()` | Must fit in IEEE 754 single-precision |
| `double()` | Must fit in IEEE 754 double-precision (no-op in PHP) |

### Floats, Booleans, Null, Mixed

```php
$ratio = Validate::float()->gte(0.0)->lte(1.0);
$flag = Validate::boolean();
$nothing = Validate::null();
$anything = Validate::mixed();   // accepts any value including null
```

### Literals and Enums

```php
// exact value match
$active = Validate::literal(value: 'active');

// set of allowed values
$status = Validate::enum(valuesOrEnumClass: ['active', 'inactive', 'pending']);

// PHP backed enum
$role = Validate::enum(valuesOrEnumClass: UserRole::class);
```

## Collection Schemas

### Arrays

```php
$tags = Validate::array(
    element: Validate::string(),
);

// with constraints
$tags = Validate::array(
    element: Validate::string(),
)->min(1)->max(10)->uniqueItems();
```

| Method | Description |
|--------|-------------|
| `min($length)` | Minimum element count |
| `max($length)` | Maximum element count |
| `length($length)` | Exact element count |
| `notEmpty()` | Shorthand for `min(1)` |
| `uniqueItems()` | All elements must be unique |
| `contains($schema)` | At least one element must match |

### Objects

Validates `stdClass` objects with a defined shape:

```php
$address = Validate::object([
    'street' => Validate::string(),
    'city'   => Validate::string(),
    'zip'    => Validate::string()->regex('/^\d{5}$/'),
]);
```

### Associative Arrays

Same as objects but validates PHP associative arrays:

```php
$config = Validate::assocArray([
    'host' => Validate::string(),
    'port' => Validate::int()->positive(),
]);
```

### Shape Manipulation

Both object and associative array schemas support shape manipulation:

```php
// add fields
$withCountry = $address->extend([
    'country' => Validate::string(),
]);

// keep only specific fields
$cityOnly = $address->pick('city', 'zip');

// remove specific fields
$noZip = $address->omit('zip');

// make all fields optional
$patchAddress = $address->partial();

// make all fields required
$fullAddress = $patchAddress->required();
```

### Unknown Key Policies

By default, unknown keys are stripped. You can change this:

```php
// reject unknown keys (throws on extra properties)
$strict = $address->strict();

// keep unknown keys unchanged
$open = $address->passthrough();

// validate unknown keys against a schema
$withExtras = $address->catchall(
    schema: Validate::string(),
);
```

### Tuples

Fixed-length arrays with per-position schemas:

```php
$coordinate = Validate::tuple(schemas: [
    Validate::float(),   // latitude
    Validate::float(),   // longitude
]);

$coordinate->parse([51.5074, -0.1278]);
```

### Records

Variable-key maps with validated keys and values:

```php
$scores = Validate::record(
    key: Validate::string(),
    value: Validate::int()->gte(0),
);

$scores->parse(['alice' => 95, 'bob' => 87]);
```

## Composition

### Nullable and Optional

```php
// allows null
$name = Validate::nullable(
    schema: Validate::string(),
);
$name->parse('Stuart');  // 'Stuart'
$name->parse(null);      // null

// allows null (for object properties, also allows missing keys)
$bio = Validate::optional(
    schema: Validate::string(),
);

// allows null and missing keys
$notes = Validate::nullish(
    schema: Validate::string(),
);
```

### Union Types (anyOf, oneOf)

```php
// at least one schema must match
$stringOrInt = Validate::anyOf(schemas: [
    Validate::string(),
    Validate::int(),
]);

// exactly one schema must match
$exactlyOne = Validate::oneOf(schemas: [
    Validate::string(),
    Validate::int(),
]);
```

### Intersection (allOf)

All schemas must match. Useful for combining object schemas:

```php
$named = Validate::object([
    'name' => Validate::string(),
]);

$aged = Validate::object([
    'age' => Validate::int(),
]);

$person = Validate::allOf(schemas: [$named, $aged]);
$person->parse((object) ['name' => 'Stuart', 'age' => 42]);
```

### Negation

```php
$notNull = Validate::not(schema: Validate::null());
```

### Discriminated Unions

Efficiently select the correct schema based on a discriminator field:

```php
$shape = Validate::discriminatedAnyOf(
    discriminator: 'type',
    schemas: [
        Validate::object([
            'type'   => Validate::literal(value: 'circle'),
            'radius' => Validate::float()->positive(),
        ]),
        Validate::object([
            'type'  => Validate::literal(value: 'square'),
            'width' => Validate::float()->positive(),
        ]),
    ],
);
```

### Conditional (if/then/else)

```php
$schema = Validate::conditional(
    if: Validate::object([
        'type' => Validate::literal(value: 'premium'),
    ]),
    then: Validate::object([
        'tier' => Validate::string(),
    ]),
    else: Validate::object([
        'trial' => Validate::boolean(),
    ]),
);
```

### Recursive Schemas

Use `lazy()` for schemas that reference themselves:

```php
$treeNode = Validate::object([
    'value'    => Validate::string(),
    'children' => Validate::array(
        element: Validate::lazy(
            function () use (&$treeNode) {
                return $treeNode;
            },
        ),
    ),
]);
```

Note: arrow functions (`fn()`) capture by value and will not work here. Use a full closure with `use (&$var)` to capture by reference.

## Custom Validation

ValidationKit provides three levels of custom validation, from simplest to most powerful.

### Level 1: Inline Callables

For one-off checks, use `withCustomConstraint()` directly on a schema:

```php
$schema = Validate::string()->withCustomConstraint(
    fn(mixed $data) => $data !== 'forbidden'
        ? null
        : 'Value is forbidden',
);
```

The callable returns `null` on success or an error message string on failure.

For reusable callables, use `Validate::constraintFrom()`:

```php
$notEmpty = Validate::constraintFrom(
    fn(mixed $data) => $data === ''
        ? 'Must not be empty'
        : null,
);

// share across schemas
$name = Validate::string()->withConstraint($notEmpty);
$label = Validate::string()->withConstraint($notEmpty)->max(50);
```

### Level 2: SimpleConstraint

For reusable constraints with proper error type URIs, extend `SimpleConstraint`:

```php
use StusDevKit\ValidationKit\Constraints\SimpleConstraint;

final class NoForbiddenWords extends SimpleConstraint
{
    protected function getType(): string
    {
        return 'https://example.com/errors/forbidden-words';
    }

    protected function check(mixed $data): ?string
    {
        assert(is_string($data));
        return str_contains($data, 'forbidden')
            ? 'Contains forbidden words'
            : null;
    }
}

// usage
$schema = Validate::string()->withConstraint(new NoForbiddenWords());
```

Two methods to implement:
- `getType()` — returns the [RFC 9457](https://www.rfc-editor.org/rfc/rfc9457) type URI that identifies this validation error
- `check()` — returns `null` on success, an error message on failure

### Level 3: BaseConstraint

For constraints that need full control (multiple issues, custom type URIs per issue, access to the validation context):

```php
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;

final class PasswordStrength extends BaseConstraint
{
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        if (strlen($data) < 8) {
            $context->addIssue(
                type: 'https://example.com/errors/password-too-short',
                input: $data,
                message: 'Password must be at least 8 characters',
            );
        }

        if (! preg_match('/[A-Z]/', $data)) {
            $context->addIssue(
                type: 'https://example.com/errors/password-no-uppercase',
                input: $data,
                message: 'Password must contain an uppercase letter',
            );
        }

        return $data;
    }
}
```

### Custom Transforms

For inline transforms:

```php
$schema = Validate::string()->withCustomTransform(
    fn(mixed $data) => strtoupper($data),
);
```

For reusable transforms:

```php
$slugify = Validate::transformerFrom(
    fn(mixed $data) => strtolower(
        preg_replace('/[^a-z0-9]+/i', '-', $data),
    ),
);

$title = Validate::string()->withNormaliser($slugify);
```

For a full class, extend `BaseTransformer`:

```php
use StusDevKit\ValidationKit\Transformers\BaseTransformer;
use StusDevKit\ValidationKit\Internals\ValidationContext;

final class SlugifyTransformer extends BaseTransformer
{
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));
        return strtolower(
            preg_replace('/[^a-z0-9]+/i', '-', $data),
        );
    }
}
```

## Error Handling

Validation errors are structured following [RFC 9457](https://www.rfc-editor.org/rfc/rfc9457) (Problem Details for HTTP APIs).

### ValidationIssue

Each validation failure is a `ValidationIssue` with:

| Property | Description |
|----------|-------------|
| `type` | RFC 9457 URI identifying the error type |
| `message` | Human-readable description |
| `path` | Location in the data structure (e.g., `['address', 'zip']`) |
| `input` | The value that failed validation |

### Error Formatters

Three formatters are available for different use cases:

**Flat errors** — grouped by field, suitable for form validation:

```php
use StusDevKit\ValidationKit\ErrorFormatting\ErrorFormatter;

$result = $schema->safeParse($data);
if ($result->failed()) {
    $flat = ErrorFormatter::flatten($result->error());

    $flat->formErrors();    // ['Form-level error messages']
    $flat->fieldErrors();   // ['email' => ['Invalid email address']]
}
```

**Tree errors** — nested structure mirroring the data shape:

```php
$tree = ErrorFormatter::treeify($result->error());

$addressErrors = $tree->maybeChild('address');
$zipErrors = $addressErrors?->maybeChild('zip');
$zipErrors?->errors();  // ['Invalid ZIP code']
```

**Pretty-printed** — human-readable string for logging:

```php
$pretty = ErrorFormatter::prettify($result->error());
// "address.zip: Invalid ZIP code"
```

## Coercion

By default, each schema validates its native PHP type. Call `coerce()` to opt in to type conversion:

```php
$age = Validate::int()->coerce();
$age->parse('42');   // 42 (string coerced to int)
$age->parse(42.0);   // 42 (float with no fractional part)

$flag = Validate::boolean()->coerce();
$flag->parse('true');  // true
$flag->parse(1);       // true
```

| Schema | Native Type | `coerce()` Converts From |
|--------|-------------|--------------------------|
| `Validate::string()` | `string` | int, float, bool |
| `Validate::int()` | `int` | numeric strings, floats (no fractional part), bool |
| `Validate::float()` | `float` | numeric strings, int, bool |
| `Validate::number()` | `int\|float` | numeric strings (preserving type), bool |
| `Validate::boolean()` | `bool` | `"true"`, `"false"`, `"yes"`, `"no"`, `0`, `1`, etc. |
| `Validate::dateTime()` | `DateTimeInterface` | ISO 8601 strings, Unix timestamps |
| `Validate::uuid()` | `string` | UUID strings (normalisation) |

Without `coerce()`, non-native types are rejected. This keeps the boundary between validation and conversion explicit.

## Codecs

A Codec is a bidirectional schema that validates data in both directions — decoding incoming data into your domain types, and encoding your domain types back into a transport format. This is the pattern you need when the same validation rules are shared between an API handler (decoding requests) and an SDK client (encoding requests).

```php
$dateCodec = Validate::codec(
    input: Validate::string()->dateTime(),
    output: Validate::dateTime(),
    decode: fn(string $s) => new DateTimeImmutable($s),
    encode: fn(DateTimeInterface $dt) => $dt->format(DATE_RFC3339),
);

// API handler: decode incoming string → DateTimeImmutable
$date = $dateCodec->decode('2024-01-15T10:30:00Z');

// SDK client: encode DateTimeImmutable → string
$json = $dateCodec->encode(new DateTimeImmutable());
```

The four parameters:
- `input` — schema that validates the incoming (wire) format
- `output` — schema that validates the decoded (domain) type
- `decode` — transforms wire format → domain type
- `encode` — transforms domain type → wire format

Codecs support the same safe variants:

```php
$result = $dateCodec->safeDecode('2024-01-15T10:30:00Z');
$result = $dateCodec->safeEncode(new DateTimeImmutable());
```

## JSON Schema Integration

ValidationKit supports JSON Schema Draft 2020-12 import and export.

### Importing JSON Schema

```php
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Importer;

$json = <<<'JSON'
    {
        "type": "object",
        "properties": {
            "name": {"type": "string", "minLength": 1},
            "email": {"type": "string", "format": "email"}
        },
        "required": ["name", "email"]
    }
    JSON;

$importer = new JsonSchemaDraft202012Importer();
$schema = $importer->import(
    new JsonSchema(json_decode($json)),
);

$schema->parse((object) [
    'name'  => 'Stuart',
    'email' => 'stuart@example.com',
]);
```

### Exporting to JSON Schema

```php
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Exporter;

$schema = Validate::object([
    'name'  => Validate::string()->min(1),
    'email' => Validate::string()->email(),
]);

$exporter = new JsonSchemaDraft202012Exporter();
$jsonSchema = $exporter->export($schema);

echo json_encode($jsonSchema, JSON_PRETTY_PRINT);
```

### Round-Trip with `$ref` and `$defs`

To preserve `$ref` and `$defs` through an import/export round-trip, pass a shared `JsonSchemaRegistry`:

```php
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaRegistry;

$registry = new JsonSchemaRegistry();

// import — $defs are registered in the registry
$schema = $importer->import(
    jsonSchema: $jsonSchema,
    registry: $registry,
);

// export — $ref and $defs are preserved
$exported = $exporter->export(
    schema: $schema,
    registry: $registry,
);
```

### External References

To resolve `$ref` values that point to other documents, provide a `JsonSchemaLoader`:

```php
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaLoader;
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;

class FileSchemaLoader implements JsonSchemaLoader
{
    public function __construct(
        private readonly string $basePath,
    ) {}

    public function load(string $uri): ?JsonSchema
    {
        $path = $this->basePath . '/' . basename($uri);
        if (! file_exists($path)) {
            return null;
        }
        $decoded = json_decode(file_get_contents($path));
        if (! $decoded instanceof \stdClass) {
            return null;
        }
        return new JsonSchema($decoded);
    }
}

$loader = new FileSchemaLoader('/path/to/schemas');
$schema = $importer->import(
    jsonSchema: $jsonSchema,
    loader: $loader,
);
```

The importer supports:
- Absolute URIs (`$ref: "https://example.com/address.json"`)
- Relative URIs resolved against the root `$id` (`$ref: "address.json"`)
- Fragment references (`$ref: "types.json#/$defs/Street"`)
- Anchor references (`$ref: "types.json#street-type"`)
- Transitive and circular references (with caching)

## Advanced Features

### Default Values

```php
$schema = Validate::string()->withDefault('unknown');
$schema->parse(null);    // 'unknown'
$schema->parse('hello'); // 'hello'
```

Note: default values are NOT validated against the schema. They are applied when decoding (`parse()`/`decode()`) but not when encoding (`encode()`) — outgoing data should already have real values.

### Fallback on Failure

```php
$schema = Validate::int()->withCatch(0);
$schema->parse('not a number');  // 0 (fallback, no exception)
```

### Pipe (Schema Chaining)

Chain the output of one schema into another for multi-step validation:

```php
$stringToInt = Validate::string()
    ->withCustomTransform(fn($data) => (int) $data)
    ->withPipe(
        Validate::int()->gte(0),
    );

$stringToInt->parse('42');   // 42
$stringToInt->parse('-1');   // throws (fails gte: 0)
```

### Metadata

All schemas support metadata for documentation and code generation:

```php
$schema = Validate::string()
    ->withTitle('User Name')
    ->withDescription('The display name of the user')
    ->withExamples(['Stuart', 'Alice'])
    ->withDeprecated()
    ->withReadOnly();
```

Metadata is preserved through JSON Schema export.

## Design Principles

### Explicit Code Over Magic

ValidationKit avoids hidden behaviour. There are no `__call`, `__get`, or `__set` magic methods. Every validation rule is a real class that can be inspected, tested, and traced through your IDE.

### Accept the Native Type, Use `coerce()` for Conversions

Each schema validates its native PHP type by default. To accept other representations (e.g., strings from decoded JSON), call `coerce()` to opt in to type conversion. This keeps the boundary between validation and conversion explicit.

### Structured Errors

Validation errors follow [RFC 9457](https://www.rfc-editor.org/rfc/rfc9457) (Problem Details). Each error has a machine-readable `type` URI, a `path` identifying where in the data structure the error occurred, and a human-readable `message`. This makes errors actionable by both machines and humans.

### Immutable Schemas

All builder methods return a new schema instance. Schemas are safe to share and reuse:

```php
$base = Validate::string()->min(1);
$name = $base->max(100);   // $base is unchanged
$tag = $base->max(50);     // $base is still unchanged
```

## License

BSD-3-Clause. See the license header in any source file for full terms.
