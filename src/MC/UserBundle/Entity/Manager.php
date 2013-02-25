<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 */
class Manager extends User
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
        return array_merge($roles, ['ROLE_SUPER_ADMIN']);
    }
}