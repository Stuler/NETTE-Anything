<?php
declare(strict_types=1);

namespace App\Components\CustomList;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use App\Components\CustomList\Models\CustomRepository;
use App\Components\CustomList\Models\CustomProcessManager;

class CustomList extends Control
{
    /** @var CustomProcessManager @inject @internal */
    public $customPM;

    /** @var CustomRepository @inject @internal */
    public $customRepo;

    /** @var string vytiahnem tabulku z databaze */
    private $tableName;

    /** @var array zoznam dat na vykreslenie - zoznam stlpcov tabulky*/
    private $columns = [];

    /** @var string spolocny stlpec - pre vykreslenie dat podla foreign ID*/
    private $relationColumn;

    /** @var int hodnota foreign ID*/
    private $relationValue;

    /** @var array */
    public $onClick;

    private $searchTerm;

    public function render()
    {
        /* pre vypis klientov potrebujem:
         *  - nazov tabulky
         *  - vyber stlpcov z db
        */
        $this->template->columns = $this->columns;
        $this->template->items = $this->customRepo->fetchAllCustom($this->tableName, $this->relationColumn, $this->relationValue, $this->searchTerm, $this->columns);
        $this->template->setFile(__DIR__ . "/customList.latte");
        $this->template->render();
    }

    public function createComponentFormSearch(): Form
    {
        $form = new Form();
        $form->getElementPrototype()->class("ajax");

        $form->addText("term");

        $form->addSubmit("send", "Vyhledat");
        $form->addSubmit("clear", "Vyčistit");

        $form->onSuccess[] = function (Form $form, $values) {
            if ($form['send']->isSubmittedBy()) {
                $this->searchTerm = $values['term'];
                $this->redrawControl("list");
            } else {
                $form->reset();
                $this->redrawControl("formSearch");
                $this->redrawControl("list");
            };
        };
        return $form;
    }

    /**
     * Vytvorim nasluchac onClick, ktory je spracovany v clientDetail
     */
    public function handleEditCustom(?int $id)
    {
        $this->onClick($id);
    }

    public function handleCloseModal()
    {
        $this->redrawControl("modal");
    }

    public function handleRemoveCustom(int $id)
    {
        $tableName = $this->tableName;
        $this->customPM->removeCustom($tableName, $id);
        $this->redrawControl("list");
    }


    public function setTable(string $tableName)
    {
        $this->tableName = $tableName;
    }

    /* Pridam stlpce, ktore chcem v zozname zobrazit*/
    public function addColumn(string $columnName, string $label)
    {
        $this->columns[] = ["name" => $columnName, "label" => $label];
    }

    /* Nastavim prepojenie tabuliek - foreign key a jeho hodnotu ID */
    public function setRelation(string $column, int $relationValue)
    {
        $this->relationColumn = $column;
        $this->relationValue = $relationValue;
    }
}