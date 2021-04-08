<?php
declare(strict_types=1);

namespace App\Components\FileSystem;

use Nette\DI\Container;


class FileSystemFactory
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function create(): FileSystem {
        return $this->container->createService("fileSystem");
    }
}