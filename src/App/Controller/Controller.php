<?php

namespace App\Controller;

use Knp\RadBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Form;

abstract class Controller extends BaseController
{
    /**
     * @param Form $form
     * @return string
     */
    protected function _getFirstErrorMessage(Form $form)
    {
        $errors = $this->_getErrorMessages($form);
        return $this->_getFirstMessage($errors);
    }

    /**
     * @param Form $form
     * @return array
     */
    protected function _getErrorMessages(Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = strtr($error->getMessageTemplate(), $error->getMessageParameters());
        }
        if ($form->hasChildren()) {
            foreach ($form->getChildren() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->_getErrorMessages($child);
                }
            }
        }
        return $errors;
    }

    /**
     * @param type $errors
     * @return string|bool
     */
    private function _getFirstMessage($errors)
    {
        if (is_string($errors)) {
            return $errors;
        }
        if (is_array($errors)) {
            foreach ($errors as $key => $value) {
                return $this->_getFirstMessage($value);
            }
        }
        return false;
    }
}
