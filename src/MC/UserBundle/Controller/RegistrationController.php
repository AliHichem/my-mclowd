<?php

namespace MC\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
        
    public function registerClientAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('MC\UserBundle\Entity\Client');
    }

    public function registerContractorAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('MC\UserBundle\Entity\Contractor');
    }
}