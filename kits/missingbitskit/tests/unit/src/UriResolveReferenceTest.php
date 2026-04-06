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

namespace StusDevKit\MissingBitsKit\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

use function StusDevKit\MissingBitsKit\uri_resolve_reference;

/**
 * Tests for RFC 3986 Section 5 reference resolution.
 *
 * Test cases are taken directly from RFC 3986 Section
 * 5.4, which provides a comprehensive set of examples
 * using the base URI:
 *
 *     http://a/b/c/d;p?q
 */
#[TestDox('uri_resolve_reference()')]
class UriResolveReferenceTest extends TestCase
{
    private const BASE_URI = 'http://a/b/c/d;p?q';

    // ================================================================
    //
    // RFC 3986 Section 5.4.1 — Normal Examples
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, string}>
     */
    public static function normalExamplesProvider(): array
    {
        return [
            'g:h'       => ['g:h', 'g:h'],
            'g'         => ['g', 'http://a/b/c/g'],
            './g'       => ['./g', 'http://a/b/c/g'],
            'g/'        => ['g/', 'http://a/b/c/g/'],
            '/g'        => ['/g', 'http://a/g'],
            '//g'       => ['//g', 'http://g'],
            '?y'        => ['?y', 'http://a/b/c/d;p?y'],
            'g?y'       => ['g?y', 'http://a/b/c/g?y'],
            '#s'        => ['#s', 'http://a/b/c/d;p?q#s'],
            'g#s'       => ['g#s', 'http://a/b/c/g#s'],
            'g?y#s'     => ['g?y#s', 'http://a/b/c/g?y#s'],
            ';x'        => [';x', 'http://a/b/c/;x'],
            'g;x'       => ['g;x', 'http://a/b/c/g;x'],
            'g;x?y#s'   => ['g;x?y#s', 'http://a/b/c/g;x?y#s'],
            'empty'     => ['', 'http://a/b/c/d;p?q'],
            '.'         => ['.', 'http://a/b/c/'],
            './'        => ['./', 'http://a/b/c/'],
            '..'        => ['..', 'http://a/b/'],
            '../'       => ['../', 'http://a/b/'],
            '../g'      => ['../g', 'http://a/b/g'],
            '../..'     => ['../..', 'http://a/'],
            '../../'    => ['../../', 'http://a/'],
            '../../g'   => ['../../g', 'http://a/g'],
        ];
    }

    #[TestDox('resolves normal example: $ref')]
    #[DataProvider('normalExamplesProvider')]
    public function test_normal_examples(
        string $ref,
        string $expected,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // these test cases come directly from RFC 3986
        // Section 5.4.1 "Normal Examples"

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: self::BASE_URI,
            ref: $ref,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $result);
    }

    // ================================================================
    //
    // RFC 3986 Section 5.4.2 — Abnormal Examples
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, string}>
     */
    public static function abnormalExamplesProvider(): array
    {
        return [
            '../../../g'    => ['../../../g', 'http://a/g'],
            '../../../../g' => ['../../../../g', 'http://a/g'],
            '/./g'          => ['/./g', 'http://a/g'],
            '/../g'         => ['/../g', 'http://a/g'],
            'g.'            => ['g.', 'http://a/b/c/g.'],
            '.g'            => ['.g', 'http://a/b/c/.g'],
            'g..'           => ['g..', 'http://a/b/c/g..'],
            '..g'           => ['..g', 'http://a/b/c/..g'],
            './../g'        => ['./../g', 'http://a/b/g'],
            './g/.'         => ['./g/.', 'http://a/b/c/g/'],
            'g/./h'         => ['g/./h', 'http://a/b/c/g/h'],
            'g/../h'        => ['g/../h', 'http://a/b/c/h'],
            'g;x=1/./y'     => ['g;x=1/./y', 'http://a/b/c/g;x=1/y'],
            'g;x=1/../y'    => ['g;x=1/../y', 'http://a/b/c/y'],
            'g?y/./x'       => ['g?y/./x', 'http://a/b/c/g?y/./x'],
            'g?y/../x'      => ['g?y/../x', 'http://a/b/c/g?y/../x'],
            'g#s/./x'       => ['g#s/./x', 'http://a/b/c/g#s/./x'],
            'g#s/../x'      => ['g#s/../x', 'http://a/b/c/g#s/../x'],
        ];
    }

    #[TestDox('resolves abnormal example: $ref')]
    #[DataProvider('abnormalExamplesProvider')]
    public function test_abnormal_examples(
        string $ref,
        string $expected,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // these test cases come directly from RFC 3986
        // Section 5.4.2 "Abnormal Examples"

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: self::BASE_URI,
            ref: $ref,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $result);
    }

    // ================================================================
    //
    // JSON Schema specific cases
    //
    // ----------------------------------------------------------------

    #[TestDox('resolves fragment-only ref against base')]
    public function test_fragment_only_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a fragment-only ref (used
        // for $anchor and JSON Pointer $defs references)
        // resolves by replacing only the fragment of the
        // base URI

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: 'https://example.com/schemas/person',
            ref: '#/$defs/Name',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/schemas/person#/$defs/Name',
            $result,
        );
    }

    #[TestDox('resolves relative file ref against base')]
    public function test_relative_file_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a relative file ref
        // resolves against the base URI's path, which is
        // the common JSON Schema external $ref pattern

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: 'https://example.com/schemas/person.json',
            ref: 'address.json',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/schemas/address.json',
            $result,
        );
    }

    #[TestDox('resolves relative file ref with fragment')]
    public function test_relative_file_ref_with_fragment(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a relative file ref with
        // a fragment resolves the path and keeps the
        // fragment — e.g. other.json#/$defs/Foo

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: 'https://example.com/schemas/person.json',
            ref: 'other.json#/$defs/Foo',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/schemas/other.json#/$defs/Foo',
            $result,
        );
    }

    #[TestDox('absolute ref is returned unchanged')]
    public function test_absolute_ref_returned_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an absolute URI ref is
        // returned without modification — it already
        // identifies its target

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: 'https://example.com/schemas/person.json',
            ref: 'https://other.com/schemas/address.json',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://other.com/schemas/address.json',
            $result,
        );
    }

    #[TestDox('anchor-only ref resolves against base')]
    public function test_anchor_only_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a plain-name fragment
        // (used for $anchor references in JSON Schema)
        // resolves by replacing the base's fragment

        // ----------------------------------------------------------------
        // perform the change

        $result = uri_resolve_reference(
            base: 'https://example.com/schemas/person',
            ref: '#name-def',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/schemas/person#name-def',
            $result,
        );
    }
}
