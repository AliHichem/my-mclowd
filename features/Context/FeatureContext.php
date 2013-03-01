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

use App\Entity\Task,
    App\Entity\TaskCategory,
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
        $em->createQuery('DELETE App:Task')->execute();
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
                    $user = new Contractor;
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
     * @Given /^the following jobs exist:$/
     */
    public function theFollowingJobsExist(TableNode $table)
    {
        $hash = $table->getHash();
        $em = $this->kernel->getContainer()->get('doctrine')->getEntityManager();
        foreach ($hash as $row) {
            $job = new Task;

            $job->setName($row['name']);
            $job->setDescription($row['description']);

            if (isset($row['timePeriod'])) {
                $job->setTimePeriod($row['description']);
            } else {
                $job->setTimePeriod(1);
            }

            $em->persist($job);
        }
        $em->flush();
    }

    /**
     * @Given /^the default categories are in database$/
     */
    public function theDefaultCategoriesAreInDatabase()
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getEntityManager();
        $em->createQuery('DELETE App:TaskCategory')->execute();
        $root = new TaskCategory;
        $root->setId(1);
        $root->setName('Root');    
        
        $em->persist($root);
        $em->flush();
        $id = 2;
        foreach (['Accounting', 'Bookkeeping', 'Data entry', 'Concierge', 'Tax', 'Audits'] as $name) {
            $category = new TaskCategory();
            $category->setId($id); // tree nodes need an id to construct path.
            $category->setChildOf($root);
            $category->setName($name);
            ++$id;
            $em->persist($category);
        }
        $em->flush();
  
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
     * @Then /^I logout$/
     */
    public function iLogout()
    {
        $this->visit('/logout');
    }


    /**
     * @Given /^I am logged in as "([^"]*)" with password "([^"]*)"$/
     */
    public function iAmLoggedInAsWithPassword($email, $password)
    {
        $this->visit('/login');
        $this->fillField('username', $email);
        $this->fillField('password', $password);
        $this->pressButton('Login');
        $this->iAmLoggedInSystem();
    }

    /**
     * @Given /^I do not follow redirects$/
     */
    public function iDoNotFollowRedirects()
    {
        $this->getSession()->getDriver()->getClient()->followRedirects(false);
    }

    /**
     * @Then /^I should be redirected to "([^"]*)"$/
     */
    public function iShouldBeRedirectedTo($location = null)
    {
        $headers = $this->getSession()->getResponseHeaders();

        $redirectComponents = parse_url($headers['Location']);

        $client = $this
            ->getSession()
            ->getDriver()
            ->getClient()
        ;

        if ($redirectComponents['path'] !== $location) {
            throw new Exception("Redirecting to ". $redirectComponents['path'] . ". Expected $location");
        }

        $client->followRedirects(true);
        $client->followRedirect(true);
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
