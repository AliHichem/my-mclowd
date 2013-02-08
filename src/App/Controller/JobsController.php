<?php
namespace App\Controller;

use App\Entity\Job;
use App\Form\Type\NewJobType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
class JobsController extends Controller
{
    
    public function newAction(Request $request)
    {                
        $job = new Job;
        $form = $this->createBoundObjectForm($job, 'new');    

        if ($form->isBound() && $form->isValid()) {
            $this->persist($job, true);
            $this->addFlashf('success', 'Job have been created');
            return $this->redirectRoute('app_jobs_show', array('id' => $job->getId()));
        }

        return array('form' => $form->createView());
    }


}