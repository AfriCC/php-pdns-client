<?php

/**
 * This file is part of the africc/pdns-client library.
 *
 * (c) Gunter Grodotzki <gunter@afri.cc>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace AfriCC\Pdns\Types;

use AfriCC\Pdns\Helper;

abstract class Type implements Typable
{
    protected $name;

    protected $content;

    protected $ttl;

    public function __construct($name, $content = [], $ttl = 300)
    {
        $this->name = $name;

        if (!is_array($content)) {
            $this->content = [$content];
        } else {
            $this->content = $content;
        }

        $this->ttl = (int) $ttl;
    }

    public function payload($changetype = null)
    {
        $rrset = [
            'name'      => Helper::canonical($this->name),
            'type'      => Helper::className($this),
        ];

        if ($changetype !== null && $changetype === 'DELETE') {
            $rrset['records'] = [];
        } else {
            $records = [];
            foreach ($this->content as $record) {
                $records[] = [
                    'content'   => $record,
                    'disabled'  => false,
                ];
            }

            $rrset['ttl'] = $this->ttl;
            $rrset['records'] = $records;
        }

        if ($changetype !== null) {
            $rrset['changetype'] = $changetype;
        }

        return $rrset;
    }
}
