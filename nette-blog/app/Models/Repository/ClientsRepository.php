<?php
declare(strict_types=1);

namespace App\Models\Repository;

use Nette\Database\Context;
use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class ClientsRepository
{
    protected $clientTable = "client";
    protected $clientPersonTable = "client_person";

    /** @var Explorer @inject @internal */
    public $db;

/*    public function fetchAllActive(): array
    {
        return $this->db->query("SELECT * FROM $this->clientTable")->fetchAll();
    }*/

    public function fetchAllActive(): Selection
    {
        return $this->db->table('client');
    }

    /*
     * Vyskladanie vyhladavacieho
     * Obsolete
     */
/*    public function fetchAllActiveBySearchTerm(string $term): Selection
    {
        $cols = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->clientTable' AND TABLE_SCHEMA='clients'")->fetchPairs(null, "COLUMN_NAME"); //preco neberie $this->db?
        $likes = [];
        $values = [];
        foreach ($cols as $column) {
            $likes[] = "`$column` LIKE ?";
            $values[] = "%$term%";
        }
        $conditionQuery = implode(" OR ", $likes);
//        return $this->db->query("SELECT * FROM `$this->clientTable` WHERE $conditionQuery", ...$values)->fetchAll();
        return $this->db->table('client')
            ->where($conditionQuery, ...$values);
    }*/

    public function fetchById(int $id): ActiveRow
    {
        // return $this->db->query("SELECT * FROM $this->clientTable WHERE id=?", $id)->fetch();
        return $this->db->table('client')
            ->get($id);
    }

    public function fetchContactById(?int $id): ?ActiveRow
    {
        // return $this->db->query("SELECT * FROM $this->clientPersonTable WHERE client_id=?", $id)->fetchAll();
        return $this->db->table('client_person')
            ->get($id);
    }

    /** Obsolete */
/*    public function fetchContact(?int $id)
    {
        // return $this->db->query("SELECT * FROM $this->clientPersonTable WHERE id=?", $id)->fetch();
        return $this->db->table('client_person')
            ->get($id);
    }*/

/*    OBSOLETE
    public function add(string $name)
    {
//        $this->db->query("INSERT INTO $this->clientTable ?", ["name" => $name]);
        $this->db->table('client')->insert([
            'name' => $name
        ]);
    }*/

    public function addClient(array $data)
    {
        unset($data['id']);
//        $this->db->query("INSERT INTO $this->clientTable ?", $data);
        $this->db->table('client')->insert($data);
        $id = $this->db->getInsertId();
        bdump($id);
    }

    public function addContactPerson(array $data)
    {
        unset($data['id']);
//        $this->db->query("INSERT INTO $this->clientPersonTable ?", $data);
        $this->db->table('client_person')->insert([$data]);
    }

    public function updateClient(int $id, array $data)
    {
        $this->db->query("UPDATE $this->clientTable SET ? WHERE id=?", $data, $id);
    }

    public function updateContactPerson(int $id, array $data)
    {
        $this->db->query("UPDATE $this->clientPersonTable SET ? WHERE id=?", $data, $id);
    }

    public function remove(int $id)
    {
        $this->db->query("DELETE FROM $this->clientTable WHERE id=?", $id);
    }

    public function removeContact(int $id)
    {
        $this->db->query("DELETE FROM $this->clientPersonTable WHERE id=?", $id);
    }

//    TEST DATABASE/EXPLORER
    public function getAll(): Selection
    {
        return $this->db->table('client')->select('name'); // vrati vsetkych klientov
    }

    public function getOne(int $id): ActiveRow
    {
        return $this->db->table('client')->get($id);//vrati klienta so zadanym id
    }


}
