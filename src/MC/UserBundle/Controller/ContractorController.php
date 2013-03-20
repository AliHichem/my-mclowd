<?php

namespace MC\UserBundle\Controller;

use App\Controller\Controller as BaseController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContractorController extends BaseController
{
    
    public function setTemplateAction(Request $request)
    {
        
    }


    /**
     * @Template()
     * */
    public function editAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();

        return ['user' => $user];
    }

}