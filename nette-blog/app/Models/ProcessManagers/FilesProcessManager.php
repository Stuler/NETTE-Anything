<?php
declare(strict_types=1);

namespace App\Models\ProcessManagers;

use App\Models\Repository\FilesRepository;
use Nette\Http\FileUpload;

// TODO pri vytvoreni zlozky check, ci uz neexistuje
// TODO Jednotna funkcia pre uploat aj create

class FilesProcessManager
{
    const PATH = __DIR__ . '/../../www/workDir';

    /** @var FilesRepository @inject @internal */
    public $filesRepo;

    public function getFilesAndDirs()
    {
        $itemsByLevel1 = $this->filesRepo->findAllItemByLevel(1)->fetchAssoc("[]"); // vrati pole poli - vsetky data
        $itemsByLevel2 = $this->filesRepo->findAllItemByLevel(2)->fetchAssoc("parent_id[]"); //vrati asociativne pole poli, ak maju vyplnene parent_id
        foreach ($itemsByLevel1 as &$item) {
            if (isset($itemsByLevel2[$item['id']])) { //ak maju itemy 2. levelu nastavene id itemu z 1. levelu
                $item['items'] = $itemsByLevel2[$item['id']]; //priradi item do druheho levelu
            } else {
                $item['items'] = []; //priradi item do levelu 1
            }
        }
        return $itemsByLevel1;
    }

    public function uploadFile(FileUpload $file, ?int $parentId)
    {
    	$fileName = $file->getUntrustedName();
        $filePath = $this->setFilePath($fileName);
        $file->move($filePath);

        $this->filesRepo->add([
            "name" => $fileName,
            "file_path" => $filePath,
            "size" => $file->getSize(),
            "date_created" => new \DateTime(),
            "is_dir" => 0,
            "parent_id" => $parentId, //tu predat ID z url ked oznacim zlozku
            "level" => $this->getNextLevelByFileId($parentId),
        ]);
    }

    public function createDir(string $file, ?int $parentId)
    {
        $this->filesRepo->add([
            "name" => $file,
            "date_created" => new \DateTime(),
            "is_dir" => 1,
            "parent_id" => $parentId,
            "level" => $this->getNextLevelByFileId($parentId),
        ]);
    }

    public function remove(int $id)
    {
        $matchedFiles = $this->filesRepo->fetchAllChildren($id);
        foreach ($matchedFiles as $file) {
            $fileName = $file['name'];
	        $filePath = $this->setFilePath($fileName);
            if (!$file['is_dir']) {
                unlink($filePath);
            }           
            $this->filesRepo->remove($id);
        }
    }

	/*
    * - Funkcia na premenovanie - prijme z presentera novy nazov z formulara a hidden ID
    * - zavola pomocnu funkciu getSimilar, ktora vytiahne z DB vsetky polozky s rovnakym nazvom
		a rovnakym rodicom
	* - vyvola vynimku, ak zlozka uz existuje
	*/
    public function rename(string $name, int $id)
    {
	    $similar = $this->getSimilar($name, $id);
	    $file = $this->filesRepo->fetchById($id);


        if (!$file['is_dir']) {
	        $filePath = $this->getFilePath($id);
            $newFilePath = $this->setFilePath($name);
            rename($filePath, $newFilePath);
            $this->filesRepo->rename($name, $id);
        } else
            if (empty($similar)) {
                $this->filesRepo->rename($name, $id);
            } else {
                throw new FileException("Složka již existuje");
            }
    }

	private function getNextLevelByFileId(?int $id): int
	{
		if ($id) {
			$parentFile = $this->filesRepo->fetchById($id);
			return $parentFile['level'] + 1;
		} else {
			return 1;
		}
	}

    private function getFileName(?int $id)
    {
        $fileName = $this->filesRepo->fetchById($id);
        return $fileName['name'];
    }

	private function getFilePath(?int $id): string
	{
		$fileName = $this->getFileName($id);
		return self::PATH . '/' . $fileName;
	}

	public function setFilePath($fileName): string
	{
		return self::PATH . '/' . $fileName;
	}

	private function getParentId(?int $id)
    {
        $fileName = $this->filesRepo->fetchById($id);
        return $fileName['parent_id'];
    }

    private function getSimilar($name, $id): array
    {
	    $fileParent = $this->getParentId($id);
	    return $this->filesRepo->findAllSimilarFolders($name, $fileParent);
    }
}

class FileException extends \Exception
{
}