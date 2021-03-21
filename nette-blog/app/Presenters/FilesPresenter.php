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
    */
    public function createComponentFormUpload(): Form
    {
        $form = new Form();
        $form->addGroup("Upload souboru");
        $form->addUpload("file", "Připni soubor:");
	    $form->addHidden("parent_id")
		    ->setDefaultValue($this->getParameter("id"));
	    $form->addHidden("level")
		    ->setDefaultValue($this->getParameter("level"));
	    $form->addHidden("is_dir")
		    ->setDefaultValue($this->getParameter("is_dir"));
        $form->addSubmit("upload", "Připni");

        /*
         * Pre pridanie levelu:
         * kontrolujem, ci mam v URL priradene ID a level
         * ak mam ID, level a mam oznacenu zlozku, level sa zvysi o hodnotu 1
         * ak nemam ani ID, ani level, vytvorim do levelu 1
         * */

        $form->onSuccess[] = function (Form $form, $values) {
        	if ($values['is_dir']==1){ //ak nie je zlozka, zatial sa neda oznacit, ale mozno v buducnosti sa bude dat - osetrit!!
        		$levelInc = $values['level']+=1;
		        $this->filesPM->uploadFile($values['file'], (int) $values['parent_id'], (int) $levelInc);
	        }
        	else {
        		$values['level'] = 1;
		        $this->filesPM->uploadFile($values['file'], (int) $values['parent_id'], (int) $values['level']);
	        }
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
	    $form->addHidden("level")
		    ->setDefaultValue($this->getParameter("level"));
	    $form->addHidden("is_dir")
		    ->setDefaultValue($this->getParameter("is_dir"));
        $form->addSubmit("create", "Vytvoř");

        $form->onSuccess[] = function (Form $form, $values) {
	        if ($values['is_dir']==1){ //ak nie je zlozka, zatial sa neda oznacit, ale mozno v buducnosti sa bude dat - osetrit!!
		        $levelInc = $values['level']+=1;
		        $this->filesPM->createDir($values['file'], (int) $values['parent_id'], (int) $levelInc);
	        }
	        else {
		        $values['level'] = 1;
		        $this->filesPM->createDir($values['file'], (int) $values['parent_id'], (int) $values['level']);
	        }
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

    }
}
