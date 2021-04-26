<?php
declare(strict_types=1);

namespace App\Components\CustomList;

use App\Components\CustomList\CustomList;
use Nette\DI\Container;

class CustomListFactory
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function create(): CustomList {
        return $this->container->createService("customList");
    }
}