<?php
namespace App\Controller;

use App\Entity\Milestone;

use App\Entity\Task;
use App\Entity\Proposal;
use App\Form\Type\NewProposalType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProposalsController extends Controller
{

    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function newAction(Request $request)
    {
        $taskType = null;
        $em = $this->getDoctrine()->getEntityManager();
        $serializer = $this->get('serializer');
        
        $postForm = $this->get('request')->request->get('form');
        
        if (isset($postForm['taskType'])) {
            $taskType = $postForm['taskType'];
            unset($postForm['taskType']);
        }
        
        $proposal = new Proposal();
        $form = $this->createForm(new \App\Form\NewProposalType(), $proposal, array('em' => $em, 'taskType' => $taskType));
        
        if ($request->isXmlHttpRequest()) {
            $form->bind($postForm);
            
            if ($form->isValid()) {
                
                $task = $em->find('App:Task', $postForm['task']);
                $proposal->setTask($task);
                $proposal->setIsAccepted(false);
                $this->persist($proposal, true);

                $response = new Response($serializer->serialize($proposal, 'json'));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            else {
                                
                $resp = json_encode([
                    'error' => $this->_getErrorMessages($form)
                ]);

                $response = new Response($resp);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }
        else {
            $task = $em->find('App\Entity\Task', $request->query->get('task'));
            $results = $em->getRepository('App\Entity\Proposal')->getProposalsByTask($task->getId());
            $results = json_encode($results);
            
            return ['form' => $form->createView(), 
                    'task' => $task->getId(),
                    'taskType' => $task->getType(),
                    'proposalsJson' => $results];
        }
    }
    
    public function showAction(Request $request, $id)
    {
        $proposal = $this->findOr404('App\Entity\Proposal', array('id' => $id));
        return compact('proposal');
    }

}