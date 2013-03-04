<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use App\Exception;

/**
 * @ORM\Entity(repositoryClass="TaskCategoryRepository")
 * @ORM\Table(name="task_budgets")
 */
class TaskBudget
{
    
    const TYPE_FIXED = 'fixed';
    const TYPE_HOURLY = 'hourly';

    protected static $types = [self::TYPE_FIXED, self::TYPE_HOURLY];
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */ 
    protected $name;

    /**
     * @ORM\Column(type="string", length=12)     
     * @Assert\Choice(callback = "getTypes")     
     */     
    protected $type = self::TYPE_FIXED;

    public static function getTypes()
    {
        return static::$types;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getType() {
        return $this->type;
    }
    
    
    public function setType($type) {
        if (in_array($type, static::getTypes()) === false) {
            throw new Exception\InvalidTaskBudgetTypeException("Invalid type $type. Avilable types: ". join($this->getTypes(), ','));
        }
        $this->type = $type;    
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}