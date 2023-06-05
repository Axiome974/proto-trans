<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
// ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function removeFile( string $fileName ){
        try {
            unlink($this->getTargetDirectory()."/".$fileName);
        } catch (FileException $e) {
            return false;
        }
        return true;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}