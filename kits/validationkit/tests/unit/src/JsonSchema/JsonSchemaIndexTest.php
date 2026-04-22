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
use RuntimeException;
use StusDevKit\CollectionsKit\Dictionaries\DictOfObjects;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaIndex;
use StusDevKit\ValidationKit\Validate;

#[TestDox(JsonSchemaIndex::class)]
class JsonSchemaIndexTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\JsonSchema namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the published namespace is part of the contract - the
        // registry and both importer/exporter type-hint against
        // this exact FQN.

        $expected = 'StusDevKit\\ValidationKit\\JsonSchema';

        $actual = (new ReflectionClass(JsonSchemaIndex::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a concrete class')]
    public function test_is_a_concrete_class(): void
    {
        // callers `new JsonSchemaIndex()` directly; marking the
        // class abstract, interface, or trait would break the
        // registry construction.

        $reflection = new ReflectionClass(JsonSchemaIndex::class);

        $this->assertFalse($reflection->isAbstract());
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('extends DictOfObjects')]
    public function test_extends_dict_of_objects(): void
    {
        // JsonSchemaIndex is a narrowly-typed dictionary of
        // ValidationSchema instances. It inherits set/get/has from
        // DictOfObjects; that base type is part of the contract
        // because the registry depends on inherited methods.

        $reflection = new ReflectionClass(JsonSchemaIndex::class);
        $parent = $reflection->getParentClass();

        $this->assertNotFalse($parent);
        $this->assertSame(
            DictOfObjects::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    /**
     * default construction must produce an empty index so the
     * registry can populate it lazily.
     */
    #[TestDox('::__construct() with no arguments produces an empty index')]
    public function test_default_constructor_is_empty(): void
    {
        $unit = new JsonSchemaIndex();

        $this->assertTrue($unit->empty());
    }

    // ================================================================
    //
    // Behaviour via DictOfObjects contract
    //
    // ----------------------------------------------------------------

    /**
     * set() must accept a string key paired with a ValidationSchema
     * value - that's the whole type the index exists to narrow.
     */
    #[TestDox('->set() stores a ValidationSchema by string key')]
    public function test_set_stores_a_validation_schema(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();
        $schema = Validate::string();

        $unit->set(key: 'Name', value: $schema);

        $this->assertTrue($unit->has(key: 'Name'));
    }

    /**
     * get() must return the exact same schema instance that was
     * stored - object identity is what the exporter uses to detect
     * $ref candidates via `spl_object_id`.
     */
    #[TestDox('->get() returns the exact schema instance that was stored')]
    public function test_get_returns_stored_instance(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();
        $schema = Validate::string();
        $unit->set(key: 'Name', value: $schema);

        $result = $unit->get(key: 'Name');

        $this->assertSame($schema, $result);
    }

    /**
     * maybeGet() must return null (not throw) when the key is
     * missing - the registry relies on this to detect unknown
     * names and raise a domain-specific exception.
     */
    #[TestDox('->maybeGet() returns null for an unknown key')]
    public function test_maybeGet_returns_null_for_unknown(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();

        $this->assertNull($unit->maybeGet(key: 'missing'));
    }

    /**
     * get() on a missing key must throw. The inherited base
     * contract raises RuntimeException; pin this so the registry's
     * null-check on maybeGet() remains the correct guard against
     * the throw.
     */
    #[TestDox('->get() throws for an unknown key')]
    public function test_get_throws_for_unknown(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();

        $this->expectException(RuntimeException::class);

        $unit->get(key: 'missing');
    }

    /**
     * has() must report true after set() and false before - the
     * registry uses has() as its cheap membership test.
     */
    #[TestDox('->has() reports presence correctly')]
    public function test_has_reports_presence(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();

        $this->assertFalse($unit->has(key: 'Name'));

        $unit->set(key: 'Name', value: Validate::string());

        $this->assertTrue($unit->has(key: 'Name'));
    }

    /**
     * setting the same key twice must replace the earlier value -
     * this is how the registry implements `register()` re-assign
     * semantics.
     */
    #[TestDox('->set() replaces an existing schema under the same key')]
    public function test_set_replaces_existing(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();
        $first = Validate::string();
        $second = Validate::int();

        $unit->set(key: 'Field', value: $first);
        $unit->set(key: 'Field', value: $second);

        $this->assertSame($second, $unit->get(key: 'Field'));
    }

    /**
     * the exporter iterates the index to emit `$defs`; pin that
     * foreach yields (string name, schema instance) pairs, in
     * insertion order, so the exported `$defs` order is stable.
     */
    #[TestDox('is iterable as string-keyed ValidationSchema pairs in insertion order')]
    public function test_iteration_yields_insertion_order_pairs(): void
    {
        /** @var JsonSchemaIndex $unit */
        $unit = new JsonSchemaIndex();
        $nameSchema = Validate::string();
        $ageSchema = Validate::int();

        $unit->set(key: 'Name', value: $nameSchema);
        $unit->set(key: 'Age', value: $ageSchema);

        $keys = [];
        $values = [];
        foreach ($unit as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        $this->assertSame(['Name', 'Age'], $keys);
        $this->assertSame([$nameSchema, $ageSchema], $values);
    }
}
