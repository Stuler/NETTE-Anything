<?php
declare(strict_types=1);

namespace App\Models\Repository;

use Nette\Database\Explorer;
use Nette\Database\Row;

class ClientsRepository {

	protected $clientTable = "client";
    protected $client_personTable = "client_person";


	/** @var Explorer @inject @internal */
	public $db;

	public function fetchAllActive(): array {
		return $this->db->query("SELECT * FROM $this->clientTable")->fetchAll();
	}

	public function fetchAllActiveBySearchTerm(string $crit, string $term): array {
	    return $this->db->query("SELECT * FROM $this->clientTable WHERE $crit LIKE '%$term%'")->fetchAll();
	}

	public function fetchById(int $id): ?Row {
		return $this->db->query("SELECT * FROM $this->clientTable WHERE id=?", $id)->fetch();
	}

	public function add(string $name) {
		$this->db->query("INSERT INTO $this->clientTable ?", ["name" => $name]);
	}

    public function addClient(array $data) {
	    unset($data['id']);
        $this->db->query("INSERT INTO $this->clientTable ?", $data);
	}

	public function update(int $id, string $label) {
		$this->db->query("UPDATE $this->clientTable SET ? WHERE id=?", ["label" => $label], $id);
	}

	public function remove(int $id) {
		$this->db->query("DELETE FROM $this->clientTable WHERE id=?", $id);
	}

}