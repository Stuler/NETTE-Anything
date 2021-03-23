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

    public function fetchAllChildren(int $parentId): array
    {
        return $this->db->query("SELECT * from $this->fileTable WHERE parent_id=?", $parentId)->fetchAll();
    }

    // DELETE functions
    public function remove(int $id)
    {
        $this->db->query("DELETE FROM $this->fileTable WHERE id=? OR parent_id=?", $id, $id);
    }

}
