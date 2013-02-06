<?php
namespace App\Tests\Entity;

use App\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $user;
 
    protected function setUp()
    {
        $this->user = new User;
        $this->user->setUserName('defrag');
        $this->user->setDisplayName('Michal D.');
    }

    public function test_display_name()
    {           
        $this->assertEquals($this->user->getDisplayName(), 'Michal D.');
        return $this->user;
    }

    /**
     * @depends test_display_name
     */
    public function test_display_name_when_display_name_is_not_set_up(User $user)
    {
        $user->setDisplayName(null);
        $this->assertEquals($user->getDisplayName(), 'defrag');
    }

    public function test_default_encoder_for_user()
    {
        $this->assertNull($this->user->getEncoderName());
    }

    public function test_wordpress_encoder_for_legacy_user()
    {
        $this->user->setIsLegacy(true);
        $this->assertEquals($this->user->getEncoderName(), 'wp_encoder');
    }

}