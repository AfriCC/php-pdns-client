[![Build Status](https://travis-ci.org/AfriCC/php-pdns-client.svg?branch=master)](https://travis-ci.org/AfriCC/php-pdns-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AfriCC/php-pdns-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AfriCC/php-pdns-client/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/AfriCC/php-pdns-client/badge.svg?branch=master)](https://coveralls.io/github/AfriCC/php-pdns-client?branch=master)
[![Latest Stable Version](https://poser.pugx.org/africc/pdns-client/v/stable.svg)](https://packagist.org/packages/africc/pdns-client)
[![Packagist](https://img.shields.io/packagist/dt/africc/pdns-client.svg)](https://packagist.org/packages/africc/pdns-client)
[![Latest Unstable Version](https://poser.pugx.org/africc/pdns-client/v/unstable.svg)](https://packagist.org/packages/africc/pdns-client)
[![License](https://poser.pugx.org/africc/pdns-client/license.svg)](https://packagist.org/packages/africc/pdns-client)

# africc/pdns-client

A PowerDNS API Client written in PHP.

## Install

```bash
$ composer require africc/pdns-client
```

## Usage

```php
<?php

require 'vendor/autoload.php';

use AfriCC\Pdns\Client as PdnsClient;

$pdnsc = new PdnsClient;

```

## License

Licensed under the MIT License. See the [LICENSE file](LICENSE) for details.

## Author Information

[AfriCC](https://afri.cc)
