<?php
declare(strict_types=1);

namespace App\Models\Repository;

use Nette\Database\Context;
use Nette\Database\Explorer;
use Nette\Database\Row;

class FilesRepository
{
    protected $fileTable = "file";

    /** @var Explorer @inject @internal */
    public $db;

    // FIND functions
    public function findAllItemByLevel(int $level, int $id)
    {
        // return $this->db->query("SELECT * FROM $this->fileTable WHERE level=? AND client_id=?", $level, $id);
        return $this->db->table('file')
            ->where('level=? AND client_id=?', $level, $id);
    }

    public function findAllSimilarFolders(string $name, int $client_id, ?int $parent_id)
    {
        if (!is_null($parent_id)) {
            // return $this->db->query("SELECT * FROM $this->fileTable WHERE name LIKE ? AND client_id LIKE ? AND parent_id LIKE ?", $name, $client_id, $parent_id)->fetchAll();
            return $this->db->table('file')
                ->where('name = ? AND client_id = ? AND parent_id = ?', 
                    $name, $client_id, $parent_id)
                ->fetchAll();

        } else {
            // return $this->db->query("SELECT * FROM $this->fileTable WHERE name LIKE ? AND client_id LIKE ? AND parent_id IS NULL", $name, $client_id)->fetchAll();
            return $this->db->table('file')
                ->where('name = ? AND client_id = ? AND parent_id IS NULL',
                    $name, $client_id)
                ->fetchAll();
        }
    }

    // INSERT functions
    public function add(array $data)
    {
        // $this->db->query("INSERT INTO $this->fileTable ?", $data);
        $this->db->table('file')->insert($data);
    }

    // SELECT functions
    public function fetchTree(): array
    {
        // return $this->db->query("SELECT * from $this->fileTable")->fetchAll();
        return $this->db->table('file')->fetchAll();
    }

    public function fetchById(?int $id)
    {
        // return $this->db->query("SELECT * from $this->fileTable WHERE id=?", $id)->fetch();
        return $this->db->table('file')
            ->where('id', $id)
            ->fetch();
    }

	public function fetchByClientId(?int $id)
	{
		// return $this->db->query("SELECT * from $this->fileTable WHERE client_id=?", $id)->fetchAll();
        return $this->db->table('file')
            ->where('client_id', $id)
            ->fetchAll();
	}

    public function fetchAllChildren(int $id): array
    {
        // return $this->db->query("SELECT * from $this->fileTable WHERE parent_id=? OR id=?", $id, $id)->fetchAll();
        return $this->db->table('file')
            ->where('parent_id=? OR id=?', $id, $id)
            ->fetchAll();
    }

    public function fetchByParent(?int $id): array
    {
        // return $this->db->query("SELECT * from $this->fileTable WHERE parent_id=?", $id)->fetchAll();
        return $this->db->table('file')
            ->where('parent_id', $id)
            ->fetch();
    }

    // DELETE functions
    public function remove(int $id)
    {
        // $this->db->query("DELETE FROM $this->fileTable WHERE id=? OR parent_id=?", $id, $id);
        $this->db->table('file')
            ->where('id=? OR parent_id=?', $id, $id)
            ->delete();
    }

    // UPDATE functions
    public function rename(string $name, int $id)
    {
        // $this->db->query("UPDATE $this->fileTable SET ? WHERE id=?", ["name" => $name], $id);
        $this->db->table('file')
            ->where('id', $id)
            ->update(['name'=>$name]);
    }
}
