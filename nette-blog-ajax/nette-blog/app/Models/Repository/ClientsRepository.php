<?php
declare(strict_types=1);

namespace App\Models\Repository;

use Nette\Database\Context;
use Nette\Database\Explorer;
use Nette\Database\Row;

class ClientsRepository
{
    protected $clientTable = "client";
    protected $clientPersonTable = "client_person";

    /** @var Explorer @inject @internal */
    public $db;

    public function fetchAllActive(): array
    {
        return $this->db->query("SELECT * FROM $this->clientTable")->fetchAll();
    }

    /*
     * Vyskladanie vyhladavacieho stringu
     */
    public function fetchAllActiveBySearchTerm(string $term): array {
        $cols = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->clientTable' AND TABLE_SCHEMA='clients'")->fetchPairs(null, "COLUMN_NAME"); //preco neberie $this->db?
        $likes = [];
        $values = [];
        foreach ($cols as $column) {
           $likes[] = "`$column` LIKE ?";
           $values[] = "%$term%";
        }
        $conditionQuery = implode(" OR ", $likes);
        return $this->db->query("SELECT * FROM `$this->clientTable` WHERE $conditionQuery", ...$values)->fetchAll();
    }

    public function fetchById(int $id): ?Row
    {
        return $this->db->query("SELECT * FROM $this->clientTable WHERE id=?", $id)->fetch();
    }

	public function fetchContactById(?int $id): array
	{
		return $this->db->query("SELECT * FROM $this->clientPersonTable WHERE client_id=?", $id)->fetchAll();
	}

	public function fetchContact(?int $id)
	{
		return $this->db->query("SELECT * FROM $this->clientPersonTable WHERE id=?", $id)->fetch();
	}


	public function add(string $name)
    {
        $this->db->query("INSERT INTO $this->clientTable ?", ["name" => $name]);
    }

    public function addClient(array $data)
    {
        unset($data['id']);
        $this->db->query("INSERT INTO $this->clientTable ?", $data);
    }

	public function addContactPerson(array $data)
	{
		unset($data['id']);
      $client_id = $this->db->table('client')->get('id');
		$this->db->query("INSERT INTO $this->clientPersonTable ?", $data);
	}

    public function updateClient(int $id, array $data)
    {
        $this->db->query("UPDATE $this->clientTable SET ? WHERE id=?",$data, $id);
    }

	public function updateContactPerson(int $id, array $data)
	{
		$this->db->query("UPDATE $this->clientPersonTable SET ? WHERE id=?",$data, $id);
	}

    public function remove(int $id)
    {
        $this->db->query("DELETE FROM $this->clientTable WHERE id=?", $id);
    }

    public function removeContact(int $id)
    {
        $this->db->query("DELETE FROM $this->clientPersonTable WHERE id=?", $id);
    }
}
