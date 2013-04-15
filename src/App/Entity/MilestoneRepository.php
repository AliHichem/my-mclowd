<?php
namespace App\Entity;

use Knp\RadBundle\Doctrine\EntityRepository;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

use App\Entity\Proposal;

class MilestoneRepository extends EntityRepository
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
            $resArray[$i]['finishDate'] = $res->getFinishDate();
            $resArray[$i]['duration'] = $res->getDuration();
            $resArray[$i]['contractorRate'] = $res->getContractorRate();
            $resArray[$i]['taskId'] = $taskId;
            $resArray[$i]['username'] = $res->getUser()->getUsername();
            $resArray[$i]['accepted'] = $res->getIsAccepted();
            $resArray[$i]['milestones'] = $res->getMilestones();
            $i++;
        }

        return $resArray;
    }
    
    public function acceptByTaskIdProposalId($taskId, $proposalId)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE App\Entity\Proposal p SET p.isAccepted = 1 WHERE p.task = :taskId AND p.id = :proposalId');
        $query->setParameter('taskId', $taskId);
        $query->setParameter('proposalId', $proposalId);
        
        $query->execute();
        return ['result' => true];
       
    }
}