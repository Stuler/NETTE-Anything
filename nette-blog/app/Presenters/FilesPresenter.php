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

    public function createComponentFormUpload(): Form {
        $form = new Form();
        $form->addGroup("Upload souboru");

        $form->addUpload("file", "Připni soubor:");
        $form->addSubmit("upload", "Připni");

        $form->onSuccess[] = function (Form $form, $file) {
            $values = $form->getValues();
            $path = __DIR__.'/../../www/workDir/' .$values->file->getName();
            $values->file->move($path);
            $this->redirect("this");
        };
        return $form;
    }

    public function createComponentFormCreate(): Form {
        $form = new Form();
        $form->addGroup("Vytvoření složky");

        $form->addText("file", "Vytvoř soubor:");
        $form->addSubmit("create", "Vytvoř");

        $form->onSuccess[] = function (Form $form) {
            $this->redirect("this");
        };
        return $form;
    }

	public function renderDefault()
	{

	}

    public function handleDelete(int $id)
    {

    }
}
