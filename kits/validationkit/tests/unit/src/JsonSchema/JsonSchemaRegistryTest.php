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
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaRegistry;
use StusDevKit\ValidationKit\Validate;

#[TestDox(JsonSchemaRegistry::class)]
class JsonSchemaRegistryTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\JsonSchema namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the published namespace is part of the contract - callers
        // type-hint against the FQN, so moving it is a breaking
        // change that must go through a major version bump.

        $expected = 'StusDevKit\\ValidationKit\\JsonSchema';

        $actual = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a concrete class')]
    public function test_is_a_concrete_class(): void
    {
        // callers `new JsonSchemaRegistry()` to get a registry.
        // Making the class abstract, an interface, or a trait is a
        // breaking change.

        $reflection = new ReflectionClass(JsonSchemaRegistry::class);

        $this->assertFalse($reflection->isAbstract());
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only the expected public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // the registry's public API is what importer and exporter
        // code lean on. Pin the set by enumeration so any addition
        // or rename fails with a diff that names the offender.

        $expected = [
            '__construct',
            'all',
            'currentBaseUri',
            'get',
            'has',
            'hasByUri',
            'popBaseUri',
            'pushBaseUri',
            'register',
            'registerAnchor',
            'registerByUri',
            'resolveAnchor',
            'resolveByUri',
            'resolveRef',
        ];
        $reflection = new ReflectionClass(JsonSchemaRegistry::class);

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() declares no parameters')]
    public function test_constructor_declares_no_parameters(): void
    {
        // pin that construction is argument-free. Any future
        // required dependency has to arrive with a default, or
        // this assertion flags the breaking change.

        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('__construct');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    #[TestDox('->register() declares $name and $schema as parameters in that order')]
    public function test_register_declares_expected_parameters(): void
    {
        // callers use named arguments for multi-parameter calls,
        // so pin the names and their order.

        $expected = ['name', 'schema'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('register');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->has() declares $name as its sole parameter')]
    public function test_has_declares_expected_parameters(): void
    {
        $expected = ['name'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('has');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->get() declares $name as its sole parameter')]
    public function test_get_declares_expected_parameters(): void
    {
        $expected = ['name'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('get');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->resolveRef() declares $ref as its sole parameter')]
    public function test_resolveRef_declares_expected_parameters(): void
    {
        $expected = ['ref'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('resolveRef');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->all() declares no parameters')]
    public function test_all_declares_no_parameters(): void
    {
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('all');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    #[TestDox('->registerByUri() declares $uri and $schema as parameters in that order')]
    public function test_registerByUri_declares_expected_parameters(): void
    {
        $expected = ['uri', 'schema'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('registerByUri');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->resolveByUri() declares $uri as its sole parameter')]
    public function test_resolveByUri_declares_expected_parameters(): void
    {
        $expected = ['uri'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('resolveByUri');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->hasByUri() declares $uri as its sole parameter')]
    public function test_hasByUri_declares_expected_parameters(): void
    {
        $expected = ['uri'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('hasByUri');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->registerAnchor() declares $baseUri, $anchor and $schema as parameters in that order')]
    public function test_registerAnchor_declares_expected_parameters(): void
    {
        $expected = ['baseUri', 'anchor', 'schema'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('registerAnchor');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->resolveAnchor() declares $baseUri and $anchor as parameters in that order')]
    public function test_resolveAnchor_declares_expected_parameters(): void
    {
        $expected = ['baseUri', 'anchor'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('resolveAnchor');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->pushBaseUri() declares $uri as its sole parameter')]
    public function test_pushBaseUri_declares_expected_parameters(): void
    {
        $expected = ['uri'];
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('pushBaseUri');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->popBaseUri() declares no parameters')]
    public function test_popBaseUri_declares_no_parameters(): void
    {
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('popBaseUri');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    #[TestDox('->currentBaseUri() declares no parameters')]
    public function test_currentBaseUri_declares_no_parameters(): void
    {
        $method = (new ReflectionClass(JsonSchemaRegistry::class))
            ->getMethod('currentBaseUri');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    // ================================================================
    //
    // Existing $defs registration (backwards compat)
    //
    // ----------------------------------------------------------------

    /**
     * ->resolveRef() must strip the `#/$defs/` prefix and return the
     * schema that was registered under the bare definition name; the
     * existing $defs-based API predates URI/anchor support and must
     * keep working unchanged.
     */
    #[TestDox('->resolveRef() resolves #/$defs/<name> ref')]
    public function test_resolves_defs_ref(): void
    {
        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();
        $unit->register(name: 'Name', schema: $schema);

        $result = $unit->resolveRef(ref: '#/$defs/Name');

        $this->assertSame($schema, $result);
    }

    // ================================================================
    //
    // URI-based registration ($id)
    //
    // ----------------------------------------------------------------

    /**
     * registering by $id URI must make the same schema retrievable
     * by that URI. External references target schemas by URI, so
     * this is the minimum round-trip the registry must guarantee.
     */
    #[TestDox('->registerByUri() stores schema by absolute URI')]
    public function test_register_by_uri(): void
    {
        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();

        $unit->registerByUri(
            uri: 'https://example.com/schemas/name',
            schema: $schema,
        );

        $result = $unit->resolveByUri(
            uri: 'https://example.com/schemas/name',
        );
        $this->assertSame($schema, $result);
    }

    /**
     * an unknown URI is a caller mistake; failing silently would
     * let importer code carry a null where a schema is expected
     * and blow up far from the source of the bug.
     */
    #[TestDox('->resolveByUri() throws for unknown URI')]
    public function test_resolve_by_uri_throws_for_unknown(): void
    {
        $unit = new JsonSchemaRegistry();

        $this->expectException(InvalidJsonSchemaException::class);

        $unit->resolveByUri(
            uri: 'https://example.com/unknown',
        );
    }

    // ================================================================
    //
    // Anchor registration ($anchor)
    //
    // ----------------------------------------------------------------

    /**
     * anchors are identified by the pair (base URI, anchor name) -
     * not by the anchor name alone - so the registry must round-trip
     * that exact composite key.
     */
    #[TestDox('->registerAnchor() stores schema by base URI + anchor name')]
    public function test_register_anchor(): void
    {
        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();

        $unit->registerAnchor(
            baseUri: 'https://example.com/schemas/person',
            anchor: 'name-def',
            schema: $schema,
        );

        $result = $unit->resolveAnchor(
            baseUri: 'https://example.com/schemas/person',
            anchor: 'name-def',
        );
        $this->assertSame($schema, $result);
    }

    /**
     * an unknown (baseUri, anchor) pair is a caller mistake and must
     * fail fast, same as resolveByUri().
     */
    #[TestDox('->resolveAnchor() throws for unknown anchor')]
    public function test_resolve_anchor_throws_for_unknown(): void
    {
        $unit = new JsonSchemaRegistry();

        $this->expectException(InvalidJsonSchemaException::class);

        $unit->resolveAnchor(
            baseUri: 'https://example.com/schemas/person',
            anchor: 'unknown',
        );
    }

    /**
     * two documents can each declare an anchor named `field` without
     * colliding, because the anchor namespace is partitioned by the
     * declaring document's base URI. Pin this so a lazy future change
     * that flattens the key space is caught.
     */
    #[TestDox('->registerAnchor() scopes anchors to their base URI')]
    public function test_anchors_scoped_to_base_uri(): void
    {
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

        $resultA = $unit->resolveAnchor(
            baseUri: 'https://example.com/a',
            anchor: 'field',
        );
        $resultB = $unit->resolveAnchor(
            baseUri: 'https://example.com/b',
            anchor: 'field',
        );

        $this->assertSame($schemaA, $resultA);
        $this->assertSame($schemaB, $resultB);
    }

    // ================================================================
    //
    // Base URI stack
    //
    // ----------------------------------------------------------------

    /**
     * the importer walks into nested $id scopes and back out again;
     * the stack must track each push and restore the previous top on
     * pop, so that anchors and relative URIs resolve against the
     * innermost active $id.
     */
    #[TestDox('->pushBaseUri() and ->popBaseUri() manage the stack')]
    public function test_base_uri_stack(): void
    {
        $unit = new JsonSchemaRegistry();

        $unit->pushBaseUri(uri: 'https://example.com/root');
        $this->assertSame(
            'https://example.com/root',
            $unit->currentBaseUri(),
        );

        $unit->pushBaseUri(uri: 'https://example.com/nested');
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
        $this->assertSame('', $unit->currentBaseUri());
    }

    /**
     * currentBaseUri() must return a string even on an empty stack,
     * so importer code can concatenate or compare the result without
     * a null-guard on every use.
     */
    #[TestDox('->currentBaseUri() returns empty string when stack is empty')]
    public function test_current_base_uri_empty_stack(): void
    {
        $unit = new JsonSchemaRegistry();

        $this->assertSame('', $unit->currentBaseUri());
    }
}
