<?php

namespace Service;

class FileService
{
    public function uploadFile(array $file): string
    {
        $uploadDirectory = "files/";
        $uploadFile = $uploadDirectory . $file["full_path"];

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $upload = move_uploaded_file($file["tmp_name"], $uploadFile);

        if ($upload) {
            return $uploadFile;
        }

        return "";
    }

}