<?php

namespace MC\UserBundle\Controller;

use App\Controller\Controller as BaseController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MC\UserBundle\Form\Type\ContractorEditFormType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use DateTime;

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
        $serializer->setGroups(['profileForm']);

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
                'userJson' => $serializer->serialize($user, 'json'), 
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
        $serializer = $this->get('serializer');

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('city')->getForm()
        ;
        $form->bind($request);
        if ($form->isValid()) {                
            $this->persist($user, true);     
            $resp = $serializer->serialize($user, 'json');
        } else {
            $resp = json_encode($form->getErrors());
        }

        $response = new JsonResponse($resp);
        return $response;

    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function updateTagLinection(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('tagLine')->getForm()
        ;
        $form->bind($request);
        if ($form->isValid()) {                
            $this->persist($user, true);     
            $resp = $serializer->serialize($user, 'json');
        } else {
            $resp = json_encode($form->getErrors());
        }

        $response = new JsonResponse($resp);
        return $response;

    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function updateFullnameAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('fullName')->getForm()
        ;
        $form->bind($request);
        if ($form->isValid()) {             
            $this->persist($user, true);     
            $resp = $serializer->serialize($user, 'json');
        } else {
            $resp = json_encode($form->getErrors());
        }

        $response = new JsonResponse($resp);
        return $response;

    }

    /**
     * 
     * @Secure(roles="ROLE_CONTRACTOR")
     * */
    public function updateOverviewAction(Request $request)
    {
        $user = $this->getSecurity()->getToken()->getUser();
        $serializer = $this->get('serializer');

        $form = $this
            ->createFormBuilder($user, ['csrf_protection' => false])
            ->add('overview')->getForm()
        ;
        $form->bind($request);
        if ($form->isValid()) {             
            $this->persist($user, true);     
            $resp = $serializer->serialize($user, 'json');
        } else {
            $resp = json_encode($form->getErrors());
        }

        $response = new JsonResponse($resp);
        return $response;

    }

}