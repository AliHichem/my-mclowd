<?php

namespace MC\UserBundle\Controller;

use MC\UserBundle\Entity\UserSetting;

use App\Controller\Controller as BaseController,
    App\Behaviours\RestableController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MC\UserBundle\Form\Type\UserSettingFormType;
use MC\UserBundle\Entity\Client;

use DateTime;


use Symfony\Component\HttpFoundation\Response;

class ClientController extends BaseController
{
    public function common()
    {
        $this->em = $this->getDoctrine()->getManager();
    }

    /**
     * @Secure(roles="ROLE_CLIENT")
     */
    public function profileAction()
    {
        $client = $this->getUser();
        if (!$client instanceof Client) {
            throw $this->createNotFoundException();
        }
        $form = $this->createBoundObjectForm($client, 'profile');
        if ($form->isBound() && $form->isValid()) {
            $this->getEntityManager()->flush();
            $this->addFlash('success', 'Profile has been updated');
            return $this->redirectToRoute('client_profile');
        }
        $taskRepo = $this->get('app.entity.task_repository');
        /* @var $taskRepo \Doctrine\ORM\EntityRepository */
        $tasks = $taskRepo->findBy(['user' => $client]);
        $stats = NULL;
/*
        return [
            'client' => $client,
            'form' => $form->createView(),
            'stats' => $stats,
            'tasks' => $tasks,
        ];
*/
        return $this->render('MCUserBundle:Client:profile.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
            'stats' => $stats,
            'tasks' => $tasks,
        ));
    }

    public function setTemplateAction(Request $request)
    {
        
    }

    /**
     *
     * @Secure(roles="ROLE_CLIENT")
     * @Template()
     * */
    public function settingsAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        
        $userSetting = $user->getSetting();
        
        return ['user' => $user,
        'userJson' => $serializer->serialize($user, 'json'),
        'userSetting' => $serializer->serialize($userSetting, 'json')
        ];
    }


    /**
     * 
     * @Secure(roles="ROLE_CLIENT")
     * */
    public function updateFullnameAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('fullName')->getForm()
        ;
        return $this->processForm($request, $form); 

    }
    
    /**
     *
     * @Secure(roles="ROLE_CLIENT")
     * */
    public function updatePasswordAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
    
        $form = $request->get('form');
        
        $password = $form['password'];
        
        $form = $this
        ->createFormBuilder($user, ['csrf_protection' => false])
        ->add('password')->getForm();
        
        $form->bind($request);
        if ($form->isValid()) {
            $user->setPassword('');
            $user->setPlainPassword('');
            $user->setPlainPassword($password);
            $this->persist($user, true);
            $resp = $serializer->serialize($user, 'json');
        } else {
            return $this->view($this->_getErrorMessages($form), self::INVALID_DATA);
        }
        
        $response = new JsonResponse($resp);
        return $response;
    
    }
    
    /**
     *
     * @Secure(roles="ROLE_CLIENT")
     * */
    public function updatePhoneAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
    
        $form = $this
        ->createFormBuilder($user, ['csrf_protection' => false])
        ->add('phone')->getForm()
        ;
        return $this->processForm($request, $form);
    
    }
    
    /**
     *
     * @Secure(roles="ROLE_CLIENT")
     * */
    public function updateEmailAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
    
        $form = $this
        ->createFormBuilder($user, ['csrf_protection' => false])
        ->add('email')->getForm()
        ;
        return $this->processForm($request, $form);
    
    }


    protected function processForm(Request $request, $form)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        $form->bind($request);
        if ($form->isValid()) {                
            $this->persist($user, true);     
            $resp = $serializer->serialize($user, 'json');
        } else {
            return $this->view($this->_getErrorMessages($form), self::INVALID_DATA);            
        }

        $response = new JsonResponse($resp);
        return $response;
    }
    
    public function saveSettingAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        
        $userSetting = $user->getSetting();
        
        if ($userSetting === null) {
            $userSetting = new UserSetting();
        }
        else {

            $userSetting->setImportantAccountNotification(false);
            $userSetting->setIncomingProposals(false);
            $userSetting->setMarketplaceNewsletter(false);
            $userSetting->setMclowdNewsletter(false);
            $userSetting->setWorkroomMessage(false);
            $this->persist($userSetting, true);
        }
        
        $userSetting->setUser($user);
        
        
        
        $form = $this->createForm(new \MC\UserBundle\Form\Type\UserSettingFormType(), $userSetting);
        
        if ($request->isXmlHttpRequest()) {
            $form->bind($request);
        
            if ($form->isValid()) {
                
                $this->persist($userSetting, true);
                $response = new Response($serializer->serialize($userSetting, 'json'));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            else {
                
                $resp = json_encode([
                        'error' => $this->_getErrorMessages($form)
                        ]);
        
                $response = new Response($resp);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }
    }

}