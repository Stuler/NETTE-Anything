<?php
declare(strict_types=1);

namespace App\Models\ProcesManagers;

use App\Models\Repository\ClientsRepository;

class ClientsProcessManager {

	/** @var ClientsRepository @inject @internal */
	public $clientsRepo;

	private $prefix;

	public function __construct() {

	}

	public function add(string $name) {
		$this->clientsRepo->add($name);
	}

    public function addClient(array $data) {
        $this->clientsRepo->addClient($data);
    }

	public function updateClient(int $id, string $label) {
		$this->clientsRepo->update($id, $label);
	}

	public function removeClient(int $id) {
		$this->clientsRepo->remove($id);
	}

}