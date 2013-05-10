<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ContractorRepository extends EntityRepository
{
    public function ContractorSelectByIdV1($user_id)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT c, ceh, cehi, cq, cs FROM MCUserBundle:Contractor c LEFT JOIN c.educationHistory ceh LEFT JOIN c.employmentHistory cehi LEFT JOIN c.qualifications cq LEFT JOIN c.skills cs WHERE c.id = :user_id')
            ->setParameter('user_id', $user_id);

        return $query;
    }

    public function getContractorByIdV1($user_id)
    {
        $query = $this->ContractorSelectByIdV1($user_id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
