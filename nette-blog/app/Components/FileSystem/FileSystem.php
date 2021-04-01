<?php
declare(strict_types=1);

namespace App\Components\FileSystem;

use Nette\Application\UI\Control;
use App\Models\ProcessManagers\FileException;
use App\Models\ProcessManagers\FilesProcessManager;
use App\Models\Repository\FilesRepository;
use Nette;
use Nette\Application\UI\Form;

class FileSystem extends Control
{

    /** @var FilesProcessManager @inject @internal */
    public $filesPM;

    /** @var FilesRepository @inject @internal */
    public $filesRepo;

    public function render(?int $id) {
        $this->template->items = $this->filesPM->getFilesAndDirs();
        $this->template->selectedId = $this->getParameter("id");

        if ($id) {
            $selectedFile = $this->filesRepo->fetchById($id);
            $this['formRename']->setDefaults($selectedFile);
        } else {
            $selectedFile = null;
        }

        $this->template->setFile(__DIR__ . "/fileSystem.latte");
        $this->template->render;
    }

    public function createComponentFormUpload(): Form
    {
        $form = new Form();
        $form->addGroup("Upload souboru");
        $form->addUpload("file", "Připni soubor:");
        $form->addHidden("parent_id")
            ->setDefaultValue($this->getParameter("id"));
        $form->addSubmit("upload", "Připni");

        $form->onSuccess[] = function (Form $form, $values) {
            $this->filesPM->uploadFile(
                $values['file'],
                $values['parent_id'] ? (int)$values['parent_id'] : null
            );
            $this->redirect("this");
        };
        return $form;
    }

    public function createComponentFormCreate(): Form
    {
        $form = new Form();
        $form->addGroup("Vytvoření složky");
        $form->addText("file", "Vytvoř složku:");
        $form->addHidden("parent_id")
            ->setDefaultValue($this->getParameter("id"));
        $form->addHidden("id");
        $form->addSubmit("create", "Vytvoř");

        $form->onSuccess[] = function (Form $form, $values) {
            try {
                $this->filesPM->createDir(
                    $values['file'],
                    $values['parent_id'] ? (int)$values['parent_id'] : null
                );
                $this->flashMessage("Složka byla vytvořena.", "ok");
                $this->redirect("this");
            } catch (FileException $e) {
                $this->flashMessage($e->getMessage(), "err");
            }
        };
        return $form;
    }

    public function createComponentFormRename(): Form
    {
        $form = new Form();
        $form->addGroup("Přejmenování");
        $form->addText("name", "Nový název: ");
        $form->addHidden("id")
            ->setDefaultValue($this->getParameter("id"));
        $form->addSubmit("rename", "Přejmenuj");

        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();
            try {
                $this->filesPM->rename(
                    $values['name'],
                    (int)$values['id']
                );
                $this->flashMessage("Soubor byl přejmenován.", "ok");

                $this->redirect("this");
            } catch (FileException $e) {
                $this->flashMessage($e->getMessage(), "err");
            }
        };
        return $form;
    }

    public function handleDelete(int $id)
    {
        $this->filesPM->remove($id);
        $this->redirect("this", ["fileSystem-id" => null]);
    }

}
