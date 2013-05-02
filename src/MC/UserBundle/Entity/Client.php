<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use MC\UserBundle\Entity\UserSetting;

/**
 * @ORM\Entity
 * UniqueEntity(fields = "username", targetClass = "MC\UserBundle\Entity\User", message="fos_user.username.already_used")
 * UniqueEntity(fields = "email", targetClass = "MC\UserBundle\Entity\User", message="fos_user.email.already_used")
 */
class Client extends User
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
     * @ORM\Column(name="phone", type="string")
     */
    protected $phone;
    
    public function __construct()
    {
        //$this->setting = new UserSetting();
    }

    public function getRoles()
    {
        $roles = parent::getRoles();
        return array_merge($roles, ['ROLE_CLIENT']);
    }
    
    public function getPhone()
    {
        return $this->phone;
    }
    
    public function setPhone($phone) 
    {
        $this->phone = $phone;
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