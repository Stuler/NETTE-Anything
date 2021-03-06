<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;
use Nette;
use Nette\Application\UI\Form;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

    public function renderDefault()
    {
        $this->template->clients = $this->clientsRepo->fetchAllActive();
    }

}
