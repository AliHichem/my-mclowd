<?php

namespace MC\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Exception;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use JMS\Serializer\Annotation as Rest;
use JMS\Serializer\Annotation\Accessor;

use MC\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="SettingsRepository")
 * @ORM\Table(name="user_settings")
 * @Rest\ExclusionPolicy("all")
 */
class UserSetting {
    
    use ORMBehaviors\Timestampable\Timestampable;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="setting")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;
    
    /**
     * @ORM\Column(name="incoming_proposals_notif", type="boolean", nullable=true)
     * @Rest\Expose
     * @Rest\SerializedName("incomingProposals")
     */
     protected $incomingProposals;
     
     /**
      * @ORM\Column(name="workroom_msg_notif", type="boolean", nullable=true)
      * @Rest\Expose
      * @Rest\SerializedName("workroomMessage")
      */
     protected $workroomMessage;
     
     
     /**
      * @ORM\Column(name="important_account_notif", type="boolean", nullable=true)
      * @Rest\Expose
      * @Rest\SerializedName("importantAccountNotification")
      */
     protected $importantAccountNotification;
     
     /**
      * @ORM\Column(name="marketplace_nsltr", type="boolean", nullable=true)
      * @Rest\Expose
      * @Rest\SerializedName("marketplaceNewsletter")
      */
     protected $marketplaceNewsletter;
     
     /**
      * @ORM\Column(name="mclowd_nsltr", type="boolean", nullable=true)
      * @Rest\Expose
      * @Rest\SerializedName("mclowdNewsletter")
      */
     protected $mclowdNewsletter;
     
     public function getId()
     {
         return $this->id;
     }
     
     public function setUser($user)
     {
         $this->user = $user;
         return $this;
     }
     
     public function getUser()
     {
         return $this->user;
     }
     
     
     public function setIncomingProposals($flag)
     {
         $this->incomingProposals = $flag;
         return $this;
     }
     
     public function getIncomingProposals()
     {
         return $this->incomingProposals;
     }
     
     public function setWorkroomMessage($flag)
     {
         $this->workroomMessage = $flag;
         return $this;
     }
     
     public function getWorkroomMessage()
     {
         return $this->workroomMessage;
     }
     
     public function setImportantAccountNotification($flag)
     {
         $this->importantAccountNotification = $flag;
         return $this;
     }
     
     public function getImportantAccountNotification()
     {
         return $this->importantAccountNotification;
     }
     
     public function setMarketplaceNewsletter($flag)
     {
         $this->marketplaceNewsletter = $flag;
         return $this;
     }
     
     public function getMarketplaceNewsletter()
     {
         return $this->marketplaceNewsletter;
     }
     
     public function setMclowdNewsletter($flag)
     {
         $this->mclowdNewsletter = $flag;
         return $this;
     }
      
     public function getMclowdNewsletter()
     {
         return $this->mclowdNewsletter;
     }
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
}