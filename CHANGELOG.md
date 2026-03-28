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


#### ValidationKit

- initial code commit ([319c6f6](https://github.com/stuartherbert/stusdevkit/commit/319c6f6)) — Stuart Herbert <stuart@stuartherbert.com>
- added placeholder ([0dc3ba3](https://github.com/stuartherbert/stusdevkit/commit/0dc3ba3)) — Stuart Herbert <stuart@stuartherbert.com>

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

- improve README for AssertionsKit ([38713af](https://github.com/stuartherbert/stusdevkit/commit/38713af)) — Stuart Herbert <stuart@stuartherbert.com>
- move CHANGELOG preamble into separate files, to improve readability ([53fdfb6](https://github.com/stuartherbert/stusdevkit/commit/53fdfb6)) — Stuart Herbert <stuart@stuartherbert.com>
- added initial README.md ([054e3bc](https://github.com/stuartherbert/stusdevkit/commit/054e3bc)) — Stuart Herbert <stuart@stuartherbert.com>
- explain phpstan template limitations on affected collection classes ([9604c11](https://github.com/stuartherbert/stusdevkit/commit/9604c11)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionKit

- add list of available methods ([7962911](https://github.com/stuartherbert/stusdevkit/commit/7962911)) — Stuart Herbert <stuart@stuartherbert.com>

### Refactoring

- NullValueNotAllowedException is now part of the ExceptionsKit ([db9e182](https://github.com/stuartherbert/stusdevkit/commit/db9e182)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidationKit

- handle optional and nullable better ([cb5691d](https://github.com/stuartherbert/stusdevkit/commit/cb5691d)) — Stuart Herbert <stuart@stuartherbert.com>
- remove duplicate code ([10c3cae](https://github.com/stuartherbert/stusdevkit/commit/10c3cae)) — Stuart Herbert <stuart@stuartherbert.com>
- make schemas extensible via withConstraint() method ([03adc7d](https://github.com/stuartherbert/stusdevkit/commit/03adc7d)) — Stuart Herbert <stuart@stuartherbert.com>
- move default typeCheckError callback definitions into methods, which can be overridden by child classes if required ([06406c5](https://github.com/stuartherbert/stusdevkit/commit/06406c5)) — Stuart Herbert <stuart@stuartherbert.com>
- DateTimeSchema is now DateTimeInterfaceSchema ([5207876](https://github.com/stuartherbert/stusdevkit/commit/5207876)) — Stuart Herbert <stuart@stuartherbert.com>
- move validators for built-in PHP object types into their own namespace ([86941c7](https://github.com/stuartherbert/stusdevkit/commit/86941c7)) — Stuart Herbert <stuart@stuartherbert.com>
- bring PHP builtins validators into a common namespace ([863da42](https://github.com/stuartherbert/stusdevkit/commit/863da42)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionsKit

- Assert interface is now AssertApi, to reduce confusion ([2a160b7](https://github.com/stuartherbert/stusdevkit/commit/2a160b7)) — Stuart Herbert <stuart@stuartherbert.com>
- drop `assertThat()` - not really a public assert API ([ecda25e](https://github.com/stuartherbert/stusdevkit/commit/ecda25e)) — Stuart Herbert <stuart@stuartherbert.com>
- now throws InvalidArgumentException where appopriate ([525b7b1](https://github.com/stuartherbert/stusdevkit/commit/525b7b1)) — Stuart Herbert <stuart@stuartherbert.com>
