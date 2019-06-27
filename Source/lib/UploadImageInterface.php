<?php


interface UploadImageInterface
{
    public function uploadImage(string $folder, array $image, int $imageType);
}