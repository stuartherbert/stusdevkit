# ChangelogTool

## Scope

ChangelogTool is a CLI tool that generates a `CHANGELOG.md` file from git history. It parses commit messages following the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) specification and groups them by release (git tag) and commit type.

## Features

- Generates a complete `CHANGELOG.md` from git tags and commit history
- Supports both `v1.0.0` and `1.0.0` tag formats
- Groups commits by type (features, bug fixes, etc.) using `.versionrc.json` configuration
- Groups commits by scope within each type
- Highlights breaking changes in a dedicated section per release
- Includes commit hash links and author attribution
- Supports pre-release tags (alpha, beta, rc)
- Runs locally and in CI environments (GitHub Actions, GitLab CI)

## Usage

```bash
# Generate CHANGELOG.md in project root
vendor/bin/changelog-tool

# Preview without writing
vendor/bin/changelog-tool --dry-run

# Custom output path
vendor/bin/changelog-tool --output docs/CHANGELOG.md

# Custom config path
vendor/bin/changelog-tool --config custom-versionrc.json
```

## Configuration

### `.versionrc.json`

Place in the project root. Maps commit types to changelog sections:

```json
{
    "types": [
        {"type": "feat", "section": "Features"},
        {"type": "fix", "section": "Bug Fixes"},
        {"type": "chore", "hidden": true},
        {"type": "docs", "hidden": true},
        {"type": "style", "hidden": true},
        {"type": "refactor", "hidden": true},
        {"type": "perf", "hidden": true},
        {"type": "test", "hidden": true}
    ]
}
```

### `.changelog.preamble.md`

Place in the project root. Contents are copied verbatim to the top of the generated `CHANGELOG.md`.
