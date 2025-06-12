<?php
spl_autoload_register(function ($className) {
    // Düzenlenmiş regex
    $regex = '/\A([A-Z][a-zA-Z]*)(Model|Repository|Controller|Handler)\z|\A(I)([A-Z][a-zA-Z]+)\z/';

    if (preg_match($regex, $className, $matches)) {
        if (!empty($matches[2]) && isset($matches[1])) {
            $folderNames = [
                'Model' => 'Models',
                'Repository' => 'Repositories',
                'Controller' => 'Controllers',
                'Handler' => 'Handlers'
            ];
            $folder = $folderNames[$matches[2]];
            $file = __DIR__ . "/$folder/$className.php";
        } else if (!empty($matches[3]) && !empty($matches[4])) {
            $folder = "Interfaces";
            $file = __DIR__ . "/$folder/$className.php";
        }
    } else {
        // fallback: Models klasörü
        $folder = 'Models';
        $file = __DIR__ . "/$folder/$className.php";
    }

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("File not found: $file for class $className");
    }
});



?>