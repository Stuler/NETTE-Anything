<?php
declare(strict_types=1);

namespace App\Components\ClientList;

use Nette\DI\Container;

class ClientListFactory {

	/**
	 * @var Container
	 */
	private $container;

	public function __construct(Container $container) {
		$this->container = $container;
	}

	public function create(): ClientList {
		return $this->container->createService("clientList");
	}
}