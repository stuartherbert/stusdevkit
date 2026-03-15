# AssertionsKit

## Acknowledgements

Based on the PHPUnit v13 `assertXXX()` method signatures and docblocks.

[PHPUnit](https://phpunit.de) is [copyright (c) 2001-2026, Sebastian Bergmann; released under the 3-Claude BSD license](https://github.com/sebastianbergmann/phpunit/blob/main/LICENSE).

All implementation errors and bugs are mine.

## Scope

AssertionsKit provides an assertions library for enforcing robustness, correctness and type-safety in PHP libraries and applications.

## Why Another Assertions Library?

Here's why I've built my own library:

- I wanted the assert methods to follow the (expected, actual) parameter convention.
- I'm so used to PHPUnit's familiar `assertXXX()` API, I'd love to have a library of those methods.
- I wanted assert exceptions that contain the information that I need for my production application logs.

### Parameter Convention

Code assertions are basically tests that are embedded in your production code. And great tests always check an actual result against an expected result.

I find that this approach comes naturally when using PHPUnit's `assertXXX()` methods.

### A Familiar API

I've been using PHPUnit for such a long time now, the `assertXXX()` methods have become somewhat ingrained. It's a familiar API, and it works well for how I use assertions in my code.

I want to be able to use the API that I'm used to.

### Useful Exceptions

Stu's Dev Kit exists so that I can build production-quality apps and tools for myself and my clients.

I place a big emphasis on robustness and correctness in my engineering approach ... which inevitably means catching and reporting exceptions. It's not enough to just log the exception name; the application logs also need to contain useful data to show why the exception happened.

The [Assert](Assert.php) class throws two types of exceptions:

- an [InvalidArgumentException](../exceptionskit/src/Exceptions/InvalidArgumentException.php) if there's a problem with the method parameters,
- an [AssertionFailedException](./src/Exceptions/AssertionFailedException.php) if the assertion itself fails.

Both exceptions contain additional data to show why they failed.

### Why Not Just Use PHPUnit Itself?

There's a few reasons why:

- PHPUnit assertions are not available as a separate, installable package.
- PHPUnit is a dev tool. It's not meant to go anywhere near your production servers.
- PHPUnit moves too quickly to fit into the ethos of Stu's Dev Kit.

None of these are criticisms of PHPUnit or Sebastian's work. We're just building tools to solve different problems for different situations.

## Public Assertion Methods

### Arrays

| Method | Description |
|--------|-------------|
| `assertArrayHasKey()` | Assert that an array has a given key |
| `assertArrayNotHasKey()` | Assert that an array does not have a given key |
| `assertIsList()` | Assert that an array is a list (sequential integer keys from 0) |
| `assertArrayIsEqualToArrayOnlyConsideringListOfKeys()` | Assert arrays are equal, comparing only specified keys |
| `assertArrayIsEqualToArrayIgnoringListOfKeys()` | Assert arrays are equal, ignoring specified keys |
| `assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys()` | Assert arrays are identical, comparing only specified keys |
| `assertArrayIsIdenticalToArrayIgnoringListOfKeys()` | Assert arrays are identical, ignoring specified keys |
| `assertArraysAreIdentical()` | Assert two arrays are identical (same types and order) |
| `assertArraysAreIdenticalIgnoringOrder()` | Assert two arrays are identical regardless of order |
| `assertArraysHaveIdenticalValues()` | Assert two arrays have identical values |
| `assertArraysHaveIdenticalValuesIgnoringOrder()` | Assert two arrays have identical values regardless of order |
| `assertArraysAreEqual()` | Assert two arrays are equal |
| `assertArraysAreEqualIgnoringOrder()` | Assert two arrays are equal regardless of order |
| `assertArraysHaveEqualValues()` | Assert two arrays have equal values |
| `assertArraysHaveEqualValuesIgnoringOrder()` | Assert two arrays have equal values regardless of order |

### Contains

| Method | Description |
|--------|-------------|
| `assertContains()` | Assert that a haystack contains a needle |
| `assertContainsEquals()` | Assert that a haystack contains an equal needle |
| `assertNotContains()` | Assert that a haystack does not contain a needle |
| `assertNotContainsEquals()` | Assert that a haystack does not contain an equal needle |
| `assertContainsOnlyArray()` | Assert that all values are arrays |
| `assertContainsOnlyBool()` | Assert that all values are booleans |
| `assertContainsOnlyCallable()` | Assert that all values are callables |
| `assertContainsOnlyFloat()` | Assert that all values are floats |
| `assertContainsOnlyInt()` | Assert that all values are integers |
| `assertContainsOnlyIterable()` | Assert that all values are iterables |
| `assertContainsOnlyNull()` | Assert that all values are null |
| `assertContainsOnlyNumeric()` | Assert that all values are numeric |
| `assertContainsOnlyObject()` | Assert that all values are objects |
| `assertContainsOnlyResource()` | Assert that all values are resources |
| `assertContainsOnlyClosedResource()` | Assert that all values are closed resources |
| `assertContainsOnlyScalar()` | Assert that all values are scalars |
| `assertContainsOnlyString()` | Assert that all values are strings |
| `assertContainsOnlyInstancesOf()` | Assert that all values are instances of a given class |
| `assertContainsNotOnlyArray()` | Assert that not all values are arrays |
| `assertContainsNotOnlyBool()` | Assert that not all values are booleans |
| `assertContainsNotOnlyCallable()` | Assert that not all values are callables |
| `assertContainsNotOnlyFloat()` | Assert that not all values are floats |
| `assertContainsNotOnlyInt()` | Assert that not all values are integers |
| `assertContainsNotOnlyIterable()` | Assert that not all values are iterables |
| `assertContainsNotOnlyNull()` | Assert that not all values are null |
| `assertContainsNotOnlyNumeric()` | Assert that not all values are numeric |
| `assertContainsNotOnlyObject()` | Assert that not all values are objects |
| `assertContainsNotOnlyResource()` | Assert that not all values are resources |
| `assertContainsNotOnlyClosedResource()` | Assert that not all values are closed resources |
| `assertContainsNotOnlyScalar()` | Assert that not all values are scalars |
| `assertContainsNotOnlyString()` | Assert that not all values are strings |
| `assertContainsNotOnlyInstancesOf()` | Assert that not all values are instances of a given class |

### Count

| Method | Description |
|--------|-------------|
| `assertCount()` | Assert that a countable has a given count |
| `assertNotCount()` | Assert that a countable does not have a given count |
| `assertSameSize()` | Assert that two countable values have the same size |
| `assertNotSameSize()` | Assert that two countable values do not have the same size |

### Equality

| Method | Description |
|--------|-------------|
| `assertEquals()` | Assert that two values are equal |
| `assertEqualsCanonicalizing()` | Assert equality after canonicalizing both values |
| `assertEqualsIgnoringCase()` | Assert string equality ignoring case |
| `assertEqualsWithDelta()` | Assert numeric equality within a given delta |
| `assertNotEquals()` | Assert that two values are not equal |
| `assertNotEqualsCanonicalizing()` | Assert non-equality after canonicalizing both values |
| `assertNotEqualsIgnoringCase()` | Assert string non-equality ignoring case |
| `assertNotEqualsWithDelta()` | Assert numeric non-equality within a given delta |
| `assertSame()` | Assert that two variables refer to the same object or have the same value and type |
| `assertNotSame()` | Assert that two variables do not refer to the same object |
| `assertObjectEquals()` | Assert that two objects are equal using a custom equals method |
| `assertObjectNotEquals()` | Assert that two objects are not equal using a custom equals method |

### Empty

| Method | Description |
|--------|-------------|
| `assertEmpty()` | Assert that a variable is empty |
| `assertNotEmpty()` | Assert that a variable is not empty |

### Comparison

| Method | Description |
|--------|-------------|
| `assertGreaterThan()` | Assert that a value is greater than another |
| `assertGreaterThanOrEqual()` | Assert that a value is greater than or equal to another |
| `assertLessThan()` | Assert that a value is less than another |
| `assertLessThanOrEqual()` | Assert that a value is less than or equal to another |

### Boolean

| Method | Description |
|--------|-------------|
| `assertTrue()` | Assert that a condition is true |
| `assertNotTrue()` | Assert that a condition is not true |
| `assertFalse()` | Assert that a condition is false |
| `assertNotFalse()` | Assert that a condition is not false |

### Null

| Method | Description |
|--------|-------------|
| `assertNull()` | Assert that a variable is null |
| `assertNotNull()` | Assert that a variable is not null |

### Numbers

| Method | Description |
|--------|-------------|
| `assertFinite()` | Assert that a value is finite |
| `assertInfinite()` | Assert that a value is infinite |
| `assertNan()` | Assert that a value is NaN |

### Types

| Method | Description |
|--------|-------------|
| `assertInstanceOf()` | Assert that a variable is an instance of a given class |
| `assertNotInstanceOf()` | Assert that a variable is not an instance of a given class |
| `assertIsArray()` | Assert that a variable is an array |
| `assertIsNotArray()` | Assert that a variable is not an array |
| `assertIsBool()` | Assert that a variable is a boolean |
| `assertIsNotBool()` | Assert that a variable is not a boolean |
| `assertIsCallable()` | Assert that a variable is callable |
| `assertIsNotCallable()` | Assert that a variable is not callable |
| `assertIsClosedResource()` | Assert that a variable is a closed resource |
| `assertIsNotClosedResource()` | Assert that a variable is not a closed resource |
| `assertIsFloat()` | Assert that a variable is a float |
| `assertIsNotFloat()` | Assert that a variable is not a float |
| `assertIsInt()` | Assert that a variable is an integer |
| `assertIsNotInt()` | Assert that a variable is not an integer |
| `assertIsIterable()` | Assert that a variable is iterable |
| `assertIsNotIterable()` | Assert that a variable is not iterable |
| `assertIsNumeric()` | Assert that a variable is numeric |
| `assertIsNotNumeric()` | Assert that a variable is not numeric |
| `assertIsObject()` | Assert that a variable is an object |
| `assertIsNotObject()` | Assert that a variable is not an object |
| `assertIsResource()` | Assert that a variable is a resource |
| `assertIsNotResource()` | Assert that a variable is not a resource |
| `assertIsScalar()` | Assert that a variable is a scalar |
| `assertIsNotScalar()` | Assert that a variable is not a scalar |
| `assertIsString()` | Assert that a variable is a string |
| `assertIsNotString()` | Assert that a variable is not a string |

### Objects

| Method | Description |
|--------|-------------|
| `assertObjectHasProperty()` | Assert that an object has a given property |
| `assertObjectNotHasProperty()` | Assert that an object does not have a given property |

### Regular Expressions

| Method | Description |
|--------|-------------|
| `assertMatchesRegularExpression()` | Assert that a string matches a regular expression |
| `assertDoesNotMatchRegularExpression()` | Assert that a string does not match a regular expression |

### Strings

| Method | Description |
|--------|-------------|
| `assertStringContainsString()` | Assert that a string contains a substring |
| `assertStringContainsStringIgnoringCase()` | Assert that a string contains a substring, ignoring case |
| `assertStringNotContainsString()` | Assert that a string does not contain a substring |
| `assertStringNotContainsStringIgnoringCase()` | Assert that a string does not contain a substring, ignoring case |
| `assertStringStartsWith()` | Assert that a string starts with a given prefix |
| `assertStringStartsNotWith()` | Assert that a string does not start with a given prefix |
| `assertStringEndsWith()` | Assert that a string ends with a given suffix |
| `assertStringEndsNotWith()` | Assert that a string does not end with a given suffix |
| `assertStringContainsStringIgnoringLineEndings()` | Assert that a string contains a substring, ignoring line endings |
| `assertStringEqualsStringIgnoringLineEndings()` | Assert that two strings are equal, ignoring line endings |
| `assertStringMatchesFormat()` | Assert that a string matches a format string |
| `assertStringMatchesFormatFile()` | Assert that a string matches a format file |
| `assertStringEqualsFile()` | Assert that a string equals the contents of a file |
| `assertStringEqualsFileCanonicalizing()` | Assert string equals file contents after canonicalizing |
| `assertStringEqualsFileIgnoringCase()` | Assert string equals file contents ignoring case |
| `assertStringNotEqualsFile()` | Assert that a string does not equal the contents of a file |
| `assertStringNotEqualsFileCanonicalizing()` | Assert string does not equal file contents after canonicalizing |
| `assertStringNotEqualsFileIgnoringCase()` | Assert string does not equal file contents ignoring case |

### Files and Directories

| Method | Description |
|--------|-------------|
| `assertFileExists()` | Assert that a file exists |
| `assertFileDoesNotExist()` | Assert that a file does not exist |
| `assertFileIsReadable()` | Assert that a file is readable |
| `assertFileIsNotReadable()` | Assert that a file is not readable |
| `assertFileIsWritable()` | Assert that a file is writable |
| `assertFileIsNotWritable()` | Assert that a file is not writable |
| `assertIsReadable()` | Assert that a file or directory is readable |
| `assertIsNotReadable()` | Assert that a file or directory is not readable |
| `assertIsWritable()` | Assert that a file or directory is writable |
| `assertIsNotWritable()` | Assert that a file or directory is not writable |
| `assertDirectoryExists()` | Assert that a directory exists |
| `assertDirectoryDoesNotExist()` | Assert that a directory does not exist |
| `assertDirectoryIsReadable()` | Assert that a directory is readable |
| `assertDirectoryIsNotReadable()` | Assert that a directory is not readable |
| `assertDirectoryIsWritable()` | Assert that a directory is writable |
| `assertDirectoryIsNotWritable()` | Assert that a directory is not writable |
| `assertFileEquals()` | Assert that two files have equal contents |
| `assertFileEqualsCanonicalizing()` | Assert two files have equal contents after canonicalizing |
| `assertFileEqualsIgnoringCase()` | Assert two files have equal contents ignoring case |
| `assertFileNotEquals()` | Assert that two files do not have equal contents |
| `assertFileNotEqualsCanonicalizing()` | Assert two files do not have equal contents after canonicalizing |
| `assertFileNotEqualsIgnoringCase()` | Assert two files do not have equal contents ignoring case |
| `assertFileMatchesFormat()` | Assert that a file matches a format string |
| `assertFileMatchesFormatFile()` | Assert that a file matches a format file |

### XML

| Method | Description |
|--------|-------------|
| `assertXmlFileEqualsXmlFile()` | Assert that two XML files are equal |
| `assertXmlFileNotEqualsXmlFile()` | Assert that two XML files are not equal |
| `assertXmlStringEqualsXmlFile()` | Assert that an XML string equals an XML file |
| `assertXmlStringNotEqualsXmlFile()` | Assert that an XML string does not equal an XML file |
| `assertXmlStringEqualsXmlString()` | Assert that two XML strings are equal |
| `assertXmlStringNotEqualsXmlString()` | Assert that two XML strings are not equal |

### JSON

| Method | Description |
|--------|-------------|
| `assertJson()` | Assert that a string is valid JSON |
| `assertJsonStringEqualsJsonString()` | Assert that two JSON strings are equal |
| `assertJsonStringNotEqualsJsonString()` | Assert that two JSON strings are not equal |
| `assertJsonStringEqualsJsonFile()` | Assert that a JSON string equals the contents of a JSON file |
| `assertJsonStringNotEqualsJsonFile()` | Assert that a JSON string does not equal the contents of a JSON file |
| `assertJsonFileEqualsJsonFile()` | Assert that two JSON files are equal |
| `assertJsonFileNotEqualsJsonFile()` | Assert that two JSON files are not equal |
