<?php


namespace App\Behaviours;

/**
 * Ownable trait.
 * Should be used inside entity, that needs to be timestamped.
 * You should implement property directly in class
 */
trait Ownable
{

  
    public function setUser(\MC\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

}
