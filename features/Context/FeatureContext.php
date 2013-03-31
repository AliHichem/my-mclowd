<?php

namespace Context;

use Behat\Behat\Exception\BehaviorException;
use Behat\Behat\Exception\UndefinedException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\Util\Inflector;

use App\Entity\Task,
    App\Entity\TaskCategory,
    App\Entity\Country,
    App\Entity\TaskBudget,
    App\Entity\HearSource,
    MC\UserBundle\Entity\User,
    MC\UserBundle\Entity\Client,
    MC\UserBundle\Entity\Contractor;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends MinkContext implements KernelAwareInterface
{

    /**
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @Given /^users table is empty$/
     */
    public function usersTableIsEmpty()
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearUsers()
    {
        $em = $this->getEM();
        $em->createQuery('DELETE App:ThreadMetadata')->execute();
        $em->createQuery('DELETE App:Thread')->execute();
        $em->createQuery('DELETE App:Task')->execute();
        $em->createQuery('DELETE MCUserBundle:User')->execute();
    }

    /**
     * @Given /^the following tasks exist:$/
     */
    public function theFollowingTasksExist(TableNode $table)
    {
        $hash = $table->getHash();
        $em = $this->getEM();
        foreach ($hash as $row) {
            $task = new Task();
            foreach ($row as $field => $value) {
                $setter = 'set'.ucfirst($field);
                switch ($field) {
                    case 'user':
                       $value = $this->findUserByName($value);
                }
                $task->$setter($value);
            }
            if (!$task->getDescription()) {
                $task->setDescription('default description');
            }
            if (!$task->getTimePeriod()) {
                $task->setTimePeriod('test');
            }
            $em->persist($task);
        }
        $em->flush();
    }

    /**
     * @Given /^the following people exist:$/
     */
    public function theFollowingPeopleExist(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $this->addUser($row);
        }
    }

    /**
     * @Given /^I am logged in as ([^"]*) with:$/
     */
    public function iAmLoggedInAsWith($type, TableNode $table)
    {
        $fields = $table->getHash()[0];
        $fields['type'] = $type;
        if (!isset($fields['username'])) {
            $fields['username'] = implode('', explode(' ', $fields['fullName']));
        }
        if (!isset($fields['email'])) {
            $fields['email'] = $fields['username'].'@default.com';
        }
        if (!isset($fields['password'])) {
            $fields['password'] = 'defaultpassword';
        }
        if ($user = $this->getCurrentUser()) {
            $this->updateUser($user);
        } else {
            $user = $this->addUser($fields);
        }
        $this->iAmLoggedInAsWithPassword($user->getUsername(), $fields['password']);
    }

    /**
     * @Given /^the following jobs exist:$/
     */
    public function theFollowingJobsExist(TableNode $table)
    {
        $hash = $table->getHash();
        $em = $this->getEM();
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
        $em = $this->getEM();
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
            $category->isChildNodeOf($root);
            $category->setName($name);
            ++$id;
            $em->persist($category);
        }
        $em->flush();
  
    }

    /**
     * @Given /^the default budgets are in database$/
     */
    public function theDefaultBudgetsAreInDatabase()
    {
        $em = $this->getEM();
        $em->createQuery('DELETE App:TaskBudget')->execute();
        $hourly = [
            '$15-25 per hour',
            '$25-30 per hour',
            '$30-45 per hour',
            '$45+ per hour'
        ];
        foreach ($hourly as $value) {
            $tb = new TaskBudget;
            $tb
                ->setName($value)
                ->setType(TaskBudget::TYPE_HOURLY)
            ;
            $em->persist($tb);
        }

        $fixed = [
            'Less than $100',
            'Between $100 and $250',
            'Between $250 and $500',
            '$500+'            
        ];
        foreach ($fixed as $value) {
            $tb = new TaskBudget;
            $tb
                ->setName($value)
                ->setType(TaskBudget::TYPE_FIXED)
            ;
            $em->persist($tb);
        }

        $em->flush();
  
    }

    /**
     * @Then /^I am logged in system$/
     */
    public function iAmLoggedInSystem()
    {
        if ($this->getCurrentUser() instanceof User !== true) {
            throw new BehaviorException('user not logged in');
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
            throw new BehaviorException("Redirecting to ". $redirectComponents['path'] . ". Expected $location");
        }

        $client->followRedirects(true);
        $client->followRedirect(true);
    }

    /**
     * @Given /^countries are loaded$/
     */
    public function countriesAreLoaded()
    {
        $em = $this->getEM();
        $em->createQuery('DELETE App:Country')->execute();
        $countriesData = file_get_contents(dirname(__FILE__).'/../../src/App/DataFixtures/DATA/countries.json');
        $countries = json_decode($countriesData);
        $id = 0;
        foreach ($countries as $code => $name) {
            $id++;
            $c = new Country();
            $c->setCode($code);
            $c->setName($name);
            $em->persist($c);
        }
        $em->flush();
    }

    /**
     * @Given /^hearsources are loaded$/
     */
    public function hearsourcesAreLoaded()
    {
        $manager = $this->getEM();
        $manager->createQuery('DELETE App:HearSource')->execute();

        $sources = array(
            'Google',
            'Yahoo!',
            'Bing',
            'Other search engine',
            'A friend',
            'A coworker',
            'Internet ad',
            'TV ad',
            'Radio ad'
        );
        foreach ($sources as $name) {
            $source = new HearSource();
            $source->setName($name);
            $manager->persist($source);
        }
        $manager->flush();
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
     * @param boolean $absolute
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

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEM()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     *
     * @return User
     */
    private function getCurrentUser()
    {
        if (is_object($token = $this->getContainer()->get('security.context')->getToken())) {
            return $token->getUser();
        }
    }

    /**
     *
     * @param array $fields
     * @return \MC\UserBundle\Entity\User
     * @throws UndefinedException
     */
    private function addUser(array $fields)
    {
        $user = null;
        switch ($fields['type']) {
            case 'client':
                $user = new Client();
                break;
            case 'contractor':
                $user = new Contractor();
                break;
            default:
                throw new UndefinedException('User type not supported');
        }
        unset($fields['type']);
        if (!isset($fields['enabled'])) {
            $fields['enabled'] = true;
        }
        return $this->updateUser($user, $fields);
    }

    /**
     *
     * @param \MC\UserBundle\Entity\User $user
     * @param array $fields
     * @return \MC\UserBundle\Entity\User
     */
    private function updateUser(User $user, array $fields)
    {
        foreach ($fields as $field => $value) {
            if ($field == 'password') {
                $setter = 'setPlainPassword';
            } else {
                $setter = 'set'.ucfirst($field);
            }
            $user->$setter($value);
        }
        $this->getContainer()->get('fos_user.user_manager')->updateUser($user);
        return $user;
    }

    private function findUserByName($name)
    {
        return $this->getEM()->getRepository(get_class(new Client))->findOneBy(['username' => $name]);
    }

}
