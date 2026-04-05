# Welcome!

## What Is Stu's Dev Kit?

Stu's Dev Kit is a PHP framework with this mission:

- building blocks for assembling the things you need to build
- in a way that will last.

You're probably here to see the _what_. If you want to learn more about the _how_ and the _why_, head on over to the [Objectives](./docs/00-Introduction/Objectives.md) docs to learn more.

## Key Concepts

### Guiding Principles

Here are some of the principles that guides the design of Stu's Dev Kit.

- focus on ease of adoption
  - clear patterns for each problem area
  - full traceability through all code levels
  - closed for modification, open for extension
  - custom PHPStan rules to enforce coding conventions
- focus on long-term stability
  - PHP PSR-compliance where available
  - IETF RFC-compliance where available
- focus on ease-of-upgrade
  - focus on forward-compatible code
  - minimal third-party dependencies

We keep the full list over in our [Engineering Standards](./docs/00-Introduction/01-Engineering-Standards/).

### Kits And Tools

Stu's Dev Kit is released as a single package. To keep things organised and easy to maintain, everything is split up into separate sub-folders by type.

"Kits" are our name for our libraries. Each one has its own clear scope:

Kit Name       | Namespace | Description
---------------|-----------|------------
[CollectionsKit](./kits/collectionskit/README.md) | `StusDevKit\Collections` | Collection classes (dictionaries, lists and indexes)
[DateTimeKit](./kits/datetimekit/README.md) | `StusDevKit\DateTimeKit` | `Now` and `When`, with test clock support
[ExceptionsKit](./kits/exceptionskit/README.md) | `StusDevKit\ExceptionsKit` | Common exceptions built on an RFC-compliant Problem Details base.
[MissingBitsKit](./kits/missingbitskit/README.md) | `StusDevKit\MissingBitsKit` | Standalone functions that fill gaps in PHP's standard library

"Tools" are utilities that can be run from a Terminal. Each one has its own clear scope:

Command | Description
---------|------------
[changelog-tool](./tools/ChangelogTool/README.md) | Build the `CHANGELOG.md` from git commit messages.
