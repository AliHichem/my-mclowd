<?php
namespace MC\AssetBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use MC\AssetBundle\Entity\Asset;

class AssetUploadedEvent extends Event
{
    protected $asset;

    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    public function getAsset()
    {
        return $this->asset;
    }
}