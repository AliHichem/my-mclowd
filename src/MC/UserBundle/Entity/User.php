<?php
namespace MC\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\AdvancedEncoderBundle\Security\Encoder\EncoderAwareInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_users")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"client" = "Client", "contractor" = "Contractor"})
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
     * @var boolean $isLegacy
     *
     * @ORM\Column(name="is_legacy", type="boolean")
     */
    protected $isLegacy = false;


    public function __construct()
    {
        parent::__construct();
    }


    public function getUrl() {
        return $this->url;
    }
    
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

}