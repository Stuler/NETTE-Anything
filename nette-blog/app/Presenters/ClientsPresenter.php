<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Components\FileSystem\FileSystem;
use App\Components\FileSystem\FileSystemFactory;
use App\Components\ClientList\ClientList;
use App\Components\ClientList\ClientListFactory;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;
use Nette;
use Nette\Application\UI\Form;
use App\Components\ClientDetail\ClientDetail;
use App\Components\ClientDetail\ClientDetailFactory;

// TODO: upravit spravanie suborov a zloziek
// TODO: modalne okno - pridat klienta


final class ClientsPresenter extends Nette\Application\UI\Presenter
{

    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

    /** @var FileSystemFactory @inject @internal */
    public $fileSystemFactory;

    /** @var ClientListFactory @inject @internal */
	public $clientListFactory;

	/** @var ClientDetailFactory @inject @internal */
	public $clientDetailFactory;

	protected function startup()
	{
		parent::startup(); // TODO: Change the autogenerated stub
	}

	protected function beforeRender()
	{
		$this->template->absoluteUrl = "C:\WWW\NETTE-Anything\nette-blog\www\bundle";
	}

    public function renderDefault()
    {
        $searchTerm = $this->getParameter("term");
        if ($searchTerm) {
            $this->template->clients = $this->clientsRepo->fetchAllActiveBySearchTerm($searchTerm);
        } else {
            $this->template->clients = $this->clientsRepo->fetchAllActive();
        }
    }

    /*
    * Funkcia vyrenderuje edit formular;
    * Ak je vyplnene ID, umozni pridat kontaktnu osobu
    *
    */
    /*public function renderEdit(?int $id)
    {
        if ($id) {
            $client = $this->clientsRepo->fetchById($id);
            $this['clientForm']->setDefaults($client);

            $client_person = $this->clientsRepo->fetchContactById($id);
            $this->template->contacts = $client_person;

        }
        $this->template->isEdit = $id != null;
    }*/

	public function createComponentClientDetail(): ClientDetail {
		$clientDetail = $this->clientDetailFactory->create();
		return $clientDetail;
	}

    public function createComponentClientList(): ClientList {
        $clientList = $this->clientListFactory->create();
        $clientList->onClick[]=function($id){
            $this["clientDetail"]->id = $id; //posielam perzistentny parameter do clientDetail
            $this->template->showModal=true;
            $this->redrawControl("modal");

                    };
        return $clientList;
    }

		/*    public function createComponentClientForm(): form
			{
				$form = new Form();

		//	    $form->getElementPrototype()->class("ajax");

				$form->addHidden("id")
				->setDefaultValue($this->getParameter("id"));

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

				/*
				 * pri uspesnom zalozeni noveho klienta sa vratim spat do vyplneneho formulara
				 * a zobrazi sa mi formular na vyplnenie kontaktnej osoby
				 * getInsertId mi donesie posledne pridane ID
				 */
		/*        $form->onSuccess[] = function (Form $form) {

					$values = $form->getValues();
					$data = (array)$values;
					if ($values['id']) {
						$this->clientsPM->updateClient((int)$values['id'], (array)$data);
						$form->setValues($values, true);
						$this->redrawControl("contactForm");
					} else {
						$this->clientsPM->addClient($data);
						$id = $this->clientsRepo->db->getInsertId();
		//                $form->setValues($values, true);
		//	            $this->redrawControl("contactList");
						$this->redirect("this", ["id" => $id]);

					}
				};
				return $form;
			}*/

		/*public function createComponentPersonForm(): Form
		{
			$form = new Form();

			$form->getElementPrototype()->class("ajax");

			$form->addHidden("id");

			$form->addHidden("client_id")
				->setDefaultValue($this->getParameter("id"));

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
				$form->setValues(['client_id'=>$values['client_id']], true);
			};

			return $form;
		}*/

		/*    public function createComponentFileSystem(): FileSystem
			{
				$fileSystem = $this->fileSystemFactory->create();
				$fileSystem->clientId = $this->getParameter("id");
				return $fileSystem;
			}*/



		/*
		 * Funkcia na vykreslenie a upravu kontaktov klienta
		 * contactId ma doniest id klienta a vypisat potrebne udaje
		 */
		/*    public function handleEditPerson(int $contactId)
			{
				$values = $this->clientsRepo->fetchContact($contactId);
				$this['personForm']->setDefaults($values);
				$this->redrawControl("contactForm");
			}*/

		/*    public function handleDeleteContact(int $contactId)
			{
				$this->clientsPM->removeContact($contactId);
				$this->redrawControl("contactList");
			}*/

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
