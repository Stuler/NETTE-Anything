<?php
declare(strict_types=1);

namespace App\Models\Repository;

use Nette\Database\Explorer;
use Nette\Database\Row;

class TaskRepository {

	protected $table = "task";

	/** @var Explorer @inject @internal */
	public $db;

	public function fetchAllActive(): array {
		return $this->db->query("SELECT * FROM $this->table WHERE is_done=0")->fetchAll();
	}

	public function fetchAllActiveBySearchTerm(string $term): array {
		return $this->db->query("SELECT * FROM $this->table WHERE is_done=0 AND label LIKE '%$term%'")->fetchAll();
	}

	public function fetchById(int $id): ?Row {
		return $this->db->query("SELECT * FROM $this->table WHERE id=?", $id)->fetch();
	}

	public function add(string $label) {
		$this->db->query("INSERT INTO $this->table ?", ["label" => $label]);
	}

	public function update(int $id, string $label) {
		$this->db->query("UPDATE $this->table SET ? WHERE id=?", ["label" => $label], $id);
	}

	public function remove(int $id) {
		$this->db->query("DELETE FROM client WHERE id=?", $id);
	}

}