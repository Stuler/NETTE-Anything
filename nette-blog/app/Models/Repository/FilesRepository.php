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


    // INSERT functions
    public function add(array $data){
        $this->db->query("INSERT INTO $this->fileTable ?", $data);
    }




}
