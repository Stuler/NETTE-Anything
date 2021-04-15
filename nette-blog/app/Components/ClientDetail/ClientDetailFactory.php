<?php
declare(strict_types=1);

namespace App\Components\ClientDetail;

use App\Components\ClientList\ClientList;
use Nette\DI\Container;

class ClientDetailFactory
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function create(): ClientList {
        return $this->container->createService("clientDetail");
    }
}