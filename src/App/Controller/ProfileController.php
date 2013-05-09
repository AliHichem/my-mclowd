<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use MC\UserBundle\Entity\Client;

/**
 * Description of ProfileController
 *
 * @author matei
 */
class ProfileController extends Controller
{
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
        $client = $this->getUser();
        if (!$client instanceof Client) {
            throw $this->createNotFoundException();
        }
        return [
            'client' => $client,
        ];
    }
}
