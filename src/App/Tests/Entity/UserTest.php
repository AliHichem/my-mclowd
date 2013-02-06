<?php
namespace App\Tests\Entity;

use App\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function test_display_name()
    {
        $user = new User();
        $user->setUserName('defrag');
        $user->setDisplayName('Michal D.');
        $this->assertEquals($user->getDisplayName(), 'Michal D.');
        return $user;
    }

    /**
     * @depends test_display_name
     */
    public function test_display_name_when_display_name_is_not_set_up(User $user)
    {
        $user->setDisplayName(null);
        $this->assertEquals($user->getDisplayName(), 'defrag');
    }

}