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

        return ['form' => $form->createView()];
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