<?php



namespace App\Behaviours;

use Doctrine\Common\Persistence\Mapping\ClassMetadata,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\Events,
    Doctrine\ORM\Event\LifecycleEventArgs,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Security\Core\User\UserInterface;


/**
 * Ownable Doctrine2 listener.
 *
 * Listens to onFlush event and marks User with current logged in user entities.
 */
class OwnableListener implements EventSubscriber
{

    
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function updateUser(LifecycleEventArgs $args)
    {        
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();    
        $entity = $args->getEntity();            
        $sc = $this->container->get('security.context');

        $classMetadata = $em->getClassMetadata(get_class($entity));
        if ($this->isEntitySupported($classMetadata)) {

            $oldValue = $entity->getUser();

            if ($sc->getToken()->getUser() instanceof UserInterface) {

                $entity->setUser($sc->getToken()->getUser());                

                $uow->propertyChanged($entity, 'user', $oldValue, $entity->getUser());                                
                $uow->scheduleExtraUpdate($entity, [
                    'user' => [$oldValue, $entity->getUser()],
                ]);
            }
        }
        
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->updateUser($eventArgs);
    }
    

    /**
     * Checks whether provided entity is supported.
     *
     * @param ClassMetadata $classMetadata The metadata
     *
     * @return Boolean
     */
    private function isEntitySupported(ClassMetadata $classMetadata)
    {
        $traitNames = $classMetadata->reflClass->getTraitNames();
        return in_array('App\Behaviours\Ownable', $traitNames);
    }

    /**
     * Returns list of events, that this listener is listening to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist];
    }
}
