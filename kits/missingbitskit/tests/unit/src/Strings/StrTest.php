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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Strings;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use StusDevKit\MissingBitsKit\Strings\Str;
use StusDevKit\MissingBitsKit\Strings\StrTrimLocation;

#[TestDox(Str::class)]
class StrTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Strings namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract — every
        // caller imports the class by FQN, so moving it is a
        // breaking change for every consumer.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Strings';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(Str::class))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published kind (class vs interface vs trait) is part
        // of the contract — switching kinds would break every
        // consumer that depends on this type.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('publishes ::trim() as a public method')]
    public function test_publishes_trim(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // trim() is part of Str's published API; this test catches
        // accidental renames or scope changes (e.g. dropping `public`)
        // that would silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('trim', $methodNames);
    }

    #[TestDox('publishes ::isEmpty() as a public method')]
    public function test_publishes_isEmpty(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // isEmpty() is part of Str's published API; this test catches
        // accidental renames or scope changes (e.g. dropping `public`)
        // that would silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('isEmpty', $methodNames);
    }

    #[TestDox('publishes ::contains() as a public method')]
    public function test_publishes_contains(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // contains() is part of Str's published API; this test catches
        // accidental renames or scope changes (e.g. dropping `public`)
        // that would silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('contains', $methodNames);
    }

    #[TestDox('publishes ::containsIgnoreCase() as a public method')]
    public function test_publishes_containsIgnoreCase(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // containsIgnoreCase() is part of Str's published API — the
        // UTF-8 aware, case-folding way to search for a substring. This
        // test catches accidental renames or scope changes that would
        // silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('containsIgnoreCase', $methodNames);
    }

    #[TestDox('publishes ::identity() as a public method')]
    public function test_publishes_identity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // identity() is part of Str's published API — the canonical
        // no-op transformer callers pass to the query methods. This
        // test catches accidental renames or scope changes that would
        // silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('identity', $methodNames);
    }

    #[TestDox('publishes ::length() as a public method')]
    public function test_publishes_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // length() is part of Str's published API — the UTF-8 aware
        // way to count a string's characters. This test catches
        // accidental renames or scope changes that would silently
        // break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('length', $methodNames);
    }

    #[TestDox('publishes ::startsWith() as a public method')]
    public function test_publishes_startsWith(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // startsWith() is part of Str's published API — the UTF-8 aware
        // way to check whether a string begins with a needle. This test
        // catches accidental renames or scope changes that would
        // silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('startsWith', $methodNames);
    }

    #[TestDox('publishes ::endsWith() as a public method')]
    public function test_publishes_endsWith(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // endsWith() is part of Str's published API — the UTF-8 aware
        // way to check whether a string ends with a needle. This test
        // catches accidental renames or scope changes that would
        // silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('endsWith', $methodNames);
    }

    #[TestDox('publishes ::foldCase() as a public method')]
    public function test_publishes_foldCase(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // foldCase() is part of Str's published API — the UTF-8 aware
        // Unicode case-folding used for caseless matching. This test
        // catches accidental renames or scope changes that would
        // silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('foldCase', $methodNames);
    }

    #[TestDox('publishes ::toLower() as a public method')]
    public function test_publishes_toLower(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toLower() is part of Str's published API — the UTF-8 aware
        // way to lowercase a string, and a producer callers pass as a
        // transformer. This test catches accidental renames or scope
        // changes that would silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('toLower', $methodNames);
    }

    #[TestDox('publishes ::toUpper() as a public method')]
    public function test_publishes_toUpper(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toUpper() is part of Str's published API — the UTF-8 aware
        // way to uppercase a string, and a producer callers pass as a
        // transformer. This test catches accidental renames or scope
        // changes that would silently break callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m
        ) {
            if ($m->getDeclaringClass()->getName() === Str::class) {
                $methodNames[] = $m->getName();
            }
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains('toUpper', $methodNames);
    }

    #[TestDox('publishes ::TRIM_DEFAULT_CHARS as a public constant')]
    public function test_publishes_TRIM_DEFAULT_CHARS(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_DEFAULT_CHARS is part of Str's published API — callers
        // reference it directly when they want to extend or replace
        // the default trim set. Renaming or hiding it is a breaking
        // change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Str::class);

        // ----------------------------------------------------------------
        // perform the change

        $hasConstant = $reflection->hasConstant('TRIM_DEFAULT_CHARS');
        $reflectionConstant = $reflection->getReflectionConstant(
            'TRIM_DEFAULT_CHARS',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasConstant);
        $this->assertNotFalse($reflectionConstant);
        $this->assertTrue($reflectionConstant->isPublic());
    }

    // ================================================================
    //
    // Shape — trim()
    //
    // ----------------------------------------------------------------

    #[TestDox('::trim() is declared public static')]
    public function test_trim_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // trim() is consumed as a static utility — callers write
        // `Str::trim(...)`, never `(new Str)->trim(...)`. Dropping
        // `static` or narrowing visibility would break every call
        // site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'trim');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::trim() takes parameters [input, trimChars, trimLocation]')]
    public function test_trim_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'trim');
        $expected = ['input', 'trimChars', 'trimLocation'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::trim() returns string')]
    public function test_trim_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'trim');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    #[TestDox('::trim() $trimChars defaults to Str::TRIM_DEFAULT_CHARS')]
    public function test_trim_trimChars_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default value of $trimChars is load-bearing for every
        // caller that omits it. Changing the default silently shifts
        // behaviour, so we pin it here.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'trim');
        $parameters = $method->getParameters();
        $trimCharsParam = $parameters[1];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $trimCharsParam->isDefaultValueAvailable();
        $defaultValue = $trimCharsParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertSame(Str::TRIM_DEFAULT_CHARS, $defaultValue);
    }

    #[TestDox('::trim() $trimLocation defaults to StrTrimLocation::TRIM_START_AND_END')]
    public function test_trim_trimLocation_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default trimLocation determines what happens when a
        // caller writes `Str::trim($input)` with no third argument.
        // Pin the default so a future refactor cannot silently change
        // it from "trim both ends" to anything else.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'trim');
        $parameters = $method->getParameters();
        $trimLocationParam = $parameters[2];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $trimLocationParam->isDefaultValueAvailable();
        $defaultValue = $trimLocationParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertSame(
            StrTrimLocation::TRIM_START_AND_END,
            $defaultValue,
        );
    }

    // ================================================================
    //
    // Shape — TRIM_DEFAULT_CHARS
    //
    // ----------------------------------------------------------------

    #[TestDox('::TRIM_DEFAULT_CHARS is the Unicode `White_Space` set plus NUL')]
    public function test_TRIM_DEFAULT_CHARS_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every character in this set is part of the contract, both
        // membership and ordering. We pin the full literal value so a
        // future edit (adding a codepoint, removing one, or rearranging
        // them) is visible in this test failure.

        // ----------------------------------------------------------------
        // setup your test

        $expected = "\u{0000}"   // NUL
            . "\u{0009}"          // HORIZONTAL TAB
            . "\u{000A}"          // LINE FEED
            . "\u{000B}"          // VERTICAL TAB
            . "\u{000C}"          // FORM FEED
            . "\u{000D}"          // CARRIAGE RETURN
            . "\u{0020}"          // SPACE
            . "\u{0085}"          // NEXT LINE (NEL)
            . "\u{00A0}"          // NO-BREAK SPACE
            . "\u{1680}"          // OGHAM SPACE MARK
            . "\u{2000}"          // EN QUAD
            . "\u{2001}"          // EM QUAD
            . "\u{2002}"          // EN SPACE
            . "\u{2003}"          // EM SPACE
            . "\u{2004}"          // THREE-PER-EM SPACE
            . "\u{2005}"          // FOUR-PER-EM SPACE
            . "\u{2006}"          // SIX-PER-EM SPACE
            . "\u{2007}"          // FIGURE SPACE
            . "\u{2008}"          // PUNCTUATION SPACE
            . "\u{2009}"          // THIN SPACE
            . "\u{200A}"          // HAIR SPACE
            . "\u{2028}"          // LINE SEPARATOR
            . "\u{2029}"          // PARAGRAPH SEPARATOR
            . "\u{202F}"          // NARROW NO-BREAK SPACE
            . "\u{205F}"          // MEDIUM MATHEMATICAL SPACE
            . "\u{3000}";         // IDEOGRAPHIC SPACE

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::TRIM_DEFAULT_CHARS;

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — default (TRIM_START_AND_END, TRIM_DEFAULT_CHARS)
    //
    // ----------------------------------------------------------------

    #[TestDox('strips ASCII whitespace from both ends by default')]
    public function test_strips_ascii_whitespace_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the no-argument form of trim() is the most common call
        // shape: it must strip the classic ASCII whitespace
        // characters (space, tab, newline, carriage return) from
        // both ends of the input.

        // ----------------------------------------------------------------
        // setup your test

        $input = " \t\n\r hello \t\n\r ";
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function unicodeWhitespaceCharsProvider(): array
    {
        // every codepoint named here is in TRIM_DEFAULT_CHARS by
        // design. We list them with human-readable names so the
        // TestDox sentence reads as a clear assertion about which
        // characters the default set covers.
        return [
            'NUL' => ['NUL', "\u{0000}"],
            'VERTICAL TAB' => ['VERTICAL TAB', "\u{000B}"],
            'FORM FEED' => ['FORM FEED', "\u{000C}"],
            'NEL' => ['NEL', "\u{0085}"],
            'NBSP' => ['NBSP', "\u{00A0}"],
            'OGHAM SPACE MARK' => ['OGHAM SPACE MARK', "\u{1680}"],
            'EN QUAD' => ['EN QUAD', "\u{2000}"],
            'EM SPACE' => ['EM SPACE', "\u{2003}"],
            'HAIR SPACE' => ['HAIR SPACE', "\u{200A}"],
            'LINE SEPARATOR' => ['LINE SEPARATOR', "\u{2028}"],
            'PARAGRAPH SEPARATOR' => ['PARAGRAPH SEPARATOR', "\u{2029}"],
            'NARROW NBSP' => ['NARROW NBSP', "\u{202F}"],
            'MEDIUM MATH SPACE' => ['MEDIUM MATH SPACE', "\u{205F}"],
            'IDEOGRAPHIC SPACE' => ['IDEOGRAPHIC SPACE', "\u{3000}"],
        ];
    }

    #[TestDox('strips $charName from both ends by default')]
    #[DataProvider('unicodeWhitespaceCharsProvider')]
    public function test_strips_unicode_whitespace_by_default(
        string $charName,
        string $char,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_DEFAULT_CHARS extends beyond ASCII whitespace to cover
        // every Unicode `White_Space` codepoint plus NUL. This test
        // confirms each named codepoint is actually stripped by the
        // no-argument form of trim().

        // ----------------------------------------------------------------
        // setup your test

        // shorthand
        $padding = $char . $char;
        $input = $padding . 'hello' . $padding;
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns input unchanged when no default chars sit at either end')]
    public function test_returns_unchanged_when_no_trim_chars_at_ends(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // trim() only removes a contiguous run from the very ends of
        // the input — when neither end starts with a trim char, the
        // input must come back identical.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'hello world';
        $expected = 'hello world';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns the empty string when input is entirely trim chars')]
    public function test_returns_empty_when_input_is_all_trim_chars(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when every codepoint in the input is in $trimChars, the
        // maximal leading + trailing runs consume the whole string
        // and the result is empty.

        // ----------------------------------------------------------------
        // setup your test

        $input = " \t\n\r \t\n\r";
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('preserves internal whitespace and only strips from the ends')]
    public function test_preserves_internal_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // trim() must not touch whitespace that sits between
        // non-trim characters. Stripping internal whitespace would
        // surprise callers using trim() for normalisation.

        // ----------------------------------------------------------------
        // setup your test

        $input = '  hello   world  ';
        $expected = 'hello   world';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — StrTrimLocation::TRIM_START
    //
    // ----------------------------------------------------------------

    #[TestDox('TRIM_START strips leading trim chars')]
    public function test_TRIM_START_strips_leading(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_START delegates to mb_ltrim(); the leading run of
        // trim chars must be consumed.

        // ----------------------------------------------------------------
        // setup your test

        $input = '   hello';
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim(
            $input,
            trimLocation: StrTrimLocation::TRIM_START,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('TRIM_START leaves trailing trim chars untouched')]
    public function test_TRIM_START_leaves_trailing_alone(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_START is one-sided — any trim chars at the trailing
        // end must survive. This is what makes TRIM_START different
        // from TRIM_START_AND_END.

        // ----------------------------------------------------------------
        // setup your test

        $input = '   hello   ';
        $expected = 'hello   ';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim(
            $input,
            trimLocation: StrTrimLocation::TRIM_START,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — StrTrimLocation::TRIM_END
    //
    // ----------------------------------------------------------------

    #[TestDox('TRIM_END strips trailing trim chars')]
    public function test_TRIM_END_strips_trailing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_END delegates to mb_rtrim(); the trailing run of
        // trim chars must be consumed.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'hello   ';
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim(
            $input,
            trimLocation: StrTrimLocation::TRIM_END,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('TRIM_END leaves leading trim chars untouched')]
    public function test_TRIM_END_leaves_leading_alone(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_END is one-sided — any trim chars at the leading end
        // must survive. This is what makes TRIM_END different from
        // TRIM_START_AND_END.

        // ----------------------------------------------------------------
        // setup your test

        $input = '   hello   ';
        $expected = '   hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim(
            $input,
            trimLocation: StrTrimLocation::TRIM_END,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — StrTrimLocation::TRIM_NONE
    //
    // ----------------------------------------------------------------

    #[TestDox('TRIM_NONE returns the input unchanged')]
    public function test_TRIM_NONE_returns_input_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // TRIM_NONE is the "do nothing" branch — even when the input
        // is padded on both sides with trim chars, the result must
        // equal the input byte-for-byte.

        // ----------------------------------------------------------------
        // setup your test

        $input = '   hello   ';
        $expected = '   hello   ';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim(
            $input,
            trimLocation: StrTrimLocation::TRIM_NONE,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — custom $trimChars
    //
    // ----------------------------------------------------------------

    #[TestDox('strips only the characters listed in a caller-supplied $trimChars')]
    public function test_custom_trimChars(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller can supply their own set of trim characters. Only
        // those characters must be stripped — characters that are
        // not in the set must survive even if they look "whitespace
        // adjacent".

        // ----------------------------------------------------------------
        // setup your test

        $input = 'xxxhelloxxx';
        $trimChars = 'x';
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input, $trimChars);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('treats a multibyte character in $trimChars as a single codepoint')]
    public function test_multibyte_trimChars(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // because we delegate to mb_trim() with UTF-8 hard-coded, a
        // multibyte character (here: NBSP, U+00A0, two bytes in
        // UTF-8) must match a complete NBSP in the input — not its
        // individual bytes. This is the multibyte-safety guarantee
        // that distinguishes Str::trim() from PHP's byte-level
        // trim().

        // ----------------------------------------------------------------
        // setup your test

        // shorthand
        $nbsp = "\u{00A0}";
        $input = $nbsp . $nbsp . 'hello' . $nbsp . $nbsp;
        $trimChars = $nbsp;
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input, $trimChars);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — Here Be Dragons
    //
    // ----------------------------------------------------------------

    #[TestDox('returns input unchanged when $trimChars is the empty string')]
    public function test_empty_trimChars_is_a_no_op(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty $trimChars means "no characters to strip" — the
        // docblock pins this as a silent no-op, so we lock it down
        // here. Any future refactor that turns this into an error
        // must update both the docblock and this test.

        // ----------------------------------------------------------------
        // setup your test

        $input = '   hello   ';
        $trimChars = '';
        $expected = '   hello   ';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input, $trimChars);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('treats ".." in $trimChars as the literal characters dot-dot, NOT as a range')]
    public function test_dot_dot_is_literal_not_range(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's byte-level trim() supports `..` as a character range
        // operator — `trim($s, "a..z")` strips a–z. We delegate to
        // mb_trim() which does not honour that syntax, so `..` must
        // be treated as the literal characters `a`, `.`, `z`. This
        // test is the contract guard against anyone "fixing" mb_trim
        // to add range support in a future PHP release.

        // ----------------------------------------------------------------
        // setup your test

        // 'a' and 'z' (and '.') are the only literal trim chars
        // here; the letters in the middle must survive.
        $input = 'azhelloza';
        $trimChars = 'a..z';
        $expected = 'hello';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input, $trimChars);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does NOT strip lowercase letters when $trimChars is "a..z"')]
    public function test_dot_dot_does_not_enable_range(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // companion to the previous test: prove that letters in the
        // middle of the alphabet are not stripped when $trimChars is
        // "a..z". If a future PHP release added range support to
        // mb_trim(), this is the test that would catch it.

        // ----------------------------------------------------------------
        // setup your test

        // 'm' is in the a-z range but not literally in "a..z" — it
        // must survive.
        $input = 'mmm';
        $trimChars = 'a..z';
        $expected = 'mmm';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::trim($input, $trimChars);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — isEmpty()
    //
    // ----------------------------------------------------------------

    #[TestDox('::isEmpty() is declared public static')]
    public function test_isEmpty_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // isEmpty() is consumed as a static utility — callers write
        // `Str::isEmpty(...)`, never `(new Str)->isEmpty(...)`.
        // Dropping `static` or narrowing visibility would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'isEmpty');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::isEmpty() takes parameters [input, transformer]')]
    public function test_isEmpty_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'isEmpty');
        $expected = ['input', 'transformer'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::isEmpty() returns bool')]
    public function test_isEmpty_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'isEmpty');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
    }

    #[TestDox('::isEmpty() $transformer defaults to null')]
    public function test_isEmpty_transformer_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default of `null` is the contract: with no transformer,
        // isEmpty() tests the input exactly as given. Pin it so a
        // future refactor cannot silently start transforming every
        // input.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'isEmpty');
        $parameters = $method->getParameters();
        $transformerParam = $parameters[1];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $transformerParam->isDefaultValueAvailable();
        $defaultValue = $transformerParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertNull($defaultValue);
    }

    // ================================================================
    //
    // Behaviour — isEmpty() (no transformer)
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when input is null')]
    public function test_isEmpty_null_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // isEmpty() accepts null for convenience, so callers do not
        // have to guard their own null checks before calling. A null
        // input is, by definition, empty.

        // ----------------------------------------------------------------
        // setup your test

        $input = null;
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns true when input is a zero-length string')]
    public function test_isEmpty_zero_length_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a zero-length string is empty regardless of any transformer —
        // this is the fast-path that short-circuits before the
        // transformer is ever called.

        // ----------------------------------------------------------------
        // setup your test

        $input = '';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when input is only whitespace and no transformer is given')]
    public function test_isEmpty_whitespace_without_transformer_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, isEmpty() tests the input exactly as
        // given. A whitespace-only string has non-zero length, so it
        // is NOT empty. This is the literal-emptiness default.

        // ----------------------------------------------------------------
        // setup your test

        $input = " \t\n\r ";
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when input contains visible content')]
    public function test_isEmpty_visible_content_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a string with non-whitespace content is never empty, with or
        // without a transformer.

        // ----------------------------------------------------------------
        // setup your test

        $input = '  hello  ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — isEmpty() with a transformer
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when a trimming transformer reduces the input to empty')]
    public function test_isEmpty_trimming_transformer_empties_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller who wants whitespace-only input to count as empty
        // passes a trimming transformer; isEmpty() applies it, sees a
        // zero-length result, and reports empty.

        // ----------------------------------------------------------------
        // setup your test

        $input = " \t\n\r ";
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input, fn(string $v) => Str::trim($v));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns true when a trimming transformer reduces a Unicode-whitespace input to empty')]
    public function test_isEmpty_trimming_transformer_empties_unicode_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Str::trim()'s default char set covers the full Unicode
        // whitespace set, so a trimming transformer empties a string of
        // only NBSP (U+00A0) — proving the transformer is not limited
        // to ASCII whitespace.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand
        $nbsp = "\u{00A0}";
        $input = $nbsp . $nbsp;
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input, fn(string $v) => Str::trim($v));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the transformer leaves visible content')]
    public function test_isEmpty_transformer_leaves_content_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the transformer leaves any content behind, the input is
        // not empty. Here a transformer that trims `x` from "xxoxx"
        // leaves "o".

        // ----------------------------------------------------------------
        // setup your test

        $input = 'xxoxx';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input, fn(string $v) => Str::trim($v, 'x'));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when input is null')]
    public function test_isEmpty_skips_transformer_for_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // null short-circuits to empty before transforming. We prove
        // the transformer never runs by making it throw: if it fired,
        // the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty(null, $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when input is already zero-length')]
    public function test_isEmpty_skips_transformer_for_zero_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a zero-length string short-circuits to empty before
        // transforming. We prove the transformer never runs by making it
        // throw: if it fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty('', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — length()
    //
    // ----------------------------------------------------------------

    #[TestDox('::length() is declared public static')]
    public function test_length_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // length() is consumed as a static utility — callers write
        // `Str::length(...)`, never `(new Str)->length(...)`. Dropping
        // `static` or narrowing visibility would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'length');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::length() takes parameters [input, transformer]')]
    public function test_length_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'length');
        $expected = ['input', 'transformer'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::length() returns int')]
    public function test_length_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'length');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('int', $returnType->getName());
    }

    #[TestDox('::length() $transformer defaults to null')]
    public function test_length_transformer_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default of `null` is the contract: with no transformer,
        // length() counts the input exactly as given. Pin it so a
        // future refactor cannot silently start transforming every
        // input.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'length');
        $parameters = $method->getParameters();
        $transformerParam = $parameters[1];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $transformerParam->isDefaultValueAvailable();
        $defaultValue = $transformerParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertNull($defaultValue);
    }

    // ================================================================
    //
    // Behaviour — length() (no transformer)
    //
    // ----------------------------------------------------------------

    #[TestDox('returns 0 when input is null')]
    public function test_length_null_returns_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // length() accepts null for convenience, so callers do not have
        // to guard their own null checks before calling. A null input
        // has no characters to count, so its length is 0.

        // ----------------------------------------------------------------
        // setup your test

        $input = null;
        $expected = 0;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns 0 when input is a zero-length string')]
    public function test_length_zero_length_returns_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a zero-length string has zero characters regardless of any
        // transformer — this is the fast-path that short-circuits
        // before the transformer is ever called.

        // ----------------------------------------------------------------
        // setup your test

        $input = '';
        $expected = 0;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('counts the characters in an ASCII string')]
    public function test_length_counts_ascii_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // for plain ASCII, one byte is one character, so length()
        // returns the obvious count.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'hello';
        $expected = 5;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('counts a multibyte character as a single codepoint, not its bytes')]
    public function test_length_counts_multibyte_as_codepoints(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // length() is UTF-8 aware: each of these three CJK characters is
        // three bytes in UTF-8, so a byte-level strlen() would report 9.
        // length() counts codepoints, so it returns 3.

        // ----------------------------------------------------------------
        // setup your test

        $input = '日本語';
        $expected = 3;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — length() with a transformer
    //
    // ----------------------------------------------------------------

    #[TestDox('measures the transformed length when a trimming transformer is given')]
    public function test_length_applies_transformer_before_counting(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller who wants the length of the meaningful content passes
        // a trimming transformer; length() applies it first, then counts
        // what is left. "  hello  " trims to "hello", so the length is
        // 5, not 9.

        // ----------------------------------------------------------------
        // setup your test

        $input = '  hello  ';
        $expected = 5;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length($input, fn(string $v) => Str::trim($v));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when input is null')]
    public function test_length_skips_transformer_for_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // null short-circuits to 0 before transforming. We prove the
        // transformer never runs by making it throw: if it fired, the
        // exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = 0;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length(null, $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when input is already zero-length')]
    public function test_length_skips_transformer_for_zero_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a zero-length string short-circuits to 0 before transforming.
        // We prove the transformer never runs by making it throw: if it
        // fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = 0;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::length('', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — identity()
    //
    // ----------------------------------------------------------------

    #[TestDox('::identity() is declared public static')]
    public function test_identity_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // identity() is the no-op transformer, consumed as a static
        // utility (`Str::identity(...)`). Dropping `static` or
        // narrowing visibility would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'identity');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::identity() takes parameters [input]')]
    public function test_identity_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // identity() is a producer, not a query — it takes only the
        // input it returns, and (unlike the query methods) does NOT
        // take a transformer of its own.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'identity');
        $expected = ['input'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::identity() returns string')]
    public function test_identity_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // identity() is a `string -> string` transformer, so it must
        // satisfy the `callable(string): string` contract the query
        // methods expect.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'identity');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // Behaviour — identity()
    //
    // ----------------------------------------------------------------

    #[TestDox('returns its input unchanged')]
    public function test_identity_returns_input_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the whole point of identity() is that what goes in comes
        // straight back out, byte for byte.

        // ----------------------------------------------------------------
        // setup your test

        $input = '  hello world  ';
        $expected = '  hello world  ';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::identity($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns an empty string unchanged')]
    public function test_identity_returns_empty_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the empty string is a legitimate input; identity() returns
        // it unchanged rather than treating it specially.

        // ----------------------------------------------------------------
        // setup your test

        $input = '';
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::identity($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns a multibyte string unchanged')]
    public function test_identity_returns_multibyte_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // identity() does no encoding work, so a UTF-8 string comes
        // back byte-identical.

        // ----------------------------------------------------------------
        // setup your test

        $input = "caf\u{00E9}";
        $expected = "caf\u{00E9}";

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::identity($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('used as an isEmpty transformer, leaves a whitespace string non-empty')]
    public function test_identity_as_isEmpty_transformer_is_a_noop(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // passing Str::identity(...) as the transformer is behaviourally
        // identical to passing no transformer: a whitespace-only string
        // is left intact, so isEmpty() reports it as NOT empty.

        // ----------------------------------------------------------------
        // setup your test

        $input = '   ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::isEmpty($input, Str::identity(...));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — toLower()
    //
    // ----------------------------------------------------------------

    #[TestDox('::toLower() is declared public static')]
    public function test_toLower_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toLower() is consumed as a static utility — callers write
        // `Str::toLower(...)`, never `(new Str)->toLower(...)`. Dropping
        // `static` or narrowing visibility would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'toLower');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::toLower() takes parameters [input]')]
    public function test_toLower_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toLower() is a producer, not a query — it takes only the
        // input it transforms, and (unlike the query methods) does NOT
        // take a transformer of its own.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'toLower');
        $expected = ['input'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::toLower() returns string')]
    public function test_toLower_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toLower() is a `string -> string` transformer, so it must
        // satisfy the `callable(string): string` contract the query
        // methods expect.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'toLower');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // Behaviour — toLower()
    //
    // ----------------------------------------------------------------

    #[TestDox('lowercases an ASCII string')]
    public function test_toLower_lowercases_ascii(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the everyday case: uppercase ASCII letters become their
        // lowercase equivalents.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'HELLO WORLD';
        $expected = 'hello world';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toLower($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('lowercases a multibyte string')]
    public function test_toLower_lowercases_multibyte(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toLower() is UTF-8 aware: "CAFÉ" lowercases to "café",
        // folding the multibyte É (U+00C9) to é (U+00E9). A byte-level
        // strtolower() would leave the É untouched.

        // ----------------------------------------------------------------
        // setup your test

        $input = "CAF\u{00C9}";
        $expected = "caf\u{00E9}";

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toLower($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('leaves an already-lowercase string unchanged')]
    public function test_toLower_leaves_lowercase_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a string that is already lowercase comes back unchanged.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'already lowercase';
        $expected = 'already lowercase';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toLower($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns an empty string unchanged')]
    public function test_toLower_returns_empty_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the empty string is a legitimate input; toLower() returns it
        // unchanged rather than treating it specially.

        // ----------------------------------------------------------------
        // setup your test

        $input = '';
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toLower($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('leaves a caseless string unchanged')]
    public function test_toLower_leaves_caseless_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // characters with no lowercase form — here three CJK ideographs
        // — pass through untouched.

        // ----------------------------------------------------------------
        // setup your test

        $input = '日本語';
        $expected = '日本語';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toLower($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — toUpper()
    //
    // ----------------------------------------------------------------

    #[TestDox('::toUpper() is declared public static')]
    public function test_toUpper_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toUpper() is consumed as a static utility — callers write
        // `Str::toUpper(...)`, never `(new Str)->toUpper(...)`. Dropping
        // `static` or narrowing visibility would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'toUpper');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::toUpper() takes parameters [input]')]
    public function test_toUpper_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toUpper() is a producer, not a query — it takes only the
        // input it transforms, and (unlike the query methods) does NOT
        // take a transformer of its own.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'toUpper');
        $expected = ['input'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::toUpper() returns string')]
    public function test_toUpper_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toUpper() is a `string -> string` transformer, so it must
        // satisfy the `callable(string): string` contract the query
        // methods expect.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'toUpper');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // Behaviour — toUpper()
    //
    // ----------------------------------------------------------------

    #[TestDox('uppercases an ASCII string')]
    public function test_toUpper_uppercases_ascii(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the everyday case: lowercase ASCII letters become their
        // uppercase equivalents.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'hello world';
        $expected = 'HELLO WORLD';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toUpper($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('uppercases a multibyte string')]
    public function test_toUpper_uppercases_multibyte(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // toUpper() is UTF-8 aware: "café" uppercases to "CAFÉ",
        // folding the multibyte é (U+00E9) to É (U+00C9). A byte-level
        // strtoupper() would leave the é untouched.

        // ----------------------------------------------------------------
        // setup your test

        $input = "caf\u{00E9}";
        $expected = "CAF\u{00C9}";

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toUpper($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('leaves an already-uppercase string unchanged')]
    public function test_toUpper_leaves_uppercase_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a string that is already uppercase comes back unchanged.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'ALREADY UPPERCASE';
        $expected = 'ALREADY UPPERCASE';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toUpper($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns an empty string unchanged')]
    public function test_toUpper_returns_empty_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the empty string is a legitimate input; toUpper() returns it
        // unchanged rather than treating it specially.

        // ----------------------------------------------------------------
        // setup your test

        $input = '';
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toUpper($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('leaves a caseless string unchanged')]
    public function test_toUpper_leaves_caseless_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // characters with no uppercase form — here three CJK ideographs
        // — pass through untouched.

        // ----------------------------------------------------------------
        // setup your test

        $input = '日本語';
        $expected = '日本語';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::toUpper($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — foldCase()
    //
    // ----------------------------------------------------------------

    #[TestDox('::foldCase() is declared public static')]
    public function test_foldCase_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // foldCase() is consumed as a static utility — callers write
        // `Str::foldCase(...)`, never `(new Str)->foldCase(...)`.
        // Dropping `static` or narrowing visibility would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'foldCase');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::foldCase() takes parameters [input]')]
    public function test_foldCase_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // foldCase() is a producer, not a query — it takes only the
        // input it transforms, and (unlike the query methods) does NOT
        // take a transformer of its own.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'foldCase');
        $expected = ['input'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::foldCase() returns string')]
    public function test_foldCase_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // foldCase() is a `string -> string` transformer, so it must
        // satisfy the `callable(string): string` contract the query
        // methods expect.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'foldCase');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // Behaviour — foldCase()
    //
    // ----------------------------------------------------------------

    #[TestDox('folds an ASCII string to lowercase')]
    public function test_foldCase_folds_ascii_to_lowercase(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // for ASCII, default case-folding lowercases: "Hello World"
        // folds to "hello world", the common-case behaviour callers
        // expect from a caseless-matching key.

        // ----------------------------------------------------------------
        // setup your test

        $input = 'Hello World';
        $expected = 'hello world';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::foldCase($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('folds a multibyte string')]
    public function test_foldCase_folds_multibyte(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // foldCase() is UTF-8 aware: "CAFÉ" folds to "café", folding the
        // multibyte É (U+00C9) to é (U+00E9). A byte-level fold would
        // leave the É untouched.

        // ----------------------------------------------------------------
        // setup your test

        $input = "CAF\u{00C9}";
        $expected = "caf\u{00E9}";

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::foldCase($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('folds the German eszett to a double s')]
    public function test_foldCase_folds_eszett_to_double_s(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the property that separates foldCase() from toLower():
        // default case-folding expands ß to "ss", so "Straße" folds to
        // "strasse". A simple lowercase (toLower) would leave the ß in
        // place, and "STRASSE" would never match "straße".

        // ----------------------------------------------------------------
        // setup your test

        $input = "Stra\u{00DF}e";
        $expected = 'strasse';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::foldCase($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('folds to a result that can be longer than the input')]
    public function test_foldCase_result_can_change_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // folding is not one-to-one: "Straße" (6 characters) folds to
        // "strasse" (7 characters), because ß expands to "ss". This
        // pins the contract that a foldCase() result may not line up
        // with the input, so callers must never map offsets back onto
        // the original string.

        // ----------------------------------------------------------------
        // setup your test

        $input = "Stra\u{00DF}e";
        $expectedInputLength = 6;
        $expectedFoldedLength = 7;

        // ----------------------------------------------------------------
        // perform the change

        $inputLength = mb_strlen($input, 'UTF-8');
        $foldedLength = mb_strlen(Str::foldCase($input), 'UTF-8');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedInputLength, $inputLength);
        $this->assertSame($expectedFoldedLength, $foldedLength);
    }

    #[TestDox('returns an empty string unchanged')]
    public function test_foldCase_returns_empty_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the empty string is a legitimate input; foldCase() returns it
        // unchanged rather than treating it specially.

        // ----------------------------------------------------------------
        // setup your test

        $input = '';
        $expected = '';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::foldCase($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('leaves a caseless string unchanged')]
    public function test_foldCase_leaves_caseless_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // characters with no case — here three CJK ideographs — have no
        // folded form and pass through untouched.

        // ----------------------------------------------------------------
        // setup your test

        $input = '日本語';
        $expected = '日本語';

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::foldCase($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — contains()
    //
    // ----------------------------------------------------------------

    #[TestDox('::contains() is declared public static')]
    public function test_contains_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // contains() is consumed as a static utility — callers write
        // `Str::contains(...)`, never `(new Str)->contains(...)`.
        // Dropping `static` or narrowing visibility would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'contains');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::contains() takes parameters [haystack, needle, transformer]')]
    public function test_contains_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'contains');
        $expected = ['haystack', 'needle', 'transformer'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::contains() returns bool')]
    public function test_contains_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'contains');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
    }

    #[TestDox('::contains() $transformer defaults to null')]
    public function test_contains_transformer_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default of `null` is the contract: with no transformer,
        // contains() searches for the needle exactly as given. Pin it
        // so a future refactor cannot silently start transforming every
        // needle.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'contains');
        $parameters = $method->getParameters();
        $transformerParam = $parameters[2];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $transformerParam->isDefaultValueAvailable();
        $defaultValue = $transformerParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertNull($defaultValue);
    }

    // ================================================================
    //
    // Behaviour — contains() with a string haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when the string haystack contains the needle')]
    public function test_contains_string_haystack_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the simplest call shape: a single string haystack that
        // contains the needle as a substring.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'o w';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the string haystack does not contain the needle')]
    public function test_contains_string_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the needle is not a substring of the haystack, contains()
        // must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'xyz';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is case-sensitive')]
    public function test_contains_is_case_sensitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // contains() delegates to str_contains(), which is
        // case-sensitive. A needle that differs only in case must not
        // match.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello World';
        $needle = 'hello';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — contains() with an array haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when only a later element of the array haystack contains the needle')]
    public function test_contains_array_haystack_found_in_later_element(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an array haystack is searched element by element. Putting the
        // match in a later element proves contains() does not stop at
        // the first element.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['alpha', 'beta'];
        $needle = 'eta';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when no element of the array haystack contains the needle')]
    public function test_contains_array_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when none of the array elements contain the needle, contains()
        // must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['alpha', 'beta', 'gamma'];
        $needle = 'delta';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — contains() with an empty haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the haystack is an empty string')]
    public function test_contains_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty string haystack contains nothing, so contains() must
        // report false for any needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '';
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the haystack is an empty array')]
    public function test_contains_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack has no strings to search, so
        // contains() must report false for any needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = [];
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — contains() with the falsy "0" haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when searching the string "0" for the needle "0"')]
    public function test_contains_zero_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "0" is a valid, non-empty haystack, but PHP's empty() treats
        // it as falsy. This test is the regression guard against an
        // empty()-based emptiness check silently discarding it.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '0';
        $needle = '0';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — contains() with UTF-8 data
    //
    // ----------------------------------------------------------------

    #[TestDox('finds a multibyte needle inside the haystack')]
    public function test_contains_finds_multibyte_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a multibyte character (here é, U+00E9, two bytes in UTF-8)
        // must be found as a whole codepoint inside a UTF-8 haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "caf\u{00E9}";
        $needle = "\u{00E9}";
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not match an ASCII needle against the bytes of a multibyte character')]
    public function test_contains_ascii_needle_does_not_match_multibyte_bytes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UTF-8 is self-synchronising: the bytes of a multibyte
        // character (here ï, U+00EF) never overlap the ASCII range, so
        // an ASCII needle ("i") cannot false-match inside it. This is
        // the property that makes byte-level str_contains() correct on
        // UTF-8.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "na\u{00EF}ve";
        $needle = 'i';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — contains() empty / whitespace needle (no transformer)
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the needle is an empty string')]
    public function test_contains_empty_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty needle can never be "contained" in any meaningful
        // sense; contains() rejects it up front and reports false,
        // regardless of the haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello';
        $needle = '';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('searches the needle verbatim when no transformer is given')]
    public function test_contains_searches_needle_verbatim_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, surrounding whitespace is part of the
        // search term. A padded needle is searched verbatim and is not
        // found inside the unpadded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello';
        $needle = ' hello ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('searches for a whitespace-only needle when no transformer is given')]
    public function test_contains_whitespace_needle_searched_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, a whitespace-only needle is a legitimate
        // search term, and is found when the haystack contains that run
        // of whitespace.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'a   b';
        $needle = '   ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — contains() with a transformer
    //
    // ----------------------------------------------------------------

    #[TestDox('transforms the needle before searching when a transformer is given')]
    public function test_contains_transforms_needle_when_asked(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller who passes a trimming transformer strips the needle's
        // surrounding whitespace before the search, so the padded needle
        // now matches the unpadded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello';
        $needle = '  hello  ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when a trimming transformer reduces the needle to empty')]
    public function test_contains_transformer_empties_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a whitespace-only needle reduces to nothing under a trimming
        // transformer; an empty needle can never be "contained", so
        // contains() reports false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'a b c';
        $needle = '   ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('applies the transformer to the needle only, never the haystack')]
    public function test_contains_transformer_does_not_touch_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the transformer is applied to the needle alone. Here a trimming
        // transformer strips the needle's padding, but the haystack's own
        // surrounding whitespace is left intact — so the trimmed needle
        // is found inside the still-padded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '   hello   ';
        $needle = '  hello  ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the string haystack is empty')]
    public function test_contains_skips_transformer_for_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty haystack short-circuits to false before transforming.
        // We prove the transformer never runs by making it throw: if it
        // fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains('', 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the array haystack is empty')]
    public function test_contains_skips_transformer_for_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack short-circuits to false before
        // transforming. We prove the transformer never runs by making it
        // throw: if it fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::contains([], 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — startsWith()
    //
    // ----------------------------------------------------------------

    #[TestDox('::startsWith() is declared public static')]
    public function test_startsWith_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // startsWith() is consumed as a static utility — callers write
        // `Str::startsWith(...)`, never `(new Str)->startsWith(...)`.
        // Dropping `static` or narrowing visibility would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'startsWith');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::startsWith() takes parameters [haystack, needle, transformer]')]
    public function test_startsWith_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'startsWith');
        $expected = ['haystack', 'needle', 'transformer'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::startsWith() returns bool')]
    public function test_startsWith_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'startsWith');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
    }

    #[TestDox('::startsWith() $transformer defaults to null')]
    public function test_startsWith_transformer_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default of `null` is the contract: with no transformer,
        // startsWith() checks the needle exactly as given. Pin it so a
        // future refactor cannot silently start transforming every
        // needle.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'startsWith');
        $parameters = $method->getParameters();
        $transformerParam = $parameters[2];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $transformerParam->isDefaultValueAvailable();
        $defaultValue = $transformerParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertNull($defaultValue);
    }

    // ================================================================
    //
    // Behaviour — startsWith() with a string haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when the string haystack starts with the needle')]
    public function test_startsWith_string_haystack_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the simplest call shape: a single string haystack whose opening
        // characters are the needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'hello';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the string haystack does not start with the needle')]
    public function test_startsWith_string_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the haystack's opening characters are not the needle,
        // startsWith() must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'xyz';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the needle appears but not at the start')]
    public function test_startsWith_needle_present_but_not_leading(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the property that separates startsWith() from
        // contains(): a needle that is a substring elsewhere in the
        // haystack, but not at its very start, must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'world';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is case-sensitive')]
    public function test_startsWith_is_case_sensitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // startsWith() delegates to str_starts_with(), which is
        // case-sensitive. A needle that differs only in case must not
        // match.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello World';
        $needle = 'hello';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — startsWith() with an array haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when only a later element of the array haystack starts with the needle')]
    public function test_startsWith_array_haystack_found_in_later_element(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an array haystack is checked element by element. Putting the
        // match in a later element proves startsWith() does not stop at
        // the first element.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['alpha', 'beta'];
        $needle = 'bet';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when no element of the array haystack starts with the needle')]
    public function test_startsWith_array_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when no array element opens with the needle, startsWith() must
        // report false — even if an element contains the needle further
        // in ("gamma" contains "amm", but does not start with it).

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['alpha', 'beta', 'gamma'];
        $needle = 'amm';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — startsWith() with an empty haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the haystack is an empty string')]
    public function test_startsWith_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty string has no opening characters, so startsWith() must
        // report false for any (non-empty) needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '';
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the haystack is an empty array')]
    public function test_startsWith_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack has no strings to check, so
        // startsWith() must report false for any needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = [];
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — startsWith() with the falsy "0" haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when checking the string "0" starts with the needle "0"')]
    public function test_startsWith_zero_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "0" is a valid, non-empty haystack, but PHP's empty() treats
        // it as falsy. This test is the regression guard against an
        // empty()-based emptiness check silently discarding it.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '0';
        $needle = '0';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — startsWith() with UTF-8 data
    //
    // ----------------------------------------------------------------

    #[TestDox('matches a multibyte needle at the start of the haystack')]
    public function test_startsWith_finds_leading_multibyte_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a multibyte character (here é, U+00E9, two bytes in UTF-8) must
        // be matched as a whole codepoint at the start of a UTF-8
        // haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "\u{00E9}clair";
        $needle = "\u{00E9}";
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not match an ASCII needle against the bytes of a leading multibyte character')]
    public function test_startsWith_ascii_needle_does_not_match_leading_multibyte_bytes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UTF-8 is self-synchronising: the bytes of a multibyte character
        // (here ï, U+00EF) never overlap the ASCII range, so an ASCII
        // needle ("i") cannot false-match against a leading ï. This is
        // the property that makes byte-level str_starts_with() correct on
        // UTF-8.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "\u{00EF}sland";
        $needle = 'i';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — startsWith() empty / whitespace needle (no transformer)
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the needle is an empty string')]
    public function test_startsWith_empty_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's str_starts_with() reports true for an empty needle (every
        // string "starts with" nothing). startsWith() deliberately
        // diverges: like contains(), it rejects an empty needle up front
        // and reports false, because "starts with nothing" is not a
        // meaningful query.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello';
        $needle = '';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('checks the needle verbatim when no transformer is given')]
    public function test_startsWith_checks_needle_verbatim_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, surrounding whitespace is part of the
        // needle. A padded needle is checked verbatim and does not match
        // the start of the unpadded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = ' hello';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('checks for a whitespace-only needle when no transformer is given')]
    public function test_startsWith_whitespace_needle_checked_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, a whitespace-only needle is a legitimate
        // needle, and matches when the haystack opens with that run of
        // whitespace.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '   indented';
        $needle = '   ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — startsWith() with a transformer
    //
    // ----------------------------------------------------------------

    #[TestDox('transforms the needle before checking when a transformer is given')]
    public function test_startsWith_transforms_needle_when_asked(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller who passes a trimming transformer strips the needle's
        // surrounding whitespace before the check, so the padded needle
        // now matches the start of the unpadded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = '  hello  ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when a trimming transformer reduces the needle to empty')]
    public function test_startsWith_transformer_empties_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a whitespace-only needle reduces to nothing under a trimming
        // transformer; an empty needle is never a meaningful "starts
        // with", so startsWith() reports false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '   indented';
        $needle = '   ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('applies the transformer to the needle only, never the haystack')]
    public function test_startsWith_transformer_does_not_touch_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the transformer is applied to the needle alone. Here a trimming
        // transformer strips the needle's padding, but the haystack's own
        // leading whitespace is left intact — so the trimmed needle does
        // NOT match the start of the still-padded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '   hello';
        $needle = '  hello  ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the string haystack is empty')]
    public function test_startsWith_skips_transformer_for_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty haystack short-circuits to false before transforming.
        // We prove the transformer never runs by making it throw: if it
        // fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith('', 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the array haystack is empty')]
    public function test_startsWith_skips_transformer_for_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack short-circuits to false before
        // transforming. We prove the transformer never runs by making it
        // throw: if it fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::startsWith([], 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — endsWith()
    //
    // ----------------------------------------------------------------

    #[TestDox('::endsWith() is declared public static')]
    public function test_endsWith_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // endsWith() is consumed as a static utility — callers write
        // `Str::endsWith(...)`, never `(new Str)->endsWith(...)`.
        // Dropping `static` or narrowing visibility would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'endsWith');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::endsWith() takes parameters [haystack, needle, transformer]')]
    public function test_endsWith_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'endsWith');
        $expected = ['haystack', 'needle', 'transformer'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::endsWith() returns bool')]
    public function test_endsWith_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'endsWith');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
    }

    #[TestDox('::endsWith() $transformer defaults to null')]
    public function test_endsWith_transformer_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default of `null` is the contract: with no transformer,
        // endsWith() checks the needle exactly as given. Pin it so a
        // future refactor cannot silently start transforming every
        // needle.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'endsWith');
        $parameters = $method->getParameters();
        $transformerParam = $parameters[2];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $transformerParam->isDefaultValueAvailable();
        $defaultValue = $transformerParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertNull($defaultValue);
    }

    // ================================================================
    //
    // Behaviour — endsWith() with a string haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when the string haystack ends with the needle')]
    public function test_endsWith_string_haystack_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the simplest call shape: a single string haystack whose closing
        // characters are the needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'world';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the string haystack does not end with the needle')]
    public function test_endsWith_string_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the haystack's closing characters are not the needle,
        // endsWith() must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'xyz';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the needle appears but not at the end')]
    public function test_endsWith_needle_present_but_not_trailing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the property that separates endsWith() from
        // contains(): a needle that is a substring elsewhere in the
        // haystack, but not at its very end, must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'hello';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is case-sensitive')]
    public function test_endsWith_is_case_sensitive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // endsWith() delegates to str_ends_with(), which is
        // case-sensitive. A needle that differs only in case must not
        // match.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello World';
        $needle = 'world';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — endsWith() with an array haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when only a later element of the array haystack ends with the needle')]
    public function test_endsWith_array_haystack_found_in_later_element(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an array haystack is checked element by element. Putting the
        // match in a later element proves endsWith() does not stop at
        // the first element.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['alpha', 'beta'];
        $needle = 'eta';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when no element of the array haystack ends with the needle')]
    public function test_endsWith_array_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when no array element closes with the needle, endsWith() must
        // report false — even if an element contains the needle earlier
        // in ("gamma" contains "gam", but does not end with it).

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['alpha', 'beta', 'gamma'];
        $needle = 'gam';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — endsWith() with an empty haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the haystack is an empty string')]
    public function test_endsWith_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty string has no closing characters, so endsWith() must
        // report false for any (non-empty) needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '';
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the haystack is an empty array')]
    public function test_endsWith_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack has no strings to check, so
        // endsWith() must report false for any needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = [];
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — endsWith() with the falsy "0" haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when checking the string "0" ends with the needle "0"')]
    public function test_endsWith_zero_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "0" is a valid, non-empty haystack, but PHP's empty() treats
        // it as falsy. This test is the regression guard against an
        // empty()-based emptiness check silently discarding it.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '0';
        $needle = '0';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — endsWith() with UTF-8 data
    //
    // ----------------------------------------------------------------

    #[TestDox('matches a multibyte needle at the end of the haystack')]
    public function test_endsWith_finds_trailing_multibyte_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a multibyte character (here é, U+00E9, two bytes in UTF-8) must
        // be matched as a whole codepoint at the end of a UTF-8 haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "caf\u{00E9}";
        $needle = "\u{00E9}";
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not match an ASCII needle against the bytes of a trailing multibyte character')]
    public function test_endsWith_ascii_needle_does_not_match_trailing_multibyte_bytes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UTF-8 is self-synchronising: the bytes of a multibyte character
        // (here é, U+00E9) never overlap the ASCII range, so an ASCII
        // needle ("e") cannot false-match against a trailing é. This is
        // the property that makes byte-level str_ends_with() correct on
        // UTF-8.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "caf\u{00E9}";
        $needle = 'e';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — endsWith() empty / whitespace needle (no transformer)
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the needle is an empty string')]
    public function test_endsWith_empty_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's str_ends_with() reports true for an empty needle (every
        // string "ends with" nothing). endsWith() deliberately diverges:
        // like contains(), it rejects an empty needle up front and
        // reports false, because "ends with nothing" is not a meaningful
        // query.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello';
        $needle = '';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('checks the needle verbatim when no transformer is given')]
    public function test_endsWith_checks_needle_verbatim_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, surrounding whitespace is part of the
        // needle. A padded needle is checked verbatim and does not match
        // the end of the unpadded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'world ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('checks for a whitespace-only needle when no transformer is given')]
    public function test_endsWith_whitespace_needle_checked_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no transformer, a whitespace-only needle is a legitimate
        // needle, and matches when the haystack closes with that run of
        // whitespace.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'trailing   ';
        $needle = '   ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — endsWith() with a transformer
    //
    // ----------------------------------------------------------------

    #[TestDox('transforms the needle before checking when a transformer is given')]
    public function test_endsWith_transforms_needle_when_asked(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller who passes a trimming transformer strips the needle's
        // surrounding whitespace before the check, so the padded needle
        // now matches the end of the unpadded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = '  world  ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when a trimming transformer reduces the needle to empty')]
    public function test_endsWith_transformer_empties_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a whitespace-only needle reduces to nothing under a trimming
        // transformer; an empty needle is never a meaningful "ends with",
        // so endsWith() reports false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'trailing   ';
        $needle = '   ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('applies the transformer to the needle only, never the haystack')]
    public function test_endsWith_transformer_does_not_touch_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the transformer is applied to the needle alone. Here a trimming
        // transformer strips the needle's padding, but the haystack's own
        // trailing whitespace is left intact — so the trimmed needle does
        // NOT match the end of the still-padded haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello   ';
        $needle = '  hello  ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the string haystack is empty')]
    public function test_endsWith_skips_transformer_for_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty haystack short-circuits to false before transforming.
        // We prove the transformer never runs by making it throw: if it
        // fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith('', 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the array haystack is empty')]
    public function test_endsWith_skips_transformer_for_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack short-circuits to false before
        // transforming. We prove the transformer never runs by making it
        // throw: if it fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::endsWith([], 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Shape — containsIgnoreCase()
    //
    // ----------------------------------------------------------------

    #[TestDox('::containsIgnoreCase() is declared public static')]
    public function test_containsIgnoreCase_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // containsIgnoreCase() is consumed as a static utility — callers
        // write `Str::containsIgnoreCase(...)`, never
        // `(new Str)->containsIgnoreCase(...)`. Dropping `static` or
        // narrowing visibility would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'containsIgnoreCase');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::containsIgnoreCase() takes parameters [haystack, needle, transformer]')]
    public function test_containsIgnoreCase_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // parameter names and order are part of the contract because
        // callers can use PHP's named-argument syntax. Renaming or
        // reordering breaks those call sites.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'containsIgnoreCase');
        $expected = ['haystack', 'needle', 'transformer'];

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::containsIgnoreCase() returns bool')]
    public function test_containsIgnoreCase_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared return type is part of the contract — callers
        // rely on it for type inference and for PHPStan to validate
        // their own code.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'containsIgnoreCase');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
    }

    #[TestDox('::containsIgnoreCase() $transformer defaults to null')]
    public function test_containsIgnoreCase_transformer_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default of `null` is the contract: with no transformer,
        // containsIgnoreCase() searches for the needle exactly as given
        // (bar the case-fold both sides always get). Pin it so a future
        // refactor cannot silently start transforming every needle.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(Str::class, 'containsIgnoreCase');
        $parameters = $method->getParameters();
        $transformerParam = $parameters[2];

        // ----------------------------------------------------------------
        // perform the change

        $hasDefault = $transformerParam->isDefaultValueAvailable();
        $defaultValue = $transformerParam->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($hasDefault);
        $this->assertNull($defaultValue);
    }

    // ================================================================
    //
    // Behaviour — containsIgnoreCase() with a string haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when the string haystack contains the needle in a different case')]
    public function test_containsIgnoreCase_matches_across_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the whole point of the method, and what separates it
        // from contains(): the haystack is folded too, so a needle in a
        // different case still matches. contains() would report false
        // here.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello World';
        $needle = 'WORLD';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns true when the string haystack contains the needle in the same case')]
    public function test_containsIgnoreCase_matches_same_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // folding both sides must not break the ordinary same-case
        // match: a needle that contains() would find is still found.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'hello world';
        $needle = 'o w';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the string haystack does not contain the needle in any case')]
    public function test_containsIgnoreCase_string_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // case-insensitivity does not manufacture a match: a needle that
        // is simply not present, in any case, reports false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello World';
        $needle = 'xyz';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('folds the German eszett on both sides before searching')]
    public function test_containsIgnoreCase_folds_eszett(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // proper case-folding expands ß to "ss" on BOTH haystack and
        // needle, so an all-caps "STRASSE" needle is found inside a
        // haystack written with ß. A toLower-based search would miss it.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = "Die stra\u{00DF}e ist lang";
        $needle = 'STRASSE';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — containsIgnoreCase() with an array haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when only a later element of the array haystack matches case-insensitively')]
    public function test_containsIgnoreCase_array_haystack_found_in_later_element(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an array haystack is searched element by element, each folded
        // in turn. Putting the match in a later element proves the
        // search does not stop at the first element.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['Alpha', 'BETA'];
        $needle = 'bet';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when no element of the array haystack matches in any case')]
    public function test_containsIgnoreCase_array_haystack_not_found(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when no array element contains the needle in any case,
        // containsIgnoreCase() must report false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = ['Alpha', 'Beta', 'Gamma'];
        $needle = 'delta';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — containsIgnoreCase() with an empty haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the haystack is an empty string')]
    public function test_containsIgnoreCase_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty string haystack contains nothing, so
        // containsIgnoreCase() must report false for any needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '';
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when the haystack is an empty array')]
    public function test_containsIgnoreCase_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack has no strings to search, so
        // containsIgnoreCase() must report false for any needle.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = [];
        $needle = 'x';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — containsIgnoreCase() with the falsy "0" haystack
    //
    // ----------------------------------------------------------------

    #[TestDox('returns true when searching the string "0" for the needle "0"')]
    public function test_containsIgnoreCase_zero_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "0" is a valid, non-empty haystack, but PHP's empty() treats
        // it as falsy. This test is the regression guard against an
        // empty()-based emptiness check silently discarding it.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '0';
        $needle = '0';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — containsIgnoreCase() empty / whitespace needle
    //
    // ----------------------------------------------------------------

    #[TestDox('returns false when the needle is an empty string')]
    public function test_containsIgnoreCase_empty_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty needle can never be "contained" in any meaningful
        // sense; like contains(), containsIgnoreCase() rejects it up
        // front and reports false, regardless of the haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello';
        $needle = '';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase($haystack, $needle);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Behaviour — containsIgnoreCase() with a transformer
    //
    // ----------------------------------------------------------------

    #[TestDox('transforms the needle before folding and searching when a transformer is given')]
    public function test_containsIgnoreCase_transforms_needle_when_asked(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller who passes a trimming transformer strips the needle's
        // surrounding whitespace before the case-fold and search, so the
        // padded, differently-cased needle now matches.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'Hello World';
        $needle = '  WORLD  ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('returns false when a trimming transformer reduces the needle to empty')]
    public function test_containsIgnoreCase_transformer_empties_needle(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a whitespace-only needle reduces to nothing under a trimming
        // transformer; an empty needle can never be "contained", so
        // containsIgnoreCase() reports false.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = 'A B C';
        $needle = '   ';
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('applies the transformer to the needle only, though it still case-folds the haystack')]
    public function test_containsIgnoreCase_transformer_does_not_touch_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the transformer is applied to the needle alone. Here a trimming
        // transformer strips the needle's padding, but the haystack's own
        // surrounding whitespace is left intact (it is only case-folded)
        // — so the trimmed, folded needle is found inside the still-padded
        // haystack.

        // ----------------------------------------------------------------
        // setup your test

        $haystack = '   HELLO   ';
        $needle = '  hello  ';
        $expected = true;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase(
            $haystack,
            $needle,
            fn(string $v) => Str::trim($v),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the string haystack is empty')]
    public function test_containsIgnoreCase_skips_transformer_for_empty_string_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty haystack short-circuits to false before transforming.
        // We prove the transformer never runs by making it throw: if it
        // fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase('', 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('does not invoke the transformer when the array haystack is empty')]
    public function test_containsIgnoreCase_skips_transformer_for_empty_array_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array haystack short-circuits to false before
        // transforming. We prove the transformer never runs by making it
        // throw: if it fired, the exception would fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $exploding = function (string $v): string {
            throw new RuntimeException('transformer should not run');
        };
        $expected = false;

        // ----------------------------------------------------------------
        // perform the change

        $actual = Str::containsIgnoreCase([], 'x', $exploding);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
