<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),


            new FOS\UserBundle\FOSUserBundle(),
            new FOS\AdvancedEncoderBundle\FOSAdvancedEncoderBundle(),
            new FOS\MessageBundle\FOSMessageBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new FOS\FacebookBundle\FOSFacebookBundle(),

            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            
            new PUGX\MultiUserBundle\PUGXMultiUserBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),

            new Knp\RadBundle\KnpRadBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),

            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),


            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            new TSS\AssetsInstallWindowsBundle\TSSAssetsInstallWindowsBundle(),

            new Leezy\PheanstalkBundle\LeezyPheanstalkBundle(),

            new TSS\AutomailerBundle\TSSAutomailerBundle(),

            #local
            new MC\UserBundle\MCUserBundle(),
            new MC\AdminBundle\MCAdminBundle(),
            new MC\AssetBundle\MCAssetBundle(),
            new App\App(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');

        if (is_file($file = __DIR__.'/config/config_'.$this->getEnvironment().'_local.yml')) {
            $loader->load($file);
        }
    }
}
