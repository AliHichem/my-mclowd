<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use MC\UserBundle\Entity\Client;

class ProfileController extends Controller
{
    public function common()
    {
        $this->em = $this->getDoctrine()->getManager();
    }

    public function indexAction()
    {
        $client = $this->getUser();
        if (!$client instanceof Client) {
            throw $this->createNotFoundException();
        }
        $form = $this->createBoundObjectForm($client, 'profile');
        if ($form->isBound() && $form->isValid()) {
            $this->getEntityManager()->flush();
            $this->addFlash('success', 'Profile has been updated');
            return $this->redirectToRoute('app_profile_index');
        }
        $taskRepo = $this->get('app.entity.task_repository');
        /* @var $taskRepo \Doctrine\ORM\EntityRepository */
        $tasks = $taskRepo->findBy(['user' => $client]);
        $stats = NULL;
        return [
            'client' => $client,
            'form' => $form->createView(),
            'stats' => $stats,
            'tasks' => $tasks,
        ];
    }

    public function editAction(Request $request, $id)
    {
        $client = $this->getUser();
        $form = $this->createBoundObjectForm($client, 'profile');

        if ($form->isBound() && $form->isValid()) {
            $this->persist($client, true);
            $this->addFlash('success', 'Profile has been updated');

            return $this->redirectToRoute('app_profile_index');
        }

        return ['form' => $form->createView()];
    }

    public function showAction($id)
    {
        $this->common();
        $client = $this->em->getRepository('MCUserBundle:Client')->find($id);

        if (!$client instanceof Client) {
            throw $this->createNotFoundException();
        }
        return $this->render('App:Profile:show.html.twig', array(
            'client' => $client,
        ));
    }
}
