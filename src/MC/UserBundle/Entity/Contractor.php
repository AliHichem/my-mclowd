<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity 
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
     * @ORM\Column(name="has_selected_template", type="boolean")
     */
    protected $hasSelectedTemplate = false;

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
}