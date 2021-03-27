<?php
declare(strict_types=1);

namespace App\Models\ProcessManagers;

use App\Models\Repository\FilesRepository;
use Nette\Http\FileUpload;

// TODO pri vytvoreni zlozky check, ci uz neexistuje

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

    /*
     * Zakomponovat funckiu/podmienku na pridanie suboru/zlozky, ak mam subor oznaceny
     * - na to musim zoskat ID zo sablony
     * - id sa zapise ako parent_id pre pridavany subor/zlozku
     * - pomocou id a css stylu zvyraznim oznacenu zlozku
     * */
    public function uploadFile(FileUpload $file, ?int $parentId)
    {
        $filePath = self::PATH . '/' . $file->getUntrustedName();
        $file->move($filePath);

        $this->filesRepo->add([
            "name" => $file->getUntrustedName(),
            "file_path" => $filePath,
            "size" => $file->getSize(),
            "date_created" => new \DateTime(),
            "is_dir" => 0,
            "parent_id" => $parentId, //tu predat ID z url ked oznacim zlozku
            "level" => $this->getNextLevelByFileId($parentId),
        ]);
    }

    private function getNextLevelByFileId(?int $id)
    {
        if ($id) {
            $parentFile = $this->filesRepo->fetchById($id);
            return $parentFile['level'] + 1;
        } else {
            return 1;
        }
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
            $filePath = self::PATH . '/' . $file['name'];
            if (!$file['is_dir'])
                unlink($filePath);
        }
        $this->filesRepo->remove($id);
    }

    public function rename(string $name, int $id)
    {
        $fileParent = $this->getParentId($id);
        $folders = $this->filesRepo->fetchByParent($fileParent);

        $folderName = $this->filesRepo->findAllSimilarFolders($name, $fileParent);
	    $file = $this->filesRepo->fetchById($id);
        bdump($folderName);

        if (!$file['is_dir']) {
            $filePath = self::PATH . '/' . $file['name'];
            $newFilePath = self::PATH . '/' . $name;
            rename($filePath, $newFilePath);
            $this->filesRepo->rename($name, $id);
        } else
            if (empty($folderName)) {
                $this->filesRepo->rename($name, $id);
            } else {
                throw new FileException("Složka již existuje");
            }
    }

    public function getFileName(?int $id)
    {
        $fileName = $this->filesRepo->fetchById($id);
        return $fileName['name'];
    }

    public function getParentId(?int $id)
    {
        $fileName = $this->filesRepo->fetchById($id);
        return $fileName['parent_id'];
    }

    public function getFilePath(?int $id)
    {
        return self::PATH . '/' . $file['name'];
    }

    /*TODO
     * Jednotna funkcia pre uploat aj create
     * */
}

class FileException extends \Exception
{
}