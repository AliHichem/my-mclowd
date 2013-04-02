<?php
namespace App\Controller;

use App\Entity\Task;
use App\Entity\Proposal;
use App\Form\Type\NewProposalType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProposalsController extends Controller
{

    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$serializer = $this->get('serializer');
        
        //print_r($postForm);
        //echo 'tu';
        //die();
        
        $proposal = new Proposal;
        $form = $this->createForm(new \App\Form\NewProposalType(), $proposal, array('em' => $em));
        
        if ($request->isXmlHttpRequest()) {
            
            
            $postForm = $this->get('request')->request->get('form');
            unset($postForm['$$hashKey']);
            
            $form->bind($postForm);
            if ($form->isValid()) {
                $task = $em->find('App:Task', $postForm['task']);
                $proposal->setTask($task);
                $this->persist($proposal, true);
                
                $resp = json_encode(['id' => $proposal->getId()]);
                //print_r($resp);
                return new JsonResponse($resp);
            }
            else {
                $errors = $this->get('validator')->validate($proposal);

                // iterate on it
                foreach( $errors as $error )
                {
                    $resp[$error->getPropertyPath()] = $error->getMessage(); 
                    echo $error->getPropertyPath();
                    echo $error->getMessage();
                }
                
                $resp = json_encode($resp);
                return new JsonResponse($resp);
            }
        }
        else {
            $query = $em->createQuery('SELECT p FROM App\Entity\Proposal p WHERE p.task = :taskId');
            $query->setParameter('taskId', $request->query->get('task'));
            $results = $query->getArrayResult(); // shortcut for $query->getResult(Query::HYDRATE_ARRAY);
            
            $results = json_encode($results);
            
            return ['form' => $form->createView(), 
                    'task' => $request->query->get('task'),
                    'proposalsJson' => $results];
        }

        
    }
    

    public function showAction(Request $request, $id)
    {
        $proposal = $this->findOr404('App\Entity\Proposal', array('id' => $id));
        return compact('proposal');
    }

}