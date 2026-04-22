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
namespace StusDevKit\DependencyKit;

use Psr\Container\ContainerInterface;
use StusDevKit\DependencyKit\Exceptions\DependencyNotFoundException;

/**
 * ContainerStack is a PSR-compliant dependency injection container.
 *
 * Need a stack of temporary dependencies (test mocks, route model bindings,
 * query path parameters)? Use a ContainerStack, and push in a new Container
 * for each layer of temporary dependencies.
 *
 * This allows each DI container to remain immutable.
 */
class ContainerStack implements ContainerInterface
{
    /**
     * keep track of things we've resolved before, to speed up resolving
     * them a second time
     *
     * @var array<string,int>
     */
    private array $resolvedMap = [];

    /**
     * keep track of things that do not resolve, to speed up resolving
     * them a second time
     *
     * @var array<string, bool>
     */
    private array $unresolvedMap = [];

    /**
     * @param ContainerInterface[] $containers
     */
    public function __construct(
        private array $containers
    )
    {

    }

    /**
     * add another container onto the stack
     *
     * most of the time, you should pass the complete stack into the
     * constructor instead!
     */
    public function push(ContainerInterface $container): void
    {
        array_unshift($this->containers, $container);
    }

    /**
     * remove the most-recently-added container off the stack
     */
    public function pop(): ?ContainerInterface
    {
        return array_shift($this->containers);
    }

    // ================================================================
    //
    // ContainerInterface
    //
    // ----------------------------------------------------------------

    public function get(string $id): mixed
    {
        // can one of the containers in the stack resolve this?
        if (!$this->has($id)) {
            throw new DependencyNotFoundException($id);
        }

        // at this point, we already know which container will resolve this
        $container = $this->containers[$this->resolvedMap[$id]];

        // delegate to the container
        return $container->get($id);
    }

    public function has(string $id): bool
    {
        // special case - we've seen this before
        if (isset($this->resolvedMap[$id])) {
            return true;
        }

        // special case - we've already proven this id has no home
        // in any of the layered containers
        //
        // footgun! the isset() guard is load-bearing: accessing an
        // undefined key under strict mode triggers a PHP warning,
        // which PHPUnit promotes to a test failure under
        // failOnWarning.
        if (isset($this->unresolvedMap[$id])) {
            return false;
        }

        // general case - we've never been asked for this before
        foreach ($this->containers as $key => $container) {
            if ($container->has($id)) {
                // let's remember this in case someone wants to
                // grab this in the future
                $this->resolvedMap[$id] = $key;

                return true;
            }
        }

        // if we get here, this is unresolvable
        $this->unresolvedMap[$id] = true;
        return false;
    }
}
