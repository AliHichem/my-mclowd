<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="setting")
  */
class Setting
{

    /**
     * @var string $settingname
     *
     * @ORM\Id
     * @ORM\Column(name="settingname", type="string", length=45, nullable=true)
     */
    private $settingname;

    /**
     * @var string $settingvalue
     *
     * @ORM\Column(name="settingvalue", type="text", nullable=true)
     */
    private $settingvalue;


    /**
     * Set settingname
     *
     * @param string $settingname
     * @return Setting
     */
    public function setSettingname($settingname)
    {
        $this->settingname = $settingname;

        return $this;
    }

    /**
     * Get settingname
     *
     * @return string
     */
    public function getSettingname()
    {
        return $this->settingname;
    }

    /**
     * Set settingvalue
     *
     * @param string $settingvalue
     * @return Setting
     */
    public function setSettingvalue($settingvalue)
    {
        $this->settingvalue = $settingvalue;

        return $this;
    }

    /**
     * Get settingvalue
     *
     * @return string
     */
    public function getSettingvalue()
    {
        return $this->settingvalue;
    }
}