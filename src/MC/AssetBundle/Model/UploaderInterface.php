<?php

namespace MC\AssetBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 *
 */
interface UploaderInterface
{

    public function upload(UploadedFile $file);

}
