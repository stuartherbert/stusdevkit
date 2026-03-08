# DateTimeKit

A PHP library that provides `When` and `Now` classes for working with datetimes in CLI tools and HTTP API applications.

- `When` extends `DateTimeImmutable` with convenience methods for creating, extracting, modifying, and formatting datetimes.
- `Now` captures a fixed "current time" for consistent use throughout a request or process.

## Requirements

- PHP 8.5+

## Installation

```bash
composer require stusdevkit/datetimekit
```

## Quick Start

### Setting Up `Now`

Call `Now::init()` early in your app's bootstrap (e.g. in middleware or a front controller). Every subsequent call to `Now::now()` returns the same `When` value for the lifetime of the request.

```php
use StusDevKit\DateTimeKit\Now;

// in your bootstrap
Now::init();

// later, anywhere in your request handler
$when = Now::now(); // always the same When instance
```

### Creating `When` Instances

```php
use StusDevKit\DateTimeKit\When;

// from a date/time string
$when = new When('2026-03-05 14:30:00');

// from any DateTimeInterface, string, or UNIX timestamp
$when = When::from('2026-03-05 14:30:00');
$when = When::from(1772899800);
$when = When::from($someDateTimeImmutable);

// from a nullable input (returns null if null)
$when = When::maybeFrom($row['deleted_at']);

// with microsecond precision
$when = When::fromRealtime();
```

### Extracting Components

```php
$when = When::from('2026-03-05 14:30:45');

$when->getYear();        // 2026
$when->getMonthOfYear(); // 3
$when->getDayOfMonth();  // 5
$when->getHour();        // 14
$when->getMinutes();     // 30
$when->getSeconds();     // 45
```

### Modifying Dates and Times

All modifiers return a new `When` instance (immutable).

```php
$when = When::from('2026-03-05 14:30:00');

// replace individual components
$when->withYear(2027);
$when->withMonthOfYear(6);
$when->withDayOfMonth(15);
$when->withHour(9);
$when->withMinutes(0);

// replace multiple date parts at once
$when->withDate(year: 2027, month: 6);

// replace multiple time parts at once
$when->withTime(hour: 9, minutes: 0, seconds: 0);

// copy the date from another datetime
$when->withDateFrom($otherDateTime);

// copy the time from another datetime
$when->withTimeFrom($otherDateTime);

// use PHP relative modifiers (restricted to day-of-month)
$when->modifyDayOfMonth('first day');  // first day of the month
$when->modifyDayOfMonth('last day');   // last day of the month

// use PHP relative modifiers (restricted to time)
$when->modifyTime('+2 hours');
```

### Formatting for Output

DateTimeKit provides domain-specific formatters accessed via `asFormat()`.

```php
$when = When::from('2026-03-05 14:30:00');

// filesystem-friendly formats (sort naturally in ls output)
$when->asFormat()->filesystem()->yearMonth();                // "2026-03"
$when->asFormat()->filesystem()->date();                     // "2026-03-05"
$when->asFormat()->filesystem()->dateTime();                 // "20260305-143000"
$when->asFormat()->filesystem()->dateTimeAndMilliseconds();  // "20260305-143000-000"

// database storage
$when->asFormat()->database()->postgres();  // "2026-03-05T14:30:00+00:00"

// HTTP headers (RFC 9110)
$when->asFormat()->http()->rfc9110();  // "Thu, 05 Mar 2026 14:30:00 GMT"
```

### Custom Formatters

You can extend DateTimeKit with your own formatters using `formatWith()` and `formatUsing()`.

#### Group Formatters (`formatWith`)

Create a class that implements `WhenGroupFormatterInterface` to provide multiple related format methods. Pass the class name to `formatWith()` to get a fully-typed instance with IDE autocomplete.

```php
use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\When;

class WhenSlackFormatter implements WhenGroupFormatterInterface
{
    public function __construct(
        private readonly When $when,
    ) {
    }

    public function timestamp(): string
    {
        return $this->when->format('U') . '.000000';
    }

    public function threadId(): string
    {
        return $this->when->format('U.u');
    }
}

// usage
$when->formatWith(WhenSlackFormatter::class)->timestamp();
$when->formatWith(WhenSlackFormatter::class)->threadId();

// also works with Now
Now::formatWith(WhenSlackFormatter::class)->timestamp();
```

#### Single Formatters (`formatUsing`)

Create a class that implements `WhenSingleFormatterInterface` when you have an existing object that needs to format a `When`. Pass the instance to `formatUsing()` to get the formatted string directly.

```php
use StusDevKit\DateTimeKit\Formatters\WhenSingleFormatterInterface;
use StusDevKit\DateTimeKit\When;

class MyLogFormatter implements WhenSingleFormatterInterface
{
    public function formatWhen(When $when): string
    {
        return $when->format('Y-m-d H:i:s.v');
    }
}

// usage
$formatter = new MyLogFormatter();
$when->formatUsing($formatter);

// also works with Now
Now::formatUsing($formatter);
```

A class can implement both interfaces if it needs to support both calling styles.

#### Single Transformers (`transformUsing`)

Create a class that implements `WhenSingleTransformerInterface` when you need to convert a `When` into something other than a string (e.g. an array, an int, or a domain object).

```php
use StusDevKit\DateTimeKit\Formatters\WhenSingleTransformerInterface;
use StusDevKit\DateTimeKit\When;

class WhenToArrayTransformer implements WhenSingleTransformerInterface
{
    /**
     * @return array{year: int, month: int, day: int}
     */
    public function transformWhen(When $when): array
    {
        return [
            'year' => $when->getYear(),
            'month' => $when->getMonthOfYear(),
            'day' => $when->getDayOfMonth(),
        ];
    }
}

// usage
$transformer = new WhenToArrayTransformer();
$when->transformUsing($transformer);

// also works with Now
Now::transformUsing($transformer);
```

### Using `Now` in Your Application

```php
use StusDevKit\DateTimeKit\Now;

// get the current time as different types
$when      = Now::now();              // When instance
$timestamp = Now::asUnixTimestamp();  // int
$dateTime  = Now::asDateTimeImmutable(); // returns new DateTimeImmutable instance

// format for different domains via asFormat()
$dbField   = Now::asFormat()->database()->postgres();    // "2026-03-05T14:30:00+00:00"
$httpDate  = Now::asFormat()->http()->rfc9110();         // "Thu, 05 Mar 2026 14:30:00 GMT"
$fileDate  = Now::asFormat()->filesystem()->date();      // "2026-03-05"

// expand an optional parameter to a When
// returns Now if the input is null, otherwise converts the input
$when = Now::or($request->get('scheduled_at'));
```

### Test Clock Support

`Now` has built-in test clock support for deterministic testing.

```php
use StusDevKit\DateTimeKit\Now;

// freeze time to a known value
Now::setTestClock('2026-01-15 10:00:00');

// advance the clock
Now::modifyTestClock('+1 hour');
Now::addToTestClock('P1D');       // add 1 day
Now::subFromTestClock('PT30M');   // subtract 30 minutes
```

## License

BSD-3-Clause. See [LICENSE](LICENSE) for details.
