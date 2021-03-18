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
        $form->addGroup("Upload");

        $form->addUpload("file", "Připni soubor:");
        $form->addSubmit("save", "Připni");

        $form->onSuccess[] = function (Form $form, $file) {
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
