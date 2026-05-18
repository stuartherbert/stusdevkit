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
// COPYRIGHT HOLDERS AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
// INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\MissingBitsKit\Tests\Fixtures\Normaliser;

/**
 * test fixture - a parent class that declares two private
 * properties. One name (`secret`) is deliberately reused by
 * {@see ChildOfParentWithPrivateProperty}, so the pair can
 * exercise same-name private fields across a class hierarchy.
 *
 * Originally added to pin GetNormalisedForComparison's handling of
 * inherited private properties: ReflectionObject::getProperties()
 * on a child instance does NOT return private properties declared
 * higher up the chain, so the normaliser has to walk parent classes
 * itself.
 */
class ParentWithPrivateProperty
{
    private string $secret = 'parent secret';

    private string $onlyInParent = 'parent only';

    /**
     * exposes the private state as a plain array.
     *
     * Originally added so static analysis can see the private
     * properties being read, instead of flagging them as write-
     * only (their real reader is reflection inside the code under
     * test, which PHPStan cannot follow).
     *
     * @return array<string,string>
     */
    public function asArray(): array
    {
        return [
            'secret' => $this->secret,
            'onlyInParent' => $this->onlyInParent,
        ];
    }
}
