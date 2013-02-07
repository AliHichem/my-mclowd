<?php

namespace MC\UserBundle\Controller;
use App\Controller\Controller;

class RegistrationController extends Controller
{
    
    /**
     * @Route("/register/client", name="client_registration")
     * @Template()
     */
    public function registerClientAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('MC\UserBundle\Entity\Client');
    }
}