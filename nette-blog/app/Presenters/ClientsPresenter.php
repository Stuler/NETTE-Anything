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
        $searchCrit = $this->getParameter("crit");

        if ($searchTerm){
            $this->template->clients = $this -> clientsRepo->fetchAllActiveBySearchTerm($searchCrit, $searchTerm);
        } else {
            $this->template->clients = $this->clientsRepo->fetchAllActive();
        }
    }

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
            if ($values['id']) {
            	$this->clientsPM->updateClient((int)$values['id'], (array)$data);
            } else {
	            $this->clientsPM->addClient($data);
            }
            $this->redirect("Clients:default");
        };

        return $form;

    }

    public function createComponentFormSearch(): Form {

        $crits = [
            'name' => 'Název',
            'ico' => 'IČO',
            'email' => 'E-mail',
        ];

        $form = new Form();

        $form->addSelect("crit", "Kde", $crits)
            ->getRawValue();
        $form->addText("term")->setValue($this->getParameter("term"));
        $form->addSubmit("send","Vyhledat");

        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();

            $this->redirect("this", [
                    "crit" => $values['crit'],
                    "term" => $values['term'] ? $values['term'] : null
                ]
            );
        };

        return $form;
    }

	public function renderEdit(?int $id)
	{
		if ($id) {
			$client = $this->clientsRepo->fetchById($id);
			$this['myForm']->setDefaults($client);
		}
	}

    public function handleDelete(int $id) {
    	$this->clientsPM->removeClient($id);
    	$this->redirect("this");
    }

}
