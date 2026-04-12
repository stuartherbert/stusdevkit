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

namespace StusDevKit\MissingBitsKit\Tests\Unit\TypeInspectors;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\BaseInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ChildOfInterfaceParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ChildOfTraitParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ClassWithExtendedInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ClassWithNestedTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ExtendedInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\InheritedInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\InheritedTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\NestedTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\OuterTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ParentWithInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ParentWithTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInvokable;
use Closure;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleStringable;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleToString;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelChild;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelGrandparent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelParent;
use StusDevKit\MissingBitsKit\TypeInspectors\GetDuckTypes;
use Stringable;

#[TestDox('GetDuckTypes')]
class GetDuckTypesTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetDuckTypes')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetDuckTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetDuckTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetDuckTypes::class, $unit);
    }

    #[TestDox('__invoke() returns the same result as from()')]
    public function test_invoke_returns_same_result_as_from(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that calling an instance as a callable
        // returns the same result as calling the static from()
        // method directly - locking in the contract that __invoke
        // is a thin wrapper around from()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetDuckTypes();
        $input = 42;
        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualFromInvoke = $unit($input);
        $actualFromStatic = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actualFromInvoke);
        $this->assertSame($expected, $actualFromStatic);
    }

    // ================================================================
    //
    // Integer inputs
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{int}>
     */
    public static function integerProvider(): array
    {
        return [
            'zero' => [0],
            'positive' => [42],
            'negative' => [-7],
            'max' => [PHP_INT_MAX],
            'min' => [PHP_INT_MIN],
        ];
    }

    #[TestDox('Integer inputs return numeric, int, and mixed types')]
    #[DataProvider('integerProvider')]
    public function test_integer_returns_numeric_int_mixed(int $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any PHP int - regardless of value -
        // is reported as satisfying 'numeric', 'int', and 'mixed'

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Float inputs
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{float}>
     */
    public static function floatProvider(): array
    {
        return [
            'zero' => [0.0],
            'positive' => [1.5],
            'negative' => [-3.14],
            'very small' => [1e-10],
        ];
    }

    #[TestDox('Float inputs return numeric, float, and mixed types')]
    #[DataProvider('floatProvider')]
    public function test_float_returns_numeric_float_mixed(float $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any PHP float is reported as
        // satisfying 'numeric', 'float', and 'mixed'

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // String inputs
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{string,array<string,string>}>
     */
    public static function stringProvider(): array
    {
        return [
            'empty string' => [
                '',
                [
                    'string' => 'string',
                    'mixed' => 'mixed',
                ],
            ],
            'plain string' => [
                'hello, world',
                [
                    'string' => 'string',
                    'mixed' => 'mixed',
                ],
            ],
            'numeric string (int)' => [
                '123',
                [
                    'numeric' => 'numeric',
                    'int' => 'int',
                    'string' => 'string',
                    'mixed' => 'mixed',
                ],
            ],
            'numeric string (float)' => [
                '45.6',
                [
                    'numeric' => 'numeric',
                    'float' => 'float',
                    'string' => 'string',
                    'mixed' => 'mixed',
                ],
            ],
            'callable string' => [
                'strlen',
                [
                    'callable' => 'callable',
                    'string' => 'string',
                    'mixed' => 'mixed',
                ],
            ],
        ];
    }

    /**
     * @param array<string,string> $expected
     */
    #[TestDox('String inputs return the expected type list')]
    #[DataProvider('stringProvider')]
    public function test_string_returns_expected_types(string $input, array $expected): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each kind of PHP string - plain,
        // numeric, callable - produces exactly the type list we
        // expect for that shape of string

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Array inputs
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{array<array-key,mixed>}>
     */
    public static function nonCallableArrayProvider(): array
    {
        return [
            'empty' => [[]],
            'list' => [[1, 2, 3]],
            'associative' => [['a' => 1, 'b' => 2]],
        ];
    }

    /**
     * @param array<array-key,mixed> $input
     */
    #[TestDox('Non-callable array inputs return just array')]
    #[DataProvider('nonCallableArrayProvider')]
    public function test_non_callable_array_returns_array_only(array $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a plain PHP array - one that is
        // not also a callable - produces a type list containing
        // only 'array'

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'array' => 'array',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Callable array input returns callable and array')]
    public function test_callable_array_returns_callable_and_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a `[ClassName, 'method']` callable
        // array produces a type list that contains both
        // 'callable' and 'array'

        // ----------------------------------------------------------------
        // setup your test

        $input = [DateTimeImmutable::class, 'createFromFormat'];
        $expected = [
            'callable' => 'callable',
            'array' => 'array',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Object inputs
    //
    // ----------------------------------------------------------------

    #[TestDox('Plain object returns just its class name')]
    public function test_plain_object_returns_class_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object with no parents,
        // interfaces, or traits produces a type list containing
        // only that object's class name

        // ----------------------------------------------------------------
        // setup your test

        $input = new stdClass();
        $expected = [
            stdClass::class => stdClass::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Object with parent, interface, and trait returns all four as duck-types')]
    public function test_object_returns_class_parent_interface_and_trait(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when an object extends a parent,
        // implements an interface, and uses a trait, every branch
        // of its type surface appears in the returned type list

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleClass();
        $expected = [
            SampleClass::class => SampleClass::class,
            SampleParent::class => SampleParent::class,
            SampleInterface::class => SampleInterface::class,
            SampleTrait::class => SampleTrait::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Closure input returns Closure, callable, object, and mixed')]
    public function test_closure_returns_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a PHP Closure - which is callable
        // but whose class name ('Closure') is not itself a global
        // function - is still reported as 'callable'. The
        // class-name-based `is_callable()` check in GetClassTypes
        // would miss this case; GetObjectTypes must check the
        // instance itself.

        // ----------------------------------------------------------------
        // setup your test

        $input = fn(): int => 1;
        $expected = [
            Closure::class => Closure::class,
            'callable' => 'callable',
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Invokable object returns its class name, callable, object, and mixed')]
    public function test_invokable_object_returns_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object of a class defining
        // `__invoke()` is reported as 'callable'

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleInvokable();
        $expected = [
            SampleInvokable::class => SampleInvokable::class,
            'callable' => 'callable',
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Stringable object returns its class name and Stringable')]
    public function test_stringable_object_returns_class_and_stringable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a Stringable object exposes the
        // Stringable interface as a duck-type. Callers use this
        // signal to decide whether the object can stand in for a
        // string.

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleStringable();
        $expected = [
            SampleStringable::class => SampleStringable::class,
            Stringable::class => Stringable::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Object with __toString() returns its class name and Stringable')]
    public function test_object_with_toString_returns_class_and_stringable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object that only implements __toString()
        // is treated as Stringable

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleToString();
        $expected = [
            SampleToString::class => SampleToString::class,
            Stringable::class => Stringable::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Deep type graphs
    //
    // ----------------------------------------------------------------

    #[TestDox('3-deep class hierarchy surfaces parent and grandparent')]
    public function test_three_level_hierarchy_surfaces_every_ancestor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class whose inheritance chain is
        // three levels deep has every ancestor (parent AND
        // grandparent) reported as a duck-type

        // ----------------------------------------------------------------
        // setup your test

        $input = new ThreeLevelChild();
        $expected = [
            ThreeLevelChild::class => ThreeLevelChild::class,
            ThreeLevelParent::class => ThreeLevelParent::class,
            ThreeLevelGrandparent::class => ThreeLevelGrandparent::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Interface implemented on a parent class is surfaced on the child')]
    public function test_interface_implemented_on_parent_is_surfaced(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a parent class implements an
        // interface, the child class still reports that interface
        // as a duck-type - even though the child itself does not
        // declare the interface in its own `implements` clause

        // ----------------------------------------------------------------
        // setup your test

        $input = new ChildOfInterfaceParent();
        $expected = [
            ChildOfInterfaceParent::class => ChildOfInterfaceParent::class,
            ParentWithInterface::class => ParentWithInterface::class,
            InheritedInterface::class => InheritedInterface::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('2-deep interface hierarchy surfaces the base interface')]
    public function test_interface_hierarchy_surfaces_base_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a class implements an
        // interface which itself extends another interface, both
        // interfaces are reported as duck-types

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithExtendedInterface();
        $expected = [
            ClassWithExtendedInterface::class => ClassWithExtendedInterface::class,
            ExtendedInterface::class => ExtendedInterface::class,
            BaseInterface::class => BaseInterface::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Trait used by another trait is surfaced transitively')]
    public function test_trait_used_by_trait_is_surfaced(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a class uses a trait which
        // itself uses another trait, both traits are reported as
        // duck-types. Type-inspection must walk
        // traits-used-by-traits, not just the direct `use` clause
        // on the class.

        // ----------------------------------------------------------------
        // setup your test

        $input = new ClassWithNestedTrait();
        $expected = [
            ClassWithNestedTrait::class => ClassWithNestedTrait::class,
            OuterTrait::class => OuterTrait::class,
            NestedTrait::class => NestedTrait::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Trait used by a parent class is surfaced on the child')]
    public function test_trait_used_by_parent_is_surfaced(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a parent class uses a trait,
        // the child class still reports that trait as a duck-type -
        // even though the child itself does not declare the trait
        // in its own `use` clause

        // ----------------------------------------------------------------
        // setup your test

        $input = new ChildOfTraitParent();
        $expected = [
            ChildOfTraitParent::class => ChildOfTraitParent::class,
            ParentWithTrait::class => ParentWithTrait::class,
            InheritedTrait::class => InheritedTrait::class,
            'object' => 'object',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Fallback inputs (types with no dedicated inspector)
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{bool,array<string,string>}>
     */
    public static function booleanProvider(): array
    {
        return [
            'true' => [
                true,
                [
                    'true' => 'true',
                    'bool' => 'bool',
                    'mixed' => 'mixed',
                ],
            ],
            'false' => [
                false,
                [
                    'false' => 'false',
                    'bool' => 'bool',
                    'mixed' => 'mixed',
                ],
            ],
        ];
    }

    /**
     * @param array<string,string> $expected
     */
    #[TestDox('Boolean inputs return their literal type, bool, and mixed')]
    #[DataProvider('booleanProvider')]
    public function test_boolean_returns_expected_types(bool $input, array $expected): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that boolean values are dispatched to
        // GetBooleanTypes and that the output carries the literal
        // 'true'/'false' type (PHP 8.2+ allows these as standalone
        // type hints), the generic 'bool', and 'mixed'

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Null input returns null and mixed')]
    public function test_null_returns_null_and_mixed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that null - which has no dedicated
        // inspector - falls back to a lowercase 'null' (the PHP
        // keyword spelling, not gettype()'s 'NULL') plus 'mixed'

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'null' => 'null',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('Resource input returns resource and mixed')]
    public function test_resource_returns_resource_and_mixed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that open PHP resources - which have no
        // dedicated inspector - fall back to a simple list of the
        // raw gettype() ('resource') and 'mixed'

        // ----------------------------------------------------------------
        // setup your test

        $handle = tmpfile();
        $this->assertNotFalse($handle);

        $expected = [
            'resource' => 'resource',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($handle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);

        // ----------------------------------------------------------------
        // tidy up

        fclose($handle);
    }

    #[TestDox('Closed resource input collapses to resource and mixed')]
    public function test_closed_resource_returns_resource_and_mixed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a *closed* PHP resource - which
        // gettype() reports as 'resource (closed)' - is collapsed
        // back to the clean 'resource' token, so callers do not
        // have to think about open/closed state to reason about
        // the value's type

        // ----------------------------------------------------------------
        // setup your test

        $handle = tmpfile();
        $this->assertNotFalse($handle);
        fclose($handle);

        $expected = [
            'resource' => 'resource',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetDuckTypes::from($handle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
