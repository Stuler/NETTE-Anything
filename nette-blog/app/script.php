<?php
require __DIR__ . '/../vendor/autoload.php';

$container = App\Bootstrap::boot()->createContainer();

$pm = $container->getByType(\App\Models\ProcessManagers\FilesProcessManager::class);
$data = $pm->getFilesAdDirs();

var_dump($data);
echo "abb";