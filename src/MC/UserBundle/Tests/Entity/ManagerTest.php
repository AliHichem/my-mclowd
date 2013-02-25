<?php

namespace MC\UserBundle\Tests\Entity;

use MC\UserBundle\Entity\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $user;
 
    protected function setUp()
    {        
        $this->user = new Manager;
    }

    public function test_super_admin_role()
    {           
        $this->assertContains('ROLE_SUPER_ADMIN', $this->user->getRoles());

        #after resetting manager still have super role
        $this->user->setRoles(array('ROLE_ADMIN_TEST'));
        $this->assertContains('ROLE_ADMIN_TEST', $this->user->getRoles());
        $this->assertContains('ROLE_SUPER_ADMIN', $this->user->getRoles());
    }

}