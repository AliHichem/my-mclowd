<?php
namespace MC\UserBundle\EventListener;

use Acme\DemoBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\DependencyInjection\ContainerAware;
class ContractorTemplateListener extends ContainerAware
{

    public function onKernelRequest(GetResponseEvent $event)
    {

        $context = $this->container->get('security.context');
        //if ($context->isGranted('ROLE_CONTRACTOR') && !$context->getToken()->getUser()->hasSelectedTemplate()) {
            //var_dump('no template'); die();
        //}

        
    }
}
