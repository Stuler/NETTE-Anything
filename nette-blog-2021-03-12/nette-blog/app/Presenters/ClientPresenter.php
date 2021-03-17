<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\ClientList\ClientList;
use App\Components\ClientList\ClientListFactory;
use Nette;
use Nette\Application\UI\Form;

final class ClientPresenter extends Nette\Application\UI\Presenter {

	/** @var Nette\Database\Explorer @inject @internal */
	public $db;

	/** @var ClientListFactory @inject @internal */
	public $clientListFactory;

	/** @var Nette\Http\Session @inject @internal */
	public $session;

	public function renderDefault() {
		$this->template->isLoggedIn = $this->getMySession()->loginName ? true : false;
		$this->template->clients = $this->db->table("client")->fetchAll();
	}

	public function createComponentFormLogin(): Form {
		$form = new Form();
		$form->addText("pin", "PIN");
		$form->addSubmit("login", "Přihlásit");
		$form->onSuccess[] = function (Form $form, $values) {
			if (sha1($values['pin']) == "40bd001563085fc35165329ea1ff5c5ecbdbbeef") {
				$this->getMySession()->loginName = "Host";
				$this->redirect("this");
			}
		};
		return $form;
	}

	private function getMySession(): Nette\Http\SessionSection {
		return $this->session->getSection("login");
	}

	public function createComponentClientList(): ClientList {
		return $this->clientListFactory->create();
	}

	public function renderEdit(int $id = null) {
		if ($id) {
			$values = $this->db->table("client")->where("id", $id)->fetch();
			$this['formClient']->setDefaults($values);
			$this->template->persons = $this->db->table("client_person")->where("client_id", $id)->fetchAll();
		}

		$this->template->isEdit = $id != null;
	}

	public function createComponentFormClient(): Form {
		$form = new Form();
		//		$form->addHidden("id");
		$form->addText("name", "Jméno");
		$form->addSubmit("save", "Uložit");
		$form->onSuccess[] = function (Form $form, $values) {
			if ($values['id']) {
				$this->db->table("client")->where("id", $values['id'])->update($values);
			} else {
				unset($values['id']);
				$this->db->query("INSERT INTO `client`", $values);
				$id = $this->db->getInsertId();
				$this->redirect("this", ["id" => $id]);
			}
		};
		return $form;
	}

	public function handleEditPerson(int $personId) {
		$values = $this->db->table("client_person")->where("id", $personId)->fetch();
		$this['formClientPerson']->setDefaults($values);
	}

	public function createComponentFormClientPerson(): Form {
		$form = new Form();
		$form->addGroup("Kontakt");
		$form->addHidden("id");
		$form->addHidden("client_id")->setDefaultValue($this->getParameter("id"));
		$form->addText("name", "Jméno");
		$form->addSubmit("save", "Uložit kontakt");
		$form->onSuccess[] = function (Form $form, $values) {
			if ($values['id']) {
				$this->db->table("client_person")->where("id", $values['id'])->update($values);
				$this->redirect("this");
			} else {
				unset($values['id']);
				$this->db->query("INSERT INTO `client_person`", $values);
				$this->redirect("this");
			}
		};
		return $form;
	}

}
