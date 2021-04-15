<?php
declare(strict_types=1);

namespace App\Components\ClientDetail;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;

class ClientDetail extends Control
{
    public function render() {
        $this->template->setFile(__DIR__ . "/clientDetail.latte");
        $this->template->render();
    }
}