<?php
namespace MC\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class UserAdmin extends Admin
{
    protected $container = null;
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $securityContext = $this->container->get('security.context');
        $loggedUser = $securityContext->getToken()->getUser();
        
        
        $formMapper->with('User Data')
            ->add('email')
            ->add('username')            
        ->end();
        
        
        $formMapper->with('User Data')
            ->add('plainPassword', 'password',  array('required' => false)) //TODO: add edit of password
        ->end();
        
           
        $formMapper->with('Management')
            ->add('enabled', 'checkbox', array('required' => false))
        ->end();
        
        
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('email')
            ->add('username')
            ->add('createdAt')
            ->add('enabled')            
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('firstname')
                ->assertNotBlank()
            ->end()
            ->with('lastname')
                ->assertNotBlank()
            ->end()
            ->with('email')
                ->assertNotBlank()
            ->end()
        ;                
        
    }
    
    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    public function prePersist($user)
    {
        if ($user->getPlainPassword() == '') {
            $user->setPlainPassword('');
        }
        $this->getUserManager()->updatePassword($user);
    }

    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
    
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getContainer()
    {
        return $this->container;
    }
}