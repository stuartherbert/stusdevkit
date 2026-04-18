# External Links

Lookup table for well-known types and functions referenced from the
manual pages. The `generate-manual-page` skill reads this file when
generating cross-links to code outside the StusDevKit repo, so it
never has to guess URLs.

If a canonical URL changes, update it here — every page regenerated
after that point picks up the correction. The skill must not invent
URLs; if a referenced type is not in this table, the skill's
pre-flight report flags it and asks the author to add an entry.

## PHP built-ins

| Name | URL |
|------|-----|
| `Closure` | https://www.php.net/manual/en/class.closure.php |
| `ReflectionClass` | https://www.php.net/manual/en/class.reflectionclass.php |
| `ReflectionFunction` | https://www.php.net/manual/en/class.reflectionfunction.php |
| `ReflectionFunctionAbstract` | https://www.php.net/manual/en/class.reflectionfunctionabstract.php |
| `ReflectionMethod` | https://www.php.net/manual/en/class.reflectionmethod.php |
| `ReflectionParameter` | https://www.php.net/manual/en/class.reflectionparameter.php |
| `function_exists()` | https://www.php.net/manual/en/function.function-exists.php |
| `class_exists()` | https://www.php.net/manual/en/function.class-exists.php |
| `method_exists()` | https://www.php.net/manual/en/function.method-exists.php |
| `is_callable()` | https://www.php.net/manual/en/function.is-callable.php |
| `is_string()` | https://www.php.net/manual/en/function.is-string.php |
| `is_array()` | https://www.php.net/manual/en/function.is-array.php |
| `is_object()` | https://www.php.net/manual/en/function.is-object.php |
| `__invoke` | https://www.php.net/manual/en/language.oop5.magic.php#object.invoke |
| `__call` | https://www.php.net/manual/en/language.oop5.overloading.php#object.call |
| `__callStatic` | https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic |

## PSR standards

| Name | URL |
|------|-----|
| PSR-11 | https://www.php-fig.org/psr/psr-11/ |
| `Psr\Container\ContainerInterface` | https://www.php-fig.org/psr/psr-11/ |
| `Psr\Container\NotFoundExceptionInterface` | https://www.php-fig.org/psr/psr-11/ |
| `Psr\Container\ContainerExceptionInterface` | https://www.php-fig.org/psr/psr-11/ |

## Third-party libraries

| Name | URL |
|------|-----|
| `Ramsey\Uuid\UuidInterface` | https://uuid.ramsey.dev/en/stable/ |
| `Ramsey\Uuid\Uuid` | https://uuid.ramsey.dev/en/stable/ |
