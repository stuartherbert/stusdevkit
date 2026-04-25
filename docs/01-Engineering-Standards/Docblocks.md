# Docblocks

## Introduction

Docblocks and inline comments divide by scope: docblocks describe
the **external contract** (what the thing does, what it takes,
what it returns, what it throws, what its hazards are); inline
comments describe the **internal reasoning**
(see [Inline-Comments.md](Inline-Comments.md)).

For classes that are consumed through their **static methods** —
utility classes, inspector classes, factory classes — docblocks
carry extra load because the reader almost never sees the
class-level docblock.

## Method docblocks are load-bearing on utility classes

When a caller writes `ResolveParameters::forMethod(...)`, their
IDE's hover tooltip shows the docblock for **`forMethod`**, not
the docblock for `ResolveParameters`. The class-level docblock is
read only when:

- the reader is browsing the file directly, or
- the reader has navigated to the class definition deliberately.

Neither of those is the common case. The common case is a caller
one `::` away, scanning the hover.

**The consequence:** for utility classes exposed via static
methods, every piece of contract the caller needs must live in
the **method docblock**, not the class-level docblock. Pushing
load-bearing content up to the class level and pointing to it
from each method ("see class docblock for details") is an
ergonomic trap: the reader who needs the detail is also the
reader least likely to see the pointer.

This means **repetition across sibling methods is not a
violation**. If three factories return the same shape and inherit
the same hazards, each of their docblocks must say so in full —
even if that's three copies of the same two paragraphs. The
alternative — one canonical paragraph up at the class level, and
three pointers down at the methods — is DRY for the writer but
broken for the reader.

## Class-level docblocks in this pattern

When method docblocks carry the full contract, the class-level
docblock shifts role. It should carry:

- **origin / motivation** — why this class exists (always
  "Originally added for …", never speculation; see below);
- **navigation** — a short list of the public static methods, one
  line each, pointing readers at where the actual contract lives;
- **shared terms** — if the class introduces terminology used
  across multiple methods, defining those terms once up here (and
  referring to them from method docblocks) is fine, because terms
  are naming, not contract.

It should **not** carry:

- return-shape contracts;
- exception lists;
- hazards that apply to individual methods.

Those live on the methods they apply to.

## Motivation, not speculation

This principle is already in CLAUDE.md under Code Standards, but
it belongs here too because docblocks are where it most often
goes wrong:

- **Don't** claim what's "primary", "main", or "most common" —
  the author never has that information, and speculation ages
  badly.
- **Do** record what actually drove the code into existence,
  phrased as **"Originally added for ..."**.

"Originally added …" is factual, stays useful once other uses
emerge, and dates cleanly (readers understand it's the original
reason, not a claim about current usage).

## Here Be Dragons

Docblock warnings — hazards, caveats, unguaranteed behaviour,
non-obvious footguns — go under a `Here Be Dragons` section
heading (with an underline matching the heading length). Use colourful language inside
the section: "footgun", "silently wins", "will have you chasing
ghosts". The goal is to make the hazard land, not to be neutral.

When a class's hazards inherit from a lower-level collaborator
(e.g. `ResolveParameters::forMethod` inherits hazards from
`ResolveParameter::for`), the inheriting method's
`Here Be Dragons` section should **summarise the inherited
hazards inline** and then point readers at the source for the
full treatment. Do not assume the reader will follow the pointer
before it matters — give them enough context in the hover to know
whether the pointer applies to them.

## Relationship to inline comments

Inline comments and docblocks serve different scopes:

- **Docblocks** document the WHY of the **API shape** — external
  contract, read by callers.
- **Inline comments** document the WHY of the **implementation**
  — internal reasoning, read by maintainers.

Both follow the "tell them WHY" rule, but at different levels.
See [Inline-Comments.md](Inline-Comments.md) for the
inline-comment standard.

## Canonical example

- [kits/dependencykit/src/Reflection/ResolveParameters.php](../../kits/dependencykit/src/Reflection/ResolveParameters.php)
  — slim class-level docblock (origin + navigation),
  self-sufficient method docblocks (return shape +
  `Here Be Dragons` + `@throws`), inherited hazards summarised
  inline with a pointer to the source.
