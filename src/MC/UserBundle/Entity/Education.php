<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Rest;
/**
 * Education
 *
 * @ORM\Table(name="educations")
 * @Rest\ExclusionPolicy("all")
 * @ORM\Entity
 */
class Education
{
    /**
     * @var integer
     * @Rest\Expose()
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Rest\SerializedName("institutionName")
     * @Rest\Expose()
     * @ORM\Column(name="institution_name", type="string", length=255)
     */
    protected $institutionName;

    /**
     * @Rest\SerializedName("startMonth")
     * @Rest\Expose()
     * @Assert\NotBlank()
     * @ORM\Column(name="start_month", type="integer")
     */
    protected $startMonth;

    /**
     * @Rest\SerializedName("startYear")
     * @Rest\Expose()
     * @Assert\NotBlank()
     * @ORM\Column(name="start_year", type="integer")
     */
    protected $startYear;
    
    /**
     *
     * @ORM\Column(name="end_month", type="integer", nullable=true)
     */
    protected $endMonth;    

    /**
     *
     * @ORM\Column(name="end_year", type="integer", nullable=true)
     */
    protected $endYear;


    /**
     * @var string
     * @Assert\NotBlank()
     * @Rest\Expose()
     * @ORM\Column(name="degree", type="string", length=255)
     */
    protected $degree;

    /**
     * @var string
     * @Rest\Expose()
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="educationHistory")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Education
     */
    public function setInstitutionName($name)
    {
        $this->institutionName = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getInstitutionName()
    {
        return $this->institutionName;
    }

    /**
     * Set degree
     *
     * @param string $degree
     * @return Education
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    
        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Education
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    
    public function getStartYear() 
    {
        return $this->startYear;
    }
    
    
    public function setStartYear($startYear) 
    {
        $this->startYear = $startYear;    

        return $this;
    }


    public function getStartMonth() 
    {
        return $this->startMonth;
    }
    

    public function setStartMonth($startMonth) 
    {
        $this->startMonth = $startMonth;

        return $this;
    }


    public function getEndMonth() 
    {
        return $this->endMonth;
    }
    
    public function setEndMonth($endMonth) 
    {
        $this->endMonth = $endMonth;
    
        return $this;
    }


    public function getEndYear() 
    {
        return $this->endYear;
    }
    
    public function setEndYear($endYear) 
    {
        $this->endYear = $endYear;
    
        return $this;
    }

    public function getDescription() 
    {
        return $this->description;
    }
    
    public function setDescription($description) 
    {
        $this->description = $description;
    
        return $this;
    }
}