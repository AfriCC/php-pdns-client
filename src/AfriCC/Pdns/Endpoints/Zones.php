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
use AfriCC\Pdns\Types\Typable;

class Zones extends Endpoint
{
    public function all()
    {
    }

    public function show($name)
    {
        $this->appendToUri(sprintf('/%s', Helper::canonical($name)));
    }

    public function delete($name)
    {
        $this->appendToUri(sprintf('/%s', Helper::canonical($name)));
        $this->method = 'DELETE';
    }

    public function add($name, array $nameservers, $account = '')
    {
        $this->method = 'POST';
        $this->data = [
            'account'       => $account,
            'kind'          => 'native',
            'soa_edit_api'  => 'INCEPTION-INCREMENT',
            'masters'       => [],
            'name'          => Helper::canonical($name),
            'nameservers'   => [],
            'rrsets'        => array_merge($this->createSOA($name, $nameservers[0]), $this->createNS($name, $nameservers)),
        ];
    }

    public function upsertRecord($name, Typable $rrset)
    {
        $this->method = 'PATCH';
        $this->data = ['rrsets' => [$rrset->payload('REPLACE')]];
        $this->appendToUri(sprintf('/%s', Helper::canonical($name)));
    }

    public function deleteRecord($name, Typable $rrset)
    {
        $this->method = 'PATCH';
        $this->data = ['rrsets' => [$rrset->payload('DELETE')]];
        $this->appendToUri(sprintf('/%s', Helper::canonical($name)));
    }

    protected function createNS($name, array $nameservers)
    {
        $rrsets = $records = [];

        foreach ($nameservers as $nameserver) {
            $records[] = [
                'content'   => Helper::canonical($nameserver),
                'disabled'  => false,
            ];
        }

        $rrsets[] = [
            'records'   => $records,
            'name'      => Helper::canonical($name),
            'ttl'       => 86400,
            'type'      => 'NS',
        ];

        return $rrsets;
    }

    protected function createSOA($name, $primary)
    {
        $rrsets = [
            'records' => [
                [
                    'content'   => sprintf(
                        // primary | contact | serial | refresh | retry | expire | ttl
                        '%s %s %s 3600 1800 604800 600',
                        Helper::canonical($primary),
                        Helper::canonical('dnsadmin.' . $name),
                        date('Ymd') . sprintf('%02d', rand(0, 99))
                     ),
                    'disabled'  => false,
                ],
            ],
            'name'      => Helper::canonical($name),
            'ttl'       => 86400,
            'type'      => 'SOA',
        ];

        return [$rrsets];
    }
}
