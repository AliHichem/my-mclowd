<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\Type\NewJobType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use App\Form\SearchType;

class JobsController extends Controller {

    public function indexAction(Request $request) {
        $chatTarget = $request->get('target');
        
        return array(
            'target' => $chatTarget,
        );
    }

}