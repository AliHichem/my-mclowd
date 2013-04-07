<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Rest;
/**
 * Education
 *
 * @ORM\Table(name="contractor_tasks")
 * @Rest\ExclusionPolicy("all")
 * @ORM\Entity
 */
class ContractorTask
{
    /**
     * @var integer
     * @Rest\Expose()
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Rest\Expose()
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**     
     * @Rest\Expose()
     * @Assert\NotBlank()
     * @ORM\Column(type="decimal", nullable=true, scale=2, precision=18)
     */
    protected $price;

    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="contractorTasks")
     * @ORM\JoinColumn(name="contractor_id", referencedColumnName="id")
     */
    protected $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

   
    public function getName() 
    {
        return $this->name;
    }
    
    
    public function setName($name) 
    {
        $this->name = $name;
    
        return $this;
    }


    public function getPrice() 
    {
        return $this->price;
    }
    
    
    public function setPrice($price) 
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Education
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

        
}