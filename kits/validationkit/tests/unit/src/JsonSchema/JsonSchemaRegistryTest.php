<?php

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
//

declare(strict_types=1);

namespace StusDevKit\ValidationKit\Tests\Unit\JsonSchema;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaRegistry;
use StusDevKit\ValidationKit\Validate;

#[TestDox('JsonSchemaRegistry')]
class JsonSchemaRegistryTest extends TestCase
{
    // ================================================================
    //
    // Existing $defs registration (backwards compat)
    //
    // ----------------------------------------------------------------

    #[TestDox('->resolveRef() resolves #/$defs/<name> ref')]
    public function test_resolves_defs_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the existing $defs-based
        // registration and resolution still works

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();
        $unit->register(name: 'Name', schema: $schema);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->resolveRef(ref: '#/$defs/Name');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($schema, $result);

    }

    // ================================================================
    //
    // URI-based registration ($id)
    //
    // ----------------------------------------------------------------

    #[TestDox('->registerByUri() stores schema by absolute URI')]
    public function test_register_by_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that schemas registered by
        // absolute URI can be retrieved by that URI

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();

        // ----------------------------------------------------------------
        // perform the change

        $unit->registerByUri(
            uri: 'https://example.com/schemas/name',
            schema: $schema,
        );

        // ----------------------------------------------------------------
        // test the results

        $result = $unit->resolveByUri(
            uri: 'https://example.com/schemas/name',
        );
        $this->assertSame($schema, $result);

    }

    #[TestDox('->resolveByUri() throws for unknown URI')]
    public function test_resolve_by_uri_throws_for_unknown(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that resolveByUri throws when
        // the URI is not registered

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(
            InvalidJsonSchemaException::class,
        );

        $unit->resolveByUri(
            uri: 'https://example.com/unknown',
        );

    }

    // ================================================================
    //
    // Anchor registration ($anchor)
    //
    // ----------------------------------------------------------------

    #[TestDox('->registerAnchor() stores schema by base URI + anchor name')]
    public function test_register_anchor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that anchors are registered
        // scoped to a base URI and can be resolved via
        // a fragment-only ref against that base

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();

        // ----------------------------------------------------------------
        // perform the change

        $unit->registerAnchor(
            baseUri: 'https://example.com/schemas/person',
            anchor: 'name-def',
            schema: $schema,
        );

        // ----------------------------------------------------------------
        // test the results

        $result = $unit->resolveAnchor(
            baseUri: 'https://example.com/schemas/person',
            anchor: 'name-def',
        );
        $this->assertSame($schema, $result);

    }

    #[TestDox('->resolveAnchor() throws for unknown anchor')]
    public function test_resolve_anchor_throws_for_unknown(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that resolveAnchor throws when
        // the anchor is not registered for the given base

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(
            InvalidJsonSchemaException::class,
        );

        $unit->resolveAnchor(
            baseUri: 'https://example.com/schemas/person',
            anchor: 'unknown',
        );

    }

    #[TestDox('->registerAnchor() scopes anchors to their base URI')]
    public function test_anchors_scoped_to_base_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the same anchor name under
        // different base URIs resolves to different schemas

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();
        $schemaA = Validate::string();
        $schemaB = Validate::int();

        $unit->registerAnchor(
            baseUri: 'https://example.com/a',
            anchor: 'field',
            schema: $schemaA,
        );
        $unit->registerAnchor(
            baseUri: 'https://example.com/b',
            anchor: 'field',
            schema: $schemaB,
        );

        // ----------------------------------------------------------------
        // perform the change

        $resultA = $unit->resolveAnchor(
            baseUri: 'https://example.com/a',
            anchor: 'field',
        );
        $resultB = $unit->resolveAnchor(
            baseUri: 'https://example.com/b',
            anchor: 'field',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($schemaA, $resultA);
        $this->assertSame($schemaB, $resultB);

    }

    // ================================================================
    //
    // Base URI stack
    //
    // ----------------------------------------------------------------

    #[TestDox('->pushBaseUri() and ->popBaseUri() manage the stack')]
    public function test_base_uri_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the base URI stack tracks
        // nested $id scopes correctly

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();

        // ----------------------------------------------------------------
        // perform the change

        $unit->pushBaseUri(
            uri: 'https://example.com/root',
        );
        $this->assertSame(
            'https://example.com/root',
            $unit->currentBaseUri(),
        );

        $unit->pushBaseUri(
            uri: 'https://example.com/nested',
        );
        $this->assertSame(
            'https://example.com/nested',
            $unit->currentBaseUri(),
        );

        $unit->popBaseUri();
        $this->assertSame(
            'https://example.com/root',
            $unit->currentBaseUri(),
        );

        $unit->popBaseUri();
        $this->assertSame(
            '',
            $unit->currentBaseUri(),
        );

    }

    #[TestDox('->currentBaseUri() returns empty string when stack is empty')]
    public function test_current_base_uri_empty_stack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that currentBaseUri returns an
        // empty string when no base URI has been pushed

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('', $unit->currentBaseUri());

    }
}
