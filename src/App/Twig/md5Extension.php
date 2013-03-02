<?php

namespace App\Twig;

class md5Extension extends \Twig_Extension {

    /**
     * {@inheritdoc}
     */
    public function getFilters() {
        return array(
            'md5'=> new \Twig_Filter_Method($this, 'calcMd5'),
        );
    }
    
    public function getName() {
        return 'md5';
    }

    public function calcMd5($val) {
        return md5($val);
    }
}