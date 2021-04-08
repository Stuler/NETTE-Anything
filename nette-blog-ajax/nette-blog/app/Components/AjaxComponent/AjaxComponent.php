<?php
declare(strict_types=1);

namespace App\Components\FileSystem;

use Nette\Application\UI\Control;

class AjaxComponent extends Control {

	public function render() {

		$this->template->rand = rand(1, 11111);
		$this->template->setFile(__DIR__ . "/ajaxComponent.latte");
		$this->template->render();
	}

}
