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

    public $clientId;

    public function render()
    {
        $clientId = $this->clientId;
        $this->template->items = $this->filesPM->getFilesAndDirs($clientId);

        $fileId = $this->getParameter("id");
        $this->template->selectedId = (int)$fileId;

        $this->template->linkSelectDir = function (int $dirId) use ($fileId) {
            return $this->link("selectDir!", ["id" => (($fileId == null || $fileId != $dirId) ? $dirId : null)]);
        };

        $this->template->linkDelete = function (int $dirId) {
            return $this->link("delete!", ["id" => $dirId]);
        };

        if ($fileId) {
            $selectedFile = $this->filesRepo->fetchById((int)$fileId);
            $this['formRename']->setDefaults($selectedFile);
        } else {
            $selectedFile = null;
        }

        $this->template->setFile(__DIR__ . "/fileSystem.latte");
        $this->template->render();
    }

    public function createComponentFormUpload(): Form
    {
        $form = new Form();

        $form->addGroup("Upload souboru");

        $form->addUpload("file", "Připni soubor:");

        $form->addHidden("id");

        $form->addHidden("client_id");
//            ->setDefaultValue($this->getPresenter()->getParameter("id"));

        $form->addHidden("parent_id");
//            ->setDefaultValue($this->getParameter("id"));

        $form->addSubmit("upload", "Připni");

        $form->onSuccess[] = function (Form $form, $values) {
            $this->filesPM->uploadFile(
                $values['file'],
                (int)$this->clientId,
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

        $form->addHidden("client_id");
//            ->setDefaultValue($this->getPresenter()->getParameter("id")); //??

        $form->addHidden("parent_id");
//            ->setDefaultValue($this->getParameter("id"));

        $form->addSubmit("create", "Vytvoř");

        $form->onSuccess[] = function (Form $form, $values) {
            try {
                $this->filesPM->createDir(
                    $values['file'],
                    (int)$this->clientId,
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

        $form->addHidden("client_id");

        $form->addHidden("id")
            ->setDefaultValue($this->getParameter("id"));

        $form->addSubmit("rename", "Přejmenuj");

        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();
            try {
                $this->filesPM->rename(
                    $values['name'],
                    $values['client_id'],
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

    public function handleSelectDir(?int $id = null)
    {
        $this->redirect("this", ["id" => $id]);
    }

    public function handleDelete(int $id)
    {
        $this->filesPM->remove($id);
        $this->redirect("this", [$this->getName() . "-id" => null]);
    }
}