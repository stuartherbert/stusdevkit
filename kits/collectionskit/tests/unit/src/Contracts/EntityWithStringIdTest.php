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

namespace StusDevKit\CollectionsKit\Tests\Unit\Contracts;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use Stringable;
use StusDevKit\CollectionsKit\Contracts\EntityWithStringId;

/**
 * Contract test for EntityWithStringId.
 *
 * The interface is a published extension point: downstream
 * classes `implements EntityWithStringId` to opt into string-keyed
 * dictionaries and indexes built by CollectionsKit. The tests
 * below pin the published shape (namespace, kind, method set,
 * return-type union) so any drift becomes a named diff rather
 * than a silent break in every implementer.
 */
#[TestDox(EntityWithStringId::class)]
class EntityWithStringIdTest extends TestCase
{
    // ================================================================
    //
    // Interface identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Contracts namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - every
        // implementer imports the interface by FQN, so moving it
        // is a breaking change that must go through a major
        // version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Contracts';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            EntityWithStringId::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the extension point is designed to be *implemented* by
        // any number of downstream entity classes. Turning it
        // into a class (abstract or concrete) or a trait would
        // break `implements EntityWithStringId` across every
        // consumer.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            EntityWithStringId::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only getId() as a public method')]
    public function test_exposes_only_getId(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this interface's sole published member is getId().
        // Pinning the member set by enumeration catches a silent
        // addition (like a new helper method that every
        // implementer would have to supply).

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['getId'];
        $reflection = new ReflectionClass(
            EntityWithStringId::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->getId() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->getId() is declared')]
    public function test_getId_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // getId() is the only contract method; its existence is
        // what makes implementers storable in string-keyed
        // dictionaries and indexes.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            EntityWithStringId::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('getId');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getId() is public')]
    public function test_getId_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // callers outside the implementer (the collections
        // themselves) need to call getId() to index entities, so
        // the method must be publicly reachable. Interface
        // methods are public by default, but pinning it makes
        // the contract explicit.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            EntityWithStringId::class,
        ))->getMethod('getId');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getId() takes no parameters')]
    public function test_getId_takes_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // getId() is an inspector - it reports the already-known
        // identity of the entity. Any parameter would imply
        // "compute an id from X", which is a different contract.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $method = (new ReflectionClass(
            EntityWithStringId::class,
        ))->getMethod('getId');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->getId() returns a string|Stringable union')]
    public function test_getId_returns_string_or_Stringable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the union return type is deliberate: implementers may
        // return a plain string, or any value object that
        // implements Stringable (e.g. a typed id wrapper).
        // Collections cast the return to string when keying, so
        // narrowing this union (e.g. to just `string`) or
        // widening it (e.g. to `mixed`) would break every
        // implementer or every consumer respectively.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['string', Stringable::class];
        $method = (new ReflectionClass(
            EntityWithStringId::class,
        ))->getMethod('getId');
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static function ($t) {
                // each member of the union is a ReflectionNamedType
                // (PHP disallows intersection types inside
                // union types for return positions)
                if ($t instanceof ReflectionNamedType) {
                    return $t->getName();
                }

                // keep phpstan happy
                //
                // the invariant above already holds for this
                // interface; this branch exists only so the map
                // callback has a total return type.
                return '';
            },
            $type->getTypes(),
        );

        // ----------------------------------------------------------------
        // test the results

        // order of members in a union type is preserved by
        // reflection in declaration order, so we compare as a
        // set to avoid pinning the source-file ordering
        sort($expected);
        sort($actual);
        $this->assertSame($expected, $actual);
    }
}
