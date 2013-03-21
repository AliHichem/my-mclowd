<?php

namespace MC\UserBundle\Controller;

use App\Controller\Controller as BaseController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MC\UserBundle\Form\Type\ContractorEditFormType;
use JMS\SecurityExtraBundle\Annotation\Secure;
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

        //var_dump($request);die();
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
        } elseif ($request->request->has("city")) {            
            /**
             * Handle submission of values from angular
             */
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

        
        return ['user' => $user, 'userJson' => $serializer->serialize($user, 'json'), 'form' => $form->createView(), 'avatarForm' => $avatarForm->createView()];
    }

}