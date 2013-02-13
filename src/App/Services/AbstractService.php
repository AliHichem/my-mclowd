<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;


/**
* Abstract class for all services. The container will inject the EntityManger (Doctrine).
*/
abstract class AbstractService
{

    private $em;

    public function __construct(EntityManager $em)
    {

        $this->em = $em;

    }

    /**
     * Returns EntityManager
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm(){
    	return $this->em;
    }
}