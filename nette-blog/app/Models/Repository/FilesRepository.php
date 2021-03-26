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
    public function findAllItemByLevel(int $level)
    {
        return $this->db->query("SELECT * FROM $this->fileTable WHERE level=?", $level);
    }

    public function findAllSimilarFolders(string $name)
    {
    	return $this->db->query("SELECT * FROM $this->fileTable WHERE name LIKE ?", $name )->fetchAll();
    }

    // INSERT functions
    public function add(array $data)
    {
        $this->db->query("INSERT INTO $this->fileTable ?", $data);
    }

    // SELECT functions
    public function fetchTree(): array
    {
        return $this->db->query("SELECT * from $this->fileTable")->fetchAll();
    }

    public function fetchById(int $id)
    {
        return $this->db->query("SELECT * from $this->fileTable WHERE id=?", $id)->fetch();
    }

    public function fetchAllChildren(int $id): array
    {
        return $this->db->query("SELECT * from $this->fileTable WHERE parent_id=? OR id=?", $id, $id)->fetchAll();
    }

    public function fetchByParent(int $id): array
    {
        return $this->db->query("SELECT * from $this->fileTable WHERE parent_id=?", $id)->fetchAll();
    }

    // DELETE functions
    public function remove(int $id)
    {
        $this->db->query("DELETE FROM $this->fileTable WHERE id=? OR parent_id=?", $id, $id);
    }

    // UPDATE functions
    public function rename(string $name, int $id)
    {
        $this->db->query("UPDATE $this->fileTable SET ? WHERE id=?", ["name" => $name], $id);
    }

}
