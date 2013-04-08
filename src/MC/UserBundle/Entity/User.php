<?php

/*
 *
 */

namespace MC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\AdvancedEncoderBundle\Security\Encoder\EncoderAwareInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use MC\AssetBundle\Entity\Asset;
use JMS\Serializer\Annotation as Rest;
use Doctrine\Common\Collections\ArrayCollection;
use ArrayIterator;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_users")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\HasLifecycleCallbacks
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"client" = "Client", "contractor" = "Contractor", "manager" = "Manager"})
 */
abstract class User extends BaseUser implements EncoderAwareInterface, ParticipantInterface
{
    // this is for contractors
    // but i've added it here in case we need this functionality globally
    const ACCOUNT_TYPE_INDIVIDUAL = 'individual';
    const ACCOUNT_TYPE_BUSINESS = 'business';

    private static $accountTypes = array(
        self::ACCOUNT_TYPE_INDIVIDUAL => 'Individual',
        self::ACCOUNT_TYPE_BUSINESS => 'Business'
    );

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
     * @Rest\Expose
     * @Rest\SerializedName("fullName")
     * @Rest\Groups({"profileForm"})
     */
    protected $fullName;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=true)
     * @Rest\Expose
     * @Rest\Groups({"profileForm"})
     */
    protected $city;

    /**
     * @var string $tagLine
     *
     * @ORM\Column(name="tag_line", type="string", length=128, nullable=true)
     * @Rest\Expose
     * @Rest\SerializedName("tagLine")
     * @Rest\Groups({"profileForm"})
     */
    protected $tagLine = null;

    /**
     * @var string $tagLine
     *
     * @ORM\Column(name="overview", type="text", nullable=true)
     * @Rest\Expose
     * @Rest\Groups({"profileForm"})
     */
    protected $overview = null;

    /**
     * @var string $city
     *
     * @Rest\SerializedName("accountType")
     * @ORM\Column(name="account_type", type="string", length=128, nullable=true)
     */
    protected $accountType = 'user';

    /**
     * @var \App\Entity\Country $country
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
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
     * @ORM\JoinColumn(name="hear_source_id", referencedColumnName="id")
     * })
     */
    protected $hearSource;

    /**
     * @ORM\ManyToOne(targetEntity="MC\AssetBundle\Entity\Asset")
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $avatar = null;

    /**
     * @Rest\SerializedName("educationHistory")
     * @ORM\OneToMany(targetEntity="Education", mappedBy="user", cascade={"persist"})
     * @ORM\OrderBy({"startYear" = "ASC"})
     */
    protected $educationHistory;

    /**
     * @Rest\SerializedName("employmentHistory")
     * @ORM\OneToMany(targetEntity="Employment", mappedBy="user", cascade={"persist"})
     * @ORM\OrderBy({"startYear" = "ASC"})
     */
    protected $employmentHistory;

    /**
     * @Rest\SerializedName("qualifications")
     * @ORM\OneToMany(targetEntity="Qualification", mappedBy="user", cascade={"persist"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $qualifications;

    /**
     * Not persisted, used for updates of avatar only
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * */
    public $uploadedAvatar = null;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255)
     */
    protected $facebookId;

    public function __construct()
    {
        parent::__construct();
        $this->educationHistory = new ArrayCollection;
        $this->employmentHistory = new ArrayCollection;
        $this->qualifications = new ArrayCollection;
    }


    /**
     * @return array<string, string> $accountTypes
     */
    public static function getAccountTypes()
    {
        return self::$accountTypes;
    }

    /**
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url string
     * @return User
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }

    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;

        return $this;
    }

    public function getActivationKey()
    {
        return $this->activationKey;
    }

    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getDisplayName()
    {
        if ($this->displayName === null) {
            return $this->getUsername();
        } else {
            return $this->displayName;
        }
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }


    public function isLegacy()
    {
        return $this->isLegacy;
    }

    public function getIsLegacy()
    { //this is for twig only
        return $this->isLegacy();
    }

    public function setIsLegacy($isLegacy)
    {
        $this->isLegacy = $isLegacy;

        return $this;
    }

    public function getEncoderName()
    {
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


    public function getTagLine()
    {
        return $this->tagLine;
    }


    public function setTagLine($tagLine)
    {
        $this->tagLine = $tagLine;

        return $this;
    }


    public function getOverview()
    {
        return $this->overview;
    }


    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
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

    /**
     * Set accountType
     *
     * @param string $accountType
     * @return User
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }


    public function getAvatar()
    {
        return $this->avatar;
    }


    public function setAvatar(Asset $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }


    public function getEmploymentHistory()
    {
        return $this->employmentHistory;
    }


    public function setEmploymentHistory(ArrayIterator $employmentHistory)
    {
        $this->employmentHistory = $employmentHistory;

        return $this;
    }

    public function addEmployment(Employment $employment)
    {
        $employment->setUser($this);
        $this->employmentHistory->add($employment);
    }

    public function getEducationHistory()
    {
        return $this->educationHistory;
    }


    public function setEducationHistory(ArrayIterator $educationHistory)
    {
        $this->educationHistory = $educationHistory;

        return $this;
    }

    public function addEducation(Education $education)
    {
        $education->setUser($this);
        $this->educationHistory->add($education);
    }

    public function setQualifications(ArrayIterator $q)
    {
        $this->qualifications = $q;

        return $this;
    }

    public function addQualification(Qualification $q)
    {
        $q->setUser($this);
        $this->qualifications->add($q);
    }


    public function getQualifications()
    {
        return $this->qualifications;
    }


    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
        $this->salt = '';
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_CLIENT');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFullName($fbdata['first_name'].' '.$fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
    }

}
