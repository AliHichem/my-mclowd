<?php
namespace MC\UserBundle\Controller;

use App\Controller\Controller as BaseController;
use MC\UserBundle\Entity\Social;
use MC\UserBundle\Entity\Client;
use MC\UserBundle\Entity\Contractor;
use MC\UserBundle\Form\Type\SocialRegistrationChooseTypeFormType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MC\UserBundle\Form\Type\SocialRegistrationClientFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SocialRegistrationController extends BaseController
{
    private $allowedTypes = array(
        'MC\UserBundle\Entity\Client' => 'Client',
        'MC\UserBundle\Entity\Contractor' => 'Contractor'
    );

    /**
     * @Secure(roles="ROLE_SOCIAL")
     * @Template()
     */
    public function chooseAccountTypeAction()
    {
        $form = $this->createForm(
            new SocialRegistrationChooseTypeFormType(
                array_values($this->allowedTypes)
            ),
            array('accountType' => 0)
        );

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $accountType = $form->getData()['accountType'];
                switch ($accountType) {
                    case 0:
                        return $this->redirectToRoute('social_register_client_account');
                    case 1:
                        return $this->redirectToRoute('social_register_contractor_account');
                }
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Template()
     */
    public function registerClientAccountAction()
    {
        $form = $this->createForm(new SocialRegistrationClientFormType('MC\UserBundle\Entity\Social'), $this->getUser());

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $this->switchCurrentUserType('MC\UserBundle\Entity\Client');
                return $this->redirectToRoute('homepage');
            }
        }

        return array('form' => $form->createView());
    }

    protected function switchCurrentUserType($class)
    {
        /** @var $user Social */
        $user = $this->getUser();
        /** @var $discriminator \PUGX\MultiUserBundle\Model\UserDiscriminator */
        $discriminator = $this->get('pugx_user_discriminator');
        /** @var $userManager \PUGX\MultiUserBundle\Doctrine\UserManager */
        $userManager = $this->get('pugx_user_manager');
        $discriminator->setClass($class);
        /** @var $newUser Client */
        $newUser = $userManager->createUser();

        // copy user data
        $newUser->setEmail($user->getEmail());
        $newUser->setUsername($user->getUsername());
        $newUser->setUsernameCanonical($user->getUsernameCanonical());
        $newUser->setEmail($user->getEmail());
        $newUser->setFacebookId($user->getFacebookId());
        $newUser->setFullName($user->getFullName());
        $newUser->setCity($user->getCity());
        $newUser->setCountry($user->getCountry());
        $newUser->setHearSource($user->getHearSource());

        $newUser->setPassword('');
        $newUser->setEnabled(true);

        $eManager = $this->getEntityManager();

        $eManager->remove($user);
        $eManager->flush();
        $eManager->persist($newUser);
        $eManager->flush();
    }
}
