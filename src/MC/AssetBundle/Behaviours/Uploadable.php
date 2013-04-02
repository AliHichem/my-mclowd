<?php


namespace MC\AssetBundle\Behaviours;

trait Uploadable
{

   /**
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    protected $fileName;

     /**
     *
     * @ORM\Column(name="directory_path", type="string", length=255, nullable=true)
     */
    protected $directoryPath = "/uploads/assets/";

    /**
     *
     * @ORM\Column(name="original_file_name", type="string", length=255)
     */
    protected $originalFileName;

    /**
     *
     * @ORM\Column(name="content_type", type="string", length=255)
     */
    protected $contentType;

    /**
     *
     * @ORM\Column(name="file_size", type="integer")
     */
    protected $fileSize;

    /**
     *
     * @ORM\Column(name="extension", type="string", length=10)
     */
    protected $extension;


    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($newFileName)
    {
        $this->fileName = $newFileName;
        return $this;
    }   


    public function getDirectoryPath()
    {
        return $this->directoryPath;
    }
    
    public function setDirectoryPath($newDirectoryPath)
    {
        $this->directoryPath = $newDirectoryPath;
        return $this;
    }   

    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }
    
    public function setOriginalFileName($newOriginalFileName)
    {
        $this->originalFileName = $newOriginalFileName;
        return $this;
    }

    public function getContentType()
    {
        return $this->contentType;
    }
    

    public function setContentType($newContentType)
    {
        $this->contentType = $newContentType;
        return $this;
    }


    public function getFileSize()
    {
        return $this->fileSize;
    }
    

    public function setFileSize($newFileSize)
    {
        $this->fileSize = $newFileSize;
        return $this;
    }
    

    public function getExtension()
    {
        return $this->extension;
    }
    

    public function setExtension($newExtension)
    {
        $this->extension = $newExtension;
        return $this;
    }

    public function createFileName()
    {
        if(!$this->getOriginalFileName() || !$this->getExtension()) {
            throw new \Exception("Extension or original file name missing to create file name");        
        }
        $this->fileName = sha1($this->getFileName() + time()) . '.' . $this->getExtension();
        return $this;
    }

    public function __toString()
    {
        return (String)$this->getOriginalFileName();
    }


    public function createAssetFromUploadedFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        $this
            ->setOriginalFileName($file->getClientOriginalName())
            ->setContentType($file->getClientMimeType())
            ->setFileSize($file->getClientSize())
            ->setExtension($file->guessExtension())
            ->createFileName()
        ;   
        return $this;
    }

    public function isImage()
    {
        return in_array($this->contentType, array(
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ));
    }

    public function getPath($version = '')
    {
        if ($version === '') {
            return $this->directoryPath . $this->fileName;    
        } else {
            return preg_replace('@\.([^\.]*)$@', '.' . $version . '.\1', $this->getPath());            
        }
        
    }
}
