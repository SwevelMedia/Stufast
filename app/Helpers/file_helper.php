<?php
function upload_file($file, $directory)
{
    if ($file->isValid() && !$file->hasMoved()) {
        $newFileName = $file->getRandomName();
        $file->move($directory, $newFileName);
        return $newFileName;
    }

    return null;
}

function remove_file($file)
{
    if (file_exists($file)) {
        unlink($file);
    }
}
