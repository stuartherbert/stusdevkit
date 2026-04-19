# ResolveParameter::for()

> `public static for(ReflectionParameter $refParam, ContainerInterface $container): mixed`

Retrieve a value for the given parameter, using its type, from the
given [PSR-11](https://www.php-fig.org/psr/psr-11/) DI container.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\DependencyKit\Reflection\ResolveParameter`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\DependencyKit\Reflection;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\DependencyKit\Exceptions\UntypedParameterException;

class ResolveParameter
{
    /**
     * @return mixed
     *   the value retrieved from the DI container
     *
     * @throws UntypedParameterException
     * @throws UnsupportedParameterTypeException
     * @throws UnresolvedParameterException
     */
    public static function for(
        ReflectionParameter $refParam,
        ContainerInterface $container,
    ): mixed
}
```

## Description

`for()` reads the parameter's declared type via
[`ReflectionParameter`](https://www.php.net/manual/en/class.reflectionparameter.php),
probes `$container` for a registered service whose id matches that
type, and returns the match. When the container has nothing, the
resolver falls back to whatever the parameter declaration itself
allows: a declared default first, then `null` for a nullable
parameter, and finally an
[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)
if neither applies.

The container is probed in two passes:

1. **First pass** — each type name from the declared type, as-is.
   For a union `A|B`, both members are tried in the order PHP reports
   them. For a nullable `?Foo` / `Foo|null`, the literal key `'null'`
   is skipped (nullability is handled later via
   [`ReflectionParameter::allowsNull()`](https://www.php.net/manual/en/class.reflectionparameter.php)).
2. **Second pass** — each class-type member is expanded to its
   parent classes, interfaces, and traits, and the container is
   probed against each ancestor id. Types already probed in the
   first pass are not re-probed.

If `$container->get()` itself throws, the exception propagates
unchanged. The resolver deliberately detects "not found" using the
container's [`has()`](https://www.php-fig.org/psr/psr-11/)
predicate rather than catching an exception, so a real
[PSR-11](https://www.php-fig.org/psr/psr-11/) failure raised while
resolving a sub-dependency is never mistaken for a missing entry.

**Deliberately Out Of Scope**

The following features are **not** part of this resolver's
contract, and PRs adding them should be discussed and consciously
accepted before being merged — not slipped in as "obvious next
steps". The resolver is intentionally minimal so that its behaviour
stays predictable for many years; each feature below trades some of
that predictability away.

- **Attribute-driven overrides (`#[Inject('foo')]` and friends).**
  The resolver consults the declared type and nothing else. It
  deliberately does not read attributes off the parameter, the
  method, or the declaring class. Callers who need attribute-driven
  behaviour should build it in a layer above this resolver, where
  the policy is explicit and inspectable.
- **Named-parameter lookup.** The resolver looks up container ids
  by type name, never by parameter name. A parameter `string $dsn`
  is not resolved by asking the container for `"dsn"`. Name-based
  DI is powerful but couples the caller's local variable naming to
  the container's key namespace, and makes rename refactors quietly
  unsafe. If you need name-based lookup, write an explicit factory.
- **Autowiring of unregistered classes.** If a class type is not
  registered in the container (directly or via one of its
  ancestors), the resolver does not attempt to recursively
  construct it. Implicit construction hides dependency graphs from
  the container configuration, which is exactly where they should
  be visible. Register the class, or write a factory.
- **Caching of resolved values or reflection output.** The resolver
  re-runs reflection on every call and re-probes the container
  every time. Callers who need caching should cache the *result*
  of a full [`ResolveParameters::forFunction()`](../ResolveParameters/forFunction.md)
  / [`ResolveParameters::forMethod()`](../ResolveParameters/forMethod.md)
  / [`ResolveParameters::forConstructor()`](../ResolveParameters/forConstructor.md)
  / [`ResolveParameters::forCallable()`](../ResolveParameters/forCallable.md)
  pass at their own layer, where the cache key and invalidation
  strategy are explicit. Per-parameter caching inside the resolver
  would couple its lifetime to the container's and introduce subtle
  staleness bugs.
- **Scalar-value resolution by convention (env vars, config keys,
  etc.).** A parameter `string $apiKey` is resolved by asking the
  container for the id `"string"`, not by looking up
  `$_ENV['API_KEY']` or reading a config file. Convention-based
  scalar resolution is a separate concern and belongs in a
  dedicated config-binding layer.

If a use case here feels essential, the right move is to open the
discussion at the kit level before touching this class — not to
widen the resolver's behaviour and hope nobody notices.

## Parameters

**`$refParam`** ([`ReflectionParameter`](https://www.php.net/manual/en/class.reflectionparameter.php))

The parameter you need a value for. PHP reflection has already
resolved `self` and `parent` keywords to real class names by the
time this method sees the parameter, so those are treated as
ordinary class types.

**`$container`** ([`ContainerInterface`](https://www.php-fig.org/psr/psr-11/))

The [PSR-11](https://www.php-fig.org/psr/psr-11/) DI container to
search for the value. The resolver only uses
[`has()`](https://www.php-fig.org/psr/psr-11/) and
[`get()`](https://www.php-fig.org/psr/psr-11/); container ids are
always type names (never parameter names).

## Return Values

Returns the value retrieved from the DI container. When the
container has no match, returns the parameter's declared default
value; otherwise, if the parameter is nullable (including `mixed`
and standalone `null`), returns `null`. If none of those apply, the
method throws rather than returning.

## Errors/Exceptions

- **[`UntypedParameterException`](../../Exceptions/UntypedParameterException.md)**
  — when the parameter has no declared type.
- **[`UnsupportedParameterTypeException`](../../Exceptions/UnsupportedParameterTypeException.md)**
  — when the parameter is variadic, or has an intersection type
  (including any intersection branch of a DNF type such as
  `(A&B)|C`).
- **[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)**
  — when the container has no match for the parameter's declared
  type and no fallback is available (no default value, not
  nullable).

## Here Be Dragons

**Union-type resolution order is best-effort, not a contract.**

When a parameter is declared with a union type such as `A|B`, the
obvious expectation is "try `A` first, then `B`". We do our best to
honour that: the resolver takes the member order from the text
representation PHP produces for the union, which — on current PHP
versions — preserves declaration order for **class-only** unions.

However:

- For **scalar-only** unions (e.g. `int|string`) and **mixed**
  unions (e.g. `stdClass|int`), PHP normalises the order at parse
  time, so declaration order is already lost before we see it. The
  resolver picks whatever order PHP reports, which is deterministic
  on a given PHP version but does not match what the developer
  wrote.
- That text-based ordering is **not a cross-version guarantee**. A
  future PHP release could in principle change how it canonicalises
  unions, and the resolution order would shift with it.

If the resolution order of a union-typed parameter is load-bearing
for your application, **do not rely on this**. Either avoid union
types on DI-injected parameters, or write a dedicated factory that
makes the choice explicit in your own code.

**Registering a service under the key `'object'` is a universal
class-type fallback.**

For any class-typed parameter that misses its own hierarchy lookup,
the resolver's second pass eventually probes the container for the
literal key `'object'` — because `object` is a real PHP type and a
class value legitimately satisfies it. This means that if you
register a service under `$container->set('object', …)`, **every**
class-typed DI resolution in your codebase that otherwise would
fail falls back to that service. That may be exactly what you want
(a deliberate "default object" for `object $x` parameters) — or it
may be an accidental catch-all that silently swallows real
resolution bugs.

Rule of thumb: do not register anything under the key `'object'`
unless you mean for it to act as a universal class-type fallback,
and you accept that any missed resolution elsewhere in the codebase
will silently pick it up.

**[`NotFoundExceptionInterface`](https://www.php-fig.org/psr/psr-11/)
from `$container->get()` is indistinguishable from the resolver's
own
[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)
at a [PSR-11](https://www.php-fig.org/psr/psr-11/) catch site.**

[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)
deliberately implements
[`NotFoundExceptionInterface`](https://www.php-fig.org/psr/psr-11/)
so that callers who only speak
[PSR-11](https://www.php-fig.org/psr/psr-11/) can treat "no
fallback available" as the same kind of failure as any other
container miss. The cost of that choice is that a
[PSR-11](https://www.php-fig.org/psr/psr-11/)-compliant exception
thrown by the container while resolving a sub-dependency of a
matched id will *also* implement
[`NotFoundExceptionInterface`](https://www.php-fig.org/psr/psr-11/),
and a naive
`catch (NotFoundExceptionInterface $e)` cannot tell the two apart.
One of them is "the resolver ran out of fallbacks"; the other is
"the container blew up mid-resolve". Those are very different bugs,
and conflating them will have you chasing ghosts.

If you need to tell the two cases apart, catch
[`UnresolvedParameterException`](../../Exceptions/UnresolvedParameterException.md)
**first**, and only then fall through to a broader
[`NotFoundExceptionInterface`](https://www.php-fig.org/psr/psr-11/)
handler.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\DependencyKit\Reflection\ResolveParameter
 ✔ ::for() is declared
 ✔ ::for() is public
 ✔ ::for() is static
 ✔ ::for() declares a `mixed` return type
 ✔ ::for() declares $refParam and $container as parameters in that order
 ✔ ::for() declares $refParam as ReflectionParameter
 ✔ ::for() declares $container as ContainerInterface
 ✔ ::for() throws UntypedParameterException when the parameter has no declared type
 ✔ ::for() prefers the untyped refusal over the variadic refusal when a parameter is both
 ✔ ::for() throws UnsupportedParameterTypeException for a variadic parameter, with paramType formatted as "Type ..."
 ✔ ::for() throws UnsupportedParameterTypeException for a variadic parameter with a union type
 ✔ ::for() returns the container-registered value when the type matches
 ✔ ::for() resolves a `mixed` typed parameter by exact-string container lookup
 ✔ ::for() resolves a `object` typed parameter by exact-string container lookup
 ✔ ::for() resolves a `callable` typed parameter by exact-string container lookup
 ✔ ::for() resolves a `iterable` typed parameter by exact-string container lookup
 ✔ ::for() resolves a `true` typed parameter by exact-string container lookup
 ✔ ::for() resolves a `false` typed parameter by exact-string container lookup
 ✔ ::for() returns null for a `mixed` parameter when the container has no match, because mixed is implicitly nullable
 ✔ ::for() returns null for a standalone `null` typed parameter without probing the container
 ✔ ::for() lets a RuntimeException thrown by $container->get() propagate unchanged
 ✔ ::for() throws UnresolvedParameterException carrying paramName and paramType for an unresolved class type
 ✔ ::for() throws UnresolvedParameterException for an unresolved `int` typed parameter
 ✔ ::for() throws UnresolvedParameterException for an unresolved `string` typed parameter
 ✔ ::for() throws UnresolvedParameterException for an unresolved `float` typed parameter
 ✔ ::for() throws UnresolvedParameterException for an unresolved `bool` typed parameter
 ✔ ::for() throws UnresolvedParameterException for an unresolved `true` typed parameter
 ✔ ::for() throws UnresolvedParameterException for an unresolved `false` typed parameter
 ✔ ::for() throws UnresolvedParameterException for an unresolved `iterable` typed parameter
 ✔ ::for() throws UnsupportedParameterTypeException for an intersection type
 ✔ ::for() throws UnsupportedParameterTypeException for a DNF type with any intersection branch
 ✔ ::for() throws UnresolvedParameterException carrying paramName and paramType for an unresolved union type
 ✔ ::for() throws UnresolvedParameterException carrying the enum FQCN for an enum-typed parameter with no container match
 ✔ ::for() resolves a `self` typed parameter by looking up the declaring class in the container
 ✔ ::for() resolves a `parent` typed parameter by looking up the parent class in the container
 ✔ ::for() does not re-probe the declared type when falling through to the second-pass hierarchy walk
 ✔ ::for() never probes the container for the literal key "null" when resolving a nullable parameter
 ✔ ::for() returns null when the parameter is nullable and the container has no match
 ✔ ::for() prefers the container match over the null fallback
 ✔ ::for() returns the declared default value when the container has no match
 ✔ ::for() prefers the container match over the declared default value
 ✔ ::for() prefers the declared default value over the null fallback
```

## Source

[`kits/dependencykit/src/Reflection/ResolveParameter.php:197`](../../../../kits/dependencykit/src/Reflection/ResolveParameter.php#L197)

## Changelog

_No tagged releases yet._

## See Also

- [`ResolveParameters::forFunction()`](../ResolveParameters/forFunction.md)
  — resolve all parameters for a function or [`Closure`](https://www.php.net/manual/en/class.closure.php)
- [`ResolveParameters::forMethod()`](../ResolveParameters/forMethod.md)
  — resolve all parameters for an object or class method
- [`ResolveParameters::forConstructor()`](../ResolveParameters/forConstructor.md)
  — resolve all parameters for a class's constructor
- [`ResolveParameters::forCallable()`](../ResolveParameters/forCallable.md)
  — resolve all parameters for any PHP `callable`

## Issues

- [Open issues mentioning `ResolveParameter::for()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ResolveParameter%3A%3Afor%22)
- [Closed issues mentioning `ResolveParameter::for()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ResolveParameter%3A%3Afor%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ResolveParameter%3A%3Afor%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
