<?php

namespace Codeages\Biz\Address;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AddressServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['migration.directories'][] = dirname(dirname(__DIR__)).'/migrations/address';
        $biz['autoload.aliases']['Address'] = 'Codeages\Biz\Address';

        $biz['console.commands'][] = function () use ($biz) {
            return new \Codeages\Biz\Address\Command\TableCommand($biz);
        };
    }
}
