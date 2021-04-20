<?php
declare(strict_types=1);

namespace App\Components\ClientDetail;

//use App\Components\ClientList\ClientList;
//use App\Components\ClientList\ClientListFactory;
use App\Components\FileSystem\FileSystem;
use App\Components\FileSystem\FileSystemFactory;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
//use Nette\Database\Explorer;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;

class ClientDetail extends Control
{
	/** @var ClientsProcessManager @inject @internal */
	public $clientsPM;

	/** @var ClientsRepository @inject @internal */
	public $clientsRepo;

	/** @var FileSystemFactory @inject @internal */
	public $fileSystemFactory;

	public $clientId;

    /**
     * @persistent
     */
    public $id;

    public function render() {

    	$id = (int)$this->id;
        if ($id) {
            $client = $this->clientsRepo->fetchById($id);
            $this['clientForm']->setDefaults($client);

            $client_person = $this->clientsRepo->fetchContactById($id);
            $this->template->contacts = $client_person;
        } else{
		$this->template->contacts = [];
		}
        $this->template->isEdit = $id != null || $this->clientId != null;

        $this->template->setFile(__DIR__ . "/clientDetail.latte");
        $this->template->render();
    }

	public function createComponentClientForm(): form
	{
		$form = new Form();

		$form->getElementPrototype()->class("ajax");

		$form->addHidden("id");
//		    ->setDefaultValue($this->getParameter("id"));

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

		$form->addSubmit("send", "Založit");

		$form->onSuccess[] = function (Form $form) {

			$values = $form->getValues();
			$data = (array)$values;
			if ($values['id']) {
				$this->clientsPM->updateClient((int)$values['id'], (array)$data);
				$form->setValues($values, true);
				$this->redrawControl("clientForm");
				$this->redrawControl("clientList");
			} else {
				$this->clientsPM->addClient($data);
				$id = $this->clientsRepo->db->getInsertId();
				$this->clientId = $id;
				$form->setValues([$values], true);

				$this->redrawControl("form");
//              $this->redrawControl("contactForm");
//				$this->redrawControl("contactList");
//				$values['id']=$this->id;
//				$this->redirect("this", ["id" => $id]);
			}
//			$form->setValues(['id'=>$this->id], true);
		};
		return $form;
	}

	public function createComponentPersonForm(): Form
	{
		$form = new Form();

		$form->getElementPrototype()->class("ajax");

		$form->addHidden("id");

        $form->addHidden("client_id")
			->setDefaultValue($this->id);

		$form->addText("name", "Meno")
			->addRule(Form::FILLED, "Uveď meno kontaktnej osoby!");

		$form->addText("email", "Email")
			->addRule(Form::FILLED, "Uveď emailovú adresu kontaktnej osoby!");

		$form->addText("phone", "Telefón");

		$form->addText("status", "Pozícia");

		$form->addSubmit("send", "Pridať kontaktnú osobu");

		$form->onSuccess[] = function (Form $form, $values) {
			$data = (array)$values;
			if ($values['id']) {
				$this->clientsPM->updateContactPerson((int)$values['id'], (array)$data);
				$this->redrawControl("contactForm");
				$this->redrawControl("contactList");
			} else {
				$this->clientsPM->addContactPerson($data);
				$this->redrawControl("contactForm");
				$this->redrawControl("contactList");
			}
			$form->setValues(['client_id'=>$this->id], true);
		};

		return $form;
	}

	public function createComponentFileSystem(): FileSystem
	{
		$fileSystem = $this->fileSystemFactory->create();
		$fileSystem->clientId = $this->getParameter("id");
		return $fileSystem;
	}

	// public function createComponentClientList(): ClientList {
	// 	$clientList = $this->clientListFactory->create();
	// 	return $clientList;
	// }

	public function handleEditPerson(int $contactId)
	{
		$values = $this->clientsRepo->fetchContact($contactId);
		$this['personForm']->setDefaults($values);
		$this->redrawControl("contactForm");
	}

	public function handleDeleteContact(int $contactId)
	{
		$this->clientsPM->removeContact($contactId);
		$this->redrawControl("contactList");
	}

	public function handleShowModal()
	{
		$this->template->showModal = true;
		$this->redrawControl("modal");
	}

	public function handleCloseModal()
	{
		$this->redrawControl("modal");
	}
}