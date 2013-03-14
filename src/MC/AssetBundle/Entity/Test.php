<?php

namespace MC\AssetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use MC\AssetBundle\Entity\Asset;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 *
 * @ORM\Table(name="test_entity")
 * @ORM\HasLifecycleCallbacks
 */
class Test
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
   
    /**
     * @var string $tite
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank     
     */
    private $title;


    /**
     * @ORM\ManyToMany(targetEntity="MC\AssetBundle\Entity\Asset", cascade={"persist"})
     * @ORM\JoinTable(name="ads_assets",
     *      joinColumns={@ORM\JoinColumn(name="test_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="asset_id", referencedColumnName="id")}
     * )
     */
    private $assets;
    
        
    public function __construct()
    {
        $this->assets = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addAsset(Asset $a)
    {
        $this->assets[] = $a;
    }

    public function getAssets()
    {
        return $this->assets;
    }

    public function setAssets($assets)
    {
        $this->assets = $assets;
    }
    

}   


