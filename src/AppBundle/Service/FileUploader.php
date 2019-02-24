<?php

/*
 * This file is part of the www.lillydoo.com test.
 *
 * @author Omar Makled <omar.makled@gmail.com.com>
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * Target directory.
     * 
     * @var string
     */
    private $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload a file.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Get target directory.
     * 
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
