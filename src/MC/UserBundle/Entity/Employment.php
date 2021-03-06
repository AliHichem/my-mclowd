<?php

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Rest;

/**
 * Education
 *
 * @ORM\Table(name="employments")
 * @Rest\ExclusionPolicy("all")
 * @ORM\Entity
 */
class Employment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Rest\Expose()
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Rest\SerializedName("companyName")
     * @Rest\Expose()
     * @ORM\Column(name="company_name", type="string", length=255)
     */
    protected $companyName;

    /**
     *
     * @Assert\NotBlank()
     * @Rest\SerializedName("startMonth")
     * @Rest\Expose()
     * @ORM\Column(name="start_month", type="integer")
     */
    protected $startMonth;

    /**
     * @Assert\NotBlank()
     * @Rest\SerializedName("startYear")
     * @Rest\Expose()
     * @ORM\Column(name="start_year", type="integer")
     */
    protected $startYear;
    
    /**
     * @Rest\SerializedName("endMonth")
     * @Rest\Expose()
     * @ORM\Column(name="end_month", type="integer", nullable=true)
     */
    protected $endMonth;    

    /**     
     * @Rest\SerializedName("endYear")
     * @Rest\Expose()
     * @ORM\Column(name="end_year", type="integer", nullable=true)
     */
    protected $endYear;


    /**
     * @var string
     * @Assert\NotBlank()
     * @Rest\Expose()
     * @ORM\Column(name="position", type="string", length=255)
     */
    protected $position;
    

    /**
     * @var string
     * @Rest\Expose()
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="employmentHistory")
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
     * @return Employment
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }



    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
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