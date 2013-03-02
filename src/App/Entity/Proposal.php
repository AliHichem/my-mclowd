<?php
namespace App\Entity;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 * @ORM\Table(name="proposals")
 */
class Proposal {

    use ORMBehaviors\Timestampable\Timestampable,
        \App\Behaviours\Ownable
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(name="task_id", type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\task")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $task;

    /**
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $hours;

    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    protected $rate;

    /**
     * Trait property here:
     * @ORM\ManyToOne(targetEntity="MC\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var User $user
     */
    protected $user;

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

    public function setRate($rate) {
        $this->rate = $rate;
        return $this;
    }

    public function getRate() {
        return $this->rate;
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

    public function getduration() {
        return $this->duration;
    }

    public function setTask(Task $task) {
        $this->task = $task;
        return $this;
    }

    public function getTask() {
        return $this->task;
    }

}