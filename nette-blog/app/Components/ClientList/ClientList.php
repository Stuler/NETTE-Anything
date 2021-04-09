<?php
declare(strict_types=1);

namespace App\Components\ClientList;

use Nette\Application\UI\Control;
use Nette\Database\Explorer;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;

class ClientList extends Control {

	/** @var Explorer @inject @internal */
	public $db;

    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

	private $count = null;

	public function showOnlyFirst(int $count) {
		$this->count = $count;
	}

	public function render() {
		$clients = $this->clientsRepo->fetchAllActive();
		$this->template->clients = $clients;

		$this->template->setFile(__DIR__ . "/clientList.latte");
		$this->template->render();
	}

	public function handleDelete(int $id) {
        $this->clientsPM->removeClient($id);
        $this->redrawControl("list");
	}
}