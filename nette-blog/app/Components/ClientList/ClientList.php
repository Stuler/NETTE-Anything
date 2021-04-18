<?php
declare(strict_types=1);

namespace App\Components\ClientList;

//use App\Components\ClientDetail\ClientDetail;
//use App\Components\ClientDetail\ClientDetailFactory;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;

class ClientList extends Control {

	/** @var Explorer @inject @internal */
	public $db;

    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

//    /** @var ClientDetailFactory @inject @internal */
//    public $clientDetailFactory;

    /** @var array */
    public $onClick; //registracia udalosti - mozem volat ako funkciu

	private $count = null;
//	private $id;

	public function showOnlyFirst(int $count) {
		$this->count = $count;
	}

	public function render() {
		$searchTerm = $this->getParameter("term");
		if ($searchTerm) {
			$this->template->clients = $this->clientsRepo->fetchAllActiveBySearchTerm($searchTerm);
		} else {
			$this->template->clients = $this->clientsRepo->fetchAllActive();
		}

		$this->template->setFile(__DIR__ . "/clientList.latte");
		$this->template->render();
	}

/*	public function renderContacts() {
		$id = $this->id;
		$this->template->contacts = $this->clientsRepo->fetchContactById($id);
		$this->template->setFile(__DIR__ . "/clientList.latte");
		$this->template->render();
	}*/

	public function createComponentFormSearch(): Form
	{
		$form = new Form();

		$form->addText("term")->setValue($this->getParameter("term"));

		$form->addSubmit("send", "Vyhledat");

		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			$this->redirect("this", [
					"term" => $values['term'] ? $values['term'] : null
				]
			);
		};
		return $form;
	}

//    public function createComponentClientDetail(): ClientDetail {
//        $clientDetail = $this->clientDetailFactory->create();
//        return $clientDetail;
//    }

    public function handleShowModal(?int $id)
    {
//        $this->template->showModal = true;
//        $this->redrawControl("modal");
        $this->onClick($id); //udalost
    }

    public function handleCloseModal()
    {
        $this->redrawControl("modal");
    }


    public function handleDelete(int $id) {
        $this->clientsPM->removeClient($id);
        $this->redrawControl("list");
	}
    
}