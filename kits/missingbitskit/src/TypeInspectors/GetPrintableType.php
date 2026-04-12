<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
// Copyright (c) 2015-2026 Ganbaro Digital Ltd
// Copyright (c) 2026-present Stuart Herbert
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
//
//   * Re-distributions of source code must retain the above copyright
//     notice, this list of conditions and the following disclaimer.
//
//   * Redistributions in binary form must reproduce the above copyright
//     notice, this list of conditions and the following disclaimer in
//     the documentation and/or other materials provided with the
//     distribution.
//
//   * Neither the names of the copyright holders nor the names of his
//     contributors may be used to endorse or promote products derived
//     from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
// BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
// LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
// CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
// ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Closure;

/**
 * GetPrintableType will tell you the PHP data type of any given input data.
 *
 * The output is controlled by passing a bitmask of `FLAG_*` constants as
 * `$options`. Combine flags with `|` (e.g.
 * `GetPrintableType::FLAG_CLASSNAME | GetPrintableType::FLAG_SCALAR_VALUE`).
 */
class GetPrintableType
{
    /**
     * use this flag value for minimum output
     */
    public const int FLAG_NONE = 0;

    /**
     * set this flag to see classnames in the return value
     */
    public const int FLAG_CLASSNAME = 1;

    /**
     * set this flag to see what kind of callable the input item is
     */
    public const int FLAG_CALLABLE_DETAILS = 2;

    /**
     * set this flag to see the value of the given input item
     */
    public const int FLAG_SCALAR_VALUE = 4;

    /**
     * the default set of flags when the caller doesn't supply one
     */
    public const int FLAG_DEFAULTS = self::FLAG_CLASSNAME
        | self::FLAG_CALLABLE_DETAILS
        | self::FLAG_SCALAR_VALUE;

    /**
     * what PHP type is $item?
     *
     * @param  mixed $item
     *         the data to examine
     * @param  int $options
     *         a bitmask of `self::FLAG_*` constants - controls what extra
     *         detail (classnames, callable shape, scalar values) appears
     *         in the return value
     * @return string
     *         the data type of $item
     */
    public static function from(
        mixed $item,
        int $options = self::FLAG_DEFAULTS
    ): string
    {
        // objects can also be callables, so we want to handle these
        // first
        if (is_object($item)) {
            return self::returnObjectType($item, $options);
        }

        // now it's safe to handle callables
        if (is_callable($item)) {
            return self::returnCallableType($item, $options);
        }

        // this should catch pretty much everything else
        if (is_scalar($item)) {
            return self::returnScalarType($item, $options);
        }

        // catch-all for null, array, resource, resource (closed).
        //
        // gettype() uses 'NULL' (uppercase) and reports closed
        // resources as 'resource (closed)'. We normalise both so
        // that the returned tokens match PHP's own spellings
        // (`null`) and stay usable in messages (`resource`, not
        // the odd parenthesised form).
        $type = gettype($item);
        return match ($type) {
            'NULL' => 'null',
            'resource (closed)' => 'resource',
            default => $type,
        };
    }

    /**
     * extract the details about a PHP callable array
     *
     * @param  callable $item
     *         the callable() to examine
     * @param  int $options
     *         a bitmask of `self::FLAG_*` constants
     * @return string
     *         the data type of $item
     */
    private static function returnCallableType(
        callable $item,
        int $options
    ): string
    {
        // user doesn't want the details
        if (($options & self::FLAG_CALLABLE_DETAILS) === 0) {
            return "callable";
        }

        // $item may contain the name of a function
        if (is_string($item)) {
            return "callable<" . $item . ">";
        }

        // $item may be a closure
        if ($item instanceof Closure) {
            return 'callable<Closure>';
        }

        // at this point, $item is an array
        // but we need to keep phpstan happy
        assert(is_array($item));
        assert(is_string($item[1]));

        if (is_string($item[0])) {
            return "callable<" . $item[0] . "::" . $item[1] . ">";
        }

        // $item contains an <object, method> pair
        assert(is_object($item[0]));
        return "callable<" . get_class($item[0]). "::" . $item[1] . ">";
    }

    /**
     * turn a PHP object into the underlying PHP data type
     *
     * @param  object $item
     *         the data to inspect
     * @param  int $options
     *         a bitmask of `self::FLAG_*` constants
     * @return string
     *         the data type of $item
     */
    private static function returnObjectType(
        object $item,
        int $options
    ): string
    {
        // special case - PHP Closure objects are always callable,
        // and 'Closure' is not a useful class name for display.
        // Route to the callable handler *before* the classname
        // gate, so that FLAG_CALLABLE_DETAILS alone is enough to
        // reveal closure shape (callers who only want callable
        // detail should not also have to pass FLAG_CLASSNAME).
        if ($item instanceof Closure) {
            return self::returnCallableType($item, $options);
        }

        // does the caller want to know what kind of object?
        if (($options & self::FLAG_CLASSNAME) === 0) {
            // the caller wants a plain 'object'
            return "object";
        }

        return 'object<' . get_class($item) . '>';
    }

    /**
     * extract the details about a PHP scalar value
     *
     * @param  bool|float|int|string $item
     *         the data to examine
     * @param  int $options
     *         a bitmask of `self::FLAG_*` constants
     * @return string
     *         the data type of $item
     */
    private static function returnScalarType(
        bool|float|int|string $item,
        int $options
    ): string
    {
        // we use PHP parameter-type-hint spellings ('int', 'float',
        // 'bool'), not gettype()'s 'integer', 'double', 'boolean' -
        // see also the other TypeInspector classes.
        $prefix = match (true) {
            is_int($item) => 'int',
            is_float($item) => 'float',
            is_bool($item) => 'bool',
            default => 'string',
        };

        // user doesn't want the details
        if (($options & self::FLAG_SCALAR_VALUE) === 0) {
            return $prefix;
        }

        // special case - boolean values
        if (is_bool($item)) {
            if ($item) {
                return $prefix . "<true>";
            }
            return $prefix . "<false>";
        }

        // general case
        return $prefix . "<{$item}>";
    }
}
