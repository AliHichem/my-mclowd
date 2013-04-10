<?php
namespace App\Entity;

use Knp\RadBundle\Doctrine\EntityRepository;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

use App\Entity\Proposal;

class ProposalRepository extends EntityRepository
{
    public function getProposalsByTask($taskId)
    {
        $resArray = array();
        $i = 0;
        
        $query = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Proposal p WHERE p.task = :taskId');
        $query->setParameter('taskId', $taskId);
        
        $results = $query->getResult(); 
        
        foreach ($results as $res) {
            $resArray[$i]['id'] = $res->getId();
            $resArray[$i]['description'] = $res->getDescription();
            $resArray[$i]['hours'] = $res->getHours();
            $resArray[$i]['duration'] = $res->getTextDuration();
            $resArray[$i]['rate'] = $res->getRate();
            $resArray[$i]['taskId'] = $taskId;
            $i++;
        }

        return $resArray;
    }
    
    public function updateByTaskIdProposalId($taskId, $proposalId)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE App\Entity\Proposal p SET p.isAccepted = 1 WHERE p.task = :taskId AND p.id = :proposalId');
        $query->setParameter('taskId', $taskId);
        $query->setParameter('proposalId', $proposalId);
        
        $query->execute();
    }
}