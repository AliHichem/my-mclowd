<?php
namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\NewTaskType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use App\Form\SearchType;
use App\Model\TaskSearch;

class TasksController extends Controller
{

    public function indexAction(Request $request)
    {
        $finder = $this->get('fos_elastica.finder.mclowd_website.Task');        
        $form = $this
            ->createForm(new SearchType(), new TaskSearch)
            ->bind($request->query->getIterator()->getArrayCopy())
        ;        

        $query = $this->getSearchQuery($request->query);
        $paginator = $this->get('knp_paginator')
            ->paginate(
                $finder->createPaginatorAdapter($query),
                $request->query->get('page', 1)
            )
        ;
        
        return ['form' => $form->createView(), 'paginator' => $paginator];
    }


    //TODO: move this into service
    protected function getSearchQuery($params) 
    {
        $q_string = '*';
        if($params->get('query')) {
            $q_string = $params->get('query');
        }

        $es = [
            'query' => [
                'filtered' => [
                    "query" => [
                        "query_string" => [
                            "query"  => $q_string,
                            "default_operator" => "OR"
                        ]
                    ],                    
                ],                
            ],
            'filter' => [],   
        ];

        $es['filter']['and'][] = ['term' => ['isActive' => true]];
        $es['query']['filtered']['filter']['and'][] = array('term' => array('isActive' => true));


        if ($params->get('categories')) {
            $es['filter']['and'][] = [
                'terms' => ['categoryId' => $params->get('categories')]
            ];
        }

        if ($params->get('type')) {
            $es['filter']['and'][] = [
                'terms' => ['type' => $params->get('type')]
            ];
        }

        if ($params->get('currency')) {
            $es['filter']['and'][] = [
                'terms' => ['currency' => $params->get('currency')]
            ];
        }

        if ($params->get('timePeriod')) {
            $es['filter']['and'][] = [
                'terms' => ['timePeriod' => $params->get('timePeriod')]
            ];
        }

        if ($params->get('budget')) {
            $es['filter']['and'][] = [
                'terms' => ['budgetId' => $params->get('budget')]
            ];
        }
             
        
        $es['facets'] = [
            'categories' => [
                'terms' => ['field' => 'categoryId', 'size' => 1000]
            ],
        ];
        
        return new \Elastica_Query($es);
    }

    /**
     * @Secure(roles="ROLE_CLIENT")
     */
    public function newAction(Request $request)
    {
        $task = new Task;
        $form = $this->createBoundObjectForm($task, 'new');

        if ($form->isBound() && $form->isValid()) {            
            $this->persist($task, true);
            $this->addFlash('success', 'Task have been created');
            return $this->redirectToRoute('app_tasks_show', ['id' => $task->getId(), 'slug' => $task->getSlug()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Secure(roles="ROLE_CLIENT")
     */
    public function editAction(Request $request, $id)
    {
        $task = $this->findOr404('App\Entity\Task',[
            'id' => $id,
            'user' => $this->getSecurity()->getToken()->getUser() 
        ]);

        $form = $this->createBoundObjectForm($task, 'edit');

        if ($form->isBound() && $form->isValid()) {            
            $this->persist($task, true);
            $this->addFlash('success', 'Task have been updated');
            return $this->redirectToRoute('app_tasks_my');
        }

        return ['form' => $form->createView(), 'task' => $task];
    }

    /**
     * @Secure(roles="ROLE_CLIENT")
     */
    public function deleteAction(Request $request, $id)
    {
        $task = $this->findOr404('App\Entity\Task',[
            'id' => $id,
            'user' => $this->getSecurity()->getToken()->getUser() 
        ]);

        $this->remove($task);
        $this->flush();

        $this->addFlash('success', 'Task have been removed');
        return $this->redirectToRoute('app_tasks_my');
    }
    
    public function myAction(Request $request)
    {
        if (!$this->isGranted("ROLE_CLIENT")) {
            return $this->redirectToRoute('homepage');
        }   

        $query  = $this->get('app.entity.task_repository')->findByUserQueryBuilder(
            $this
                ->getSecurity()
                ->getToken()
                ->getUser()
        );
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->get('page', 1)
        );

        return ['pagination' => $pagination];
    }

    public function showAction(Request $request, $id, $slug)
    {
        $task = $this->getEntityManager()->find('App\Entity\Task', $id);
        $results = $this->getEntityManager()->getRepository('App\Entity\Proposal')->getProposalsByTask($task->getId());
        $results = json_encode($results);
            
        return [ 
                'task' => $task,
                'taskType' => $task->getType(),
                'proposalsJson' => $results];
    }
    
    /**
     * @Secure(roles="ROLE_CLIENT")
     */
    public function acceptProposalAction(Request $request)
    {
        $data = json_decode($this->getRequest()->getContent());
        $taskId = $data->{'task_id'};
        $proposalId = $data->{'proposal_id'};
        echo $proposalId;
        
        $this->getEntityManager()->getRepository('App\Entity\Proposal')->updateByTaskIdProposalId($taskId, $proposalId);
        
        die();
    }

}