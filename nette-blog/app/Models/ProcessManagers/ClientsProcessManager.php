<?php
declare(strict_types=1);

namespace App\Models\ProcessManagers;

use App\Models\Repository\ClientsRepository;

class ClientsProcessManager {

	/** @var ClientsRepository @inject @internal */
	public $clientsRepo;

	public function __construct() {
	}

	public function add(string $name) {
		$this->clientsRepo->add($name);
	}

    public function addClient(array $data) {
        $this->clientsRepo->addClient($data);
    }

	public function addContactPerson(array $data) {
		$this->clientsRepo->addContactPerson($data);
	}

	public function updateClient(int $id, array $data) {
		$this->clientsRepo->updateClient($id, $data);
	}

	public function updateContactPerson(int $id, array $data) {
		$this->clientsRepo->updateContactPerson($id, $data);
	}

	public function removeClient(int $id) {
		$this->clientsRepo->remove($id);
	}

	public function removeContact(int $id) {
		$this->clientsRepo->removeContact($id);
	}

    public function removeCustom(string $tableName, int $id) {
        $this->clientsRepo->removeCustom($tableName, $id);
    }

}