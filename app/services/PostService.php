<?php
namespace App\Services;

class PostService {

    public function uploadFileToPublic(array $files)
    {
        $tempPath = $files['upload']['tmp_name'];
        $randomFileName = md5(uniqid());
        $filePath = public_path('upload/' . $randomFileName . '.' . basename($files['upload']['type']));

        move_uploaded_file($tempPath, $filePath);

        return $filePath;
    }
}