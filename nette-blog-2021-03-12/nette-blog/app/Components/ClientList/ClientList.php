<?php
declare(strict_types=1);

namespace App\Components\ClientList;

use Nette\Application\UI\Control;
use Nette\Database\Explorer;

class ClientList extends Control {

	/** @var Explorer @inject @internal */
	public $db;

	private $count = null;

	public function showOnlyFirst(int $count) {
		$this->count = $count;
	}

	public function render() {
		$query = $this->db->table("client");
		if ($this->count) {
			$query->limit($this->count);
		}
		$this->template->clients = $query->fetchAll();

		$this->template->setFile(__DIR__ . "/clientList.latte");
		$this->template->render();
	}

	public function handleDelete(int $id) {
		$this->db->table("client")->where("id", $id)->delete();
	}
}