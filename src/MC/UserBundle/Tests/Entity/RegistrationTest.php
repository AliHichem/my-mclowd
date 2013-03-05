<?php
namespace Acme\DemoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    const VALIDATE_VALID_USERNAME = '{"success":"Username is available."}';
    const VALIDATE_INVALID_USERNAME = '{"error":"Username already in use."}';
    const VALIDATE_VALID_EMAIL = '{"success":"Email is available."}';
    const VALIDATE_INVALID_EMAIL = '{"error":"Email already in use."}';

    /** @var $em \Doctrine\ORM\EntityManager */
    private $em;

    /** @var $user \MC\UserBundle\Entity\Client */
    private $user;

    private $client;

    public function setUp()
    {
        $this->client = $this->createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getEntityManager();
        $this->user = $this->em->getRepository('MCUserBundle:User')->findOneByUsername('webTestCaseUser');
        if ($this->user === null) {
            $this->user = new \MC\UserBundle\Entity\Client();
            $this->user->setUsername('webTestCaseUser');
            $this->user->setFullName('Full Name');
            $this->user->setEmail('webTestCaseUser@trisoft.ro');
            $this->user->setPassword('123456');
            $this->user->setCountry(
                $this->em->getRepository('App:Country')
                    ->createQueryBuilder('c')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult()
            );
            $this->user->setCity('Sidney');
            $this->user->setHearSource(
                $this->em->getRepository('App:HearSource')
                    ->createQueryBuilder('h')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult()
            );
            $this->em->persist($this->user);
            $this->em->flush();
        }
    }

    public function testClientUsernameInvalid()
    {
        /*  case 'username': // fos_user_registration_form%5Busername%5D
                $data = $request->query->get('fos_user_registration_form');
                $username = $data['username'];
            case 'email': // fos_user_registration_form%5Bemail%5D
                $data = $request->query->get('fos_user_registration_form');
                $email = $data['email'];*/
        $crawler = $this->client->request(
            'GET',
            '/register/validate/username?fos_user_registration_form[username]=' . $this->user->getUsername()
        );
        $this->assertEquals(
            self::VALIDATE_INVALID_USERNAME,
            $this->client->getResponse()->getContent()
        );
    }

    public function testClientUsernameValid()
    {
        $crawler = $this->client->request(
            'GET',
            '/register/validate/username?fos_user_registration_form[username]=' . $this->user->getUsername() . time()
        );
        $this->assertEquals(
            self::VALIDATE_VALID_USERNAME,
            $this->client->getResponse()->getContent()
        );
    }

    public function testClientEmailInvalid()
    {
        $crawler = $this->client->request(
            'GET',
            '/register/validate/email?fos_user_registration_form[email]=' . $this->user->getEmail()
        );
        $this->assertEquals(
            self::VALIDATE_INVALID_EMAIL,
            $this->client->getResponse()->getContent()
        );
    }

    public function testClientEmailValid()
    {
        $crawler = $this->client->request(
            'GET',
            '/register/validate/email?fos_user_registration_form[email]=' . $this->user->getEmail() . time()
        );
        $this->assertEquals(
            self::VALIDATE_VALID_EMAIL,
            $this->client->getResponse()->getContent()
        );
    }
}