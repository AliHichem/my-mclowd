<?php

namespace App\Task\Tests\Entity;

use App\Entity\Task;
use App\Entity\TaskCategory;
use Symfony\Component\Validator\Validation;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    protected $job;
    protected $validator;

    protected function setUp()
    {        
        $this->job = new Task;
        $this->job
            ->setName('Will work for food')
            ->setDescription('sample desc')
            ->setCategory(new TaskCategory)
            ->setTimePeriod(1)
        ;
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    public function test_default_valid()
    {
        $errors = $this->validator->validate($this->job);          
        $this->assertEquals(0, count($errors)); 
        return $this->job;
    }

    public function test_default_type()
    {
        $job = new Task;
        $this->assertEquals($job->getType(), Task::TYPE_FIXED);
    }

    public function test_default_currency()
    {
        $job = new Task;
        $this->assertEquals($job->getCurrency(), 'USD');
    }

    /**
     * @expectedException     \App\Exception\InvalidTaskTypeException     
     */
    public function test_invalid_types()
    {
        $this->job->setType('non_existend');
    }

    /**
     * @expectedException     \App\Exception\InvalidTaskCurrencyException     
     */
    public function test_invalid_currency()
    {
        $this->job->setCurrency('PLN');
    }

    /**
     * @depends test_default_valid
     */
    public function test_valid_name(Task $job)
    {       
        $not_valid = array(
            null,
            '',             
        );
        foreach ($not_valid as $key) {          
            $job->setName($key);  
            $errors = $this->validator->validate($job);                
            $this->assertGreaterThan(0, count($errors), "name should not be '$key'");            
        }

    }

    /**
     * @depends test_default_valid
     */
    public function test_valid_description(Task $job)
    {       
        $not_valid = array(
            null,
            '',             
        );
        foreach ($not_valid as $key) {          
            $job->setDescription($key);  
            $errors = $this->validator->validate($job);                
            $this->assertGreaterThan(0, count($errors), "description should not be '$key'");            
        }

    }
    
}