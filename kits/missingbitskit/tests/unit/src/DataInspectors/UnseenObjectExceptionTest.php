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
// COPYRIGHT HOLDERS AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
// INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\MissingBitsKit\Tests\Unit\DataInspectors;

use ArrayObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use stdClass;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use StusDevKit\MissingBitsKit\DataInspectors\UnseenObjectException;
use Throwable;

#[TestDox(UnseenObjectException::class)]
class UnseenObjectExceptionTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\DataInspectors namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract. Callers
        // catch UnseenObjectException by its FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\DataInspectors';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(UnseenObjectException::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published kind (class vs interface vs trait) is part
        // of the contract - an exception must be a class so it can
        // be instantiated and thrown.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UnseenObjectException::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    // ================================================================
    //
    // Hierarchy
    //
    // ----------------------------------------------------------------

    #[TestDox('extends Rfc9457ProblemDetailsException')]
    public function test_extends_rfc9457_problem_details_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnseenObjectException adds no behaviour of its own - it is
        // a thin configuration of the RFC 9457 problem-details base.
        // Pin the parent so the inherited getters (getStatus(),
        // getTitle(), getExtra(), ...) the rest of this test class
        // relies on remain available.

        // ----------------------------------------------------------------
        // setup your test

        $expected = Rfc9457ProblemDetailsException::class;

        // ----------------------------------------------------------------
        // perform the change

        $parent = (new ReflectionClass(UnseenObjectException::class))
            ->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($parent);
        $this->assertSame($expected, $parent->getName());
    }

    #[TestDox('is throwable')]
    public function test_is_throwable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the whole point of the class is to be thrown by
        // NormalisationContext::visitIndexOf() and caught by
        // application code. Pin via reflection that the class
        // satisfies the Throwable contract PHP's `throw` / `catch`
        // depend on.
        //
        // reflection (rather than an `instanceof` on a live
        // instance) is used deliberately: PHPStan can already
        // prove an instanceof check here is always true and would
        // reject the test as redundant. implementsInterface()
        // returns a runtime bool the analyser does not fold away.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UnseenObjectException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->implementsInterface(Throwable::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // Constructor shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares only __construct as its own method')]
    public function test_declares_only_the_constructor(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnseenObjectException's only job is to configure the
        // RFC 9457 base with fixed values. It must not add getters
        // or behaviour of its own - everything observable is
        // inherited. Pin the own-method set so an accidental
        // addition shows up as a named diff.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(UnseenObjectException::class);

        // ----------------------------------------------------------------
        // perform the change

        $ownMethods = [];
        foreach ($reflection->getMethods() as $method) {
            if (
                $method->getDeclaringClass()->getName()
                === UnseenObjectException::class
            ) {
                $ownMethods[] = $method->getName();
            }
        }
        sort($ownMethods);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $ownMethods);
    }

    #[TestDox('::__construct() takes exactly one parameter')]
    public function test_constructor_takes_one_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor takes the offending object and nothing
        // else - every other RFC 9457 field is fixed inside the
        // class. Pin the cardinality.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 1;
        $method = (new ReflectionClass(UnseenObjectException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct()\'s parameter is named $obj')]
    public function test_constructor_parameter_is_named_obj(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is visible to any caller using named
        // arguments. Pin the spelling so a rename is a deliberate,
        // reviewed change.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'obj';
        $method = (new ReflectionClass(UnseenObjectException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getParameters()[0]->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct()\'s parameter is typed as object')]
    public function test_constructor_parameter_is_typed_as_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the offending value is always an object - that is the
        // only thing NormalisationContext tracks. A bare `object`
        // typehint accepts any class while still rejecting scalars.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(UnseenObjectException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getParameters()[0]->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('object', $type->getName());
    }

    #[TestDox('::__construct()\'s parameter is required, not optional')]
    public function test_constructor_parameter_is_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // there is no meaningful "unseen object" exception without
        // the object. Pin that the parameter is required and
        // non-nullable.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(UnseenObjectException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $parameter = $method->getParameters()[0];

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($parameter->isOptional());
        $this->assertFalse($parameter->allowsNull());
    }

    // ================================================================
    //
    // Construction and throwing
    //
    // ----------------------------------------------------------------

    #[TestDox('can be instantiated with the offending object')]
    public function test_can_be_instantiated(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor is the only way to build an
        // UnseenObjectException, and NormalisationContext::visitIndexOf()
        // depends on it succeeding. A successfully constructed exception
        // has already run the RFC 9457 base constructor, so its
        // inherited getMessage() returns the pre-filled title rather
        // than the empty string an unconstructed Exception would carry.
        // Pin that a plain `new` produces such a usable instance.

        // ----------------------------------------------------------------
        // setup your test

        $obj = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        $exception = new UnseenObjectException($obj);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame('', $exception->getMessage());
    }

    #[TestDox('can be thrown and caught as an UnseenObjectException')]
    public function test_can_be_thrown_and_caught(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the whole point of the class is the throw/catch round trip:
        // NormalisationContext::visitIndexOf() throws it, and
        // application code catches the concrete type. Pin that a thrown
        // instance is caught by `catch (UnseenObjectException)` and
        // arrives intact - the very object that was thrown.

        // ----------------------------------------------------------------
        // setup your test

        $thrown = new UnseenObjectException(new stdClass());
        $caught = null;

        // ----------------------------------------------------------------
        // perform the change

        try {
            throw $thrown;
        } catch (UnseenObjectException $e) {
            $caught = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($thrown, $caught);
    }

    #[TestDox('can be caught as a Rfc9457ProblemDetailsException')]
    public function test_can_be_caught_as_its_parent_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnseenObjectException adds no behaviour of its own, so
        // callers are free to catch it at the RFC 9457 base type
        // alongside every other problem-details exception. Pin that a
        // thrown instance is caught by
        // `catch (Rfc9457ProblemDetailsException)` and arrives intact.

        // ----------------------------------------------------------------
        // setup your test

        $thrown = new UnseenObjectException(new stdClass());
        $caught = null;

        // ----------------------------------------------------------------
        // perform the change

        try {
            throw $thrown;
        } catch (Rfc9457ProblemDetailsException $e) {
            $caught = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($thrown, $caught);
    }

    // ================================================================
    //
    // RFC 9457 problem-details payload
    //
    // ----------------------------------------------------------------

    #[TestDox('->getStatus() returns HTTP 500')]
    public function test_status_is_500(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // calling visitIndexOf() without first checking hasSeen()
        // is a programmer error - a server-side fault, not a
        // client one. HTTP 500 is the correct RFC 9457 status, and
        // pinning it keeps the exception classified as "our bug"
        // wherever problem-details responses are rendered.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 500;
        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->getTitle() returns the unseen-object summary')]
    public function test_title_is_the_unseen_object_summary(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the title is the human-readable summary of the problem
        // type. Pin the exact wording - it is part of the published
        // contract and appears in logs and problem-details
        // responses.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'visitIndexOf() called on an object that '
            . 'NormalisationContext has not seen';
        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->getMessage() uses the title as the exception message')]
    public function test_message_falls_back_to_the_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnseenObjectException supplies no `detail`, so the
        // RFC 9457 base uses the title as the PHP exception
        // message. getMessage() is what surfaces in stack traces
        // and uncaught-exception output, so pin that it carries
        // the meaningful summary rather than an empty string.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'visitIndexOf() called on an object that '
            . 'NormalisationContext has not seen';
        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->getTypeAsString() returns the StusDevKit problem-type URI')]
    public function test_type_is_the_stusdevkit_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `type` URI identifies this class of problem in an
        // RFC 9457 response. Pin the published URI so a change is a
        // deliberate, reviewed decision.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'http://github.com/stuartherbert/stusdevkit/';
        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->hasDetail() returns false')]
    public function test_has_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnseenObjectException carries no per-occurrence `detail` -
        // the title already says everything, and the offending
        // class travels in `extra` instead. Pin the no-detail
        // shape so getMessage()'s title fallback stays in force.

        // ----------------------------------------------------------------
        // setup your test

        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->hasDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->hasExtra() returns true')]
    public function test_has_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every UnseenObjectException carries the offending
        // object's class name in `extra`, so hasExtra() is always
        // true. Pin it so a regression that dropped the extra
        // payload surfaces here.

        // ----------------------------------------------------------------
        // setup your test

        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    /**
     * @return array<string, array{0: object, 1: class-string}>
     */
    public static function offendingObjectProvider(): array
    {
        return [
            'stdClass'    => [new stdClass(), stdClass::class],
            'ArrayObject' => [new ArrayObject(), ArrayObject::class],
        ];
    }

    #[DataProvider('offendingObjectProvider')]
    #[TestDox('->getExtra() reports the offending object class $expectedClass')]
    public function test_extra_reports_the_offending_object_class(
        object $offending,
        string $expectedClass,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the `extra` payload exists to name WHICH object was
        // misused, so the diagnostic points at the real culprit.
        // Pin that the class name is read from the supplied object
        // (two distinct classes prove it is not hard-coded) and
        // arrives under the `class_name` key.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['class_name' => $expectedClass];
        $exception = new UnseenObjectException($offending);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->jsonSerialize() returns the RFC 9457 problem-details payload')]
    public function test_json_serialises_to_the_rfc9457_payload(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnseenObjectException inherits JsonSerializable from the
        // RFC 9457 base, so json_encode() of an instance must yield a
        // valid problem-details document. Pin the whole payload: the
        // fixed fields the constructor pre-fills (type, title,
        // status), the null `instance`/`detail` slots left unset, and
        // the offending class travelling in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'type'     => 'http://github.com/stuartherbert/stusdevkit/',
            'title'    => 'visitIndexOf() called on an object that '
                . 'NormalisationContext has not seen',
            'status'   => 500,
            'instance' => null,
            'detail'   => null,
            'extra'    => ['class_name' => 'stdClass'],
        ];
        $exception = new UnseenObjectException(new stdClass());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $exception->jsonSerialize();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
