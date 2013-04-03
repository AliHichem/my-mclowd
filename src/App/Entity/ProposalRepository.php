<?php
namespace App\Entity;

use Knp\RadBundle\Doctrine\EntityRepository;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

use App\Entity\Proposal;

class ProposalRepository extends EntityRepository
{
    public function getProposalsByTask($taskId)
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Proposal p WHERE p.task = :taskId');
        $query->setParameter('taskId', $taskId);
        
        return $query->getArrayResult();
    }
}