<?php
namespace App\Entity;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Entity
 * @ORM\Table(name="proposals")
 * @ORM\Entity(repositoryClass="App\Entity\ProposalRepository")
 * 
 * @Assert\Callback(methods={"validateHourlyOptions", "validateFixedOptions"})
 * 
 */
class Proposal {

    use ORMBehaviors\Timestampable\Timestampable,
        \App\Behaviours\Ownable
    ;
    
    public static $durationOptions = [
        1 => '1-2 weeks',
        2 => '3-4 weeks'
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="proposals")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $task;
    
    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Milestone", mappedBy="proposal", cascade={"persist", "remove"})
    **/
    public $milestones;

    /**
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(groups="hourly_group")
     */
    protected $hours;
    
    /**
     * @ORM\Column(name="finish_date", type="datetime", nullable=true)
     * @Assert\NotBlank(groups="fixed_group")
     */
    protected $finishDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(groups="hourly_group")
     */
    protected $duration;

    /**
     * @ORM\Column(name="contractor_rate", type="float")
     * @Assert\NotBlank()
     */
    protected $contractorRate;
    
    /**
     * @ORM\Column(name="final_rate", type="float")
     * @Assert\NotBlank()
     */
    protected $finalRate;
    
    /**
     * @ORM\Column(name="is_accepted", type="smallint", options={"default" = 0})
     */
    protected $isAccepted;

    /**
     * Trait property here:
     * @ORM\ManyToOne(targetEntity="MC\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var User $user
     */
    protected $user;
    
    public function __construct()
    {
        $this->milestones = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function validateHourlyOptions(ExecutionContext $ec)
    {
        if ($this->getTask() != null && $this->getTask()->getType() == 'hourly')
        {
            $ec->validate($this, '', 'hourly_group', true); 
        }
    }
    
    public function validateFixedOptions(ExecutionContext $ec)
    {
        if ($this->getTask() != null && $this->getTask()->getType() == 'fixed')
        {
            $ec->validate($this, '', 'fixed_group', true);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setContractorRate($rate) {
        $this->contractorRate = $rate;
        return $this;
    }

    public function getContractorRate() {
        return $this->contractorRate;
    }
    
    public function setFinalRate($finalRate) {
        $this->finalRate = $finalRate;
        return $this;
    }
    
    public function getFinalRate() {
        return $this->finalRate;
    }

    public function setHours($hours) {
        $this->hours = $hours;
        return $this;
    }

    public function getHours() {
        return $this->hours;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
        return $this;
    }

    public function getDuration() {
        return $this->duration;
    }
    
    public function getTextDuration()
    {
        return self::$durationOptions[$this->getDuration()];
    }

    public function setFinishDate($finishDate)
    {
        if ($finishDate != '')
            $this->finishDate = new \DateTime(date($finishDate));
        return $this;
    }
    
    public function getFinishDate()
    {
        if ($this->finishDate != null)
            return date('Y-m-d H:i:s', $this->finishDate->getTimestamp());
    }
    
    public function setTask(Task $task) {
        $this->task = $task;
        return $this;
    }

    public function getTask() {
        return $this->task;
    }
    
    public function setIsAccepted($accepted)
    {
        $this->isAccepted = $accepted;
        return $this;
    }
    
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }
    
    public function setMilestones($milestones)
    {
        foreach ($milestones as $milestone) {
            if ($milestone->getName() != '')
                $milestone->setProposal($this);
            else 
                return $this;
        }
        $this->milestones = $milestones;
        return $this;
    }
    
    public function getMilestones()
    {
        return $this->milestones;
    }

}