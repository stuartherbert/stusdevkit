# CHANGELOG

## How To Read This Changelog

### How To Rebuild This Changelog

Run `make changelog` to rebuild this CHANGELOG.md from the Git commit messages.

### Version Numbers

This project uses [semantic versioning](https://semver.org): version numbers like 2.1.5 (aka `MAJOR`-`MINOR`-`PATCH`).

The `MAJOR` number is bumped when:

- there is a backwards-compatibility break.

**We specifically avoid "marketing bumps" (bumping the `MAJOR` number to promote a new feature, or to play the parity game with other frameworks).**

The `MINOR` number is bumped when:

- a new feature is added to the package.

The `PATCH` number is bumped when:

- we fix a bug in an existing feature.

### Types Of Change

This project uses [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/) to describe the changes:

- `Features` are new functionality added in that release
- `Bug Fixes` are fixes to existing functionality in a previous release
- `Refactoring` documents internal changes that we've done
- `Documentation` lists the ways we're improving the information we produce to developers and maintainers.

The full mapping of Git commit messages is controlled by [.versionrc.json](.versionrc.json).

## Upcoming Release

### Features


#### changelog-tool

- added initial changelog-tool ([9ec9113](https://github.com/stuartherbert/stusdevkit/commit/9ec9113)) — Stuart Herbert <stuart@stuartherbert.com>

### Documentation

- added initial README.md ([3cae8b9](https://github.com/stuartherbert/stusdevkit/commit/3cae8b9)) — Stuart Herbert <stuart@stuartherbert.com>
- explain phpstan template limitations on affected collection classes ([9604c11](https://github.com/stuartherbert/stusdevkit/commit/9604c11)) — Stuart Herbert <stuart@stuartherbert.com>

### Refactoring

- NullValueNotAllowedException is now part of the ExceptionsKit ([db9e182](https://github.com/stuartherbert/stusdevkit/commit/db9e182)) — Stuart Herbert <stuart@stuartherbert.com>
