<?php
declare(strict_types=1);

namespace App\Components\CustomList\Models;

use Nette\Database\Context;
use Nette\Database\Explorer;
use Nette\Database\Row;

class CustomRepository
{
    /** @var Explorer @inject @internal */
    public $db;

    public function fetchAllCustom(string $tableName, ?string $relativeColumn, ?int $relativeValue, ?string $searchTerm = null, ?array $columns): array
    {
        $allCols = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND TABLE_SCHEMA='clients'")->fetchPairs(null, "COLUMN_NAME");
        $likes = [];
        $values = [];
        $selectedCols = [];

        foreach ($columns as $column){
            array_push($selectedCols, $column['name']);
        }

        foreach ($allCols as $column) {
            if (in_array($column, $selectedCols)) {
                $likes[] = "`$column` LIKE ?";
                $values[] = "%$searchTerm%";
                }
            }

        $conditionQuery = implode(" OR ", $likes);

        if (!$searchTerm) {
            if (!$relativeColumn) {
                return $this->db->query("SELECT * FROM $tableName")->fetchAll();
            } else {
                return $this->db->query("SELECT * FROM $tableName WHERE $relativeColumn = $relativeValue")->fetchAll();
            }
        } else {
            if (!$relativeColumn) {
                return $this->db->query("SELECT * FROM $tableName WHERE $conditionQuery", ...$values)->fetchAll();
            }
        }
    }

    public function removeCustom(string $tableName, int $id)
    {
        $this->db->query("DELETE FROM $tableName WHERE id=?", $id);
    }
}