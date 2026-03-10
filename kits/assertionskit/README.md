# AssertionsKit

## Scope

AssertionsKit provides interfaces (contracts) that describe assertion methods, along with a concrete implementation that delegates to PHPUnit. Its primary purpose is to define a common contract for assertion behaviour, allowing different assertion providers to implement the same API.

## What Belongs Here

- Interfaces describing assertion method signatures
- Contracts for assertion providers
- The concrete `Assert` implementation that delegates to PHPUnit

## What Does Not Belong Here

- Test helpers or utilities
