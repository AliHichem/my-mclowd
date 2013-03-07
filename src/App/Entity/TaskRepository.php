<?php
namespace App\Entity;

use Knp\RadBundle\Doctrine\EntityRepository;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

use MC\UserBundle\Entity\User;

class TaskRepository extends EntityRepository
{
    
    public function findByUserQueryBuilder(User $user, $orderBy = 'desc')
    {
        $q = $this
            ->build()
            ->where($this->getAlias() . '.user = :user')
            ->setParameter('user', $user)            
            ->orderBy($this->getAlias() . '.createdAt ', $orderBy)
        ;
        return $q;
    }

}