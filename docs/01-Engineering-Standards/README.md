# Engineering Standards for Stu's Dev Kit

## Purpose

This folder contains the engineering standards that govern how code is written,
tested, documented, and versioned across all kits in StusDevKit. These are not
suggestions — they are the rules we follow so that every file in this mono-repo
reads like it was written by a single disciplined author.

Originally added to capture tribal knowledge about coding conventions before
that knowledge walked out the door when people left projects. The standards
evolve as the codebase grows, and changes are committed alongside the code they
affect.

## Available Standards

| Standard | What It Covers |
|----------|----------------|
| [Asserts.md](./Asserts.md) | When and why to use `Assert::*` calls — regression guards for opaque APIs, contract documentation, developer-facing error messages |
| [Backwards-Compatibility.md](./Backwards-Compatibility.md) | Our backwards-compatibility policy: Linux-kernel style, bug fixes may break BC within a major version |
| [Docblocks.md](./Docblocks.md) | How to write class and method docblocks — external contract only, load-bearing on utility classes, "Here Be Dragons" for hazards |
| [Enums.md](./Enums.md) | Enum conventions: UPPER_SNAKE_CASE cases, no static factories that inspect external state, backed enums must implement `StaticallyArrayable` |
| [Git-Commit-Messages.md](./Git-Commit-Messages.md) | Conventional Commits format for commit messages — Features, Bug Fixes, Refactoring, Documentation |
| [Inline-Comments.md](./Inline-Comments.md) | How to write inline comments inside method bodies — the WHY of implementation, narrative shape, marker vocabulary |
| [Tests-Are-Documentation.md](./Tests-Are-Documentation.md) | Test writing conventions — TestDox output is the specification, factual sentences, four-block test structure, pin sets not counts |
| [Versioning.md](./Versioning.md) | Semantic versioning rules for releases — MAJOR for BC breaks, MINOR for features, PATCH for bug fixes |

## How to Use This Folder

### For Developers Reading Code

When you encounter a coding question while reading or modifying code in this
project:

1. **Identify the topic** — is it about assertions, docblocks, tests, enums,
   commit messages, inline comments, backwards compatibility, or versioning?
2. **Read the relevant standard file** before making a decision. Don't guess
   from another project's conventions or your personal preferences.
3. **When in doubt, check first** — it's better to read an irrelevant standard
   than to miss a relevant one.

### For AI Agents (Including This One)

This folder is authoritative for all engineering decisions. When working on code
in this project:

- **Read the full list of files** at the start of every session using:
  ```bash
  ls docs/01-Engineering-Standards/*.md 2>/dev/null
  ```
- **Read all standard files** at the start of every session. The directory
  contents may grow over time — always discover rather than assume a fixed set.
- **Before working on any topic**, read the relevant standard file first:

  | Topic | Read This First |
  |-------|-----------------|
  | Writing or reviewing `Assert::*` calls | [Asserts.md](./Asserts.md) |
  | Writing docblocks (especially for utility classes) | [Docblocks.md](./Docblocks.md) |
  | Writing inline comments inside methods | [Inline-Comments.md](./Inline-Comments.md) |
  | Writing or reviewing tests | [Tests-Are-Documentation.md](./Tests-Are-Documentation.md) |
  | Creating or modifying enums | [Enums.md](./Enums.md) |
  | Making a commit message | [Git-Commit-Messages.md](./Git-Commit-Messages.md) |
  | Bumping version numbers | [Versioning.md](./Versioning.md) |
  | Discussing backwards compatibility | [Backwards-Compatibility.md](./Backwards-Compatibility.md) |

- **Never ignore a standard** because "it seems reasonable to do it differently."
  The standards exist for consistency across years of code, not convenience.

### For Contributors Adding New Standards

When the project needs a new engineering standard:

1. Create the file in this folder with a clear, descriptive name (use hyphens
   or underscores consistently — follow existing naming patterns).
2. Write it using the same structure as other standards: introduction, rules,
   examples, and canonical references to code in the repo.
3. Update this README's table of contents so the new standard is discoverable.
4. Commit alongside the first piece of code that exercises the new standard.
