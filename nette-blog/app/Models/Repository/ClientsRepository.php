<?php
declare(strict_types=1);

namespace App\Models\Repository;

use Nette\Database\Context;
use Nette\Database\Explorer;
use Nette\Database\Row;

/* TODO:
- implementovat tabulku client_person a funkcionalitu
*/

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

    public function fetchAllActiveBySearchTerm(string $term)
    {
        return $this->db->query("SELECT * FROM $this->clientTable WHERE CONCAT (name, ico, email) LIKE ?", "%$term%")->fetchAll();
//      SELECT * FROM $this->clientTable WHERE CONCAT (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = clients AND TABLE_NAME = $this->clientTable) LIKE ?", "%$term%"
    }

    public function fetchById(int $id): ?Row
    {
        return $this->db->query("SELECT * FROM $this->clientTable WHERE id=?", $id)->fetch();
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

    public function update(int $id, array $data)
    {
        $this->db->query("UPDATE $this->clientTable SET ? WHERE id=?", $data, $id);
    }

    public function remove(int $id)
    {
        $this->db->query("DELETE FROM $this->clientTable WHERE id=?", $id);
    }

    public function getColNames()
    {
        $cols = $this->db->query("SHOW COLUMNS FROM $this->clientTable")->fetch();
        //("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='client' ")->fetch();
    }

}