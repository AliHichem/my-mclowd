<?php
namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

class TaskCategoryRepository extends EntityRepository
{
    use ORMBehaviors\Tree\Tree;
}