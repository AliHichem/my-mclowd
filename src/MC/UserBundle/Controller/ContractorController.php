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
use DateTime;
use MC\UserBundle\Entity\Education;
use MC\UserBundle\Entity\Employment;


class ContractorController extends BaseController
{
    use RestableController;

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
        return ['user' => $user, 
                'userJson' => $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(['profileForm'])), 
                'form' => $form->createView(), 
                'avatarForm' => $avatarForm->createView(),
                'helperForm' => $helperForm->createView()
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
    public function updateTagLinection(Request $request)
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
            $resp = json_encode([
                'error' => $this->_getErrorMessages($form)
            ]);
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
            $resp = $serializer->serialize($employment, 'json');
        } else {
            $resp = json_encode([
                'error' => $this->_getErrorMessages($form)
            ]);
        }

        $response = new JsonResponse($resp);
        return $response;

    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function addEducationAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');

        $form = $this
            ->createFormBuilder(new Education, ['csrf_protection' => false])
            ->add('institutionName')
            ->add('degree')
            ->getForm()
        ;
        $form->bind($request);
        if ($form->isValid()) {             
            $this->persist($user, true);     
            $resp = $serializer->serialize($user, 'json');
        } else {
            $resp = json_encode([
                'error' => $this->_getErrorMessages($form)
            ]);
        }

        $response = new JsonResponse($resp);
        return $response;

    }

}