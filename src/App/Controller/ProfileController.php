<?php

/*
 */

namespace App\Controller;

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

        $taskRepo = $this->get('app.entity.task_repository');
        /* @var $taskRepo \Doctrine\ORM\EntityRepository */
        $tasks = $taskRepo->findBy(['user' => $client]);
        $stats = NULL;
        return [
            'client' => $client,
            'stats' => $stats,
            'tasks' => $tasks,
        ];
    }

}
