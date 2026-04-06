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

namespace StusDevKit\MissingBitsKit;

/**
 * resolve a URI reference against a base URI per
 * RFC 3986 Section 5
 *
 * Implements the reference resolution algorithm from
 * RFC 3986 Section 5.2.2. Given a base URI and a
 * reference, produces the resolved target URI.
 *
 * Usage:
 *
 *     use function StusDevKit\MissingBitsKit\uri_resolve_reference;
 *
 *     $target = uri_resolve_reference(
 *         base: 'https://example.com/schemas/person',
 *         ref: 'address',
 *     );
 *     // 'https://example.com/schemas/address'
 *
 *     $target = uri_resolve_reference(
 *         base: 'https://example.com/schemas/person',
 *         ref: '#/$defs/Name',
 *     );
 *     // 'https://example.com/schemas/person#/$defs/Name'
 *
 * @see https://www.rfc-editor.org/rfc/rfc3986#section-5
 */
function uri_resolve_reference(
    string $base,
    string $ref,
): string {
    $r = uri_parse($ref);
    $b = uri_parse($base);

    // RFC 3986 Section 5.2.2 algorithm
    if ($r['scheme'] !== '') {
        // ref has a scheme — it's an absolute URI
        $tScheme = $r['scheme'];
        $tAuthority = $r['authority'];
        $tPath = uri_remove_dot_segments($r['path']);
        $tQuery = $r['query'];
    } else {
        if ($r['authority'] !== '') {
            // ref has an authority — use it
            $tScheme = $b['scheme'];
            $tAuthority = $r['authority'];
            $tPath = uri_remove_dot_segments($r['path']);
            $tQuery = $r['query'];
        } else {
            if ($r['path'] === '') {
                // empty path — inherit base path
                $tPath = $b['path'];
                $tQuery = $r['query'] !== ''
                    ? $r['query']
                    : $b['query'];
            } else {
                if (str_starts_with($r['path'], '/')) {
                    // absolute path
                    $tPath = uri_remove_dot_segments(
                        $r['path'],
                    );
                } else {
                    // relative path — merge with base
                    $tPath = uri_merge_paths(
                        $b['authority'],
                        $b['path'],
                        $r['path'],
                    );
                    $tPath = uri_remove_dot_segments($tPath);
                }
                $tQuery = $r['query'];
            }
            $tAuthority = $b['authority'];
        }
        $tScheme = $b['scheme'];
    }
    $tFragment = $r['fragment'];

    // RFC 3986 Section 5.3 — recompose
    return uri_recompose(
        scheme: $tScheme,
        authority: $tAuthority,
        path: $tPath,
        query: $tQuery,
        fragment: $tFragment,
    );
}

/**
 * parse a URI into its five components
 *
 * Returns scheme, authority, path, query, and fragment
 * as strings. Missing components are empty strings
 * rather than null, to simplify the resolution
 * algorithm.
 *
 * @return array{
 *     scheme: string,
 *     authority: string,
 *     path: string,
 *     query: string,
 *     fragment: string,
 * }
 */
function uri_parse(string $uri): array
{
    // RFC 3986 Appendix B regex
    $pattern = '/^'
        . '(?:([^:\/?#]+):)?'  // scheme
        . '(?:\/\/([^\/?#]*))?'  // authority
        . '([^?#]*)'  // path
        . '(?:\?([^#]*))?'  // query
        . '(?:#(.*))?'  // fragment
        . '$/';

    preg_match($pattern, $uri, $matches);

    return [
        'scheme'    => $matches[1] ?? '',
        'authority' => $matches[2] ?? '',
        'path'      => $matches[3] ?? '',
        'query'     => $matches[4] ?? '',
        'fragment'  => $matches[5] ?? '',
    ];
}

/**
 * merge a relative path with a base URI's path
 *
 * RFC 3986 Section 5.2.3.
 */
function uri_merge_paths(
    string $baseAuthority,
    string $basePath,
    string $refPath,
): string {
    if ($baseAuthority !== '' && $basePath === '') {
        // base has authority and empty path
        return '/' . $refPath;
    }

    // replace everything after the last '/' in base path
    $lastSlash = strrpos($basePath, '/');
    if ($lastSlash !== false) {
        return substr($basePath, 0, $lastSlash + 1) . $refPath;
    }

    return $refPath;
}

/**
 * remove dot segments from a URI path
 *
 * RFC 3986 Section 5.2.4.
 */
function uri_remove_dot_segments(string $path): string
{
    $output = '';

    while ($path !== '') {
        // A: remove ../ and ./ prefixes
        if (str_starts_with($path, '../')) {
            $path = substr($path, 3);
            continue;
        }
        if (str_starts_with($path, './')) {
            $path = substr($path, 2);
            continue;
        }

        // B: remove /./ prefix or replace /. at end
        if (str_starts_with($path, '/./')) {
            $path = '/' . substr($path, 3);
            continue;
        }
        if ($path === '/.') {
            $path = '/';
            continue;
        }

        // C: remove /../ prefix or replace /.. at end,
        // and remove the last output segment
        if (str_starts_with($path, '/../')) {
            $path = '/' . substr($path, 4);
            $lastSlash = strrpos($output, '/');
            $output = $lastSlash !== false
                ? substr($output, 0, $lastSlash)
                : '';
            continue;
        }
        if ($path === '/..') {
            $path = '/';
            $lastSlash = strrpos($output, '/');
            $output = $lastSlash !== false
                ? substr($output, 0, $lastSlash)
                : '';
            continue;
        }

        // D: remove standalone . and ..
        if ($path === '.' || $path === '..') {
            $path = '';
            continue;
        }

        // E: move the first path segment (including
        // initial / if any) to the output
        if (str_starts_with($path, '/')) {
            $nextSlash = strpos($path, '/', 1);
            if ($nextSlash !== false) {
                $output .= substr($path, 0, $nextSlash);
                $path = substr($path, $nextSlash);
            } else {
                $output .= $path;
                $path = '';
            }
        } else {
            $nextSlash = strpos($path, '/');
            if ($nextSlash !== false) {
                $output .= substr($path, 0, $nextSlash);
                $path = substr($path, $nextSlash);
            } else {
                $output .= $path;
                $path = '';
            }
        }
    }

    return $output;
}

/**
 * recompose a URI from its five components
 *
 * RFC 3986 Section 5.3.
 */
function uri_recompose(
    string $scheme,
    string $authority,
    string $path,
    string $query,
    string $fragment,
): string {
    $result = '';

    if ($scheme !== '') {
        $result .= $scheme . ':';
    }

    if ($authority !== '') {
        $result .= '//' . $authority;
    }

    $result .= $path;

    if ($query !== '') {
        $result .= '?' . $query;
    }

    if ($fragment !== '') {
        $result .= '#' . $fragment;
    }

    return $result;
}
