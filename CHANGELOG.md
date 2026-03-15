# CHANGELOG

## Introduction

This is the CHANGELOG for [Stus's Dev Kit](https://github.com/stuartherbert/stusdevkit).

- This project uses [semantic versioning](./docs/01-Engineering-Standards/Versioning.md).
- This project uses [conventional commits](./docs/01-Engineering-Standards/Git-Commit-Messages.md).

## Auto-Built

This CHANGELOG is automatically compiled.

- run `make init` to setup the `git` hooks to auto-update this CHANGELOG.
- run `make changelog` to manually update this CHANGELOG.


## Upcoming Release

### Features


#### ExceptionsKit

- added InvalidArgumentException ([88fc3e7](https://github.com/stuartherbert/stusdevkit/commit/88fc3e7)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionsKit

- initial build of PHPUnit assertions API ([b9e9711](https://github.com/stuartherbert/stusdevkit/commit/b9e9711)) — Stuart Herbert <stuart@stuartherbert.com>

#### changelog-tool

- added initial changelog-tool ([9ec9113](https://github.com/stuartherbert/stusdevkit/commit/9ec9113)) — Stuart Herbert <stuart@stuartherbert.com>

### Bug Fixes


#### AssertionsKit

- Assert implementation now uses docblocks from parent interface ([b6d7ba1](https://github.com/stuartherbert/stusdevkit/commit/b6d7ba1)) — Stuart Herbert <stuart@stuartherbert.com>

### Documentation

- move CHANGELOG preamble into separate files, to improve readability ([53fdfb6](https://github.com/stuartherbert/stusdevkit/commit/53fdfb6)) — Stuart Herbert <stuart@stuartherbert.com>
- added initial README.md ([054e3bc](https://github.com/stuartherbert/stusdevkit/commit/054e3bc)) — Stuart Herbert <stuart@stuartherbert.com>
- explain phpstan template limitations on affected collection classes ([9604c11](https://github.com/stuartherbert/stusdevkit/commit/9604c11)) — Stuart Herbert <stuart@stuartherbert.com>

### Refactoring

- NullValueNotAllowedException is now part of the ExceptionsKit ([db9e182](https://github.com/stuartherbert/stusdevkit/commit/db9e182)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionsKit

- drop `assertThat()` - not really a public assert API ([8ae8b6a](https://github.com/stuartherbert/stusdevkit/commit/8ae8b6a)) — Stuart Herbert <stuart@stuartherbert.com>
- now throws InvalidArgumentException where appopriate ([525b7b1](https://github.com/stuartherbert/stusdevkit/commit/525b7b1)) — Stuart Herbert <stuart@stuartherbert.com>
