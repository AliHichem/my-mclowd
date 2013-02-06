<?php
namespace App\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Common\Persistence\PersistentObject;

class PersistentObjectCompilerPass implements CompilerPassInterface {

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {                        
        PersistentObject::setObjectManager(
            $container->get('doctrine')->getEntityManager()
        );                
    }
}