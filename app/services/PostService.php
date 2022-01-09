<?php
namespace App\Services;

class PostService {

    public function uploadFileToPublic(array $files)
    {
        $tempPath = $files['upload']['tmp_name'];
        $randomFileName = md5(uniqid());
        $filePath = UPLOAD_PATH . $randomFileName . '.' . basename($files['upload']['type']);

        move_uploaded_file($tempPath, $filePath);

        return $filePath;
    }
}