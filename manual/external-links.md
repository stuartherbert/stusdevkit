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
| `ArrayIterator` | https://www.php.net/manual/en/class.arrayiterator.php |
| `Closure` | https://www.php.net/manual/en/class.closure.php |
| `Countable` | https://www.php.net/manual/en/class.countable.php |
| `get_class()` | https://www.php.net/manual/en/function.get-class.php |
| `gettype()` | https://www.php.net/manual/en/function.gettype.php |
| `IteratorAggregate` | https://www.php.net/manual/en/class.iteratoraggregate.php |
| `JSON_*` constants | https://www.php.net/manual/en/json.constants.php |
| `JsonException` | https://www.php.net/manual/en/class.jsonexception.php |
| `ReflectionClass` | https://www.php.net/manual/en/class.reflectionclass.php |
| `ReflectionFunction` | https://www.php.net/manual/en/class.reflectionfunction.php |
| `ReflectionFunctionAbstract` | https://www.php.net/manual/en/class.reflectionfunctionabstract.php |
| `ReflectionMethod` | https://www.php.net/manual/en/class.reflectionmethod.php |
| `ReflectionParameter` | https://www.php.net/manual/en/class.reflectionparameter.php |
| `ReflectionType` | https://www.php.net/manual/en/class.reflectiontype.php |
| `ReflectionNamedType` | https://www.php.net/manual/en/class.reflectionnamedtype.php |
| `ReflectionUnionType` | https://www.php.net/manual/en/class.reflectionuniontype.php |
| `ReflectionIntersectionType` | https://www.php.net/manual/en/class.reflectionintersectiontype.php |
| `stdClass` | https://www.php.net/manual/en/class.stdclass.php |
| `Stringable` | https://www.php.net/manual/en/class.stringable.php |
| `BackedEnum` | https://www.php.net/manual/en/enum.backedenum.php |
| `function_exists()` | https://www.php.net/manual/en/function.function-exists.php |
| `class_exists()` | https://www.php.net/manual/en/function.class-exists.php |
| `interface_exists()` | https://www.php.net/manual/en/function.interface-exists.php |
| `json_decode()` | https://www.php.net/manual/en/function.json-decode.php |
| `json_encode()` | https://www.php.net/manual/en/function.json-encode.php |
| `json_last_error()` | https://www.php.net/manual/en/function.json-last-error.php |
| `json_last_error_msg()` | https://www.php.net/manual/en/function.json-last-error-msg.php |
| `json_validate()` | https://www.php.net/manual/en/function.json-validate.php |
| `method_exists()` | https://www.php.net/manual/en/function.method-exists.php |
| `is_callable()` | https://www.php.net/manual/en/function.is-callable.php |
| `is_numeric()` | https://www.php.net/manual/en/function.is-numeric.php |
| `is_int()` | https://www.php.net/manual/en/function.is-int.php |
| `is_float()` | https://www.php.net/manual/en/function.is-float.php |
| `is_string()` | https://www.php.net/manual/en/function.is-string.php |
| `is_array()` | https://www.php.net/manual/en/function.is-array.php |
| `is_object()` | https://www.php.net/manual/en/function.is-object.php |
| `strrpos()` | https://www.php.net/manual/en/function.strrpos.php |
| `substr()` | https://www.php.net/manual/en/function.substr.php |
| `trait_exists()` | https://www.php.net/manual/en/function.trait-exists.php |
| `class_uses()` | https://www.php.net/manual/en/function.class-uses.php |
| `class_implements()` | https://www.php.net/manual/en/function.class-implements.php |
| `__invoke` | https://www.php.net/manual/en/language.oop5.magic.php#object.invoke |
| `__call` | https://www.php.net/manual/en/language.oop5.overloading.php#object.call |
| `__callStatic` | https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic |
| `Throwable` | https://www.php.net/manual/en/class.throwable.php |
| `Exception` | https://www.php.net/manual/en/class.exception.php |
| `JsonSerializable` | https://www.php.net/manual/en/class.jsonserializable.php |
| `Exception::getMessage()` | https://www.php.net/manual/en/exception.getMessage |
| `Exception::getCode()` | https://www.php.net/manual/en/exception.getCode |
| `Exception::getFile()` | https://www.php.net/manual/en/exception.getFile |
| `Exception::getLine()` | https://www.php.net/manual/en/exception.getLine |
| `Exception::getTrace()` | https://www.php.net/manual/en/exception.getTrace |
| `Exception::getTraceAsString()` | https://www.php.net/manual/en/exception.getTraceAsString |
| `Exception::getPrevious()` | https://www.php.net/manual/en/exception.getPrevious |
| `Exception::__toString()` | https://www.php.net/manual/en/exception.toString |
| `InvalidArgumentException` | https://www.php.net/manual/en/class.invalidargumentexception.php |
| `RuntimeException` | https://www.php.net/manual/en/class.runtimeexception.php |

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
