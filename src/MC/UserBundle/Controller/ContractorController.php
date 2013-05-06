<?php

namespace MC\UserBundle\Controller;

use App\Controller\Controller as BaseController,
    App\Behaviours\RestableController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MC\UserBundle\Form\Type\ContractorEditFormType;
use MC\UserBundle\Form\Type\EmploymentFormType;
use MC\UserBundle\Form\Type\EducationFormType;
use MC\UserBundle\Form\Type\ContractorTaskFormType;
use MC\UserBundle\Form\Type\QualificationFormType;

use DateTime;
use MC\UserBundle\Entity\Education;
use MC\UserBundle\Entity\Employment;
use MC\UserBundle\Entity\ContractorTask;
use MC\UserBundle\Entity\Qualification;

use Symfony\Component\HttpFoundation\Response;

use MC\UserBundle\Entity\UserSetting;
use MC\UserBundle\Form\Type\UserSettingFormType;

// SerializationContext::create()->setGroups(['profileForm'])
class ContractorController extends BaseController
{
    
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $contractors = $this->getRepository('MC\UserBundle\Entity\Contractor')->findAll();
        return compact('contractors');
    }

    /**
     * @Template()
     */
    public function profileAction(Request $request, $id)
    {
        $contractor = $this->findOr404('MC\UserBundle\Entity\Contractor', ['id' => $id]);
        return compact('contractor');
    }


    public function setTemplateAction(Request $request)
    {
        
    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * @Template()
     * */
    public function editAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');

        $avatarForm = $this
            ->createFormBuilder($user)
            ->add('uploadedAvatar', 'file', ['required' => false])
            ->getForm()
        ;
        $form = $this->createForm(new ContractorEditFormType(), $user);

        /**
         * Handle avatar change
         */
        if ($request->request->has("uploadingAvatar"))
        {
            $avatarForm->bind($request);
            if ($avatarForm->isValid()) {
                $data = $avatarForm->getData();                
                $return = $this->get('mc.asset_manager')->upload(
                    $data->uploadedAvatar, true
                );
                $user->setAvatar($return['object']);                                                
                $this->persist($user, true);     
                $this->addFlash('success', 'Avatar has been updated');
                return $this->redirectToRoute('contractor_edit');       
                                        
            }
        }    
        $helperForm = $this
            ->createFormBuilder(['simple_date' => new DateTime])
            ->add('simple_date', 'date', ['format' => 'yyyy-MM-dd'])
            ->getForm()
        ;

        $years = array_combine(range(1920, date('Y')), range(1920, date('Y')));

        return ['user' => $user, 
                'userJson' => $serializer->serialize($user, 'json'), 
                'form' => $form->createView(), 
                'avatarForm' => $avatarForm->createView(),
                'helperForm' => $helperForm->createView(),
                'years' => $years,
                'months' => $this->getMonths()
        ];
    }
    
    /**
     *
     * @Secure(roles="ROLE_CONTRACTOR")
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
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function updateCityAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('city')->getForm()
        ;

        return $this->processForm($request, $form);        
    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function updateTagLineAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('tagLine')->getForm()
        ;
        return $this->processForm($request, $form);        

    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
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
     * @Secure(roles="ROLE_CONTRACTOR")
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
     * @Secure(roles="ROLE_CONTRACTOR")
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
     * @Secure(roles="ROLE_CONTRACTOR")
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

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function updateOverviewAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('overview')->getForm()
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

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function addEmploymentAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        $employment = new Employment;

        $form = $this->createForm(new EmploymentFormType(), $employment);        
        $form->bind($request);        
        if ($form->isValid()) {             
            $user->addEmployment($employment);
            $this->persist($user, true);     

            $r =  new Response($serializer->serialize($employment, 'json'));
            $r->headers->set('Content-Type', 'application/json');            
            return $r;

        } else {
           return new JsonResponse($this->_getErrorMessages($form), self::INVALID_DATA);
        }        
    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function addEducationAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        $education = new Education;
        $form = $this->createForm(new EducationFormType(), $education);        
        $form->bind($request);
        if ($form->isValid()) {             
            $user->addEducation($education);
            $this->persist($user, true);   
            $r =  new Response($serializer->serialize($education, 'json'));
            $r->headers->set('Content-Type', 'application/json');            
            return $r;        
        } else {
            return new JsonResponse($this->_getErrorMessages($form), self::INVALID_DATA);
        }

    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function addContractorTaskAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        $task = new ContractorTask;
        $form = $this->createForm(new ContractorTaskFormType(), $task);        
        $form->bind($request);
        if ($form->isValid()) {             
            $user->addContractorTask($education);
            $this->persist($user, true);   
            $r =  new Response($serializer->serialize($task, 'json'));
            $r->headers->set('Content-Type', 'application/json');            
            return $r;        
        } else {
            return new JsonResponse($this->_getErrorMessages($form), self::INVALID_DATA);
        }

    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function addQualificationAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');
        $q = new Qualification;
        $form = $this->createForm(new QualificationFormType(), $q);        
        $form->bind($request);
        if ($form->isValid()) {             
            $user->addQualification($q);
            $this->persist($user, true);   
            $r =  new Response($serializer->serialize($q, 'json'));
            $r->headers->set('Content-Type', 'application/json');            
            return $r;        
        } else {
            return new JsonResponse($this->_getErrorMessages($form), self::INVALID_DATA);
        }

    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function removeEducationAction(Request $request, $id)
    {
        return $this->restRemoveById('MC\UserBundle\Entity\Education', $id);
    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function removeEmploymentAction(Request $request, $id)
    {
        return $this->restRemoveById('MC\UserBundle\Entity\Employment', $id);        
    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function removeContractorTaskAction(Request $request, $id)
    {
        return $this->restRemoveById('MC\UserBundle\Entity\ContractorTask', $id);
    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function removeQualificationAction(Request $request, $id)
    {
        return $this->restRemoveById('MC\UserBundle\Entity\Qualification', $id);
    }

    protected function getMonths()
    {
        $months = [];
        for ($m=1; $m<=12; $m++) {
            $months[$m] = date('M', mktime(0,0,0,$m));
        }
        return $months;
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