<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProcessManagers\FilesProcessManager;
use App\Models\Repository\FilesRepository;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\FileSystem;
use Nette\SmartObject;
use Nette\Http\FileUpload;


final class FilesPresenter extends Nette\Application\UI\Presenter
{

    /** @var FilesProcessManager @inject @internal */
    public $filesPM;

    /** @var FilesRepository @inject @internal */
    public $filesRepo;

    public function renderDefault()
    {
        $items = [
            ["id" => 1, "name" => "složka", "level" => 1],
            ["id" => 2, "name" => "soubor", "level" => 2],
            ["id" => 3, "name" => "soubor 2", "level" => 2],
            ["id" => 4, "name" => "složka 5", "level" => 2],
            ["id" => 5, "name" => "složka XX", "level" => 3],
        ];
    }

    /*
     * Formular na upload suboru
     * TODO sprava uzivatelovi o uspesnom uploade
    */
    public function createComponentFormUpload(): Form
    {
        $form = new Form();
        $form->addGroup("Upload souboru");
        $form->addUpload("file", "Připni soubor:");
        $form->addSubmit("upload", "Připni");

        $form->onSuccess[] = function (Form $form, $values) {
            // get Session id - ak by som chcel ist cez session
            $this->filesPM->uploadFile($values['file'], $this->getParameter("id"));
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
        $form->addSubmit("create", "Vytvoř");

        $form->onSuccess[] = function (Form $form, $values) {
            $this->filesPM->createDir($values['file'], $this->getParameter("id"));
            $this->redirect("this");
        };
        return $form;
    }

    public function handleDelete(int $id)
    {

    }
}
