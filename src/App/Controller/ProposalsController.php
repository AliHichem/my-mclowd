<?php
namespace App\Controller;

use App\Entity\Job;
use App\Entity\Proposal;
use App\Form\Type\NewProposalType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ProposalsController extends Controller
{

    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function newAction(Request $request)
    {
        $proposal = new Proposal;
        $proposal->setJob($this->getEntityManager()->getReference('App:Job', $request->get('job')));
        $form = $this->createBoundObjectForm($proposal, 'new');

        if ($form->isBound() && $form->isValid()) {
            $this->persist($proposal, true);
            $this->addFlash('success', 'Proposal has been sent');
            return $this->redirectToRoute('app_proposals_show', array('id' => $job->getId()));
        }

        return ['form' => $form->createView()];
    }

    public function showAction(Request $request, $id)
    {
        $proposal = $this->findOr404('App\Entity\Proposal', array('id' => $id));
        return compact('proposal');
    }

}