<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;
use Nette;
use Nette\Application\UI\Form;

final class ClientsPresenter extends Nette\Application\UI\Presenter
{

    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

    public function renderDefault()
    {
        $searchTerm = $this->getParameter("term");
        if ($searchTerm){
            $this->template->clients = $this -> clientsRepo->fetchAllActiveBySearchTerm($searchTerm);
        } else {
            $this->template->clients = $this->clientsRepo->fetchAllActive();
        }
    }

    public function createComponentFormSearch(): Form {
        $form = new Form();

        $form->addText("term")->setValue($this->getParameter("term"));
        $form->addSubmit("send","Vyhledat");

        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();

            $this->redirect("this", [
                    "term" => $values['term'] ? $values['term'] : null,
                ]
            );
        };

        return $form;
    }

}
