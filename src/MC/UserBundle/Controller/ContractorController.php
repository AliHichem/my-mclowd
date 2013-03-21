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

        $avatarForm = $this
            ->createFormBuilder($user)
            ->add('uploadedAvatar', 'file', ['required' => false])
            ->getForm()
        ;
        $form = $this->createForm(new ContractorEditFormType(), $user);

        /**
         * Handle avatar change
         */
        if ($request->request->has("uploadedAvatar"))
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
        } elseif ($request->request->has("id")) {
            /**
             * Handle submission of values from angular
             */
            $form->bind($request);
            var_dump($form->getErrors());die();
            if ($form->isValid()) {                
                $this->persist($user, true);     
            } else {

            }
        }

        return ['user' => $user, 'form' => $form->createView(), 'avatarForm' => $avatarForm->createView()];
    }

}