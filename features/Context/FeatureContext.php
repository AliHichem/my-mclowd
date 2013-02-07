<?php

namespace Context;

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\Util\Inflector;
use Behat\MinkExtension\Context\MinkContext;

use App\Entity,\
    MC\UserBundle\Entity\User,
    MC\UserBundle\Entity\Client,
    MC\UserBundle\Entity\Contractor;

use Behat\Behat\Exception\Exception;


class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;

    
    /**
     * @Given /^users table is empty$/
     */
    public function usersTableIsEmpty()
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getEntityManager();
        $em->createQuery('DELETE MCUserBundle:User')->execute();
    }


    /**
     * @Given /^the following people exist:$/
     */
    public function theFollowingPeopleExist(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $user = null;
            switch ($row['type']) {
                case 'client':
                    $user = new Client;
                    break;

                case 'contractor':
                    $user = new Client;
                    break;                    
                
                default:
                    throw new Exception("User type not supported");                    
                    break;
            }
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setPlainPassword($row['password']);
            $user->setEnabled(true);
            $this->getContainer()->get('fos_user.user_manager')->updateUser($user);                  
        }
    }

    /**
     * @Then /^I am logged in system$/
     */
    public function iAmLoggedInSystem()
    {
        if ($this->getContainer()->get('security.context')->getToken()->getUser() instanceof User !== true) {
            throw new Exception("Security token is:\n" . $this->output);
        }        
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns Container instance.
     * 
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * Generates url with Router.
     * 
     * @param string  $route
     * @param array   $parameters
     * @param Boolean $absolute
     * 
     * @return string
     */
    private function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->getContainer()->get('router')->generate($route, $parameters, $absolute);
    }

    /**
     * Generate page url from name and parameters.
     * 
     * @param string $page
     * @param array  $parameters
     * 
     * @return string
     */
    private function generatePageUrl($page, array $parameters = array())
    {
        $parts = explode(' ', trim($page), 2);
        if (2 === count($parts)) {
            $parts[1] = Inflector::camelize($parts[1]);
        }

        $route  = implode('_', $parts);
        $routes = $this->getContainer()->get('router')->getRouteCollection();

        if (null === $routes->get($route)) {
            $route = 'app_'.$route;
        }

        return $this->getMinkParameter('base_url').$this->generateUrl($route, $parameters);
    }
}
