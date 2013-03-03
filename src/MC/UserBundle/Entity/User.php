<?php
namespace MC\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\AdvancedEncoderBundle\Security\Encoder\EncoderAwareInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_users")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"client" = "Client", "contractor" = "Contractor", "manager" = "Manager"})
 */
abstract class User extends BaseUser implements EncoderAwareInterface, ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $url
     *
     * @ORM\Column(name="user_url", type="string", length=100, nullable=true)
     */
    protected $url;

    /**
     * @var datetime $registeredDate
     *
     * @ORM\Column(name="user_registered", type="datetime", nullable=true)
     */
    protected $registeredDate;

    /**
     * @var string $activationKey
     *
     * @ORM\Column(name="user_activation_key", type="string", length=60, nullable=true)
     */
    protected $activationKey;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="user_status", type="integer", length=11, nullable=true)
     */
    protected $status;

    /**
     * @var string $displayName
     *
     * @ORM\Column(name="display_name", type="string", length=250, nullable=true)
     */
    protected $displayName;

    /**
     * @var string $fullName
     *
     * @ORM\Column(name="full_name", type="string", length=250, nullable=true)
     */
    protected $fullName;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=true)
     */
    protected $city;

    /**
     * @var \App\Entity\Country $country
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $country;

    /**
     * @var boolean $isLegacy
     *
     * @ORM\Column(name="is_legacy", type="boolean")
     */
    protected $isLegacy = false;

    /**
     * @var \App\Entity\HearSource $hearSource
     * @ORM\ManyToOne(targetEntity="\App\Entity\HearSource", inversedBy="users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hear_source_id", referencedColumnName="id")
     * })
     */
    protected $hearSource;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string $url
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param $url string
     * @return User
     */
    public function setUrl($url) {
        $this->url = $url;    
        return $this;
    }

    public function getRegisteredDate() {
        return $this->registeredDate;
    }
    
    public function setRegisteredDate($registeredDate) {
        $this->registeredDate = $registeredDate;    
        return $this;
    }

    public function getActivationKey() {
        return $this->activationKey;
    }
    
    public function setActivationKey($activationKey) {
        $this->activationKey = $activationKey;    
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getDisplayName() {
        if ($this->displayName === null) {
            return $this->getUsername();
        } else {
            return $this->displayName;    
        }        
    }
    
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;    
        return $this;
    }


    public function isLegacy()
    {
        return $this->isLegacy;
    }

    public function getIsLegacy() { //this is for twig only
        return $this->isLegacy();
    }
        
    public function setIsLegacy($isLegacy) {
        $this->isLegacy = $isLegacy;    
        return $this;
    }

    public function getEncoderName() {
        if ($this->isLegacy()) {
            return "wp_encoder";
        } else {
            return null;
        }      
    }


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
     * Set fullName
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    
        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set country
     *
     * @param \App\Entity\Country $country
     * @return User
     */
    public function setCountry(\App\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \App\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set hearSource
     *
     * @param \App\Entity\HearSource $hearSource
     * @return User
     */
    public function setHearSource(\App\Entity\HearSource $hearSource = null)
    {
        $this->hearSource = $hearSource;
    
        return $this;
    }

    /**
     * Get hearSource
     *
     * @return \App\Entity\HearSource 
     */
    public function getHearSource()
    {
        return $this->hearSource;
    }
}