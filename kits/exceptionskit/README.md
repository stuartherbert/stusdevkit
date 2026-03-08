# ErrorsKit for Stu's Dev Kit

An [RFC 9457](https://www.rfc-editor.org/rfc/rfc9457) problem details exception for PHP 8.5+.

Part of Stu's Dev Kit: building blocks for assembling the things you need to build, in a way that will last.

## Installation

```bash
composer require stusdevkit/errorskit
```

## Usage

### Rfc9457ProblemDetailsException

`Rfc9457ProblemDetailsException` is a PHP Exception that carries structured error data following the [RFC 9457 Problem Details](https://www.rfc-editor.org/rfc/rfc9457) standard. It implements `JsonSerializable`, making it straightforward to return as an API error response.

#### Constructor Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `$type` | `non-empty-string` | Yes | URI to a page that documents this class of problem (e.g. a wiki page explaining the error) |
| `$status` | `int` | Yes | HTTP status code that describes the nature of this problem (typically 4xx or 5xx) |
| `$title` | `non-empty-string` | Yes | Short, human-readable summary of the problem type |
| `$extra` | `array` | No | Additional data specific to this occurrence of the problem |
| `$detail` | `non-empty-string\|null` | No | Human-readable explanation specific to this occurrence |
| `$instance` | `non-empty-string\|null` | No | Absolute URI to a resource specific to this occurrence |
| `$previous` | `Throwable\|null` | No | The previous exception, if any |

The exception message is set to `$detail` if provided, otherwise `$title`.

#### Basic Example

```php
use StusDevKit\ExceptionsKit\Rfc9457ProblemDetailsException;

throw new Rfc9457ProblemDetailsException(
    type: 'https://example.com/errors/insufficient-funds',
    status: 403,
    title: 'Insufficient funds',
    detail: 'Your account balance of 10.00 is less than the required 25.00',
);
```

#### With Extra Data and Instance URI

```php
throw new Rfc9457ProblemDetailsException(
    type: 'https://example.com/errors/validation-error',
    status: 422,
    title: 'Validation error',
    extra: [
        'field' => 'email',
        'rejected_value' => 'not-an-email',
    ],
    detail: 'The email field must be a valid email address',
    instance: 'https://example.com/account/settings',
);
```

#### Wrapping a Previous Exception

```php
try {
    $db->query($sql);
} catch (\PDOException $e) {
    throw new Rfc9457ProblemDetailsException(
        type: 'https://example.com/errors/database-error',
        status: 500,
        title: 'Database error',
        previous: $e,
    );
}
```

#### JSON Serialization

The exception implements `JsonSerializable`, so you can convert it directly to a JSON response:

```php
try {
    // ... application logic
} catch (Rfc9457ProblemDetailsException $e) {
    header('Content-Type: application/problem+json');
    http_response_code($e->getStatus());
    echo json_encode($e);
}
```

This produces JSON following the RFC 9457 structure:

```json
{
    "type": "https://example.com/errors/insufficient-funds",
    "title": "Insufficient funds",
    "status": 403,
    "instance": null,
    "detail": "Your account balance of 10.00 is less than the required 25.00",
    "extra": []
}
```

You can pass the result through `array_filter()` to strip out null/empty fields:

```php
echo json_encode(array_filter($e->jsonSerialize()));
```

#### Available Getters

| Method | Returns | Description |
|--------|---------|-------------|
| `getTypeAsString()` | `string` | The problem type URI |
| `getStatus()` | `int` | The HTTP status code |
| `getTitle()` | `string` | The problem title |
| `hasDetail()` | `bool` | Whether a detail message was provided |
| `maybeGetDetail()` | `string\|null` | The detail message, or null |
| `hasExtra()` | `bool` | Whether extra data was provided |
| `getExtra()` | `array` | The extra data array |
| `hasInstance()` | `bool` | Whether an instance URI was provided |
| `maybeGetInstanceAsString()` | `string\|null` | The instance URI, or null |

## License

BSD-3-Clause. See the license header in any source file for full terms.
