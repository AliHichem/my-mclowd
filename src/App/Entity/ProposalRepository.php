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
            $i++;
        }

        return $resArray;
    }
}