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
    protected $api_url;

    protected $api_key;

    public function __construct($api_url, $api_key)
    {
        $this->api_url = (string) $api_url;

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
                'header' => implode("\r\n", $headers),
                'user_agent' => 'africc-pdns-client/1.0 (+https://github.com/AfriCC/php-pdns-client)',
                'protocol_version' => 1.1,
                'timeout' => 30,
                'ignore_errors' => true,
            ]
        ];

        if ($endpoint->hasPayload()) {
            $context['http']['content'] = $endpoint->getPayload();
        }

        $context = stream_context_create($context);

        $result = @file_get_contents($this->url($endpoint->getUri()), false, $context);
        if ($result === false) {
            throw new Exception('Unable to connect to PDNS API');
        }

        $result = json_decode($result);
        if (!empty($result->error)) {
            throw new Exception($result->error, $this->getHttpResponseCode($http_response_header));
        }

        return $result;
    }

    protected function url($uri)
    {
        return $this->api_url . $uri;
    }

    protected function getHttpResponseCode(array $http_response_header)
    {
        if (empty($http_response_header[0])) {
            return 0;
        }

        if (!preg_match('~^HTTP/1\.[01]\s*([0-9]{3})~i', $http_response_header[0], $match)) {
            return 0;
        }

        return $match[1];
    }
}
