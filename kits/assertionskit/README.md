# AssertionsKit

## Acknowledgements

Based on the PHPUnit v13 `assertXXX()` method signatures and docblocks.

[PHPUnit](https://phpunit.de) is [copyright (c) 2001-2026, Sebastian Bergmann; released under the 3-Claude BSD license](https://github.com/sebastianbergmann/phpunit/blob/main/LICENSE).

## Scope

AssertionsKit provides an assertions library for enforcing robustness, correctness and type-safety in PHP libraries and applications.

## Why Another Assertions Library?

Here's why I've built my own library:

- I wanted the assert methods to follow the (expected, actual) parameter convention.
- I'm so used to PHPUnit's familiar `assertXXX()` API, I'd love to have a library of those methods
- I wanted assert methods that throw exceptions that I find useful.
- I want to be able to add strong type-checking / type hints.

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

### Assertions That Support Type Safety

Because it's a scripted language with no compile stage, PHP itself only checks things like parameter types at runtime. Historically, that meant you needed 100% code coverage to catch silly mistakes like passing the wrong object into a function/method.

Clients are rarely willing to pay for the time and effort involved to achieve 100% code coverage. Time-to-market is often more important to a business than outright quality. I saw this shift begin in the Dot Com boom at the turn of the millennium, and it's only getting stronger as the decades go by.

Why do I mention this?

Over the last decade, the PHP community has adopted static analysis tools (I use [PHPStan](https://phpstan.org) for Stu's Dev Kit). These tools catch type-mismatch errors, so that we don't have to write unit tests.

These tools rely on the docblocks and inline comments in our code, and in the packages we pull in as dependencies. When that's not quite enough, we use assertion libraries to:

- catch type mismatches in production,
- and perform type-narrowing for static analysis tools like PHPStan.

The language server processes (LSP for short) in our IDEs _also_ do their own forms of type-checking. They work the same way, but sometimes (most of the time?) they have an independent implementation.

At the time of writing, the LSP that I use can make sense of the types used by the PHPUnit assertXXX() API ... but cannot make sense of the types used by other popular assertion libraries.

That's a problem for me, because even though the code is correct, my IDE is still full of warnings and errors. That makes it impossible for me to spot genuine bugs quickly and easily, and confuses the heck out of any AI tool that integrates with my LSP/IDE of choice.
