<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\FormFactory;
use App\Entity\Setting;

/*
 * Hotel Service
 */

class Settings extends AbstractService {

    /**
     * @var Symfony\Component\Form\FormFactory
     */
    protected $_formFactory;
    protected $_settings;

    public function setFormFactory(FormFactory $ff) {
        $this->_formFactory = $ff;
    }

    public function __construct(EntityManager $em) {
        parent::__construct($em);

        $results = $em->getRepository('App:Setting')->findAll();

        array_walk($results, function($value, $key) use(&$result) {
                    $result[$value->getSettingname()] = $value->getSettingvalue();
                });

        $this->_settings = $result;
    }

    public function getForm() {
        $settings = $this->_settings;
        $settingsForm = $this->_formFactory->createBuilder();
        $settingsForm->add('google_analytics_tracking_code', 'textarea', array(
                    'required' => false,
                    'label' => 'Google Analytics tracking code',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Paste tracking code here ...',
                        'rows' => 10
                    )
                ))
                ->add('facebook_link', 'textarea', array(
                    'required' => false,
                    'label' => 'Facebook page link',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Paste link here ...',
                    )
                ))
                ->add('youtube_link', 'textarea', array(
                    'required' => false,
                    'label' => 'Youtube page link',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Paste link here ...',
                    )
                ))
                ->add('tweeter_link', 'textarea', array(
                    'required' => false,
                    'label' => 'Tweeter page link',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Paste link here ...',
                    )
                ))
                ->add('google_plus_link', 'textarea', array(
                    'required' => false,
                    'label' => 'Google+ page link',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Paste link here ...',
                    )
                ))
                ->add('copyright_year', 'text', array(
                    'required' => false,
                    'label' => 'Copyright year',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Type in the year to appear on copyright ...',
                    )
                ))
                ->add('contact_email', 'text', array(
                    'required' => false,
                    'label' => 'Conatact email address',
                    'attr' => array(
                        'class' => 'span12',
                        'placeholder' => 'Type in the mail address for contact ...',
                    )
                ))
                ->setData($settings);

        $form = $settingsForm->getForm();

        return $form;
    }

    public function saveSettingsForm(\Symfony\Component\Form\Form $data) {
        $settings = $data->getData();

        foreach ($settings as $name => $value) {
            $settingEntity = new Setting();
            $settingEntity->setSettingname($name);
            $settingEntity->setSettingvalue($value);
            $this->getEm()->merge($settingEntity);
        }

        $this->getEm()->flush();
    }

    public function getSettingValue($name) {
        return $this->_settings[$name];
    }

}