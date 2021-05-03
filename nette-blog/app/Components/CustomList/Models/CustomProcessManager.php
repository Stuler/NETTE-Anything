<?php
declare(strict_types=1);

namespace App\Components\CustomList\Models;

use App\Components\CustomList\Models\CustomRepository;

class CustomProcessManager
{
    /** @var CustomRepository @inject @internal */
	public $customRepo;

	public function __construct() {
	}

    public function removeCustom(string $tableName, int $id) {
        $this->customRepo->removeCustom($tableName, $id);
    }
}