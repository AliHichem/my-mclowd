<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

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

        public function getRoles()
    {
        $roles = parent::getRoles();
        return array_merge($roles, ['ROLE_CLIENT']);
    }
}