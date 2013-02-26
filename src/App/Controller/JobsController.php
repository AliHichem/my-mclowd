<?php
namespace App\Controller;

use App\Entity\Job;
use App\Form\Type\NewJobType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use App\Form\SearchType;

class JobsController extends Controller
{

    public function indexAction(Request $request)
    {
        $finder = $this->get('foq_elastica.finder.mclowd_website.job');
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
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function newAction(Request $request)
    {
        $job = new Job;
        $form = $this->createBoundObjectForm($job, 'new');

        if ($form->isBound() && $form->isValid()) {            
            $this->persist($job, true);
            $this->addFlash('success', 'Job have been created');
            return $this->redirectToRoute('app_jobs_show', array('id' => $job->getSlug()));
        }

        return ['form' => $form->createView()];
    }

    public function showAction(Request $request, $id)
    {
        $job = $this->findOr404('App\Entity\Job', array('slug' => $id));
        return compact('job');
    }

}