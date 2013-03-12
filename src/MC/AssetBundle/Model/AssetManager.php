<?php
namespace MC\AssetBundle\Model;

use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Gaufrette\FileStream\Local;      
use Gaufrette\StreamMode;

use MC\AssetBundle\Entity\Asset;


class AssetManager implements UploaderInterface {

    protected $fs_map;
    protected $fs;
    protected $em;
    protected $eventDispacher;

    public function __construct(FilesystemMap $fs_map, EntityManager $em, EventDispatcher $eventDispacher, $asset_fs)  
    {
        $this->fs_map = $fs_map;
        $this->fs = $this->fs_map->get($asset_fs);
        $this->em = $em;    
        $this->eventDispacher = $eventDispacher;    
    }

    public function upload(UploadedFile $file)
    {   
        $asset = new Asset;
        $asset->createAssetFromUploadedFile($file);
         
        $this->em->persist($a);
        $this->em->flush();

        //$name = $a->createUploadPath();     
        //$a->createDirectoryPath();
        $this->em->persist($a);
        $this->em->flush();
        
        //$this->fs->write($name, file_get_contents($file->getPathname()));

        $src = new LocalStream($file->getPathname());
        $dst = $this->fs->createStream($name);

        $src->open(new StreamMode('rb+'));
        $dst->open(new StreamMode('ab+'));

        while (!$src->eof()) {
            $data = $src->read(100000);
            $written = $dst->write($data);
        }

        $dst->close();
        $src->close();

        return array(
            'id' => $a->getId(), 
            'originalFileName' => $a->getOriginalFileName(), 
            'path' => $a->getPath()
        );

        
    }
    
    public function getFileSystem()
    {
        return $this->fs;
    }

    public function processThumbnails(Asset $asset)
    {
        //TODO: apply thumbnail processing
    }

}