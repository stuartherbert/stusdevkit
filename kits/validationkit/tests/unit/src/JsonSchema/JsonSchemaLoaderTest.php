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

namespace StusDevKit\ValidationKit\Tests\Unit\JsonSchema;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use stdClass;
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaLoader;

#[TestDox(JsonSchemaLoader::class)]
class JsonSchemaLoaderTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\JsonSchema namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the published namespace is part of the contract - users
        // implement the interface by name, so moving it breaks
        // every downstream loader.

        $expected = 'StusDevKit\\ValidationKit\\JsonSchema';

        $actual = (new ReflectionClass(JsonSchemaLoader::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is an interface')]
    public function test_is_an_interface(): void
    {
        // JsonSchemaLoader is a behavioural contract injected into
        // the importer. Converting it to a class, abstract or
        // otherwise, is a breaking change because downstream code
        // `implements JsonSchemaLoader`.

        $reflection = new ReflectionClass(JsonSchemaLoader::class);

        $this->assertTrue($reflection->isInterface());
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('declares only load() as a public method')]
    public function test_declares_only_load_as_public_method(): void
    {
        // pin the method set by enumeration - an interface that
        // grows silently forces every implementer to scramble.
        // Any addition fails here with a diff that names the new
        // method.

        $expected = ['load'];
        $reflection = new ReflectionClass(JsonSchemaLoader::class);

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // load() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->load() declares $uri as its sole parameter')]
    public function test_load_declares_expected_parameters(): void
    {
        // the parameter name is part of the published surface -
        // the importer calls load(uri: $uri) with a named argument,
        // so renaming it would be a breaking change.

        $expected = ['uri'];
        $method = (new ReflectionClass(JsonSchemaLoader::class))
            ->getMethod('load');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->load() declares a string $uri parameter')]
    public function test_load_uri_is_string(): void
    {
        // the uri parameter is typed `string` (with non-empty-string
        // as a phpstan refinement). Pin the raw PHP type so a
        // well-meaning loosening to `mixed` is caught.

        $method = (new ReflectionClass(JsonSchemaLoader::class))
            ->getMethod('load');
        $parameter = $method->getParameters()[0];
        $type = $parameter->getType();

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('string', $type->getName());
    }

    #[TestDox('->load() returns a nullable JsonSchema')]
    public function test_load_returns_nullable_json_schema(): void
    {
        // implementations return null to signal "not found" and a
        // JsonSchema to signal "resolved". The importer branches
        // on null, so pin both the name and the nullability.

        $method = (new ReflectionClass(JsonSchemaLoader::class))
            ->getMethod('load');
        $type = $method->getReturnType();

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame(JsonSchema::class, $type->getName());
        $this->assertTrue($type->allowsNull());
    }

    // ================================================================
    //
    // Behaviour via an in-test implementation
    //
    // ----------------------------------------------------------------

    /**
     * a minimal implementation that returns a registered schema or
     * null must satisfy the contract. Pin the happy path with an
     * in-test class so the interface's behaviour documentation
     * remains executable evidence, not prose.
     */
    #[TestDox('implementations may return a JsonSchema for a known URI')]
    public function test_implementation_returns_json_schema(): void
    {
        $document = new stdClass();
        $document->type = 'string';

        $loader = new class ($document) implements JsonSchemaLoader {
            public function __construct(
                private readonly stdClass $document,
            ) {
            }

            public function load(string $uri): ?JsonSchema
            {
                if ($uri === 'https://example.com/name') {
                    return new JsonSchema($this->document);
                }

                return null;
            }
        };

        $result = $loader->load(uri: 'https://example.com/name');

        $this->assertInstanceOf(JsonSchema::class, $result);
    }

    /**
     * the documented contract for an unknown URI is "return null" -
     * not throw. Pin this so a naive implementer who throws a
     * RuntimeException doesn't quietly regress the importer's
     * fall-through behaviour.
     */
    #[TestDox('implementations return null for an unknown URI')]
    public function test_implementation_returns_null_for_unknown(): void
    {
        $loader = new class () implements JsonSchemaLoader {
            public function load(string $uri): ?JsonSchema
            {
                return null;
            }
        };

        $this->assertNull($loader->load(uri: 'https://example.com/unknown'));
    }
}
