<?php
declare(strict_types=1);

namespace App\Components\CustomList\Models;

use Nette\Database\Context;
use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\Selection;

class CustomRepository
{
    /** @var Explorer @inject @internal */
    public $db;

    public function fetchAllCustom(string $tableName, ?string $relativeColumn, ?int $relativeValue, ?string $searchTerm = null, ?array $columns): Selection
    {
        $allCols = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND TABLE_SCHEMA='clients'")->fetchPairs(null, "COLUMN_NAME");
        $likes = [];
        $values = [];
        $selectedCols = [];

        foreach ($columns as $column) {
            $selectedCols[] = $column['name'];
//            array_push($selectedCols, $column['name']);
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
//                return $this->db->query("SELECT * FROM $tableName")->fetchAll();
                return $this->db->table($tableName);
            } else {
//                return $this->db->query("SELECT * FROM $tableName WHERE $relativeColumn = $relativeValue")->fetchAll();
                return $this->db->table($tableName)
                    ->where('$relativeColumn = ?', $relativeValue);
            }
        } else {
            if (!$relativeColumn) {
//                return $this->db->query("SELECT * FROM $tableName WHERE $conditionQuery", ...$values)->fetchAll();
                return $this->db->table($tableName)
                    ->where('$conditionQuery', ...$values);
            }
        }

//        $query = $this->db->table($tableName);
//        if ($searchTerm) {
//            $likes = [];
//            $values = [];
//            foreach ($allCols as $column) {
//                if (in_array($column, $selectedCols)) {
//                    $likes[] = "`$column` LIKE ?";
//                    $values[] = "%$searchTerm%";
//                }
//            }
//            $query->where($conditionQuery, ...$values);
//        }
//        if ($relativeColumn) {
//            $query->where($relativeColumn, $relativeValue);
//        }
//
//
//        return $query->fetchAll();

    }

    public function removeCustom(string $tableName, int $id)
    {
//        $this->db->query("DELETE FROM $tableName WHERE id=?", $id);
        $this->db->table($tableName)
            ->where('id', $id)
            ->delete();
    }
}