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

namespace StusDevKit\ValidationKit\Tests\Unit\Constraints;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Constraints\StringUuidConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('StringUuidConstraint')]
class StringUuidConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringUuidConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringUuidConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringUuidConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('implements ValidationConstraint')]
    public function test_implements_ValidationConstraint(): void
    {
        $reflection = new \ReflectionClass(StringUuidConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares only __construct and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringUuidConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringUuidConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringUuidConstraint::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['error'], $paramNames);
    }

    #[TestDox('::__construct() declares $error as optional (nullable callable)')]
    public function test_construct_error_parameter_optional(): void
    {
        $method = new \ReflectionMethod(StringUuidConstraint::class, '__construct');
        $params = $method->getParameters();

        $this->assertTrue($params[0]->isOptional());
        $this->assertTrue($params[0]->allowsNull());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringUuidConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringUuidConstraint::class, 'process');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringUuidConstraint::class, 'process');
        $params = $method->getParameters();

        $dataType = $params[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $dataType);
        $this->assertSame('mixed', $dataType->getName());

        $contextType = $params[1]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $contextType);
        $this->assertSame(ValidationContext::class, $contextType->getName());
    }

    #[TestDox('->process() declares return type mixed')]
    public function test_process_return_type(): void
    {
        $method = new \ReflectionMethod(StringUuidConstraint::class, 'process');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    // ================================================================
    //
    // Behaviour — Happy Path
    //
    // ----------------------------------------------------------------

    /**
     * The constraint accepts every RFC 4122 UUID version
     * (v1-v8) in canonical 8-4-4-4-12 form. The version
     * nibble is not inspected — any hex digit works.
     */
    #[TestDox('->process() accepts a valid $version UUID string')]
    #[DataProvider('provideValidUuids')]
    public function test_process_accepts_valid_uuid(string $version, string $uuid): void
    {
        $context = new ValidationContext();
        $unit = new StringUuidConstraint();

        $unit->process(data: $uuid, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * UUIDs are case-insensitive in hex — uppercase
     * letters are accepted alongside lowercase.
     */
    #[TestDox('->process() accepts an uppercase-hex UUID string')]
    public function test_process_accepts_uppercase_uuid(): void
    {
        $context = new ValidationContext();
        $unit = new StringUuidConstraint();

        $unit->process(
            data: 'F47AC10B-58CC-4372-A567-0E02B2C3D479',
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Constraints never mutate the input — a valid UUID
     * is returned byte-for-byte.
     */
    #[TestDox('->process() returns the input value unchanged on success')]
    public function test_process_returns_input_unchanged(): void
    {
        $uuid = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
        $unit = new StringUuidConstraint();

        $actualResult = $unit->process(
            data: $uuid,
            context: new ValidationContext(),
        );

        $this->assertSame($uuid, $actualResult);
    }

    // ================================================================
    //
    // Behaviour — Failure
    //
    // ----------------------------------------------------------------

    /**
     * A $label string does not match the canonical
     * UUID shape — the constraint records an issue.
     */
    #[TestDox('->process() records an issue for $label')]
    #[DataProvider('provideInvalidUuids')]
    public function test_process_rejects_invalid_uuid(string $label, string $input): void
    {
        $context = new ValidationContext();
        $unit = new StringUuidConstraint();

        $unit->process(data: $input, context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Even on failure, the input value is returned
     * unchanged — the issue is reported on the context,
     * not raised as an exception.
     */
    #[TestDox('->process() returns the input value unchanged even on failure')]
    public function test_process_returns_input_unchanged_on_failure(): void
    {
        $unit = new StringUuidConstraint();

        $actualResult = $unit->process(
            data: 'not-a-uuid',
            context: new ValidationContext(),
        );

        $this->assertSame('not-a-uuid', $actualResult);
    }

    // ================================================================
    //
    // Behaviour — Custom Error Callback
    //
    // ----------------------------------------------------------------

    /**
     * When a custom error callback is supplied, it is the
     * issue the callback produces that lands in the
     * context — not the built-in default.
     */
    #[TestDox('->process() routes failures through the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new StringUuidConstraint(
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:bad-uuid',
                input: $data,
                path: [],
                message: 'custom uuid message',
            ),
        );

        $unit->process(data: 'not-a-uuid', context: $context);

        // pull out the first recorded issue and check it
        // carries the custom `type` — proving the callback
        // was the one invoked.
        $this->assertTrue($context->hasIssues());
        $issues = $context->issues();
        $first = $issues->first();
        $this->assertSame('urn:custom:bad-uuid', $first->type);
    }

    /**
     * The custom error callback is only consulted on
     * failure — valid UUIDs leave the context clean and
     * the callback is never invoked.
     */
    #[TestDox('->process() does not invoke the custom error callback on success')]
    public function test_process_does_not_invoke_callback_on_success(): void
    {
        $context = new ValidationContext();
        $callbackInvoked = false;
        $unit = new StringUuidConstraint(
            error: function (mixed $data) use (&$callbackInvoked): ValidationIssue {
                $callbackInvoked = true;
                return new ValidationIssue(
                    type: 'urn:custom:bad-uuid',
                    input: $data,
                    path: [],
                    message: 'custom uuid message',
                );
            },
        );

        $unit->process(
            data: 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            context: $context,
        );

        $this->assertFalse($callbackInvoked);
    }

    // ================================================================
    //
    // Behaviour — Inherited
    //
    // ----------------------------------------------------------------

    /**
     * Constraints run regardless of prior issues —
     * inherited from BaseConstraint.
     */
    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new StringUuidConstraint();

        $this->assertFalse($unit->skipOnIssues());
    }

    // ================================================================
    //
    // Data Providers
    //
    // ----------------------------------------------------------------

    /**
     * Canonical UUIDs of every standard version. The
     * regex in StringUuidConstraint does not inspect the
     * version nibble, so each case is a fair smoke-test
     * for the full version range.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideValidUuids(): array
    {
        return [
            'v1' => ['v1', 'c232ab00-9414-11ec-b3c8-9f6bdeced846'],
            'v3' => ['v3', '5df41881-3aed-3515-88a7-2f4a814cf09e'],
            'v4' => ['v4', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'],
            'v5' => ['v5', '2ed6657d-e927-568b-95e1-2665a8aea6a2'],
            'v6' => ['v6', '1ec9414c-232a-6b00-b3c8-9f6bdeced846'],
            'v7' => ['v7', '017f22e2-79b0-7cc3-98c4-dc0c0c07398f'],
            'v8' => ['v8', '320c3d4d-cc00-875b-8ec9-32b5f1b0e5f3'],
            'nil uuid'    => ['nil', '00000000-0000-0000-0000-000000000000'],
            'max uuid'    => ['max', 'ffffffff-ffff-ffff-ffff-ffffffffffff'],
        ];
    }

    /**
     * A spread of inputs that all fail the
     * 8-4-4-4-12 hex shape — empty string, wrong
     * length, wrong punctuation, non-hex characters,
     * and surrounding whitespace.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideInvalidUuids(): array
    {
        return [
            'empty string' => [
                'an empty string',
                '',
            ],
            'arbitrary text' => [
                'arbitrary text',
                'not-a-uuid',
            ],
            'too short' => [
                'a truncated UUID',
                'f47ac10b-58cc-4372-a567-0e02b2c3d47',
            ],
            'too long' => [
                'a UUID with trailing hex',
                'f47ac10b-58cc-4372-a567-0e02b2c3d4790',
            ],
            'missing hyphens' => [
                'a UUID with no hyphens',
                'f47ac10b58cc4372a5670e02b2c3d479',
            ],
            'wrong hyphen positions' => [
                'a UUID with misplaced hyphens',
                'f47ac10b5-8cc-4372-a567-0e02b2c3d479',
            ],
            'non-hex characters' => [
                'a UUID-shaped string with non-hex characters',
                'g47ac10b-58cc-4372-a567-0e02b2c3d479',
            ],
            'leading whitespace' => [
                'a UUID with leading whitespace',
                ' f47ac10b-58cc-4372-a567-0e02b2c3d479',
            ],
            'trailing whitespace' => [
                'a UUID with trailing whitespace',
                'f47ac10b-58cc-4372-a567-0e02b2c3d479 ',
            ],
            'braced form' => [
                'a UUID in braced form',
                '{f47ac10b-58cc-4372-a567-0e02b2c3d479}',
            ],
            'urn-prefixed form' => [
                'a UUID in urn:uuid: form',
                'urn:uuid:f47ac10b-58cc-4372-a567-0e02b2c3d479',
            ],
        ];
    }
}
