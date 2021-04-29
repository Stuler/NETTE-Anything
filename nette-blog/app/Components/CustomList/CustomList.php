<?php
declare(strict_types=1);

namespace App\Components\CustomList;

use Nette\Application\UI\Control;
use App\Models\ProcessManagers\ClientsProcessManager;
use App\Models\Repository\ClientsRepository;
use Nette\Application\UI\Form;

class CustomList extends Control
{
    /** @var ClientsProcessManager @inject @internal */
    public $clientsPM;

    /** @var ClientsRepository @inject @internal */
    public $clientsRepo;

    /** @var string tabulka z databaze */
    private $tableName;

    /** @var array zoznam dat na vykreslenie */
    private $columns = [];

    /** @var string spolocny kluc */
    private $relationColumn;

    /** @var int */
    private $relationValue;

    /** @var array */
    public $onClick;

    public function render()
    {
        $searchTerm = $this->getParameter("term");
        if ($searchTerm) {
			$this->template->clients = $this->clientsRepo->fetchAllActiveBySearchTerm($searchTerm);
		} else {
/* pre vypis klientov potrebujem:
 *  - nazov tabulky
 *  - vyber stlpcov z db
*/
            $this->template->columns = $this->columns;
            $this->template->relationValue = $this->relationValue;
            $this->template->relationColumn = $this->relationColumn;
			$this->template->clients = $this->clientsRepo->fetchAllCustom($this->tableName);
		}
        $this->template->setFile(__DIR__ . "/customList.latte");
        $this->template->render();
    }

    public function setTable(string $tableName) {
        $this->tableName=$tableName;
    }

    public function setRelCol(string $relationColumn) {
        $this->relationColumn=$relationColumn;
    }

    public function setColumns(array $columns) {
        $this->columns = $columns;
    }

    public function setRelVal(int $relationValue) {
        $this->relationValue = $relationValue;
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

    public function handleShowModal(?int $id)
    {
        $this->onClick($id);
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