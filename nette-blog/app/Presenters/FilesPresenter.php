<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProcessManagers\FilesProcessManager;
use App\Models\Repository\FilesRepository;
use Nette;
use Nette\Application\UI\Form;


final class FilesPresenter extends Nette\Application\UI\Presenter
{

    /** @var FilesProcessManager @inject @internal */
    public $filesPM;

    /** @var FilesRepository @inject @internal */
    public $filesRepo;

    public function renderDefault()
    {
        $this->template->items = $this->filesPM->getFilesAndDirs();
        $this->template->selectedId = $this->getParameter("id");
    }

    /*
     * Formular na upload suboru
     * TODO sprava uzivatelovi o uspesnom uploade
     * TODO Premenovanie suboru - bude sa dat oznacit subor
    */
    public function createComponentFormUpload(): Form
    {
        $form = new Form();
        $form->addGroup("Upload souboru");
        $form->addUpload("file", "Připni soubor:");
        $form->addHidden("parent_id")
            ->setDefaultValue($this->getParameter("id"));
        $form->addSubmit("upload", "Připni");

        /*
         * Pre pridanie levelu:
         * kontrolujem, ci mam v URL priradene ID a level
         * ak mam ID, level a mam oznacenu zlozku, level sa zvysi o hodnotu 1
         * ak nemam ani ID, ani level, vytvorim do levelu 1
         * */

        $form->onSuccess[] = function (Form $form, $values) {
            $this->filesPM->uploadFile(
                $values['file'],
                $values['parent_id'] ? (int)$values['parent_id'] : null
            );
            $this->redirect("this");
        };
        return $form;
    }

    /*
     * Formular na vytvorenie zlozky
     * TODO sprava uzivatelovi o uspesnom vytvoreni
    */
    public function createComponentFormCreate(): Form
    {
        $form = new Form();
        $form->addGroup("Vytvoření složky");
        $form->addText("file", "Vytvoř složku:");
        $form->addHidden("parent_id")
            ->setDefaultValue($this->getParameter("id"));
        $form->addSubmit("create", "Vytvoř");

        $form->onSuccess[] = function (Form $form, $values) {
            $this->filesPM->createDir(
                $values['file'],
                $values['parent_id'] ? (int)$values['parent_id'] : null
             );
            $this->redirect("this");
        };
        return $form;
    }

    /*
     * Zjednotena funkcia pre onSucces na formular;
     * okem dat z formulara na vstupe potrebuje data, ci sa jedna o funckiu createDir alebo uploadFile
     * */
    private function addElement($form, $values)
    {
// TODO
    }

    public function handleDelete(int $id)
    {
        $this->filesPM->remove($id);
        $this->redirect("this");
    }
}
