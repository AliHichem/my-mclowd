<?php

namespace MC\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends BaseController
{
    /**
     * @param $class string
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register($class)
    {
        /** @var $userDiscriminator \PUGX\MultiUserBundle\Model\UserDiscriminator */
        $userDiscriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $userDiscriminator->setClass($class);

        $request = $this->container->get('request');
        $result = $this->registerAction($request);
        if ($result instanceof RedirectResponse) {
            return $result;
        }

        $template = $userDiscriminator->getTemplate('registration');
        if (is_null($template)) {
            $engine = $this->container->getParameter('fos_user.template.engine');
            $template = 'FOSUserBundle:Registration:register.html.'.$engine;
        }

        /** @var $formFactory \PUGX\MultiUserBundle\Form\FormFactory */
        $formFactory = $this->container->get('pugx_multi_user.registration_form_factory');

        $form = $formFactory->createForm();
        // this will  allow us to show for errors
        if ($request->isMethod('post')) {
            $form->bind($request);
            $form->isValid();
        }

        // default country must be Australia unless the user has posted something else
        if (!$request->isMethod('post')) {
            $australia = $this
                ->container
                ->get('doctrine')
                ->getManager()
                ->getRepository('App:Country')
                ->findOneByCode('AU');
            $form['country']->setData($australia);
        }

        return $this->container->get('templating')->renderResponse($template, array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerClientAction()
    {
        return $this->register('MC\UserBundle\Entity\Client');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerContractorAction()
    {
        return $this->register('MC\UserBundle\Entity\Contractor');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $what string
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function validateAction(Request $request, $what) {
        $out = array();
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        switch ($what) {
            case 'username': // fos_user_registration_form%5Busername%5D
                $data = $request->query->get('fos_user_registration_form');
                $username = $data['username'];
                $user = $em->getRepository('MCUserBundle:User')->findOneByUsername($username);
                if ($user === null) {
                    $out['success'] = 'Username is available.';
                } else {
                    $out['error'] = 'Username already in use';
                }
                break;
            case 'email': // fos_user_registration_form%5Bemail%5D
                $data = $request->query->get('fos_user_registration_form');
                $email = $data['email'];
                $user = $em->getRepository('MCUserBundle:User')->findOneByEmail($email);
                if ($user === null) {
                    $out['success'] = 'Email is available.';
                } else {
                    $out['error'] = 'Email already in use';
                }
                break;
        }
        return new JsonResponse($out);
    }
}