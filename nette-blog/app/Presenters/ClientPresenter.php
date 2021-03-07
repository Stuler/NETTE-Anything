<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;
use Nette;
use Nette\Application\UI\Form;

final class ClientPresenter extends Nette\Application\UI\Presenter
{

    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

    public function createComponentMyForm(): form
    {
        $form = new Form();

        $form->addHidden("id");

        $form->addText("name", "Client name")
            ->addRule(Form::FILLED, "Enter client name");

        $form->addText("ico", "Client ICO");

        $form->addText("dic", "Client DIC");

        $form->addText("number", "Client number");

        $form->addText("street_num", "Street number");

        $form->addText("city", "City");

        $form->addText("zip", "ZIP Code");

        $form->addText("email", "Client's email address");

        $form->addText("mobile", "Client cell phone number");

        $form->addText("phone", "Client telephone number");

        $form->addText("fax", "Client fax number");

        $form->addText("website", "Client website");

        $form->addText("contact_person", "Contact person");

        $form->addText("note", "Note");

        $form->addSubmit("send", "Add client");

        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();
            $data = (array)$values;
            $this->clientsPM->addClient($data);

            $this->redirect("Clients:default");
        };

        return $form;

    }
}
