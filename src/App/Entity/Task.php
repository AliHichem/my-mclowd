<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Exception;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use JMS\Serializer\Annotation as Rest;
use JMS\Serializer\Annotation\Accessor;

/**
 * @ORM\Entity(repositoryClass="TaskRepository")
 * @ORM\Table(name="tasks")
 * @Rest\ExclusionPolicy("all")
 */
class Task {

    use ORMBehaviors\Timestampable\Timestampable,                
        ORMBehaviors\Sluggable\Sluggable,
        \App\Behaviours\Ownable
    ;

    const TYPE_FIXED = 'fixed';
    const TYPE_HOURLY = 'hourly';

    protected static $types = [self::TYPE_FIXED, self::TYPE_HOURLY];
    protected static $currencies = ['USD', 'EUR'];
    protected static $time_periods = [
        1 => '1-2 days',
        2 => '3-5 days',
        4 => '1-2 weeks',
        8 => '2-4 weeks',
        16 => '1-3 months',
        32 => 'Ongoing',
    ];


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Rest\Expose
     * @Rest\SerializedName("id")
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

    /**
     * @ORM\Column(type="integer")     
     * @Assert\Choice(callback = "getTimePeriodKeys")     
     * @Assert\NotBlank()
     */     
    protected $timePeriod;

    /**
     * @ORM\Column(name="hours_per_week", type="integer", nullable=true)     
     */     
    protected $hoursPerWeek;

    /**
     * @ORM\Column(name="is_active", type="boolean")          
     */     
    protected $isActive = true;

    /**
     * @ORM\ManyToOne(targetEntity="TaskCategory", inversedBy="tasks")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     * @Assert\NotBlank()
     *
     * @var TaskCategory $category
     */
    protected $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="TaskBudget")
     * @ORM\JoinColumn(name="budget_id", referencedColumnName="id", onDelete="SET NULL")
     * @Assert\NotBlank()
     *
     * @var TaskBudget $budget
     */
    protected $budget;
    
    /**
     * @ORM\OneToMany(targetEntity="Proposal", mappedBy="task")
     * @Rest\SerializedName("proposals")
     **/
    protected $proposals;

    /*
     * Trait property here:
     * @ORM\ManyToOne(targetEntity="MC\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var User $user
     */
    protected $user;
    
    public function __construct()
    {
        $this->proposals = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public static function getTimePeriods()
    {
        return static::$time_periods;
    }

    public static function getTimePeriodKeys()
    {
        return array_keys(static::$time_periods);
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
            throw new Exception\InvalidTaskTypeException("Invalid type $type. Avilable types: ". join($this->getTypes(), ','));
        }
        $this->type = $type;    
        return $this;
    }


    public function getCurrency() {
        return $this->currency;
    }
    
    public function setCurrency($currency) {
        if (in_array($currency, static::getCurrencies()) === false) {
            throw new Exception\InvalidTaskCurrencyException("Invalid currency $currency. Avilable currencies: ". join($this->getCurrencies(), ','));
        }
        $this->currency = $currency;    
        return $this;
    }

    public function getSluggableFields()
    {
        return ['id','name'];    
    }

    public function getCategory() {
        return $this->category;
    }
    
    public function setCategory($category) {
        $this->category = $category;    
        return $this;
    }


    public function getBudget() {
        return $this->budget;
    }
    
    public function setBudget($budget) {
        $this->budget = $budget;
    
        return $this;
    }

    public function setUser($user) {
        $this->user = $user;
        return $user;
    }

    public function getUser()
    {
        return $this->user;
    }
    
    public function getTimePeriod() {
        return $this->timePeriod;
    }
    
    public function setTimePeriod($timePeriod) {
        $this->timePeriod = $timePeriod;
    
        return $this;
    }

    public function getCategoryId()
    {
        if ($this->category !== null) {
            return $this->category->getId();
        }
        return null;
    }
    
    public function getHoursPerWeek() {
        return $this->hoursPerWeek;
    }
    
    public function setHoursPerWeek($hoursPerWeek) {
        $this->hoursPerWeek = $hoursPerWeek;
    
        return $this;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($value)
    {
        $this->isActive = (Boolean)$value;
    }

    public function getBudgetId()
    {
        if ($this->budget !== null) {
            return $this->budget->getId();    
        }
        return null;
    }

    public function getUserName()
    {
        return (String)$this->getUser();
    }
    
    public function getProposals()
    {
        return $this->proposals;
    }
    
    public function addProposal($proposal)
    {
        $this->proposals[] = $proposal;
        return $this;
    }
    
    public function setProposals($proposals)
    {
        $this->proposals = $proposals;
        return $this;
    }
    
    public function removeProposal($proposal)
    {
        $this->proposals->removeElement($proposal);
    }
    
    public function __toString() {
        return strval($this->id);
    }
}