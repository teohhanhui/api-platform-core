<?php

/*
 * This file is part of the DunglasApiBundle package.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dunglas\ApiBundle\Util;

use Symfony\Component\HttpFoundation\Request;

/**
 * Utility functions for working with Symfony HttpFoundation request.
 *
 * @author Teoh Han Hui <teohhanhui@gmail.com>
 */
abstract class RequestUtils
{
    /**
     * Gets a fixed request.
     *
     * @param Request $request
     *
     * @return Request
     */
    public static function getFixedRequest(Request $request)
    {
        $query = self::parseRequestParams($_SERVER['QUERY_STRING']);
        $body = self::parseRequestParams(file_get_contents('php://input'));
        $cookies = isset($_SERVER['HTTP_COOKIE']) ? self::parseRequestParams($_SERVER['HTTP_COOKIE']) : [];

        return $request->duplicate($query, $body, null, $cookies, null, null);
    }

    /**
     * Parses request parameters from the specified source.
     *
     * @author Rok Kralj
     *
     * @see http://stackoverflow.com/a/18209799/1529493
     *
     * @param string $source
     *
     * @return array
     */
    public static function parseRequestParams($source)
    {
        $source = urldecode($source);

        $source = preg_replace_callback(
            '/(^|(?<=&))[^=[&]+/',
            function ($key) {
                return bin2hex(urldecode($key[0]));
            },
            $source
        );

        parse_str($source, $params);

        return array_combine(array_map('hex2bin', array_keys($params)), $params);
    }
}
