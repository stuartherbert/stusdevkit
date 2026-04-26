<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
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
 * get a full list of interfaces implemented by a class
 */
class GetClassInterfaces
{
    /**
     * do we have a PHP interface? If so, what types does it match?
     *
     * @param mixed $input
     *   the value to examine
     *
     *   returns an empty list if `$input` is not a valid class-string
     *
     * @return array<string,string>
     *   a list of PHP types that `$input` can match
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
     * get a full list of interfaces implemented by a class
     *
     * @param  class-string $className
     *         the item to examine
     * @return array<class-string,class-string>
     *         the list of interfaces implemented by the class
     */
    public static function from(string $className): array
    {
        // our return value
        $retval = [];

        // convert the interfaces into a lookup table
        foreach (class_implements($className) as $interfaceName) {
            $retval[$interfaceName] = $interfaceName;
        }

        // all done
        return $retval;
    }
}
