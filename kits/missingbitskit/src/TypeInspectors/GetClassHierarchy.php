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

/**
 * get a full list of a class / interface and its parent classes
 */
class GetClassHierarchy
{
    /**
     * do we have a PHP class name? If so, what types does it match?
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
    {
        if (!is_string($input)) {
            return [];
        }

        if (!class_exists($input) && !interface_exists($input)) {
            return [];
        }

        return static::from($input);
    }

    /**
     * get a full list of a class / interface and its parent classes
     *
     * @param  class-string $className
     *         the item to examine
     * @return array<class-string,class-string>
     *         the class's inheritance hierarchy
     */
    public static function from(string $className): array
    {
        // our return value
        $retval = [];

        // add the class and any parents to the return value
        $retval = [
            $className => $className,
            ...self::getBaseClasses($className)
        ];

        // all done
        return $retval;
    }

    /**
     * get a list of parent classes
     *
     * @param  class-string $className
     *         the class or interface to examine
     * @return array<class-string, class-string>
     */
    private static function getBaseClasses(string $className): array
    {
        // our return value
        $retval = [];

        // build up our preferred result format
        foreach (class_parents($className) as $parentName) {
            $retval[$parentName] = $parentName;
        }

        // all done
        return $retval;
    }
}
