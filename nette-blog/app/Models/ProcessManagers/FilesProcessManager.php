<?php
declare(strict_types=1);

namespace App\Models\ProcessManagers;

use App\Models\Repository\FilesRepository;
use Nette\Http\FileUpload;
use Nette\Utils\Random;

class FilesProcessManager
{
    const PATH = __DIR__ . '/../../www/workDir';

    /** @var FilesRepository @inject @internal */
    public $filesRepo;

    public function getFilesAndDirs($id)
    {
        $itemsByLevel1 = $this->filesRepo->findAllItemByLevel(1, (int)$id)->fetchAssoc("[]"); // vrati pole poli - vsetky data
        $itemsByLevel2 = $this->filesRepo->findAllItemByLevel(2, (int)$id)->fetchAssoc("parent_id[]"); //vrati asociativne pole poli, ak maju vyplnene parent_id

        foreach ($itemsByLevel1 as &$item) {
            if (isset($itemsByLevel2[$item['id']])) { //ak maju itemy 2. levelu nastavene id itemu z 1. levelu
                $item['items'] = $itemsByLevel2[$item['id']]; //priradi item do druheho levelu
            } else {
                $item['items'] = []; //priradi item do levelu 1
            }
        }
        return $itemsByLevel1;
    }

// TODO: prekopat remove funkciu
    public function uploadFile(FileUpload $file, ?int $client_id, ?int $parentId)
    {
        /*  1. potrebujem zistit, ci na disku uz podobny subor nemam
            2. ak mam, nastavim nazov noveho suboru "nazov_suboru"+random hash
            3. subor s novym nazvom ulozim na disk
            4. subor s povodnym nazvom ulozim do db
        */

        $fileName = $file->getUntrustedName();
        /*---------------------------------------------------------------------
        Procedura pri prioritazcii DB namiesto suborov na disku:
                // potrebujem ziskat rovnake subory:
                // $similar = $this->filesRepo->findAllByName($fileName);

                // if ($similar) {
                //     $ext = substr($fileName, strrpos($fileName, '.')); // oddelim priponu suboru
                //     $baseName = substr($fileName, 0, strrpos($fileName, '.')); // basename = nazov bez koncovky
                //     $itemsCount = $this->filesRepo->countByBaseName($baseName, $ext); // pocet rovnakych suborov s basename
                //     $fileName = $baseName . '(' . ++$itemsCount . ')' . $ext; // pridam por. cislo a koncovku
                // }
        ---------------------------------------------------------------------*/

        $files = scandir(self::PATH);
        if (in_array($fileName, $files)) {
            $ext = substr($fileName, strrpos($fileName, '.')); // oddelim priponu suboru
            $baseName = substr($fileName, 0, strrpos($fileName, '.')); // basename = nazov bez koncovky
            $nameHash = Random::generate(7, '0-9a-z');
            $baseNameMod = $baseName . $nameHash . $ext;
            $filePath = $this->setFilePath($baseNameMod);
        } else {
            $filePath = $this->setFilePath($fileName);
        }
        $file->move($filePath); // potrebujem fyzicky uploadnut premenovany subor

        $this->filesRepo->add([
            "name" => $fileName,
            "client_id" => $client_id,
            "file_path" => $filePath,
            "size" => $file->getSize(),
            "date_created" => new \DateTime(),
            "is_dir" => 0,
            "parent_id" => $parentId, //tu predat ID z url ked oznacim zlozku
            "level" => $this->getNextLevelByFileId($parentId),
        ]);
    }

    public function createDir(string $file, ?int $client_id, ?int $parentId)
    {
        $similar = $this->filesRepo->findAllSimilarFolders($file, $client_id, $parentId);

        if (!$similar) {
            $this->filesRepo->add([
                "name" => $file,
                "client_id" => $client_id,
                "date_created" => new \DateTime(),
                "is_dir" => 1,
                "parent_id" => $parentId,
                "level" => $this->getNextLevelByFileId($parentId),
            ]);
        } else {
            throw new FileException("Složka již existuje");
        }
    }

    public function remove(int $id)
    {
        $matchedFiles = $this->filesRepo->fetchAllChildren($id);
        foreach ($matchedFiles as $file) {
            $fileName = $file['name'];
            $filePath = $this->setFilePath($fileName);
            $origPath = $file['file_path'];
            if (!$file['is_dir']) {
                unlink($origPath);
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
    public function rename(string $name, string $path, $clientId, int $id)
    {
        $similar = $this->getSimilar($name, $clientId, $id);
        $file = $this->filesRepo->fetchById($id);

        if (!$file['is_dir']) {
//            $filePath = $this->getFilePath($id);
//            bdump($filePath);
            $newFilePath = $this->setFilePath($name);
            rename($path, $newFilePath);
            $this->filesRepo->rename($name, $newFilePath, $id);
        } else
            if (empty($similar)) {
                $this->filesRepo->rename($name, $path, $id);
            } else {
                throw new FileException("Složka již existuje");
            }
    }

//  Utility functions

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

    private function getSimilar($name, $clientId, $id): array
    {
        $fileParent = $this->getParentId($id);
        return $this->filesRepo->findAllSimilarFolders($name, (int) $clientId, $fileParent);
    }
}

class FileException extends \Exception
{
}