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
use DateTime;
use MC\UserBundle\Entity\Education;
use MC\UserBundle\Entity\Employment;
use MC\UserBundle\Entity\ContractorTask;
use Symfony\Component\HttpFoundation\Response;
// SerializationContext::create()->setGroups(['profileForm'])
class ContractorController extends BaseController
{
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
    public function removeEducationAction(Request $request, $id)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $education = $this->findOr404('MC\UserBundle\Entity\Education',[
            'id' => $id,
            'user' => $this->getSecurity()->getToken()->getUser() 
        ]);
        
        $this->remove($education);
        $this->flush();
        return new JsonResponse('success');

    }

    /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function removeEmploymentAction(Request $request, $id)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $employment = $this->findOr404('MC\UserBundle\Entity\Employment',[
            'id' => $id,
            'user' => $this->getSecurity()->getToken()->getUser() 
        ]);
        
        $this->remove($employment);
        $this->flush();
        return new JsonResponse('success');

    }

     /**
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function removeContractorTaskAction(Request $request, $id)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $education = $this->findOr404('MC\UserBundle\Entity\ContractorTask',[
            'id' => $id,
            'user' => $this->getSecurity()->getToken()->getUser() 
        ]);
        
        $this->remove($education);
        $this->flush();
        return new JsonResponse('success');

    }

    protected function getMonths()
    {
        $months = [];
        for ($m=1; $m<=12; $m++) {
            $months[$m] = date('M', mktime(0,0,0,$m));
        }
        return $months;
    }

}