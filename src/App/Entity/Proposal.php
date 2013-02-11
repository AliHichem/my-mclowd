<?php
namespace App\Entity;

use App\Entity\Job;
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
     * @ORM\Column(name="job_id", type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\Job")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $job;

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

    public function setJob(Job $job) {
        $this->job = $job;
        return $this;
    }

    public function getJob() {
        return $this->job;
    }

}