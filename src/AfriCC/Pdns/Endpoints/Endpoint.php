<?php

/**
 * This file is part of the africc/pdns-client library.
 *
 * (c) Gunter Grodotzki <gunter@afri.cc>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace AfriCC\Pdns\Endpoints;

use AfriCC\Pdns\Helper;

abstract class Endpoint implements Endpointable
{
    protected $data;

    protected $method = 'GET';

    protected $appended_uri = '';

    public function getUri()
    {
        return sprintf(
            '/api/v1/servers/localhost/%s%s',
            strtolower(Helper::className($this)),
            $this->appended_uri
        );
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function hasPayload()
    {
        return $this->data !== null;
    }

    public function getPayload()
    {
        return json_encode($this->data);
    }

    protected function appendToUri($uri)
    {
        $this->appended_uri = $uri;
    }
}
