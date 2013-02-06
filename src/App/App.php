<?php

namespace App;

use Knp\RadBundle\AppBundle\Bundle;
use App\Compiler\PersistentObjectCompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class App extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(
            new PersistentObjectCompilerPass($this), PassConfig::TYPE_AFTER_REMOVING
        );        
    }
}
