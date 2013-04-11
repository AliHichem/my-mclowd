<?php
namespace MC\UserBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;

class SocialAccountListener extends ContainerAware
{
    /** @var RouterInterface */
    private $router;

    private $whitelist;

    private $redirect;

    public function __construct(ContainerInterface $container, RouterInterface $router, array $whitelist, $redirect)
    {
        $this->container = $container;
        $this->router = $router;
        $this->whitelist = $whitelist;
        $this->redirect = $redirect;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        /** @var $context SecurityContext */
        $context = $this->container->get('security.context');
        if ($context->getToken() != null && $context->isGranted('ROLE_SOCIAL')) {
            $paths = $this->router->match($event->getRequest()->getPathInfo());
            if (!in_array($paths['_route'], $this->whitelist) && $paths['_route'] != $this->redirect) {
                $event->setResponse(new RedirectResponse($this->router->generate($this->redirect)));
            }
        }
    }
}
