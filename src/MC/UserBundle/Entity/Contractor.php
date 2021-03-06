<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use ArrayIterator;
use JMS\Serializer\Annotation as Rest;
use MC\UserBundle\Entity\UserSetting;

/**
 * @ORM\Entity(repositoryClass="MC\UserBundle\Entity\ContractorRepository")
 * UniqueEntity(fields = "username", targetClass = "MC\UserBundle\Entity\User", message="fos_user.username.already_used")
 * UniqueEntity(fields = "email", targetClass = "MC\UserBundle\Entity\User", message="fos_user.email.already_used")
 */
class Contractor extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="UserSetting", mappedBy="user")
     **/
    protected $setting;

    /**
     * @ORM\Column(name="has_selected_template", type="boolean")
     */
    protected $hasSelectedTemplate = false;
    
    /**
     * @ORM\Column(name="phone", type="string")
     */
    protected $phone;

     /**
     * @Rest\SerializedName("contractorTasks")
     * @ORM\OneToMany(targetEntity="ContractorTask", mappedBy="user", cascade={"persist"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $contractorTasks;
    
    
    
     /**
     * @Rest\SerializedName("contractorProposals")
     * @ORM\OneToMany(targetEntity="App\Entity\Proposal", mappedBy="user", cascade={"persist"})
     */
    protected $contractorProposals;
    

    public function __construct()
    {
        parent::__construct();
        $this->contractorTasks = new ArrayCollection;        
    }

    public function getRoles()
    {
        $roles = parent::getRoles();
        return array_merge($roles, ['ROLE_CONTRACTOR']);
    }


    public function hasSelectedTemplate() 
    {
        return $this->hasSelectedTemplate;
    }
    
    
    public function setHasSelectedTemplate($hasSelectedTemplate) 
    {
        $this->hasSelectedTemplate = $hasSelectedTemplate;
        return $this;
    }
    
    public function setContractorProposals(ArrayIterator $tasks) 
    {
        $this->contractorTasks = $tasks;        
        return $this;
    }
    
    public function getContractorProposals() 
    {
        return $this->contractorTasks;
    }

    public function setContractorTasks(ArrayIterator $tasks) 
    {
        $this->contractorTasks = $tasks;        
        return $this;
    }

    public function addContractorTask(ContractorTask $task)
    {
        $task->setUser($this);
        $this->contractorTasks->add($task);
    }

    public function getContractorTasks() 
    {
        return $this->contractorTasks;
    }
    
    public function getPhone()
    {
        return $this->phone;
    }
    
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $phone;
    }
    
    public function setSetting($config)
    {
        $this->setting = $config;
        return $this;
    }
    
    public function getSetting()
    {
        return $this->setting;
    }
    
}