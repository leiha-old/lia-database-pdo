<?php

namespace Lia\Database\PdoBundle\DependencyInjection;

use Lia\Database\PdoBundle\Pdo;
use Lia\KernelBundle\Service\ServiceBase;

class Factory
    extends ServiceBase
{
    public function get($connectionName)
    {
        $pdo = new Pdo($this->getService('doctrine.dbal.'.$connectionName.'_connection'));
        $pdo->setContainer($this->container);
        return $pdo;
    }
}