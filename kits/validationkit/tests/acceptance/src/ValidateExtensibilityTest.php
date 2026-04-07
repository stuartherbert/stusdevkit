<?php

//
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
//

declare(strict_types=1);

namespace StusDevKit\ValidationKit\Tests\Acceptance;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Transformers\BaseTransformer;
use StusDevKit\ValidationKit\Validate;

#[TestDox('Extensibility')]
class ValidateExtensibilityTest extends TestCase
{
    // ================================================================
    //
    // BaseConstraint
    //
    // ----------------------------------------------------------------

    #[TestDox('BaseConstraint subclass accepts valid data')]
    public function test_base_constraint_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom constraint extending
        // BaseConstraint works in the validation pipeline
        // and accepts data that passes its check

        // ----------------------------------------------------------------
        // setup your test

        $constraint = new class extends BaseConstraint {
            public function process(
                mixed $data,
                ValidationContext $context,
            ): mixed {
                assert(is_string($data));
                if (! str_contains($data, '@')) {
                    $context->addIssue(
                        type: 'https://example.com/errors/missing-at',
                        input: $data,
                        message: 'Must contain @',
                    );
                }

                return $data;
            }
        };

        $unit = Validate::string()->withConstraint($constraint);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('user@example.com');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('user@example.com', $actualResult);
    }

    #[TestDox('BaseConstraint subclass rejects invalid data')]
    public function test_base_constraint_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom constraint extending
        // BaseConstraint correctly reports validation issues
        // via $context->addIssue()

        // ----------------------------------------------------------------
        // setup your test

        $constraint = new class extends BaseConstraint {
            public function process(
                mixed $data,
                ValidationContext $context,
            ): mixed {
                assert(is_string($data));
                if (! str_contains($data, '@')) {
                    $context->addIssue(
                        type: 'https://example.com/errors/missing-at',
                        input: $data,
                        message: 'Must contain @',
                    );
                }

                return $data;
            }
        };

        $unit = Validate::string()->withConstraint($constraint);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('no-at-sign');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://example.com/errors/missing-at',
                    'path'    => [],
                    'message' => 'Must contain @',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );
    }

    // ================================================================
    //
    // BaseTransformer
    //
    // ----------------------------------------------------------------

    #[TestDox('BaseTransformer subclass transforms data')]
    public function test_base_transformer_transforms(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom transformer
        // extending BaseTransformer works in the
        // validation pipeline as a normaliser

        // ----------------------------------------------------------------
        // setup your test

        $transformer = new class extends BaseTransformer {
            public function process(
                mixed $data,
                ValidationContext $context,
            ): mixed {
                assert(is_string($data));
                $replaced = preg_replace(
                    '/[^a-z0-9]+/i',
                    '-',
                    $data,
                );
                assert(is_string($replaced));
                return strtolower($replaced);
            }
        };

        $unit = Validate::string()
            ->withNormaliser($transformer);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('Hello World!');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello-world-', $actualResult);
    }

    // ================================================================
    //
    // Validate::constraintFrom()
    //
    // ----------------------------------------------------------------

    #[TestDox('constraintFrom() creates reusable constraint that accepts')]
    public function test_constraint_from_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::constraintFrom()
        // creates a reusable constraint that can be shared
        // across schemas and accepts valid data

        // ----------------------------------------------------------------
        // setup your test

        $notEmpty = Validate::constraintFrom(
            fn(mixed $data) => $data === ''
                ? 'Must not be empty'
                : null,
        );

        $name = Validate::string()
            ->withConstraint($notEmpty);
        $label = Validate::string()
            ->withConstraint($notEmpty)
            ->max(length: 50);

        // ----------------------------------------------------------------
        // perform the change

        $nameResult = $name->parse('Stuart');
        $labelResult = $label->parse('My Label');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Stuart', $nameResult);
        $this->assertSame('My Label', $labelResult);
    }

    #[TestDox('constraintFrom() creates reusable constraint that rejects')]
    public function test_constraint_from_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::constraintFrom()
        // creates a constraint that correctly reports
        // validation issues when the callable returns an
        // error message

        // ----------------------------------------------------------------
        // setup your test

        $notEmpty = Validate::constraintFrom(
            fn(mixed $data) => $data === ''
                ? 'Must not be empty'
                : null,
        );

        $schema = Validate::string()
            ->withConstraint($notEmpty);

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse('');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'Must not be empty',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );
    }

    #[TestDox('constraintFrom() constraint is shared across schemas')]
    public function test_constraint_from_shared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a single constraint created
        // via constraintFrom() can be reused across multiple
        // schemas independently

        // ----------------------------------------------------------------
        // setup your test

        $positive = Validate::constraintFrom(
            fn(mixed $data) => $data <= 0
                ? 'Must be positive'
                : null,
        );

        $age = Validate::int()->withConstraint($positive);
        $price = Validate::number()->withConstraint($positive);

        // ----------------------------------------------------------------
        // perform the change

        $ageResult = $age->safeParse(-1);
        $priceResult = $price->safeParse(9.99);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($ageResult->failed());
        $this->assertFalse($priceResult->failed());
    }
}
