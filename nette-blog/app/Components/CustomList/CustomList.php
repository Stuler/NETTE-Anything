<?php
declare(strict_types=1);

namespace App\Components\CustomList;

use Nette\Application\UI\Control;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;

class CustomList extends Control
{
    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

    /** @var string tabulka z databaze */
    private $table;

    /** @var array zoznam dat na vykreslenie */
    private $columns = [];

    /** @var spolocny kluc */
    private $relativeColumn;

    /** @var int */
    private $relationValue;

    public function renderClientList()
    {
        $searchTerm = $this->getParameter("term");
        if ($searchTerm) {
			$this->template->clients = $this->clientsRepo->fetchAllActiveBySearchTerm($searchTerm);
		} else {
			$this->template->clients = $this->clientsRepo->fetchAllActive();
		}
        $this->template->setFile(__DIR__ . "/customList.latte");
        $this->template->render();
    }

    public function renderContactList()
    {
        $this->template->setFile(__DIR__ . "/customList.latte");
        $this->template->render();
    }

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
}