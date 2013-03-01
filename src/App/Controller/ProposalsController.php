<?php
namespace App\Controller;

use App\Entity\Task;
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
        $form = $this->createObjectForm($proposal, 'new', array('em' => $this->getDoctrine()->getEntityManager()));

        if ($form->isBound() && $form->isValid()) {
            $proposal->setTask($this->getEntityManager()->getReference('App:Task', $form->getData->get('task')));
            $this->persist($proposal, true);
            $this->addFlash('success', 'Proposal has been sent');
            return $this->redirectToRoute('app_proposals_show', array('id' => $proposal->getTask()->getId()));
        }

        return ['form' => $form->createView()];
    }

    public function showAction(Request $request, $id)
    {
        $proposal = $this->findOr404('App\Entity\Proposal', array('id' => $id));
        return compact('proposal');
    }

}