<?php
declare(strict_types=1);

namespace App\Models\ProcessManagers;

use App\Models\Repository\TaskRepository;

class TaskProcessManager {

	/** @var TaskRepository @inject @internal */
	public $taskRepo;

	private $prefix;

	public function __construct($prefix) {
		$this->prefix = $prefix;
	}

	public function addTask(string $label) {
		$this->taskRepo->add($label);
	}

	public function updateTask(int $id, string $label) {
		$this->taskRepo->update($id, $label);
	}

	public function removeTask(int $id) {
		$this->taskRepo->remove($id);
	}

}