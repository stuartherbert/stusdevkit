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

namespace StusDevKit\ValidationKit\Tests\Unit\Transformers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Transformers\BaseTransformer;
use StusDevKit\ValidationKit\Transformers\TrimTransformer;

#[TestDox('TrimTransformer')]
class TrimTransformerTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Transformers namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the namespace is part of the published API - moving the class
        // would break every `use` statement that imports it.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new \ReflectionClass(TrimTransformer::class);

        // ----------------------------------------------------------------
        // perform the change

        $namespace = $reflection->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Transformers',
            $namespace,
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TrimTransformer is a concrete class - not an interface or a
        // trait. Callers instantiate it directly.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new \ReflectionClass(TrimTransformer::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('extends BaseTransformer')]
    public function test_extends_BaseTransformer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TrimTransformer inherits the shared transformer plumbing from
        // BaseTransformer. Breaking that parentage would drop behaviour
        // callers rely on.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new \ReflectionClass(TrimTransformer::class);

        // ----------------------------------------------------------------
        // perform the change

        $parent = $reflection->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseTransformer::class,
            $parent->getName(),
        );
    }

    #[TestDox('implements ValueTransformer')]
    public function test_implements_value_transformer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ValueTransformer is the contract the validation pipeline
        // dispatches against. TrimTransformer must satisfy it to be
        // usable as a transformer.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new TrimTransformer();

        // ----------------------------------------------------------------
        // perform the change

        // (nothing to perform - we inspect the instance directly)

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            ValueTransformer::class,
            $unit,
        );
    }

    #[TestDox('declares only process as its own public method')]
    public function test_declares_own_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TrimTransformer's own public surface is exactly `process()`.
        // Pinning the enumerated set (not a count) names the offender if
        // an unexpected public method is added.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new \ReflectionClass(TrimTransformer::class);

        // ----------------------------------------------------------------
        // perform the change

        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === TrimTransformer::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // process() is called on a TrimTransformer instance by the
        // pipeline. Dropping `public`, or making it static, would break
        // that dispatch.

        // ----------------------------------------------------------------
        // setup your test

        $method = new \ReflectionMethod(TrimTransformer::class, 'process');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertFalse($isStatic);
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new \ReflectionMethod(TrimTransformer::class, 'process');
        $expected = ['data', 'context'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared parameter types are part of the contract: `$data`
        // accepts anything (`mixed`) and `$context` is the pipeline's
        // ValidationContext.

        // ----------------------------------------------------------------
        // setup your test

        $method = new \ReflectionMethod(TrimTransformer::class, 'process');
        $params = $method->getParameters();

        // ----------------------------------------------------------------
        // perform the change

        $dataType = $params[0]->getType();
        $contextType = $params[1]->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(\ReflectionNamedType::class, $dataType);
        $this->assertSame('mixed', $dataType->getName());

        $this->assertInstanceOf(\ReflectionNamedType::class, $contextType);
        $this->assertSame(ValidationContext::class, $contextType->getName());
    }

    #[TestDox('->process() declares return type mixed')]
    public function test_process_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract - a
        // transformer hands back a (possibly transformed) value of any
        // type, so the return is `mixed`.

        // ----------------------------------------------------------------
        // setup your test

        $method = new \ReflectionMethod(TrimTransformer::class, 'process');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideTrimCases(): array
    {
        return [
            'leading spaces'            => ['  hello', 'hello'],
            'trailing spaces'           => ['hello  ', 'hello'],
            'both sides'                => ['  hello  ', 'hello'],
            'tabs'                      => ["\thello\t", 'hello'],
            'newlines'                  => ["\nhello\n", 'hello'],
            'mixed whitespace'          => [" \t\nhello \n", 'hello'],
            'no whitespace'             => ['hello', 'hello'],
            'empty string'              => ['', ''],
            'only whitespace'           => ['   ', ''],
            'internal spaces preserved' => ['hello world', 'hello world'],
        ];
    }

    #[DataProvider('provideTrimCases')]
    #[TestDox('->process() trims whitespace')]
    public function test_trims_whitespace(
        string $inputValue,
        string $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // TrimTransformer strips outer whitespace but leaves internal
        // whitespace intact. The provider covers leading, trailing, both
        // ends, tabs, newlines, mixed runs, no-whitespace, empty,
        // whitespace-only, and the internal-spaces-preserved case.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new TrimTransformer();
        $context = new ValidationContext();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->process(data: $inputValue, context: $context);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);
    }

    #[TestDox('->process() strips Unicode whitespace such as NBSP')]
    public function test_strips_unicode_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TrimTransformer strips the full Unicode whitespace set, not
        // just ASCII whitespace: an input padded with NO-BREAK SPACE
        // (U+00A0) trims away entirely. A byte-level trim() would leave
        // those NBSP bytes in place.

        // ----------------------------------------------------------------
        // setup your test

        $nbsp = "\u{00A0}";
        $unit = new TrimTransformer();
        $context = new ValidationContext();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->process(
            data: $nbsp . 'hello' . $nbsp,
            context: $context,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);
    }
}
