<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProcesManagers\TaskProcessManager;
use App\Models\Repository\TaskRepository;
use Nette;
use Nette\Application\UI\Form;

final class TaskPresenter extends Nette\Application\UI\Presenter {

	/** @var TaskProcessManager @inject @internal */
	public $taskPM;

	/** @var TaskRepository @inject @internal */
	public $taskRepo;

	public function renderDefault() {
		$this->template->date = date("d.m.Y");
		$searchTerm = $this->getParameter("term");
		if ($searchTerm) {
			$this->template->items = $this->taskRepo->fetchAllActiveBySearchTerm($searchTerm);
		} else {
			$this->template->items = $this->taskRepo->fetchAllActive();
		}
	}

	public function createComponentFormSearch(): Form {
		$form = new Form();

		$form->addText("term")->setValue($this->getParameter("term"));
		$form->addSubmit("send", "Vyhledat");

		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();

			$this->redirect("this", [
					"term" => $values['term'] ? $values['term'] : null,
				]
			);
		};

		return $form;
	}

	public function handleDelete(int $id) {
		$this->taskPM->removeTask($id);
		$this->redirect("this");
	}

	public function renderEdit(?int $id) {
		if ($id) {
			$task = $this->taskRepo->fetchById($id);
			$this['myForm']->setDefaults(["label" => $task->label, "id" => $task->id]);
		}
	}

	public function createComponentMyForm(): Form {
		$form = new Form();
		$form->addHidden("id");

		$form->addText("label", "Název úkolu")
			->addRule(Form::FILLED, "Vyplň popis");

		$form->addSubmit("send", "Vytvořit");

		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			if ($values['id']) {
				$this->taskPM->updateTask((int)$values['id'], $values['label']);
			} else {
				$this->taskPM->addTask($values['label']);
			}
			$this->redirect("default");
		};

		return $form;
	}

}
