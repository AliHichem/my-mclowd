<?php

namespace App\Controller;

use App\Entity\Proposal;
use App\Form\Type\NewProposalType;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

class SettingsController extends Controller
{


    public function listAction() {
        $request = $this->getRequest();
        $settings = $this->get('app.services.settings');

        $form = $settings->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                    $settings->saveSettingsForm($form);
                }
        }

        return array (
            'settingsForm' => $form->createView(),
        );
    }
}