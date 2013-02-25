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


        $query = $request->query->get('query') ? $request->query->get('query') : '*';
        
        $paginator = $this->get('knp_paginator')
            ->paginate(
                $finder->createPaginatorAdapter($query),
                $request->query->get('page', 1)
            )
        ;
        

        return ['form' => $form->createView(), 'paginator' => $paginator];
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
            return $this->redirectToRoute('app_jobs_show', ['id' => $job->getId(), 'slug' => $job->getSlug()]);
        }

        return ['form' => $form->createView()];
    }

    public function showAction(Request $request, $id, $slug)
    {
        $job = $this->findOr404('App\Entity\Job', ['id' => $id, 'slug' => $slug]);
        return compact('job');
    }

}