# Match Over If/Else

## Introduction

Where the code is dispatching on a finite set of values — most often the
cases of an enum, but also things like type names, status codes, or any
other closed enumeration — use a `match()` expression rather than an
`if / elseif / else` ladder.

## The rule

When the decision being made is **"which of these N known options is
this?"**, write it as `match()`:

```php
return match (GetArrayShape::from($input)) {
    ArrayShape::LIST => self::fromArrayListInternal($input, $seen),
    ArrayShape::MAP  => self::fromArrayMapInternal($input, $seen),
};
```

Not as:

```php
$shape = GetArrayShape::from($input);

if ($shape === ArrayShape::LIST) {
    return self::fromArrayListInternal($input, $seen);
} elseif ($shape === ArrayShape::MAP) {
    return self::fromArrayMapInternal($input, $seen);
}
```

**Why:** `match()` is **forwards-robust**. The day a new case is added
to the enum, every `match()` over that enum that does not handle the new
case fatals at runtime with `UnhandledMatchError`. That failure mode is
loud, immediate, and points straight at the dispatcher that needs
updating. The equivalent `if / elseif / else` ladder silently falls
through to whichever branch happens to be last — usually `else`, which
quietly returns the wrong answer instead of raising the alarm.

This matters more than it looks. Adding an enum case is exactly the kind
of change that touches one file and ripples through twenty, and the
ripples are usually invisible until something downstream breaks. Forcing
the dispatcher to error on the spot is the cheapest possible way to
surface those ripples at the moment the change is made.

`match()` also uses strict comparison (`===`), so it cannot be tripped
by the loose-comparison surprises that `switch` and untyped equality
sometimes deliver.

## When this applies

Use `match()` whenever the discriminator is:

- an **enum** value (the dominant case)
- a **class-string** out of a known set
- a **status code** or other closed scalar enumeration

If the branches need more than a single expression, extract each into
its own method and call it from the arm — see the example above, where
each arm calls a dedicated `fromArray*Internal` helper. That extraction
is a feature, not a workaround: it gives each branch a self-documenting
name.

## When this does NOT apply

`match()` is the wrong tool when:

- The decision is a **single boolean** (`if ($input->isValid())`). Use
  `if`.
- The conditions are **complex predicates** that cannot be expressed as
  equality against a discriminator
  (`if ($order->total > 100 && $order->isUrgent())`). Use `if`.
- The branches have **side effects mid-condition** (rare, but it
  happens). `match()` is an expression — its arms produce values.

In short: `match()` is for **dispatch**. `if` is for **predicates**.

## Default arms are a smell

Avoid `default => ...` arms over an enum discriminator. The whole
forwards-robustness argument depends on a missing case being a fatal
error; a `default` branch turns that fatal into silent fall-through and
defeats the point of using `match()` in the first place.

Allowed when the discriminator is genuinely open (a class-string from
user input, an HTTP method name, etc.) and a fallback is the right
semantic.

### Allowed

```php
// closed enumeration - exhaustive arms, no default
return match ($shape) {
    ArrayShape::LIST => $this->renderList(),
    ArrayShape::MAP  => $this->renderMap(),
};
```

```php
// open enumeration - default is a real fallback, not a "just in case"
return match ($httpMethod) {
    'GET', 'HEAD' => $this->readHandler(),
    'POST', 'PUT', 'PATCH' => $this->writeHandler(),
    default => throw new UnsupportedMethodException($httpMethod),
};
```

### Not allowed

```php
// silent fall-through hides the unhandled case
return match ($shape) {
    ArrayShape::LIST => $this->renderList(),
    default => $this->renderMap(),  // wrong - new cases collapse to MAP
};
```

```php
// match() pretending to be if/else - the discriminator is a boolean
return match (true) {
    $order->total > 100 => $this->premiumHandler(),
    default => $this->standardHandler(),
};
```
