<?php
namespace MC\AssetBundle\EventListener;

use MC\AssetBundle\Event\AssetUploadedEvent;
use Imagine\Image\ImagineInterface;
use Avalanche\Bundle\ImagineBundle\Imagine\Filter\FilterManager;

class AssetThumbnailsEventListener
{   
    const JPEG_QUALITY = 80;
    private $filters, $webDir, $imagine, $filterManager;


    public function __construct(ImagineInterface $imagine, $filterManager, array $filters, $webDir)
    {
        $this->imagine = $imagine;
        $this->filters = $filters;
        $this->webDir = $webDir;
        $this->filterManager = $filterManager;
    }

    public function onAssetUploadedEvent(AssetUploadedEvent $event)
    {
        $asset = $event->getAsset();

        if (false === $asset->isImage()) {
            return;
        }

        $originalFile = $this->webDir . $asset->getPath();

        $image = $this->imagine->open($originalFile);
        $dimensions = array(
            'width' => $image->getSize()->getWidth(),
            'height' => $image->getSize()->getHeight()
        );
        
        foreach (array_reverse($this->filters) as $version => $params) {

            $outputFile = preg_replace('@\.([^\.]*)$@', '.' . $version . '.\1', $originalFile);

            if ($dimensions['width'] < $params['options']['size'][0] && $dimensions['height'] < $params['options']['size'][1]) {
                $this->imagine->open($originalFile)->save($outputFile, array('quality' => self::JPEG_QUALITY));
            }
            $this->processAssetVersion($originalFile, $outputFile, $version);
                         
        }
    }

    /**
     *
     * Process an asset generating one specific version
     *
     * @param string $inputFile  where is the input file
     * @param string $outputFile where to store the result
     * @param string $filter     which filter to apply
     *
     * @return result of applied filter
     */
    protected function processAssetVersion($inputFile, $outputFile, $filter)
    {
        return $this
            ->filterManager->get($filter)
            ->apply($this->imagine->open($inputFile))
            ->save($outputFile, array('quality' => self::JPEG_QUALITY))
        ;        
    }

}