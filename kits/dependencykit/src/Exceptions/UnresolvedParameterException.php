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
namespace StusDevKit\DependencyKit\Exceptions;

use Psr\Container\NotFoundExceptionInterface;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * UnresolvedParameterException is thrown by ResolveParameter::for()
 * when a parameter's declared type cannot be satisfied from the DI
 * container, and the parameter has no declared default value and is
 * not nullable.
 *
 * Distinct from DependencyNotFoundException: that one is raised by
 * PSR-11 containers when a caller explicitly asks get($id) for an
 * unknown id. This one is raised one level up, by the reflection-
 * driven resolver, when it has exhausted the container plus every
 * fallback the parameter declaration offered. Carrying both the
 * parameter name and the declared type lets the caller point at the
 * exact slot that failed, which matters when several parameters of
 * the same constructor share a type.
 */
class UnresolvedParameterException extends Rfc9457ProblemDetailsException
    implements NotFoundExceptionInterface
{
    public function __construct(
        string $paramName,
        string $paramType,
    )
    {
        parent::__construct(
            type: "https://github.com/stuartherbert/stusdevkit/",
            status: 500,
            title: "Cannot resolve parameter from DI container",
            extra: [
                'paramName' => $paramName,
                'paramType' => $paramType,
            ],
        );
    }
}
