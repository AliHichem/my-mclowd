<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Exception;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * @ORM\Entity()
 * @ORM\Table(name="jobs")
 */
class Job {

    use ORMBehaviors\Timestampable\Timestampable,                
        ORMBehaviors\Sluggable\Sluggable
    ;

    const TYPE_FIXED = 'fixed';
    const TYPE_HOURLY = 'hourly';

    protected static $types = array(self::TYPE_FIXED, self::TYPE_HOURLY);
    protected static $currencies = array('USD', 'EUR');


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank()     
     */     
    protected $name;

    /**
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=12)     
     * 
     * @Assert\Choice(callback = "getTypes")     
     */     
    protected $type = self::TYPE_FIXED;

    /**
     * @ORM\Column(type="string", length=3)     
     * @Assert\Choice(callback = "getCurrencies")     
     */     
    protected $currency = 'USD';

    public function getId()
    {
        return $this->id;
    }

    public static function getTypes()
    {
        return static::$types;
    }

    public static function getCurrencies()
    {
        return static::$currencies;
    }

    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    
    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function getType() {
        return $this->type;
    }
    
    
    public function setType($type) {
        if (in_array($type, static::getTypes()) === false) {
            throw new Exception\InvalidJobTypeException("Invalid type $type. Avilable types: ". join($this->getTypes(), ','));
        }
        $this->type = $type;    
        return $this;
    }


    public function getCurrency() {
        return $this->currency;
    }
    
    public function setCurrency($currency) {
        if (in_array($currency, static::getCurrencies()) === false) {
            throw new Exception\InvalidJobCurrencyException("Invalid currency $currency. Avilable currencies: ". join($this->getCurrencies(), ','));
        }
        $this->currency = $currency;    
        return $this;
    }

    public function getSluggableFields()
    {
        return ['id','name'];    
    }
}