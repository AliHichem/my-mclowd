<?php

namespace App\Task\Tests\Entity;

use App\Entity\TaskBudget;
use App\Entity\TaskCategory;
use Symfony\Component\Validator\Validation;

class TaskBudgetTest extends \PHPUnit_Framework_TestCase
{
    protected $tb;

    protected function setUp()
    {        
        $this->tb = new TaskBudget;       
    }

    /**
     * @expectedException     \App\Exception\InvalidTaskBudgetTypeException     
     */
    public function test_invalid_types()
    {
        $this->tb->setType('non_existend');
    }
    

}