<?php
namespace MC\AssetBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use MC\AssetBundle\Behaviours\Uploadable; 
   
/**
 * @ORM\Table(name="assets")
 * @ORM\Entity
 */
class Asset
{
    
    use Uploadable,
        ORMBehaviors\Timestampable\Timestampable
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }


}