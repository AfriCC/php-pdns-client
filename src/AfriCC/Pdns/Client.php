<?php

/**
 * This file is part of the africc/pdns-client library.
 *
 * (c) Gunter Grodotzki <gunter@afri.cc>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace AfriCC\Pdns;

use Exception;
use AfriCC\Pdns\Endpoints\Endpointable;

class Client
{
    protected $host;

    protected $port;

    protected $api_key;

    protected $last_response_headers = [];

    public function __construct($host, $api_key, $port = 8081)
    {
        $this->host = (string) $host;

        $this->port = (int) $port;

        $this->api_key = (string) $api_key;
    }

    public function request(Endpointable $endpoint)
    {
        $headers = [
            'Accept: application/json',
            'X-API-Key: ' . $this->api_key,
            'Connection: close',
        ];

        if ($endpoint->getMethod() === 'POST') {
            $headers[] = 'Content-type: application/json';
        }

        $context = [
            'http' => [
                'method' => $endpoint->getMethod(),
                'header' => $headers,
                'user_agent' => 'africc-pdns-client/1.0 (+https://github.com/AfriCC/php-pdns-client)',
                'content' => $endpoint->getPayload(),
                'protocol_version' => 1.1,
                'timeout' => 30,
                'ignore_errors' => true,
            ]
        ];

        $context = stream_context_create($context);

        $result = file_get_contents($this->url($endpoint->getUri()), false, $context);
        if (!$result) {
            throw new Exception('Unable to connect to PDNS API');
        }

        $this->last_response_headers = $http_response_header;
        $result = json_decode($result);
        if (!empty($result->error)) {
            throw new Exception($result->error, $this->getHttpResponseCode());
        }

        return $result;
    }

    protected function url($uri)
    {
        return sprintf('http://%s:%d%s', $this->host, $this->port, $uri);
    }

    protected function getHttpResponseCode()
    {
        if (empty($this->last_response_headers[0])) {
            return 0;
        }

        if (!preg_match('~^HTTP/1\.[01]\s*([0-9]{3})~i', $this->last_response_headers[0], $match)) {
            return 0;
        }

        return $match[1];
    }
}
