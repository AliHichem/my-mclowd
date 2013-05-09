<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
    public function ClientSelectByIdV1($user_id)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT c, ceh FROM MCUserBundle:Client c LEFT JOIN c.educationHistory ceh WHERE c.id = :user_id')
            ->setParameter('user_id', $user_id);

        return $query;
    }

    public function getClientByIdV1($user_id)
    {
        $query = $this->ClientSelectByIdV1($user_id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
