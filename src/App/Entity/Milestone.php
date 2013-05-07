<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Exception;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use JMS\Serializer\Annotation as Rest;

/**
 * @ORM\Entity(repositoryClass="App\Entity\MilestoneRepository")
 * @ORM\Table(name="milestones")
 * @Rest\ExclusionPolicy("all")
 */
class Milestone {

    use ORMBehaviors\Timestampable\Timestampable,                
        ORMBehaviors\Sluggable\Sluggable
        
    ;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Proposal", inversedBy="milestones")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="proposal_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    public $proposal;
    
    /**
     * @ORM\Column(name="name", type="text")
     * @Rest\Expose
     * @Rest\SerializedName("name")
     */
    protected $name;

    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setProposal($proposal)
    {
        $this->proposal = $proposal;
        return $this;
    }
    
    public function getProposal()
    {
        return $this->proposal;
    }
    
    public function getSluggableFields()
    {
        return ['id','name'];
    }
    
}