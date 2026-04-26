# GetClassInterfaces

Returns the complete set of interfaces implemented by a PHP class or interface.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetClassInterfaces
{
    /**
     * do we have a PHP interface? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * get a full list of interfaces implemented by a class
     */
    public static function from(string $className): array;
}
```

## Description

`GetClassInterfaces` maps a class or interface name to the set of
interfaces it implements. It is designed for use in type-inspection
pipelines where a caller needs to know which declared interfaces a
given class satisfies.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not a string naming a known class or
  interface. Uses [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php)
  and [`interface_exists()`](https://www.php.net/manual/en/function.interface-exists.php)
  to validate the name before delegating to `::from()`.
- [`::from()`](from.md) — accepts a class-string directly and returns
  its interface list. This is the fast path when the caller already
  knows the input is a valid class or interface name.

The return map uses fully qualified interface names as both keys and
values, in the order PHP reports them via [`class_implements()`](https://www.php.net/manual/en/function.class-implements.php).
This includes interfaces inherited from parent classes and interfaces
transitively extended by other interfaces (e.g. `BaseInterface` →
`ExtendedInterface`).

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-class-string values
- [`::from()`](from.md) — Static factory; accepts a class-string and returns its interface list

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassInterfaces.php:47`](../src/TypeInspectors/GetClassInterfaces.php#L47)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassHierarchy`](../GetClassHierarchy/README.md) — returns the full class hierarchy including parents
- [`GetClassTraits`](../GetClassTraits/README.md) — returns the traits used by a class
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level type inspection

## Issues

- [Open issues mentioning `GetClassInterfaces`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassInterfaces%22)
- [Closed issues mentioning `GetClassInterfaces`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassInterfaces%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassInterfaces%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
