<?php
namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\NewTaskType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use App\Form\SearchType;

class TasksController extends Controller
{

    public function indexAction(Request $request)
    {
        $finder = $this->get('foq_elastica.finder.mclowd_website.Task');
        $form = $this
            ->createForm(new SearchType())
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
        //$es['query']['filtered']['filter']['and'][] = array('term' => array('isActive' => true));


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

    public function showAction(Request $request, $id, $slug)
    {
        $task = $this->findOr404('App\Entity\Task', ['id' => $id, 'slug' => $slug]);
        return compact('task');
    }

}