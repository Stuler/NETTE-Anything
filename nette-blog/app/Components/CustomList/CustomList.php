<?php
declare(strict_types=1);

namespace App\Components\CustomList;

use Nette\Application\UI\Control;

class CustomList extends Control
{
    public function render()
    {
        $this->template->setFile(__DIR__ . "/customList.latte");
        $this->template->render();
    }
}